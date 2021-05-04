<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use Auth;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    //getOrderFeedback
    public function getOrderFeedback($order_id){
        $order = Order::findOrFail($order_id);
        if(Auth::user()->role == 'writer'){
            $buyer_review = Review::where('order_id',$order_id)->where('user_id',$order->customer)->where('type','buyer')->first();
            $writer_review = Review::where('order_id',$order_id)->where('user_id',Auth::user()->id)->where('type','writer')->first();
            if(empty($buyer_review) || empty($writer_review) ){
                if(empty(Review::where('order_id',$order_id)->where('user_id',$order->customer)->where('type','buyer')->first())){
                    return response()->json([]);
                }else{
                    return response()->json([
                        'buyer_review' =>  'need_writer_feedback',
                        'writer_review' => false,
                    ]);
    
                }
    
            }else{
                return response()->json([
                    'buyer_review' =>  $buyer_review,
                    'writer_review' => empty( $writer_review) ? false :  $writer_review,
                ]);
            }

        }else{
            $buyer_review = Review::where('order_id',$order_id)->where('user_id',Auth::user()->id)->where('type','buyer')->first();
            $writer_review = Review::where('order_id',$order_id)->where('user_id',$order->writer)->where('type','writer')->first();
            if(empty($buyer_review)){
                if(empty(Review::where('order_id',$order_id)->where('user_id',$order->customer)->where('type','buyer')->first())){
                    return response()->json([]);
                }else{
                    return response()->json([
                        'buyer_review' =>  'need_writer_feedback',
                        'writer_review' => false,
                    ]);
    
                }
    
            }else{
                return response()->json([
                    'buyer_review' =>  $buyer_review,
                    'writer_review' => empty( $writer_review) ? false :  $writer_review,
                ]);
            }

        }
    }

    //submitFeedback
    public function submitFeedback(Request $request){
        $v = \Validator::make($request->all(), [
            'star' => 'required',
            'feedback' => 'required',
            'order_id' => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'Feedback and rating is requered.'
            ], 200);
        }

        if(!empty(Review::where('order_id',$request->order_id)->where('user_id',Auth::user()->id)->where('type',Auth::user()->role)->first())){
            return response()->json([
                'success' => false,
                'message' => 'Already rated this order.'
            ], 200);
        }else{
            Review::insert([
                'order_id' => $request->order_id,
                'user_id' => Auth::user()->id,
                'type' => Auth::user()->role,
                'star' => $request->star,
                'feedback' => $request->feedback,
                'created_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rated Successfully'
            ], 200);
            
        }
    }
}
