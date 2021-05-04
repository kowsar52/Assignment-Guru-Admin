<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryFile;
use Storage,Auth;

class DownloadController extends Controller
{
    //downloadDeliveryFile
    public function downloadDeliveryFile($id){
        $data = OrderDeliveryFile::select('order_delivery_files.*')
                                ->join('orders','orders.id','order_delivery_files.order_id')
                                ->where('order_delivery_files.id',$id)
                                ->where(function ($query) {
                                    $query->where('orders.writer', '=',Auth::user()->id)
                                          ->orWhere('orders.customer', '=',Auth::user()->id);
                                })->first();
         if(!empty( $data)){
             $path = config('path.delivery');
             $filePath  = public_path('/storage/'.config('path.delivery').$data->file);
             $fileName = time().'.'.$data->format;
     
             return response()->download($filePath,$fileName);
         }                       
    }
}
