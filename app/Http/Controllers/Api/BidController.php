<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\InviteWriter;
use App\Models\Review;
use App\Models\WriterOrder;
use Carbon\Carbon;
use Cache;

class BidController extends Controller
{
    //getBidders
    public function getBidders($id,$type){
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6 ;
        if($type == 'bids'){
            $data = Bid::select('users.*','bids.id as bid_id', 'bids.message as bid_message','bids.order_id as order_id','bids.shortlisted as shortlisted','bids.decline','bids.bidding_price','bids.created_at as bid_at')
                        ->join('users','users.id','=','bids.writer_id')
                        ->where('bids.order_id',$id)
                        ->orderBy('bids.created_at','desc')->paginate($per_page);
        }else if($type == 'invitations'){
            $data = InviteWriter::select('users.*','invite_writers.id as invite_id','invite_writers.order_id as order_id','invite_writers.created_at as invited_at')
                                ->join('users','users.id','=','invite_writers.writer_id')
                                ->where('invite_writers.order_id',$id)
                                ->orderBy('invite_writers.created_at','desc')->paginate($per_page);
        }else{
            $data = Bid::select('users.*','bids.id as bid_id', 'bids.message as bid_message','bids.order_id as order_id','bids.shortlisted as shortlisted','bids.decline','bids.bidding_price','bids.created_at as bid_at')
                        ->join('users','users.id','=','bids.writer_id')
                        ->where('bids.order_id',$id)
                        ->where('bids.shortlisted',1)
                        ->orderBy('bids.created_at','desc')->paginate($per_page);
        }

        $res = [];
        foreach ($data as $user) {
                //get writer total order 
                $total_orders = WriterOrder::where('writer_id',$user->id)->where('status',3)->count();
                $total_review = Review::where('user_id',$user->id)->where('type','writer')->count();
                $avg_rating = Review::where('user_id',$user->id)->where('type','writer')->avg('star');
                if (Cache::has('is-online-' . $user->id)) {
                    $isOnline = true;
                } else {
                    $isOnline = false;
                }
                $success_percentage = 99;
                $extra=[
                    'total_orders' => $total_orders,
                    'success_percentage' => $success_percentage,
                    'isOnline' => $isOnline,
                    'avg_rating' => round($avg_rating,1),
                    'total_reviews' => $total_review,
                ];
        
            $res[] = array_merge($user->toArray() , $extra);
        }

        return response()->json([
            'status' => 'success',
            'data'=> [
                'data' => $res
            ],
        ], 200);
    }

    //inviteWriter
    public function inviteWriter($writer_id,$order_id){
        InviteWriter::insert([
            'writer_id' => $writer_id,
            'order_id' => $order_id,
            'status' => 1, //active
            'created_at' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Invited Successfully!",
        ]);
    }

    //cancel invitation
    public function cancelInviteWriter($inviation_id){
        InviteWriter::findOrFail($inviation_id)->delete();
        return response()->json([
            'success' => true,
            'message' => "Invitation cancel successfully!",
        ]);
    }

    //addToShortList
    public function addToShortList($bid_id){
        Bid::where('id',$bid_id)->update([
            'shortlisted' => 1,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Updated successfully!",
        ]);
    }

    //removeFromShortList
    public function removeFromShortList($bid_id){
        Bid::where('id',$bid_id)->update([
            'shortlisted' => 0,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Updated successfully!",
        ]);
    }

    //declineBid
    public function declineBid($bid_id){
        Bid::where('id',$bid_id)->update([
            'decline' => 1,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => "Updated successfully!",
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
}
