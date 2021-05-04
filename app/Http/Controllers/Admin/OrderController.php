<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,App;
use DataTables;
use App\Models\Product;
use App\Models\Order;
use App\Models\Bid;
use App\Models\InviteWriter;
use App\Models\User;
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
    //orders
    public function Orders(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function($row){
                        $status = DB::table('order_status')->where('id',$row->status)->first();
                        return '<span class="badge badge-'.$status->class.'">'.$status->name.'</span>';
                    })
                    ->editColumn('customer', function($row){
                        $customer = User::find($row->customer);
                        return $customer->first_name.' '.$customer->last_name;
                    })
                    ->editColumn('writer', function($row){
                        if($row->writer != '')
                        {
                            $writer = User::find($row->writer);
                            return $writer->first_name.' '.$writer->last_name;
                        }
                        return '';
                    })
                    ->editColumn('deadline', function($row){
                        
                        return date("jS \of F Y h:i:s A",strtotime($row->deadline));
                    })
                    
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','customer','writer','deadline'])
                    ->make(true);
        }
        
    	return view('admin.order.orders');
    }
    
    public function EditOrder(Request $request)
    {
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'id_order'      => 'required',
	            'status'        => 'required',
            ]);
            if($validator->passes()) {
                
	            $Order = Order::where('id',$request->id_order)->first();
	            if($Order == false)
	            {
	                $error['error']         = true;
                    $error['check']         = true;
                    $error['message'][0]    = 'Order not found!';
                    
	            }else{
	                
	                $Order->status = $request->status;
	                if($Order->update())
    	            {
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Successfully Updated';
    	            }else{
    	                
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Update failed';
    	            } 
	            } 
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
            
        }
        $error['error']     = true;
        $error['msg']       = '';
        $error['html']      = '';
        $Order = Order::where('id',$request->id)->first();
        
        if($Order == false)
        {
            $error['msg']       = 'Order not found';
        }else{
            $statuss = DB::table('order_status')->get();
            $html = '';
            
            foreach($statuss as $status)
            {
                $selected = '';
                if($status->id == $Order->status) 
                {
                    $selected = 'selected' ;
                }
                $html .= '<option '.$selected.' value="'.$status->id.'">'.$status->name.'</option>';
            }
            $error['error']     = false;
            $error['html']      = $html;
        }
        echo json_encode($error);
    }
    
    public function DeleteOrder($id)
    {
        $error['error']     = true;
        $error['msg']       = '';
        $Order = Order::where('id',$id)->first();
        if($Order == false)
        {
            $error['msg']       = 'Order not found';
        }else{
            $error['error']     = false;
            $error['msg']       = 'Order successfully deleted';
            $Order->delete();
        }
         return response()->json($error);
    }
    
    
    //bids
    public function Bids(Request $request)
    {
        if ($request->ajax()) {
            $data = Bid::select();
       
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
                    ->editColumn('writer', function($row){
                        if($row->writer_id != '')
                        {
                            $writer = User::find($row->writer_id);
                            return $writer->first_name.' '.$writer->last_name;
                        }
                        return '';
                    })
                    
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','writer'])
                    ->make(true);
        }
        
    	return view('admin.order.bids.index');
    }
    
    public function EditBid(Request $request)
    {
        
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'bids_id'      => 'required',
	            'status'        => 'required',
            ]);
            if($validator->passes()) {
                
	            $bid = Bid::where('id',$request->bids_id)->first();
	            if($bid == false)
	            {
	                $error['error']         = true;
                    $error['check']         = true;
                    $error['message'][0]    = 'Bid not found!';
                    
	            }else{
	                
	                $bid->status = $request->status;
	                if($bid->update())
    	            {
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Successfully Updated';
    	            }else{
    	                
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Update failed';
    	            } 
	            } 
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
            
        }
        $error['error']     = true;
        $error['msg']       = '';
        $error['html']      = '';
        $bid = Bid::where('id',$request->id)->first();
        
        if($bid == false)
        {
            $error['msg']       = 'Bid not found';
        }else{
            $statuss[0]['name']     = 'Active';
            $statuss[0]['value']    =  1;
            $statuss[1]['name']     = 'Deactive';
            $statuss[1]['value']    = 0;
            $html = '';
            
            foreach($statuss as $status)
            {
                $selected = '';
                if($status['value'] == $bid->status) 
                {
                    $selected = 'selected' ;
                }
                $html .= '<option '.$selected.' value="'.$status['value'].'">'.$status['name'].'</option>';
            }
            $error['error']     = false;
            $error['html']      = $html;
        }
        echo json_encode($error);
    }
    public function DeleteBid($id)
    {
        $error['error']     = true;
        $error['msg']       = '';
        $bid = Bid::where('id',$id)->first();
        if($bid == false)
        {
            $error['msg']       = 'Bid not found';
        }else{
            $error['error']     = false;
            $error['msg']       = 'Bid successfully deleted';
            $bid->delete();
        }
         return response()->json($error);
    }
    
    //invitation
    public function Invitations(Request $request)
    {
        if ($request->ajax()) {
            $data = InviteWriter::select();
       
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
                    ->editColumn('writer', function($row){
                        if($row->writer_id != '')
                        {
                            $writer = User::find($row->writer_id);
                            return $writer->first_name.' '.$writer->last_name;
                        }
                        return '';
                    })
                    
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="Edit('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="Delete('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','writer'])
                    ->make(true);
        }
        
    	return view('admin.order.invitations');
    }
    public function EditInvitation(Request $request)
    {
        
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'invite_id'      => 'required',
	            'status'        => 'required',
            ]);
            if($validator->passes()) {
                
	            $invite = InviteWriter::where('id',$request->invite_id)->first();
	            if($invite == false)
	            {
	                $error['error']         = true;
                    $error['check']         = true;
                    $error['message'][0]    = 'Invitation not found!';
                    
	            }else{
	                
	                $invite->status = $request->status;
	                if($invite->update())
    	            {
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Successfully Updated';
    	            }else{
    	                
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Update failed';
    	            } 
	            } 
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
            
        }
        $error['error']     = true;
        $error['msg']       = '';
        $error['html']      = '';
        $invite = InviteWriter::where('id',$request->id)->first();
        
        if($invite == false)
        {
            $error['msg']       = 'Invitation not found';
        }else{
            $statuss[0]['name']     = 'Active';
            $statuss[0]['value']    =  1;
            $statuss[1]['name']     = 'Deactive';
            $statuss[1]['value']    = 0;
            $html = '';
            
            foreach($statuss as $status)
            {
                $selected = '';
                if($status['value'] == $invite->status) 
                {
                    $selected = 'selected' ;
                }
                $html .= '<option '.$selected.' value="'.$status['value'].'">'.$status['name'].'</option>';
            }
            $error['error']     = false;
            $error['html']      = $html;
        }
        echo json_encode($error);
    }
    
    public function DeleteInvitation($id)
    {
        $error['error']     = true;
        $error['msg']       = '';
        $invite = InviteWriter::where('id',$id)->first();
        if($invite == false)
        {
            $error['msg']       = 'Invitation not found';
        }else{
            $error['error']     = false;
            $error['msg']       = 'Invitation successfully deleted';
            $invite->delete();
        }
         return response()->json($error);
    }
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
