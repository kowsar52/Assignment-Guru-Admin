<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\HonorCode;
use App\Models\ThemeOrderContent;
use App\Models\HomeWritingFeature;
use App\Models\FrequentlyAskedQuestion;
use Validator,DataTables;

class ThemeController extends Controller
{
    public function OrderPages(Request $request)
    {
        if ($request->ajax()) {
            $data = ThemeOrderContent::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('language', function($row){
                        $lang = Language::find($row->language_id);
                        return $lang->title;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/theme/edit-order-page-content',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" onclick="delete_content('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','language'])
                    ->make(true);
        }
        return view('admin.theme.order.index');
    }
    public function AddOrderPageContent(Request $request)
    {
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'image'         => 'required',
                'language'      => 'required',
	            'title'         => 'required',
	            'description'   => 'required',
	            'short_description'   => 'required',
            ]);
            if($validator->passes()) {
	            
	            $file_path = 'empty';
	            if ($request->file('image')) {

                    $image 				= $request->file('image');
                    $image_name 		= time().'_'.rand(10000,9999999).'.' . $image->getClientOriginalExtension();
                    $destinationPath 	= 'uploads/images';
                    $image->move($destinationPath,$image_name);
                    $file_path 			= "uploads/images/" . $image_name;
    
                }

	            $icon_file_path = 'empty';
	            if ($request->file('icon')) {

                    $image 				= $request->file('icon');
                    $image_name 		= time().'_'.rand(10000,9999999).'.' . $image->getClientOriginalExtension();
                    $destinationPath 	= 'uploads/images';
                    $image->move($destinationPath,$image_name);
                    $icon_file_path 			= "uploads/images/" . $image_name;
    
                }
	                
                    $ThemeOrderContent = new ThemeOrderContent();
    	            $ThemeOrderContent->language_id     = $request->language;
    	            $ThemeOrderContent->title           = $request->title;
    	            $ThemeOrderContent->description     = $request->description;
    	            $ThemeOrderContent->short_description     =$request->short_description;
    	            $ThemeOrderContent->image           = $file_path;
    	            $ThemeOrderContent->icon           = $icon_file_path;
    	            if($ThemeOrderContent->save())
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
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.order.create',$data);
    }
    public function EditOrderPageContent(Request $request,$id)
    {
        $ThemeOrderContent = ThemeOrderContent::where('id',$id)->first();
        if($ThemeOrderContent == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'language'      => 'required',
	            'title'         => 'required',
	            'description'   => 'required',
            ]);
            if($validator->passes()) {
	            
	                $ThemeOrderContent->language_id     = $request->language;
    	            $ThemeOrderContent->title           = $request->title;
    	            $ThemeOrderContent->description     = $request->description;
                    $ThemeOrderContent->short_description     =$request->short_description;
    	            
	            if ($request->file('image')) {
	                
	                $path = base_path()."/public/".$ThemeOrderContent->image;
                    if($ThemeOrderContent->image != '')
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $image 				= $request->file('image');
                    $image_name 		= time().'_'.rand(10000,9999999).'.' . $image->getClientOriginalExtension();
                    $destinationPath 	= 'uploads/images';
                    $image->move($destinationPath,$image_name);
                    $file_path 			= "uploads/images/" . $image_name;
                    $ThemeOrderContent->image           = $file_path;
                }

	            if ($request->file('icon')) {
	                
	                $path = base_path()."/public/".$ThemeOrderContent->icon;
                    if($ThemeOrderContent->icon != '')
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $image 				= $request->file('icon');
                    $image_name 		= time().'_'.rand(10000,9999999).'.' . $image->getClientOriginalExtension();
                    $destinationPath 	= 'uploads/images';
                    $image->move($destinationPath,$image_name);
                    $icon_file_path 			= "uploads/images/" . $image_name;
                    $ThemeOrderContent->icon           = $icon_file_path;
                }
	                
    	            
    	            
    	            if($ThemeOrderContent->update())
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
        $data['content']            = $ThemeOrderContent;
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.order.edit',$data);
    }
    
    public function DeleteOrderPageContent(Request $request)
    {
        $msg = 2;
    	if ($request->isMethod('post')) {
    		$ThemeOrderContent = ThemeOrderContent::where('id',$request->id)->first();
    		if($ThemeOrderContent != false)
    		{
    		    $path = base_path()."/public/".$ThemeOrderContent->image;
                    if($ThemeOrderContent->image != '')
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
    			$ThemeOrderContent->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
    
    //theme home page writing service features
     public function HomeWritingFeatures(Request $request)
    {
        if ($request->ajax()) {
            $data = HomeWritingFeature::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('language', function($row){
                        $lang = Language::find($row->language_id);
                        return $lang->title;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/home/edit-writing-service-features',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" onclick="delete_content('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','language'])
                    ->make(true);
        }
        return view('admin.theme.home-writing-feature.index');
    }
    public function AddHomeWritingFeatures(Request $request)
    {
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'language'      => 'required',
	            'title'         => 'required',
	            'description'   => 'required',
            ]);
            if($validator->passes()) {
	                
                    $HomeWritingFeature = new HomeWritingFeature();
    	            $HomeWritingFeature->language_id     = $request->language;
    	            $HomeWritingFeature->title           = $request->title;
    	            $HomeWritingFeature->description     = $request->description;
    	            if($HomeWritingFeature->save())
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
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.home-writing-feature.create',$data);
    }
    public function EditHomeWritingFeatures(Request $request,$id)
    {
        $HomeWritingFeature = HomeWritingFeature::where('id',$id)->first();
        if($HomeWritingFeature == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'language'      => 'required',
	            'title'         => 'required',
	            'description'   => 'required',
            ]);
            if($validator->passes()) {
	            
	                $HomeWritingFeature->language_id     = $request->language;
    	            $HomeWritingFeature->title           = $request->title;
    	            $HomeWritingFeature->description     = $request->description;
    	            
    	            if($HomeWritingFeature->update())
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
        $data['content']            = $HomeWritingFeature;
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.home-writing-feature.edit',$data);
    }
    public function DeleteHomeWritingFeatures(Request $request)
    {
        $msg = 2;
    	if ($request->isMethod('post')) {
    		$HomeWritingFeature = HomeWritingFeature::where('id',$request->id)->first();
    		if($HomeWritingFeature != false)
    		{
    			$HomeWritingFeature->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
    
    //theme home page frequently asked questions
     public function FrequentlyAskedQuestions(Request $request)
    {
        if ($request->ajax()) {
            $data = FrequentlyAskedQuestion::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('language', function($row){
                        $lang = Language::find($row->language_id);
                        return $lang->title;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/home/edit-frequently-asked-question',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" onclick="delete_content('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','language'])
                    ->make(true);
        }
        return view('admin.theme.faq.index');
    }
     public function AddFrequentlyAskedQuestion(Request $request)
    {
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'language'      => 'required',
	            'question'      => 'required',
	            'answer'        => 'required',
            ]);
            if($validator->passes()) {
	                
                    $FrequentlyAskedQuestion = new FrequentlyAskedQuestion();
    	            $FrequentlyAskedQuestion->language_id   = $request->language;
    	            $FrequentlyAskedQuestion->question      = $request->question;
    	            $FrequentlyAskedQuestion->answer        = $request->answer;
    	            if($FrequentlyAskedQuestion->save())
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
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.faq.create',$data);
    }
    public function EditFrequentlyAskedQuestion(Request $request,$id)
    {
        $FrequentlyAskedQuestion = FrequentlyAskedQuestion::where('id',$id)->first();
        if($FrequentlyAskedQuestion == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'language'      => 'required',
	            'question'      => 'required',
	            'answer'        => 'required',
            ]);
            if($validator->passes()) {
	            
	                 $FrequentlyAskedQuestion->language_id      = $request->language;
    	            $FrequentlyAskedQuestion->question          = $request->question;
    	            $FrequentlyAskedQuestion->answer            = $request->answer;
    	            
    	            if($FrequentlyAskedQuestion->update())
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
        $data['content']            = $FrequentlyAskedQuestion;
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.faq.edit',$data);
    }
     public function DeleteFrequentlyAskedQuestion(Request $request)
    {
        $msg = 2;
    	if ($request->isMethod('post')) {
    		$FrequentlyAskedQuestion = FrequentlyAskedQuestion::where('id',$request->id)->first();
    		if($FrequentlyAskedQuestion != false)
    		{
    			$FrequentlyAskedQuestion->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
    
    public function HonorCodes(Request $request)
    {
        if ($request->ajax()) {
            $data = HonorCode::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('language', function($row){
                        $lang = Language::find($row->language_id);
                        return $lang->title;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/theme/edit-honor-code',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" onclick="delete_content('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','language'])
                    ->make(true);
        }
        return view('admin.theme.honor.index');
    }
    public function AddHonorCode(Request $request)
    {
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'image'         => 'required',
                'language'      => 'required',
	            'title'         => 'required',
	            'description'   => 'required',
            ]);
            if($validator->passes()) {
	            
	            $file_path = 'empty';
	            if ($request->file('image')) {

                    $image 				= $request->file('image');
                    $image_name 		= time().'_'.rand(10000,9999999).'.' . $image->getClientOriginalExtension();
                    $destinationPath 	= 'uploads/images';
                    $image->move($destinationPath,$image_name);
                    $file_path 			= "uploads/images/" . $image_name;
    
                }
	                
                    $HonorCode = new HonorCode();
    	            $HonorCode->language_id     = $request->language;
    	            $HonorCode->title           = $request->title;
    	            $HonorCode->description     = $request->description;
    	            $HonorCode->image           = $file_path;
    	            if($HonorCode->save())
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
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.honor.create',$data);
    }
    public function EditHonorCode(Request $request,$id)
    {
        $HonorCode = HonorCode::where('id',$id)->first();
        if($HonorCode == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'language'      => 'required',
	            'title'         => 'required',
	            'description'   => 'required',
            ]);
            if($validator->passes()) {
	            
	                $HonorCode->language_id     = $request->language;
    	            $HonorCode->title           = $request->title;
    	            $HonorCode->description     = $request->description;
    	            
	            if ($request->file('image')) {
	                
	                $path = base_path()."/public/".$HonorCode->image;
                    if($HonorCode->image != '')
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $image 				= $request->file('image');
                    $image_name 		= time().'_'.rand(10000,9999999).'.' . $image->getClientOriginalExtension();
                    $destinationPath 	= 'uploads/images';
                    $image->move($destinationPath,$image_name);
                    $file_path 			= "uploads/images/" . $image_name;
                    $HonorCode->image           = $file_path;
                }
	                
    	            
    	            
    	            if($HonorCode->update())
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
        $data['content']            = $HonorCode;
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.theme.honor.edit',$data);
    }
    
    public function DeleteHonorCode(Request $request)
    {
        $msg = 2;
    	if ($request->isMethod('post')) {
    		$HonorCode = HonorCode::where('id',$request->id)->first();
    		if($HonorCode != false)
    		{
    		    $path = base_path()."/public/".$HonorCode->image;
                    if($HonorCode->image != '')
                    {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
    			$HonorCode->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
}