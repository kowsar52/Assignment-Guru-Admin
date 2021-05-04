<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\WriterOrder;
use App\Models\Review;
use Auth,Cache;


class UserController extends Controller
{
    //updateProfile
    public function updateProfile(Request $request){
        $v = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 200);
        }

        if($img = $request->new_avater){
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $name =  uniqid() . '. '.$image_type;

            file_put_contents(public_path('uploads/user/'.$name), $image_base64);
            $avater = 'uploads/user/'.$name;

            if(file_exists(public_path($request->avater))){
                unlink(public_path($request->avater));
            }
        }else{
            $avater = $request->avater;

        }
        User::where('id',$request->id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'about' => $request->about,
            'short_about' => $request->short_about,
            'avater' => $avater,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    //delete account
    public function deleteAccount(){
        $user = User::findOrFail(Auth::user()->id);
        $order = Order::where('customer',Auth::user()->id)->delete();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => "User Deleted Successfully!",
        ]);
    }

    //getUser
    public function getUser($id){
        $user = User::findOrFail($id);
        if(Auth::user()->role == 'writer'){
            $total_orders = WriterOrder::where('writer_id',$user->id)->where('status',3)->count();
            $total_review = Review::where('user_id',$user->id)->where('type','writer')->count();
            $reviews = Review::where('user_id',$user->id)->where('type','writer')->get();
            $avg_rating = Review::where('user_id',$user->id)->where('type','writer')->avg('star');
            
        }else{    
            $total_orders = Order::where('customer',$user->id)->where('status',3)->count();
            $total_review = Review::where('user_id',$user->id)->where('type','buyer')->count();
            $reviews = Review::where('user_id',$user->id)->where('type','buyer')->get();
            $avg_rating = Review::where('user_id',$user->id)->where('type','buyer')->avg('star');
        }
        if (Cache::has('is-online-' . $user->id)) {
            $isOnline = true;
        } else {
            $isOnline = false;
        }
        $success_percentage = 99;
        $data=[
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'about' => $user->about,
            'short_about' => $user->short_about,
            'country' => $user->country,
            'avater' => $user->avater,
            'status' => $user->status,
            'total_orders' => $total_orders,
            'success_percentage' => $success_percentage,
            'isOnline' => $isOnline,
            'avg_rating' => round($avg_rating,1),
            'total_reviews' => $total_review,
            'reviews' => $reviews,
            'languages' => $languages = "English (US) ,  English (UK)",
            'last_seen' => isset($user->last_seen) ? Carbon::parse($user->last_seen)->diffForHumans() : '1 day ago',
            'created_at' => $user->created_at,
        ];
        return response()->json([
            'success' => true,
            'data' =>  $data,
        ]);
    }
}
