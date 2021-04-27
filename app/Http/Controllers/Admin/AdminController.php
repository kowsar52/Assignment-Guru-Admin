<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Settings;
use Session;
use Validator;
use DataTables;
use DB;

class AdminController extends Controller
{
    public function login(Request $request)
    {
    	if(session('admin'))
        {
            return redirect('admin/dashboard');
        }

    	if ($request->isMethod('post')) {

    		$validatedData = $request->validate([
                'username' 	=> 'required',
                'password' 	=> 'required|max:20',
            ]);

            $username 	= $request->username;
            $pass 		= $request->password;
            if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {

            	$admin = Admin::where('email',$request->username)->first();
	            if (!empty($admin))
	            {
	                if (Hash::check($request->password, $admin->password)) {
	                	
	                	if($admin->status == 1)
	                	{
	                		Session::put('admin',$admin->id);
	                		Session::put('admin_role',$admin->role);
	                    	return redirect('admin/dashboard');
	                	}else{
	                		return redirect()->back()->with(['error'=>'Your account has been deactivated!']);
	                	}
	                }else{

	                    return redirect()->back()->with(['error'=>'Invalid password!']);
	                }
	            }else{

	                return redirect()->back()->with(['error'=>'Invalid email!']);
	            }

            }else{

            	$admin = Admin::where('username',$request->username)->first();
	            if (!empty($admin))
	            {
	                if (Hash::check($request->password, $admin->password)) {
	                	
	                	if($admin->status == 1)
	                	{
	                		Session::put('admin',$admin->id);
	                		Session::put('admin_role',$admin->role);
	                    	return redirect('admin/dashboard');
	                	}else{
	                		return redirect()->back()->with(['error'=>'Your account has been deactivated!']);
	                	}
	                }else{

	                    return redirect()->back()->with(['error'=>'Invalid password!']);
	                }
	            }else{

	                return redirect()->back()->with(['error'=>'Invalid email!']);
	            }
            } 
            
    	}
    	return view('admin.login');
    }

    public function dashboard()
    {
    	$data['users'] = User::count();
        $data['cards'] = User::count();
        $data['cardTypes'] = User::count();
        $data['MonthUsers'] = User::Where('created_at', 'like', '%' .date('Y-m'). '%')->count();
        $data['MonthCards'] = User::Where('created_at', 'like', '%' .date('Y-m'). '%')->count();
    	return view('admin.dashboard',$data);
    }

    public function logout()
    {
    	session()->forget('admin');
    	session()->forget('admin_role');
        return redirect('admin/login');
    }

