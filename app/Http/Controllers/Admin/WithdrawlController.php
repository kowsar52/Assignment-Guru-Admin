<?php

namespace App\Http\Controllers\Admin;

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
use DataTables;

class WithdrawlController extends Controller
{
    //	
    public function withdrawals(Request $request)
	{
        if ($request->ajax()) {
            $data = Withdrawals::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function($row){
                        if($row->status == 'pending'){
                            $btn = '<span class="badge badge-warning">Pending</span>'; 
                        }else if($row->status == 'rejected'){
                            $btn = '<span class="badge badge-danger">Refected</span>'; 
                        }else if($row->status == 'paid'){
                            $btn = '<span class="badge badge-success">Paid</span>'; 
                        }else{
                            $btn = '<span class="badge badge-success">'.$row->status.'</span>'; 
                        }
                        return $btn;
                    })
                    ->addColumn('user', function($row){
                        $user = User::findOrFail($row->user_id);
                        return $user->first_name.' '. $user->last_name;
                    })
                    ->editColumn('amount', function($row){
                        return Settings::getOption('currency').$row->amount;
                    })
                    ->editColumn('date', function($row){
                        return $row->date;
                    })
                    ->addColumn('action', function($row){
						if($row->status == 'pending'){
							$btn = '<div aria-label="..." role="group" class="btn-group btn-group">
							<button type="button" class="btn btn-rounded btn-success" onclick="acceptBtn('.$row->id.')"><i class="fa fa-check mr-2"></i>Accept/Pay</button>
							<button type="button" class="btn btn-rounded btn-danger" onclick="rejectBtn('.$row->id.')"><i class="fa fa-times mr-2"></i>Reject</button></div>'; 
                        }else if($row->status == 'rejected'){
							$btn = '<div aria-label="..." role="group" class="btn-group btn-group">
							<button type="button" class="btn btn-rounded btn-danger" disabled><i class="fa fa-times mr-2"></i>Rejected</button></div>'; 
                        }else if($row->status == 'paid'){
							$btn = '<div aria-label="..." role="group" class="btn-group btn-group">
							<button type="button" class="btn btn-rounded btn-success" disabled><i class="fa fa-check mr-2"></i>Paid</button></div>'; 
                        }
						
                        return $btn;
                    })
                    ->rawColumns(['action','status','user','date','amount'])
                    ->make(true);
        }

		return view('admin.withdrawals.withdrawals');
	}//<--- End Method

	public function withdrawalsView($id)
	{
		$data = Withdrawals::findOrFail($id);
		return view('admin.withdrawals.withdrawal-view', ['data' => $data]);
	}//<--- End Method

	public function withdrawalsPaid(Request $request)
	{
		$data = Withdrawals::findOrFail($request->id);

		$user = $data->user();
		if($user->stripe_connect_id){
			try {
				$payment = PaymentGateways::where('slug','stripe')->firstOrFail();
				Stripe::setApiKey($payment->key_secret);
	 
				$withdraw_amount = $data->amount - Settings::getOption('withdraw_fee');
				  $res = Transfer::create([
					'amount' => $withdraw_amount * 100,
					"currency" => "usd",
					'destination' => $user->stripe_connect_id ,
				 ]);

				 $txn = new Transaction;
				 $txn->txn_id ='tnx_'.$user->id.Helper::strRandom();
				 $txn->user_id = $user->id;
				 $txn->receiver_id = $user->id;
				 $txn->type = 2; //1 = order payment, 2 = withdraw , 3 = affiliate commission 
				 $txn->description = 'withdrawal success!'; 
				 $txn->payment_method = 'stripe'; 
				 $txn->amount = $withdraw_amount; 
				 $txn->status = 'succeed'; 
				 $txn->created_at = Carbon::now();
				 $txn->save();
	 
				 $data->status    = 'paid';
				 $data->date_paid = Carbon::now();
				 $data->save();
		 
				 //<------ Send Email to User ---------->>>
	
				 return response()->json([
					 'success' => true,
					 'data' => $res,
				 ], 200);
			  } catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				return response()->json([
					'success' => false,
					'message' => 'Stripe Transfer Failed!',
				], 200);
			  }


		}else{
  			return response()->json([
				 'success' => false,
			 ], 200);
		}
	

	}//<--- End Method
}
