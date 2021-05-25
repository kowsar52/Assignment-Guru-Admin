<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PaymentGateways;
use App\Models\Transaction;
use App\Models\UserPaymentMethod;
use App\Models\Settings;
use App\Models\Withdrawals;
use App\Models\User;
use App\Helper;
use Auth,DB;
use Carbon\Carbon;
use App\Services\Stripe\Customer;
use App\Services\Stripe\Seller;
use Stripe\Token;
use Stripe\Transfer;
use Stripe\Stripe;


class TransactionController extends Controller
{
    //getTransactions
    public function getTransactions(){
        $user = Auth::user();
        $earnings = Transaction::where('receiver_id',$user->id)->orderBy('created_at','desc')->paginate($_GET['per_page']);
        $net_income = Transaction::where('receiver_id',$user->id)
                                ->where(function($query){
                                    $query->where('type', '=', 1);
                                    $query->orWhere('type', '=',3);
                                })
                                ->sum('amount');
        $total_withdrawn = Transaction::where('receiver_id',$user->id)
                                ->where('type',2)
                                ->sum('amount');
        $available_for_withdraw = $user->balance;
        

        return response()->json([
            'earnings' =>  $earnings,
            'net_income' =>  Helper::amountFormatDecimal($net_income),
            'available_for_withdraw' =>  Helper::amountFormatDecimal($available_for_withdraw),
            'total_withdrawn' =>  Helper::amountFormatDecimal($total_withdrawn),
            'payment_method' =>  Helper::amountFormatDecimal($total_withdrawn),
            'user_stripe_connect_id' =>  $user->stripe_connect_id,
        ]);
    }

    //add user payment method (card)
    public function savePaymentMethod(Request $request){
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'cc_number' => 'required',
            'month' => 'required',
            'year' => 'required',
            'cvv' => 'required'
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 200);
        }

        
        $card = [
            'card' => [
                'number' => $request->cc_number,
                'exp_month' => $request->month,
                'exp_year' => $request->year,
                'cvc' => $request->cvv
            ]
        ];
        /** @var User $user */
        $user = Auth::user();
        Customer::save($user, $card);
        return response()->json([
            'status' => 'success',
            'message' => 'Payment Method Added Successfully!'
        ], 200);
    }

    //confirm stripe transfer
    public function confirmStripeTransfer(){
       if(Auth::user()->stripe_connect_id){
           $payment = PaymentGateways::where('slug','stripe')->firstOrFail();
           Stripe::setApiKey($payment->key_secret);

           $withdraw_amount = $user->balance - Settings::getOption('withdraw_fee');
             $res = Transfer::create([
               'amount' => $withdraw_amount,
               "currency" => "usd",
               'destination' => Auth::user()->stripe_connect_id ,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $res,
            ], 200);

       }else{

       }
    }

    //createExpressAccount
    public function createExpressAccount(){
        $user = Auth::user();
        $payment = PaymentGateways::where('slug','stripe')->firstOrFail();
        Stripe::setApiKey($payment->key_secret);
        if(is_null($user->stripe_connect_id)){

            $account = \Stripe\Account::create([
                'country' => 'US',
                'type' => 'express',
              ]);
              
            UserPaymentMethod::insert([
                'user_id' => $user->id,
                'payment_method_id' => 2,
                'stripe_connect_id' => $account->id,
                'created_at' => Carbon::now(),
            ]);
            $user->stripe_connect_id = $account->id;
            $user->update();

            $account_links = \Stripe\AccountLink::create([
                'account' => $account->id,
                'refresh_url' => Settings::getOption('app_url').'/user/earnings',
                'return_url' => Settings::getOption('app_url').'/user/earnings',
                'type' => 'account_onboarding',
              ]);
           

            return response()->json([
                'status' => 'success',
                'data' => $account_links,
            ], 200);
        }else{
            
            $account_links =  \Stripe\Account::createLoginLink($user->stripe_connect_id);
           

            return response()->json([
                'status' => 'success',
                'data' => $account_links,
            ], 200);
        }
    }

//make withdraw request
    public function makeWithdrawals()
    {
      if (Auth::user()->balance >= Settings::getOption('min_withdrawn_amount')
          && Auth::user()->stripe_connect_id
          && Withdrawals::where('user_id', Auth::user()->id )->where('status','pending') ->count() == 0) 
        {

        if (Auth::user()->stripe_connect_id) {
        //add transaction
            $txn = new Transaction;
            $txn->txn_id ='tnx_'.Auth::user()->id.Helper::strRandom();
            $txn->user_id = Auth::user()->id;
            $txn->receiver_id = Auth::user()->id;
            $txn->type = 2; //1 = order payment, 2 = withdraw , 3 = affiliate commission 
            $txn->description = 'withdraw created'; 
            $txn->payment_method = 'stripe'; 
            $txn->amount = Auth::user()->balance; 
            $txn->status = 'pending'; 
            $txn->created_at = Carbon::now();
            $txn->save();

            //add withdraw
            $sql           = new Withdrawals;
            $sql->user_id  = Auth::user()->id;
            $sql->amount   = Auth::user()->balance;
            $sql->gateway  = 'stripe';
            $sql->account  = Auth::user()->stripe_connect_id;
            $sql->txn_id  = $txn->txn_id;
            $sql->save();

            //send admin email notification
   		} 


        // Remove Balance the User
        $userBalance = User::find(Auth::user()->id);
        $userBalance->balance = 0;
        $userBalance->save();

        return response()->json([
          'success' => true,
          'message' =>'Withdraw Success!',
        ], 200);
    }

    return response()->json([
      'success' => false,
      'message' =>'Something Wrong!',
     ], 200);

    } // End Method makeWithdrawals
}
