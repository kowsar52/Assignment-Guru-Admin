<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,App;
use DataTables;
use App\Models\User;
use App\Models\Review;
use App\Models\PaymentGateways;
use Auth;
use Validator;

class ReviewController extends Controller
{
    public function Index(Request $request)
    {
        if ($request->ajax()) {
            $data = Review::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    
                    ->editColumn('user', function($row){
                        
                        $user = User::find($row->user_id);
                        return $user->first_name.' '.$user->last_name;
                    })->editColumn('type', function($row){
                        
                        return ucfirst($row->type);
                    })
                    
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','user','type'])
                    ->make(true);
        }
        
    	return view('admin.reviews.index');
    }
    
    public function Destroy($id)
    {
        $error['error']     = true;
        $error['msg']       = '';
        $Review = Review::where('id',$id)->first();
        if($Review == false)
        {
            $error['msg']       = 'Review not found';
        }else{
            $error['error']     = false;
            $error['msg']       = 'Review successfully deleted';
            $Review->delete();
        }
         return response()->json($error);
    }
    
    public function PaymentGetWay(Request $request)
    {
        
        if ($request->ajax()) {
            $data = PaymentGateways::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    
                    ->editColumn('type', function($row){
                        
                        return ucfirst($row->type);
                    })
                    
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','type'])
                    ->make(true);
        }
        
    	return view('admin.reviews.paymentgetway');
    }
}