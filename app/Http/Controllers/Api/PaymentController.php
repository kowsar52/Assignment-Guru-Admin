<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateways;
use App\Models\Settings;
use App\Models\Bid;
use App\Models\Order;
use App\Models\WriterOrder;
use Carbon\Carbon;
use Auth;
use Session;
use Stripe;
use Illuminate\Support\Facades\Http;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaymentController extends Controller
{
    private $_api_context;
    
    public function __construct()
    {
            
        $paypal_configuration =PaymentGateways::where('slug','paypal')->firstOrFail();
        $paypal_conf = \Config::get('paypal');
        $paypal_conf['client_id'] = $paypal_configuration->key;
        $paypal_conf['secret'] = $paypal_configuration->key_secret;
        $paypal_conf['settings']['mode'] =  $paypal_configuration->sandbox == 1 ? 'sandbox' : 'live';
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

    }

    //get payment getway
    public function getPaymentGetway(){
        $data = PaymentGateways::where('enabled',1)->orderBy('id','asc')->get();
        return response()->json($data);
    }


    //stripeChargeCreate
    public function stripeChargeCreate(Request $request){
       
        // Get Payment Gateway
        $payment = PaymentGateways::where('slug','stripe')->firstOrFail();
        $bid = Bid::findOrFail($request->bid_id);
        $order = Order::findOrfail($bid->order_id);
        if($order->writer){
            return response()->json([
                'status' => 'failed',
                'message' => 'Already Hired Another One',
            ]);
        }else{
            $cents  = Settings::getOption('currency_code') == 'JPY' ? $bid->bidding_price : ($bid->bidding_price*100);
            $amount = (int)$cents;
            $currency_code = Settings::getOption('currency_code');
            $description = 'Order Payment!';
    
    
    
            $stripe = new \Stripe\StripeClient($payment->key_secret);
            $res = [];
            try {
                if (isset($request->stripeToken)) {
                    $res = $stripe->charges->create([
                      'amount' => $amount,
                      'currency' => $currency_code,
                      'source' => $request->stripeToken,
                      'description' => $description,
                      'metadata' => [
                          'bid_id' => $bid->id,
                          'order_id' => $bid->order_id,
                          'writer_id' => $bid->writer_id,
                          'created_at' => Carbon::now(),
                        ],
                    ]);
                    if($res->status == "succeeded" || $res->status == 'succeeded'){
                        $order = Order::where('id',$bid->order_id)->update([
                            'writer' => $bid->writer_id,
                            'isHired' => 1,
                            'paid_amount' => $bid->bidding_price,
                            'updated_at' => Carbon::now(),
                        ]);
                        WriterOrder::insert([
                            'order_id' => $bid->order_id,
                            'writer_id' => $bid->writer_id,
                            'amount' => $bid->bidding_price,
                            'status' => 1,
                            'created_at' => Carbon::now(),
                        ]);
    
                        //send message 
                        $token = $request->header('Authorization');
                        $response = Http::withHeaders([
                            'Authorization' => $token,
                        ])->post(url('/api/user/message/send'), [
                            'id_user' =>$bid->writer_id,
                            'message' => 'Order Confirmed!',
                        ]);
                    }
                    return response()->json($res);
                }
            } catch (\Stripe\Exception\ApiErrorException $e) {
            # Display error on client
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }

        }

    }
    //paypalPaymentCreate
    public function paypalPaymentCreate(Request $request){
        // Get Payment Gateway
        $payment = PaymentGateways::where('slug','stripe')->firstOrFail();
        $bid = Bid::findOrFail($request->bid_id);
        $order = Order::findOrfail($bid->order_id);
        if($order->writer){
            return response()->json([
                'status' => 'failed',
                'message' => 'Already Hired Another One',
            ]);
        }else{
            $currency_code = Settings::getOption('currency_code');
            $description = 'Order Payment!';
            
            $order['item_name'] = $order->topic;
            $order['item_number'] = time();
            $order['item_amount'] = round($bid->bidding_price, 2);
            $cancel_url = "https://dev.to/yazansalhi/paypal-integration-in-laravel-and-vue-js-2ng8";
            $notify_url = "https://dev.to/yazansalhi/paypal-integration-in-laravel-and-vue-js-2ng8";
    
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName($order['item_name']) /** item name **/
                ->setCurrency($currency_code)
                ->setQuantity(1)
                ->setPrice($order['item_amount']); /** unit price **/
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency($currency_code)
                ->setTotal($order['item_amount']);
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription($order['item_name'].' Via Paypal');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl($notify_url) /** Specify return URL **/
                ->setCancelUrl($cancel_url);
            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            /** dd($payment->create($this->_api_context));exit; **/
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                return redirect()->back()->with('unsuccess',$ex->getMessage());
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            

            if(isset($redirect_url)) {            
                return response()->json([
                    'redirect_url' => $redirect_url,
                    'payment' => $payment,
                ]);
            }


        }
    }

    public function paypalNotify(){

    }
    public function paypalCancel(){
        
    }

}