    public function Users(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select();
       
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
                    ->editColumn('role', function($row){
                        if($row->role == 'writer'){
                            $btn = '<span class="badge badge-success">Writer</span>'; 
                        }else{
                            $btn = '<span class="badge badge-info">Buyer</span>'; 
                        }
                        return $btn;
                    })
                    ->editColumn('first_name', function($row){
                        if($row->first_name){
                            return $row->first_name.' '. $row->last_name;
                        }else{
                            return 'No Name';

                        }
                    })
                    ->editColumn('avater', function($row){
                            return '<img src="'.asset($row->avater).'" style="height:40px; width: 40px; object-fit: cover;border-radius: 5px"/>';
                    })
                    ->editColumn('balance', function($row){
                            return Settings::getOption('currency').$row->balance;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <button type="button" class="btn btn-rounded btn-warning" onclick="EditUser('.$row->id.')"><i class="fa fa-edit mr-2"></i> Edit</button>
                        <button type="button" class="btn btn-rounded btn-danger" onclick="deleteUser('.$row->id.')"><i class="mdi mdi-delete-sweep mr-2"></i> Delete</button></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','status','first_name','avater','role','balance'])
                    ->make(true);
        }

    	return view('admin.users');
    }

    public function resetPassword(Request $request)
    {

    	if ($request->isMethod('post')) {

    		$validatedData = $request->validate([
                'email' 	=> 'required|email',
            ]);
    		$user_code = bin2hex(random_bytes(20));
    		$admin = Admin::where('email',$request->email)->first();
    		if(!empty($admin))
            {
            	$admin->remember_token = $user_code;
                $admin->update();

                $details = [
                        'to'        => $request->email,
                        'from'      => get_setting()['from_mail'],
                        'subject'   => 'Password Reset',
                        'title'     => 'Password Reset',
                        "url"      => url('admin/update-password').'/'.$user_code
                    ];

                \Mail::to($request->email)->send(new \App\Mail\ResetPassword($details));
                if (\Mail::failures()) {
                   return redirect()->back()->with(['error'=>'Email not send .Please Try again.']);
               }else{
                    return redirect()->back()->with(['success'=>'Successfully Reset link send your email']);
                }
            }else{
            	return redirect()->back()->with(['error'=>'Email is not valid.']);
            } 	
        }
    	return view('admin.reset-password');
    }

    public function updatePassword(Request $request,$code)
    {
    	$admin = Admin::where('remember_token',$code)->first();
    	if(empty($admin))
    	{
    		return redirect('admin/login')->with(['error'=>"Unauthorized access page."]);
    	}
    	if ($request->isMethod('post')) {

    		$validatedData = $request->validate([
                'password' 			=> 'required',
                'confirm_password' 	=> 'required',
            ]);

            if($request->password == $request->confirm_password)
            {
            	$admin->password 		= Hash::make($request->password);
            	$admin->remember_token  = '';
            	if($admin->update())
            	{
            		return redirect('admin/login')->with(['success'=>"Password Successfully Updated"]);
            	}else{

            		return redirect()->back()->with(['error'=>"Password update failed!"]);
            	}
            }else{
            	return redirect()->back()->with(['error'=>"Password and Confirm password don't matched!"]);
            }
        }    
    	return view('admin.update-password',['code'=>$code]);
    }
    

    
    public function EditUser(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        echo json_encode($user);
    }
    
    public function UpdateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'user_id'       => 'required',
            'status'        => 'required',

        ]);
        if ($validator->passes()) {
            
            $user = User::where('id',$request->user_id)->first();
            $user->status     = $request->status;
            $user->update();

            return response()->json(['success'=>'successfully updated.']);

        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    
    public function Profile(Request $request)
    {
        $admin_id = session('admin');
        $admin = Admin::where('id',$admin_id)->first();
        if ($request->isMethod('post')) {
            
            $validatedData = $request->validate([
                'name' 			=> 'required',
                'username'      => 'unique:admins,username',
                'email'         => 'unique:admins,email',
                'image'         => 'mimes:jpeg,jpg,png'
            ]);
            $admin->name = $request->name;
            if($request->username != '')
            {
                $admin->username = $request->username;
            }
            if($request->email != '')
            {
                $admin->email = $request->email;
            }
            
            if($request->file('image'))
            {
                if($admin->image != '')
                {
                    $photo_path = base_path().'/public/'.$admin->image;
                    if (file_exists($photo_path)){
                        unlink($photo_path);
                    }
                }
                
                $file = $request->file('image');
                $image_name = time().'-'.rand(10000,99999).'.'.$file->getClientOriginalExtension();
                \Image::make($file)->save(public_path('assets/images/admin/') . $image_name);
                
                $filepath = 'assets/images/admin/'.$image_name;
                $admin->image = $filepath;
            }
            
            if($admin->update())
            {
                return redirect()->back()->with(['success'=>"update successfully!"]);
            }else{
                return redirect()->back()->with(['error'=>"update failed!"]);
            }
        }
        
        return view('admin.profile',['admin'=>$admin]);
    }
    
    public function passwordChange(Request $request)
    {
        $admin_id = session('admin');
        $admin = Admin::where('id',$admin_id)->first();
        if ($request->isMethod('post')) {
        $validatedData = $request->validate([
                'password' 			=> 'required',
                'confirm_password' 	=> 'required',
            ]);

            if($request->password == $request->confirm_password)
            {
            	$admin->password 		= Hash::make($request->password);
            
            	if($admin->update())
            	{
            		return redirect()->back()->with(['success'=>"Password successfully updated"]);
            	}else{

            		return redirect()->back()->with(['error'=>"Password update failed!"]);
            	}
            }else{
            	return redirect()->back()->with(['error'=>"Password and Confirm password don't matched!"]);
            }
        }
        return view('admin.password');
    }


    public function apiDocument()
    {
        return view('admin.api.documentation');
    }


    public function deleteUser($user_id)
    {
        $user = User::findOrFail($user_id);


        if($user->image != ''){
            $check = strpos($user->image, 'default_avater.jpg');

            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
            if ($check === false) {
                if(str_replace(url('/uploads/images/'), '', $user->image) != 'uploads/images/') {
                    $file_path = str_replace(url('/'), public_path('/'), $user->image);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }    
            } 

            
        }

        $user->delete();
        echo json_encode($user);
    }

}
