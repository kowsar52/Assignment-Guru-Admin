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
}
