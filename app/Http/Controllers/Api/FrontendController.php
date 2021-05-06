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
use App\Models\User;
use App\Models\WriterOrder;
use App\Models\Review;
use App\Models\ThemeOrderContent;
use App\Models\HomeWritingFeature;
use App\Models\FrequentlyAskedQuestion;
use App\Models\Page;
use Carbon\Carbon;
use Auth,Cache;

class FrontendController extends Controller
{
    public $language_id = null;
    public function __construct(Request $request)
    {
        $this->request = $request;
        if($request->get('locale')){
            $lang = Language::where('code',$request->get('locale'))->first();
            if(!empty($lang)){
                $this->language_id = $lang->id;
            }else{
                $this->language_id = Language::orderBy('id','asc')->first()->id;
            }
        }else{
            $this->language_id = Language::orderBy('id','asc')->first()->id;
        }
    }
//get language
    public function getLanguage(){
        return response()->json([
            'status' => 'success',
            'data'=> [
                'languages' => Language::where('status',1)->orderBy('title','asc')->get(),
            ]
        ], 200);
    }

    public function getCalculatorPrice(Request $request){
        $total_price = 0;
        $sub_total_price = 0;
        $discount_percentage = 0;
        $diff_duration = 0;

        if($request->product && $request->service && $request->level){

                $product_price = Product::find($request->product)->price; //get assignment type price
                $service_price = Service::find($request->service)->price; //get service price
                $education_level_price = EducationLevel::find($request->level)->price; //get level price
                if($request->space =="single" || $request->space == 'single'){
                    $space = 2;
                }else{
                    $space = 1; //if double space then it's 1
                }

                if($request->deadline < 1){
                    $total_price = ($product_price + $education_level_price) * $request->quantity * $space;

                }else{
                    $deadline = Deadline::where('status',1)->where('duration',$request->deadline)->first();
                    if(!empty($deadline)){
                        $total_price = (($product_price + $education_level_price) - $deadline->price) * $request->quantity * $space;
                    }else{
                        $l_deadline = Deadline::where('status',1)->orderBy('duration','desc')->first();
                        $total_price = (($product_price + $education_level_price) - $l_deadline->price) * $request->quantity * $space;
                    }
             
                }
                $discount_percentage = Settings::getOption('discount'); 
                $sub_total_price = $total_price - (($total_price * $discount_percentage) / 100) ;

        }

      

        return response()->json([
            'total_price' => number_format($total_price,2,".",',') ,
            'sub_total_price' => number_format($sub_total_price,2,".",','),
            'discount_percentage' => $discount_percentage,
            'diff_duration' => $request->deadline,
        ]);
    }

    //getWriters
    public function getWriters(){ //get only top rated writers
        
        $users = User::select('users.*')
            ->where('users.role', 'writer')
            ->where('users.status', 1)
            ->get();

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

        //order desc by total orders
        $reference_array = [];
        $column = "total_orders";
        foreach($res as $key => $row) {
            $reference_array[$key] = $row[$column];
        } 
        array_multisort($reference_array,SORT_DESC, $res);
        //order desc by total end

        return response()->json([
            'success' => true,
            'data' => $res,
        ]);
    }

    //getServices
    public function getServices(){ //get products
        $proucts = Product::where('status', 1)->get();

        $res = [];
        foreach ($proucts as $prouct) {
            //get writer total order 
            if($product->parent == 0){
                $res[]['parent'] = [
                    'id' => $prouct->id,
                    'title' => $prouct->title,
                    'created_at' => $prouct->created_at,
                ];
            }else{
                $res[]['sub_item']=[
                    'id' => $prouct->id,
                    'title' => $prouct->title,
                    'created_at' => $prouct->created_at,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $res,
        ]);
    }

    //get service from services table
    public function getServiceTypes(){ //get 
        $res = Service::where('language_id', $this->language_id)->get();
        return response()->json($res);
    }
    //getPageContent
    public function getPageContent($slug){ //get 
        $res = Page::where('language_id', $this->language_id)->where('slug',$slug)->first();
        return response()->json($res);
    }

    //getReviews
    public function getReviews(){
        $res = Review::select('reviews.id','reviews.star','reviews.feedback','reviews.created_at','reviews.user_id as buyer_id','orders.topic as order_title','users.first_name','users.last_name')
                        ->join('orders','orders.id','=','reviews.order_id')
                        ->join('users','users.id','=','orders.writer')
                        ->where('reviews.star', '>',3)
                        ->where('reviews.type','buyer')
                        ->orderBy('reviews.star','desc')
                        ->limit(20)
                        ->get();

    
        return response()->json([
            'success' => true,
            'data' => $res,
        ]);
    }

    //getWritingServiceFeatures
    public function getWritingServiceFeatures(){
        $res = HomeWritingFeature::where('language_id', $this->language_id)
                        ->orderBy('created_at','asc')
                        ->limit(6)
                        ->get();

    
        return response()->json([
            'success' => true,
            'data' => $res,
        ]);
    }

    //getFAQs
    public function getFAQs(){
        $res = FrequentlyAskedQuestion::where('language_id', $this->language_id)
                        ->orderBy('created_at','asc')
                        ->limit(16)
                        ->get();
        $left_side = array_slice($res->toArray() , 0, count($res) / 2);
        $right_side = array_slice($res->toArray() , count($res) / 2);
     

        return response()->json([
            'success' => true,
            'data' => [
                'left_side' => $left_side,
                'right_side' => $right_side,
            ],
        ]);
    }

    //getHowToOrders
    public function getHowToOrders(){
        $res = ThemeOrderContent::where('language_id', $this->language_id)
                        ->orderBy('created_at','asc')
                        ->limit(3)
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $res,
        ]);
    }
}
