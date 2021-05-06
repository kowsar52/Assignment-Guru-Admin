<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\WriterOrder;
use App\Models\Notifications;
use App\Models\Messages;
use App\Models\Review;
use Auth,Cache,DB;
use Illuminate\Support\Str;


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
        if(Auth::user() && Auth::user()->role == 'writer'){
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


    //notifications
    public function notifications()
    {
      // Notifications
      $notifications = DB::table('notifications')
         ->select(DB::raw('
        notifications.id id_noty,
        notifications.type,
        notifications.title,
        notifications.target,
        notifications.status as isSeen,
        notifications.created_at,
        notifications.author,
        users.id userId,
        users.first_name,
        users.last_name,
        users.avater,
        orders.id,
        orders.topic,
        users.first_name usernameAuthor
        '))
        ->leftjoin('users', 'users.id', '=', DB::raw('notifications.author'))
        ->leftjoin('orders', 'orders.id', '=', DB::raw('notifications.target '))
        ->where('notifications.destination', '=',  Auth::user()->id )
        ->where('users.status', '=',  1)
        ->groupBy('notifications.id')
        ->orderBy('notifications.id', 'DESC')
        ->paginate(20);

      // Mark seen Notification
      $getNotifications = Notifications::where('destination', Auth::user()->id)->where('status', '0');
      $getNotifications->count() > 0 ? $getNotifications->update(['status' => '1']) : null;

      $res = [];
      foreach ($notifications as $key) {
        if (Cache::has('is-online-' . $key->userId)) {
            $isOnline = true;
        } else {
            $isOnline = false;
        }
        switch ($key->type) {
            case 1:
                $res [] = [
                    'avater'          => $key->avater,
                    'created_at'          => $key->created_at,
                    'userId'          => $key->userId,
                    'first_name'          => $key->first_name,
                    'last_name'          => $key->last_name,
                    'isOnline'          => $isOnline,
                    'isSeen'          => (int) $key->isSeen,
                    'linkDestination' => '/user/manage_order/writer/'.$key->target,
                    'title'       => $key->title,
                ];
                break;
            case 2:
               
            case 3:
            }
        }

      return response()->json($res);
    }

    public function settingsNotifications(Request $request)
    {

      $user = User::find(Auth::user()->id);
      $user->notifications = $request->notifications;
      $user->email_notifications = $request->email_notifications;
      $user->save();

      return response()->json([
          'success' => true,
      ]);
    }

    public function deleteNotifications()
    {
      Auth::user()->notifications()->delete();
      return back();
    }

    //cehck new message and notification
    public function checkNewNotifications()
    {
        $getNotifications = Notifications::where('destination', Auth::user()->id)->where('status', '0');
        $getmessages = Messages::where('to_user_id', Auth::user()->id)->where('status', 'new');
        return response()->json([
            'unseen_notifications' =>  $getNotifications->count(),
            'unseen_messages' =>  $getmessages->count(),
        ]);
    }
}
