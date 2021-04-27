<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,App;
use DataTables;
use App\Models\Product;
use App\Models\Order;
use App\Models\EducationLevel;
use App\Models\Language;
use App\Models\Service;
use App\Models\CitationStyle;
use App\Models\Subject;
use App\Models\Settings;
use App\Models\Deadline;
use Carbon\Carbon;
use Auth;
use Validator;


class OrderController extends Controller
{
    //products
    public function products(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select();
       
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
                    ->editColumn('parent', function($row){
                        if($row->parent == 0){
                            return $btn = '<span class="badge badge-success">Parent Product</span>'; 
                        }else{
                            return $btn = '<span class="badge badge-info">Sub Product</span>'; 
                        }
                    })
                    ->editColumn('price', function($row){
                            return Settings::getOption('currency').number_format($row->price, 2, '.',',');
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','price','parent'])
                    ->make(true);
        }
        $parents = Product::where('parent',0)->orderBy('title','asc')->get();
    	return view('admin.order.products',compact('parents'));
    }

    public function productSave(Request $request){
        if($request->type == "edit" && isset($request->id)){ //update
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'price'        => 'required',
            ]);

            if ($validator->passes()) {
                
                 Product::where('id',$request->id)->update([
                    'title' => $request->title,
                    'price' => $request->price,
                    'status' => $request->status,
                    'parent' => $request->parent,
                    'updated_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'successfully updated.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        } else{ //create
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'price'        => 'required',
            ]);
            if ($validator->passes()) {
                
                Product::insert([
                    'title' => $request->title,
                    'price' => $request->price,
                    'status' => $request->status,
                    'parent' => $request->parent,
                    'created_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'Successfully created.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function editProduct(Request $request)
    {
        $user = Product::where('id',$request->id)->first();
        echo json_encode($user);
    }

    public function deleteProduct($id)
    {
        Product::where('id',$id)->delete();
        Order::where('product',$id)->delete(); //deleted the related product
        echo json_encode(['message' => " Product Deleted Successfully!"]);
    }

    //services management
    //Services
    public function services(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::select();
       
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
                    ->editColumn('price', function($row){
                            return Settings::getOption('currency').number_format($row->price, 2, '.',',');
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','price'])
                    ->make(true);
        }

    	return view('admin.order.services');
    }

    public function serviceSave(Request $request){
        if($request->type == "edit" && isset($request->id)){ //update
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                // 'price'        => 'required',
            ]);

            if ($validator->passes()) {
                
                 Service::where('id',$request->id)->update([
                    'title' => $request->title,
                    'price' => $request->price,
                    'status' => $request->status,
                    'updated_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'successfully updated.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        } else{ //create
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                // 'price'        => 'required',
            ]);
            if ($validator->passes()) {
                
                Service::insert([
                    'title' => $request->title,
                    'price' => $request->price,
                    'status' => $request->status,
                    'created_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'Successfully created.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function editService(Request $request)
    {
        $user = Service::where('id',$request->id)->first();
        echo json_encode($user);
    }

    public function deleteService($id)
    {
        Service::where('id',$id)->delete();
        Order::where('service',$id)->delete(); //deleted the related Service
        echo json_encode(['message' => " Service Deleted Successfully!"]);
    }


    //levels management
    //levels
    public function levels(Request $request)
    {
        if ($request->ajax()) {
            $data = EducationLevel::select();
       
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
                    ->editColumn('price', function($row){
                            return Settings::getOption('currency').number_format($row->price, 2, '.',',');
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','price'])
                    ->make(true);
        }

    	return view('admin.order.levels');
    }

    public function levelSave(Request $request){
        if($request->type == "edit" && isset($request->id)){ //update
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'price'        => 'required',
            ]);

            if ($validator->passes()) {
                
                EducationLevel::where('id',$request->id)->update([
                    'title' => $request->title,
                    'price' => $request->price,
                    'status' => $request->status,
                    'updated_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'successfully updated.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        } else{ //create
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'price'        => 'required',
            ]);
            if ($validator->passes()) {
                
                EducationLevel::insert([
                    'title' => $request->title,
                    'price' => $request->price,
                    'status' => $request->status,
                    'created_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'Successfully created.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function editLevel(Request $request)
    {
        $user = EducationLevel::where('id',$request->id)->first();
        echo json_encode($user);
    }

    public function deleteLevel($id)
    {
        EducationLevel::where('id',$id)->delete();
        Order::where('level',$id)->delete(); //deleted the related level
        echo json_encode(['message' => " level Deleted Successfully!"]);
    }


    //deadlines management
    //deadlines
    public function deadlines(Request $request)
    {
        if ($request->ajax()) {
            $data = Deadline::select();
       
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
                    ->editColumn('price', function($row){
                            return Settings::getOption('currency').number_format($row->price, 2, '.',',');
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','price'])
                    ->make(true);
        }

    	return view('admin.order.deadlines');
    }

    public function deadlineSave(Request $request){
        if($request->type == "edit" && isset($request->id)){ //update
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'price'        => 'required',
                'duration'        => 'required',
            ]);

            if ($validator->passes()) {
                
                Deadline::where('id',$request->id)->update([
                    'title' => $request->title,
                    'price' => $request->price,
                    'duration' => $request->duration,
                    'status' => $request->status,
                    'updated_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'successfully updated.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        } else{ //create
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'price'        => 'required',
                'duration'        => 'required',
            ]);
            if ($validator->passes()) {
                
                Deadline::insert([
                    'title' => $request->title,
                    'duration' => $request->duration,
                    'price' => $request->price,
                    'status' => $request->status,
                    'created_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'Successfully created.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function editDeadline(Request $request)
    {
        $data = Deadline::where('id',$request->id)->first();
        echo json_encode($data);
    }

    public function deleteDeadline($id)
    {
        Deadline::where('id',$id)->delete();
        Order::where('deadline',$id)->delete(); //deleted the related deadline
        echo json_encode(['message' => " deadline Deleted Successfully!"]);
    }


    //languages management
    //languages
    public function languages(Request $request)
    {
        if ($request->ajax()) {
            $data = Language::select();
       
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
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
        }

    	return view('admin.order.languages');
    }

    public function languageSave(Request $request){
        if($request->type == "edit" && isset($request->id)){ //update
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'code'        => 'required',
            ]);

            if ($validator->passes()) {
                
                Language::where('id',$request->id)->update([
                    'title' => $request->title,
                    'code' => $request->code,
                    'status' => $request->status,
                    'updated_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'successfully updated.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        } else{ //create
            $validator = Validator::make($request->all(), [
                'title'       => 'required',
                'status'        => 'required',
                'code'        => 'required',
            ]);
            if ($validator->passes()) {
                
                Language::insert([
                    'title' => $request->title,
                    'code' => $request->code,
                    'status' => $request->status,
                    'created_at' => Carbon::now(),
                ]);
    
                return response()->json(['success'=>'Successfully created.']);
    
            }
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }

    public function editLanguage(Request $request)
    {
        $data = Language::where('id',$request->id)->first();
        echo json_encode($data);
    }

    public function deleteLanguage($id)
    {
        Language::where('id',$id)->delete();
        Order::where('language',$id)->delete(); //deleted the related language
        echo json_encode(['message' => " language Deleted Successfully!"]);
    }
}
