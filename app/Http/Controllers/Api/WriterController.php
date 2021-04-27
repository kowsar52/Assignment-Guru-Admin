<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\WriterOrder;
use App\Models\InviteWriter;
use App\Models\Review;
use Auth,Cache;

class WriterController extends Controller
{
    //getWriters
    public function getWriters($status){
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6 ;

        $query = User::select('users.*')
            ->where('users.role', 'writer')
            ->where('users.id', '!=', Auth::user()->id);
        if(isset($_GET['searchText'])){
            $query->where('users.first_name', 'like', '%' . $_GET['searchText'] . '%');
        }
        if($status == 'mine'){
            // $query->where('users.status',  $status);
        }else if($status == 'blocked'){
            // $query->where('users.status',  $status);
        }
        $users = $query->orderBy('users.id', 'desc')->paginate( $per_page);

        $res = [];
        foreach ($users as $user) {
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
            $res[]=[
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'short_about' => $user->short_about,
                'avater' => $user->avater,
                'status' => $user->status,
                'total_orders' => $total_orders,
                'success_percentage' => $success_percentage,
                'isOnline' => $isOnline,
                'avg_rating' => round($avg_rating,1),
                'total_reviews' => $total_review,
                'created_at' => $user->created_at,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $res,
            ],
        ]);
    }

    public function getInviteWriters($status,$order_id){
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6 ;

        $query = User::select('users.*')
            ->where('users.role', 'writer')
            ->where('users.id', '!=', Auth::user()->id);
        if(isset($_GET['searchText'])){
            $query->where('users.first_name', 'like', '%' . $_GET['searchText'] . '%');
        }
        if($status == 'mine'){
            // $query->where('users.status',  $status);
        }else if($status == 'blocked'){
            // $query->where('users.status',  $status);
        }
        $users = $query->orderBy('users.id', 'desc')->paginate( $per_page);

        $res = [];
        foreach ($users as $user) {
            $check_exist = InviteWriter::where('order_id',$order_id)->where('writer_id',$user->id)->count();
            if(!$check_exist > 0){
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
                $res[]=[
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'short_about' => $user->short_about,
                    'avater' => $user->avater,
                    'status' => $user->status,
                    'total_orders' => $total_orders,
                    'success_percentage' => $success_percentage,
                    'isOnline' => $isOnline,
                    'avg_rating' => round($avg_rating,1),
                    'total_reviews' => $total_review,
                    'created_at' => $user->created_at,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $res,
            ],
        ]);
    }
}
