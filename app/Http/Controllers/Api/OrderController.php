<?php

namespace App\Http\Controllers\Api;

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
use Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getOrderRequirements','getPrice']]);
    }

    //order-requirements
    public function getOrderRequirements(){
        $sources = [];
        $x = 1;
        while($x <= 30) {
            $sources[]['number'] = $x;
            $x++;
          }

          $m_products = Product::where('status',1)->where('parent',0)->orderBy('title','asc')->get();
          $products = [];
          foreach( $m_products as  $m_product){
              $t_sub_products =   Product::where('status',1)->where('parent',$m_product->id)->orderBy('title','asc')->get();
              if(!empty($t_sub_products)){
                  $sub_products = [];
                  foreach($t_sub_products as $t_sub_product){
                        $sub_products[] = [
                            'id' => $t_sub_product->id,
                            'title' => $t_sub_product->title,
                        ];
                  }

                  $products[] = [
                      'id' => $m_product->id,
                      'title' => $m_product->title,
                      'sub_products' => $sub_products,
                  ];

              }else{
                  $products[] = [
                      'id' => $m_product->id,
                      'title' => $m_product->title,
                  ];
              }
          }

        return response()->json([
            'status' => 'success',
            'data'=> [
                'products' => $products,
                'education_levels' => EducationLevel::where('status',1)->orderBy('title','asc')->get(),
                'languages' => Language::where('status',1)->orderBy('title','asc')->get(),
                'services' => Service::where('status',1)->orderBy('id','asc')->get(),
                'citiation_styles' => CitationStyle::where('status',1)->orderBy('title','asc')->get(),
                'subjects' => Subject::where('status',1)->orderBy('title','asc')->get(),
                'amount' => 1,
                'words_count' => 275,
                'space' => "double",
                'sources' => $sources,
            ]
        ], 200);
    }

    //get total price 
    public function getPrice(Request $request){
        $total_price = 0;
        $sub_total_price = 0;
        $discount_percentage = 0;
        $diff_duration = 0;

        if($request->deadline && $request->product && $request->service && $request->level){
            $today = date("Y-m-d H:i:s");

            $today_time = strtotime($today);
            $deadline = strtotime($request->deadline);

            if ($deadline > $today_time) { 
                //get duration
                $to = date("Y-m-d H:i:s");
                $from = date('Y-m-d H:i:s', strtotime($request->deadline));
                $date1=date_create($to);
                $date2=date_create($from);
                $different_days=date_diff($date1,$date2);
                $diff_duration = $different_days->days;
                //end get duration

                $product_price = Product::find($request->product)->price; //get assignment type price
                $service_price = Service::find($request->service)->price; //get service price
                $education_level_price = EducationLevel::find($request->level)->price; //get level price
                if($request->space =="single" || $request->space == 'single'){
                    $space = 2;
                }else{
                    $space = 1; //if double space then it's 1
                }

                if($diff_duration < 1){
                    $total_price = ($product_price + $education_level_price) * $request->quantity * $space;

                }else{
                    $deadline = Deadline::where('status',1)->where('duration',$diff_duration)->first();
                    if(!empty($deadline)){
                        $total_price = (($product_price + $education_level_price) - $deadline->price) * $request->quantity * $space;
                    }else{
                        $l_deadline = Deadline::where('status',1)->orderBy('duration','desc')->first();
                        $total_price = (($product_price + $education_level_price) - $l_deadline->price) * $request->quantity * $space;
                    }
             
                }
                $discount_percentage = 0 ;
                if($request->promocode){
                    $coupon = Coupon::where('code',$request->promocode)->where('status',1)->where('deadline', '>=', date('Y-m-d H:i:s'))->first();
                    if(!empty($coupon)){
                        $discount_percentage = $coupon->percentage; 
                    }
                }
                 $discount_percentage =  $discount_percentage + Settings::getOption('discount'); 
                $sub_total_price = $total_price - (($total_price * $discount_percentage) / 100) ;

             }
        }

      

        return response()->json([
            'total_price' => number_format($total_price,2,".",',') ,
            'sub_total_price' => number_format($sub_total_price,2,".",','),
            'discount_percentage' => $discount_percentage,
            'diff_duration' => $diff_duration,
        ]);
    }

    static function getPriceStatic($request){
        $total_price = 0;
        $sub_total_price = 0;
        $discount_percentage = 0;
        $diff_duration = 0;

        if($request->deadline && $request->product && $request->service && $request->level){
            $today = date("Y-m-d H:i:s");

            $today_time = strtotime($today);
            $deadline = strtotime($request->deadline);

            if ($deadline > $today_time) { 
                //get duration
                $to = date("Y-m-d H:i:s");
                $from = date('Y-m-d H:i:s', strtotime($request->deadline));
                $date1=date_create($to);
                $date2=date_create($from);
                $different_days=date_diff($date1,$date2);
                $diff_duration = $different_days->days;
                //end get duration

                $product_price = Product::find($request->product)->price; //get assignment type price
                $service_price = Service::find($request->service)->price; //get service price
                $education_level_price = EducationLevel::find($request->level)->price; //get level price
                if($request->space =="single" || $request->space == 'single'){
                    $space = 2;
                }else{
                    $space = 1; //if double space then it's 1
                }

                if($diff_duration < 1){
                    $total_price = ($product_price + $education_level_price) * $request->quantity * $space;

                }else{
                    $deadline = Deadline::where('status',1)->where('duration',$diff_duration)->first();
                    if(!empty($deadline)){
                        $total_price = (($product_price + $education_level_price) - $deadline->price) * $request->quantity * $space;
                    }else{
                        $l_deadline = Deadline::where('status',1)->orderBy('duration','desc')->first();
                        $total_price = (($product_price + $education_level_price) - $l_deadline->price) * $request->quantity * $space;
                    }
             
                }
                $discount_percentage = 0 ;
                if($request->promocode){
                    $coupon = Coupon::where('code',$request->promocode)->where('status',1)->where('deadline', '>=', date('Y-m-d H:i:s'))->first();
                    if(!empty($coupon)){
                        $discount_percentage = $coupon->percentage; 
                    }
                }
                 $discount_percentage =  $discount_percentage + Settings::getOption('discount'); 
                $sub_total_price = $total_price - (($total_price * $discount_percentage) / 100) ;

             }
        }

        return [
            'total_price' => number_format($total_price,2,".",',') ,
            'sub_total_price' => number_format($sub_total_price,2,".",','),
            'discount_percentage' => $discount_percentage,
            'diff_duration' => $diff_duration,
        ];
    }

    //get getOrders
    public function getOrders($status){
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6 ;

        $query = Order::select('orders.*', 'products.title as product_title', 'languages.title as language_title')
            ->join('products', 'products.id', '=', 'orders.product')
            ->join('languages', 'languages.id', '=', 'orders.language')
            ->where('orders.customer', Auth::user()->id);
        if(isset($_GET['searchText'])){
            $query->where('orders.topic', 'like', '%' . $_GET['searchText'] . '%');
        }
        if($status != 0){
            $query->where('orders.status',  $status);
        }

        $orders = $query->orderBy('orders.id', 'desc')->paginate($per_page);

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

    //get getOrder
    public function getOrder($id){
       
        $order = Order::select('orders.*', 'products.title as product_title', 'languages.title as language_title')
            ->join('products', 'products.id', '=', 'orders.product')
            ->join('languages', 'languages.id', '=', 'orders.language')
            ->where('orders.id', $id)
            ->where('orders.customer', Auth::user()->id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    //delete order
    public function deleteOrder($id){
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            'success' => true,
            'message' => "Order Deleted Successfully!",
        ]);
    }

    //get getOrderDetails
    public function getOrderDetails($id){
        $order = Order::findOrFail($id);
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

    //create order
    public function create(Request $request){
                   
        if(!$request->id){ //create new order
            $v = \Validator::make($request->all(), [
                'product' => 'required',
                'service' => 'required',
                'deadline' => 'required',
                'language' => 'required',
                'level' => 'required',
                'words_count' => 'required',
                'space' => 'required',
            ]);
            if ($v->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 200);
            }
            
            $today = date("Y-m-d H:i:s");
            $today_time = strtotime($today);
            $deadline = strtotime($request->deadline);

            if ($deadline > $today_time) { 
                $get_price = $this->getPriceStatic($request); //calculate the price
                $id = Order::insertGetId([
                    'category' =>  $request->category,
                    'citiation_style' =>  $request->citiation_style,
                    'customer' =>  Auth::user()->id,
                    'deadline' =>  date('Y-m-d H:i:s',strtotime($request->deadline)),
                    'description' =>  $request->description,
                    'discount' => $get_price['discount_percentage'],
                    'for_final_date' =>  $request->for_final_date,
                    'initial_deadline' =>  $request->initial_deadline,
                    'is_private' =>  $request->is_private,
                    'language' =>  $request->language,
                    'level' =>  $request->level,
                    'number_of_sources' =>  $request->number_of_sources,
                    'promocode' =>  $request->promocode,
                    'product' =>  $request->product,
                    'quantity' =>  $request->quantity,
                    'service' =>  $request->service,
                    'space' =>  $request->space,
                    'price' =>  $get_price['sub_total_price'],
                    'subject' =>  $request->subject,
                    'topic' =>  $request->topic,
                    'words_count' =>  $request->words_count,
                    'status' =>  $request->status,
                    'created_at' =>  Carbon::now(),
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'errors' => ['deadline' => "Please select a deadline greater then today"]
                ], 200);
            }

        }else{ //update existing order
            $id = $request->id;
            if($request->step == 1){
                $v = \Validator::make($request->all(), [
                    'product' => 'required',
                    'service' => 'required',
                    'deadline' => 'required',
                    'language' => 'required',
                    'level' => 'required',
                    'words_count' => 'required',
                    'space' => 'required',
                ]);
            }else{
                $v = \Validator::make($request->all(), [
                    'topic' => 'required',
                    'description' => 'required',
                ]);

            }
            if ($v->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 200);
            }
            $get_price = $this->getPriceStatic($request); //calculate the price
            //update data
            $today = date("Y-m-d H:i:s",strtotime('+3 hours'));
            $today_time = strtotime($today);
            $deadline = strtotime($request->deadline);

            if ($deadline > $today_time) { 
                Order::where('id',$id)->update([
                    'category' =>  $request->category,
                    'citiation_style' =>  $request->citiation_style,
                    'customer' =>  Auth::user()->id,
                    'deadline' =>  date('Y-m-d H:i:s',strtotime($request->deadline)),
                    'description' =>  $request->description,
                    'discount' => $get_price['discount_percentage'],
                    'for_final_date' =>  $request->for_final_date,
                    'initial_deadline' =>  $request->initial_deadline,
                    'is_private' =>  $request->is_private,
                    'language' =>  $request->language,
                    'level' =>  $request->level,
                    'number_of_sources' =>  $request->number_of_sources,
                    'promocode' =>  $request->promocode,
                    'product' =>  $request->product,
                    'quantity' =>  $request->quantity,
                    'service' =>  $request->service,
                    'space' =>  $request->space,
                    'price' =>  $get_price['sub_total_price'],
                    'subject' =>  $request->subject,
                    'topic' =>  $request->topic,
                    'words_count' =>  $request->words_count,
                    'status' =>  $request->status,
                    'updated_at' =>  Carbon::now(),
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'errors' => ['deadline' => "Please select a deadline greater then today"]
                ], 200);
            }

        }

        $order = Order::FindOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'=> $order
        ], 200);
    }
    
}