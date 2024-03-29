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
use App\Models\OrderStatus;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryFile;
use App\Models\OrderStatusTrack;
use App\Models\Notifications;
use App\Models\User;
use App\Helper;
use Illuminate\Support\Facades\Http;
use Auth, Validator, Image;


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
                $status = OrderStatus::findOrFail($order->status);
                $extra=[
                    'status' => $status,
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


    //////upload delivery
    public function uploadDelivery(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(array('session_null' => true));
        }

        // Setup the validator
        $rules = [
            'files' => 'required',
            'message' => 'required|min:1|max:' . Settings::getOption('comment_length') . '',
        ];

        $messages = [
            "required" => trans('validation.required'),
            "message.max" => trans('validation.max.string'),
            'files.dimensions' => trans('general.validate_dimensions'),
            'files.mimetypes' => trans('general.formats_available'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ));
        }

        // $settings = AdminSettings::first();

        // PATHS
        $path = config('path.delivery');

        $sizeAllowed = Settings::getOption('MAX_FILE_SIZE_ALLOW') * 1024;

        // Find order in Database
        $order = Order::findOrFail($request->order_id);
        $files = $request->file('files');

        if($request->hasFile('files'))
        {
            $allowedfileExtension=['pdf','jpg','png','docx','zip','jpeg'];
            $files = $request->file('files');
            $file_ids = [];
            foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check=in_array($extension,$allowedfileExtension);
                //dd($check);
                if($check)
                {
                    // $filename = $photo->store('files');
                    // $file->move($destinationPath,$file->getClientOriginalName());
                    $file->storePubliclyAs($path, $filename);
                    $file_ids[] = OrderDeliveryFile::insertGetId([
                        'order_id' => $order->id,
                        'file' => $filename,
                        'original_name' => $filename,
                        'format' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                        'details' => $request->message,
                        'created_at' => Carbon::now(),
                    ]);
                    
                }
                else
                {
                    return response()->json(array(
                        'success' => false,
                        'order_id' => $order->id,
                    ), 200);
                }
            }

            if(!empty($file_ids)){
                Order::where('id',$order->id)->update([
                    'status' => 4, //delivered
                    'updated_at' => Carbon::now(),
                ]);
                OrderStatusTrack::insert([
                    'status_id' => 4, //completed
                    'order_id' => $order->id,
                    'created_at' => Carbon::now(),
                ]);
                OrderDelivery::insert([
                    'order_id' => $order->id,
                    'delivery_files' =>json_encode($file_ids),
                    'message' => $request->message,
                    'created_at' => Carbon::now(),
                ]);

            // Send Notification to User --- destination, author, type, target, title
            $notify_user = User::findOrFail($order->customer);
            if($notify_user->email_notifications){
                Notifications::send($order->customer, auth()->user()->id, '1', $order->id,'<strong>'.Auth::user()->username.'</strong> Send You Order Delivery');
            }

            return response()->json(array(
                'success' => true,
                'order_id' => $order->id,
            ), 200);
            }

        }


    } //<<--- End Method uploadDelivery()
}
