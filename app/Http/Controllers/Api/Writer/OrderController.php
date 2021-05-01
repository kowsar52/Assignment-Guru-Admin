<?php

namespace App\Http\Controllers\Api\Writer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\EducationLevel;
use App\Models\Language;
use App\Models\Service;
use App\Models\CitationStyle;
use App\Models\Subject;
use App\Models\Deadline;
use App\Models\Settings;
use App\Models\Bid;
use App\Models\Review;
use App\Models\Coupon;
use Carbon\Carbon;
use App\Models\Conversations;
use App\Models\Messages;
use App\Models\SaveOrder;
use App\Helper;
use Illuminate\Support\Facades\Http;
use Auth;


class OrderController extends Controller
{
    //findOrders
    public function findOrders(){
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6 ;

        $query = Order::select('orders.*', 'products.title as product_title', 'languages.title as language_title')
            ->join('products', 'products.id', '=', 'orders.product')
            ->join('languages', 'languages.id', '=', 'orders.language');
            
        if(isset($_GET['searchText'])){
            $query->where('orders.topic', 'like', '%' . $_GET['searchText'] . '%');
        }
        $query->where('orders.status',  1);
        $query->where('orders.isHired',  0);
    

        $orders = $query->orderBy('orders.created_at', 'desc')->paginate($per_page);

        $res = [];
        foreach ($orders as $order) {
                //get writer total order 
                $total_bids = Bid::where('order_id',$order->id)->count();
                $extra=[
                    'total_bids' => $total_bids,
                ];
        
            $res[] = array_merge($order->toArray() , $extra);
        }

        return response()->json([
            'status' => 'success',
            'data'=> [
                'data' => $res
            ],
        ], 200);
    }

    //SubmitProposal
    public function SubmitProposal(Request $request){
        $v = \Validator::make($request->all(), [
            'order_id' => 'required',
            'message' => 'required',
            'writer_amount' => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 200);
        }

        if($request->bid_id && $request->edit){ //edit bid
            $order = Order::find($request->order_id);
            Bid::where('id',$request->bid_id)->update([
                'bidding_price' =>$request->writer_amount,
                'message' =>$request->message,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success'
            ], 200);

        }else{
            $order = Order::find($request->order_id);
            $bidCheck = Bid::where('order_id',$request->order_id)->where('writer_id',Auth::user()->id)->count();
            if( $order->isHired || $order->isCompleted){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something Wrong!'
                ], 200);
            }else if( $bidCheck > 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'You Already Apply for this order!'
                ], 200);
            }else{
                Bid::insert([
                    'order_id' =>$request->order_id,
                    'writer_id' => Auth::user()->id,
                    'bidding_price' =>$request->writer_amount,
                    'message' =>$request->message,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                ]);
    
                if ($request->message) {
                    $token = $request->header('Authorization');
                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->post(url('/api/user/message/send'), [
                        'id_user' => $order->customer,
                        'message' => $request->message,
                    ]);
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success'
                ], 200);
            }

        }
        
    }

    //save order 
    public function saveOrder($order_id){
        if(SaveOrder::where('order_id',$order_id)->where('user_id',Auth::user()->id)->count() < 1){
            SaveOrder::insert([
                'order_id' =>$order_id,
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Success'
            ], 200);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Already Saved!'
            ], 200);
        }
    }

    //getBids
    public function getBids($status){
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6 ;

        $query = Order::select('orders.*','bids.id as bid_id', 'bids.bidding_price', 'bids.message','bids.decline','bids.created_at as bid_created_at')
            ->join('bids', 'bids.order_id', '=', 'orders.id')
            ->where('bids.writer_id', Auth::user()->id);
        if(isset($_GET['searchText'])){
            $query->where('orders.topic', 'like', '%' . $_GET['searchText'] . '%');
        }
        // if($status != 0){
        //     $query->where('bids.status',  $status);
        // }

        $orders = $query->orderBy('bids.created_at', 'desc')->paginate($per_page);

        $res = [];
        foreach ($orders as $order) {
                //get writer total order 
                $total_bids = Bid::where('order_id',$order->id)->count();
                $rate = Review::where('order_id',$order->id)->first();
                $extra=[
                    'total_bids' => $total_bids,
                    'rate' =>isset( $rate->star) ? $rate->star : null,
                ];
        
            $res[] = array_merge($order->toArray() , $extra);
        }

        return response()->json([
            'status' => 'success',
            'data'=> [
                'data' => $res
            ],
        ], 200);
    }

    //declineBid
    public function declineBid($bid_id){
        Bid::where('id',$bid_id)->update([
            'decline' => 1,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Declined successfully!",
        ]);
    }

    //undoDeclineBid
    public function undoDeclineBid($bid_id){
        Bid::where('id',$bid_id)->update([
            'decline' => 0,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Updated successfully!",
        ]);
    }

    //get edit bid data
    public function editBid($bid_id){
        $order = Order::select('orders.*','bids.id as bid_id', 'bids.bidding_price', 'bids.message','bids.decline','bids.created_at as bid_created_at')
        ->join('bids', 'bids.order_id', '=', 'orders.id')
        ->where('bids.id', $bid_id)
        ->first();
        return response()->json([
            'success' => true,
            'main_order' => $order,
            'product' => Product::findOrFail($order->product)->title,
            'level' => EducationLevel::findOrFail($order->level)->title,
            'language' => Language::findOrFail($order->language)->title,
            'service' => Service::findOrFail($order->service)->title,
            'citiation_style' => $order->citiation_style ? CitationStyle::findOrFail($order->citiation_style)->title : '',
            'subject' => $order->subject ? Subject::findOrFail($order->subject)->title : '',
            'deadline' => date('d M Y, h:i:s A',strtotime($order->deadline)),
        ]);
    }
}
