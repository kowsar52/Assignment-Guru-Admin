<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,App;
use DataTables;
use App\Models\User;
use App\Models\Coupon;
use Auth;
use Validator;

class CouponController extends Controller
{
    public function Index(Request $request)
    {
        if ($request->ajax()) {
            $data = Coupon::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function($row){
                        if($row->status == 1){
                            $btn = '<span class="badge badge-success">Active</span>'; 
                        }else{
                            $btn = '<span class="badge badge-danger">Deactive</span>'; 
                        }
                        return $btn;
                    })
                    ->editColumn('deadline', function($row){
                        
                        return date("jS \of F Y h:i:s A",strtotime($row->deadline));
                    })
                    
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/coupon/edit',$row->id).'" class="btn btn-rounded btn-warning"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','deadline'])
                    ->make(true);
        }
        
    	return view('admin.coupons.index');
    }
    
    public function Add(Request $request)
    {
        
        if ($request->isMethod('post')) {

            
	        $validator = Validator::make($request->all(), [
                'title'         => 'required',
                'code'          => 'required',
	            'percentage'    => 'required',
	            'deadline'      => 'required',
	            'status'        => 'required',
            ]);
	        if($validator->passes()) {
	            
	            $coupon = new Coupon();
	            $coupon->title          = $request->title;
	            $coupon->code           = $request->code;
	            $coupon->percentage     = $request->percentage;
	            $coupon->deadline       = date("Y-m-d h:i:s",strtotime($request->deadline));
	            $coupon->status         = $request->status;
	            if($coupon->save())
	            {
	                $error['error']     = false;
                    $error['check']     = false;
                    $error['message']   = 'Successfully Saved';
	            }else{
	                
	                $error['error']     = false;
                    $error['check']     = false;
                    $error['message']   = 'Save failed';
	            }
	            
                
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
        }
        
        return view('admin.coupons.create');
    }
    public function Edit(Request $request,$id)
    {
        $coupon = Coupon::where('id',$id)->first();
        if($coupon == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {

            
	        $validator = Validator::make($request->all(), [
                'title'         => 'required',
                'code'          => 'required',
	            'percentage'    => 'required',
	            'deadline'      => 'required',
	            'status'        => 'required',
            ]);
	        if($validator->passes()) {
	            
	            
	            $coupon->title          = $request->title;
	            $coupon->code           = $request->code;
	            $coupon->percentage     = $request->percentage;
	            $coupon->deadline       = date("Y-m-d h:i:s",strtotime($request->deadline));
	            $coupon->status         = $request->status;
	            if($coupon->update())
	            {
	                $error['error']     = false;
                    $error['check']     = false;
                    $error['message']   = 'Successfully Updated';
	            }else{
	                
	                $error['error']     = false;
                    $error['check']     = false;
                    $error['message']   = 'Update failed';
	            }
	            
                
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
        }
        
        
        return view('admin.coupons.edit',['coupon'=>$coupon]);
    }
    public function Destroy($id)
    {
    	$error['error']     = true;
        $error['msg']       = '';
        $Coupon = Coupon::where('id',$id)->first();
        if($Coupon == false)
        {
            $error['msg']       = 'Coupon not found';
        }else{
            $error['error']     = false;
            $error['msg']       = 'Coupon successfully deleted';
            $Coupon->delete();
        }
         return response()->json($error);
    }
    
}