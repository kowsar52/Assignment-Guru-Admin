<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Validator,DataTables;
class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function Index(Request $request)
    {
        
        if ($request->ajax()) {
            $data = Page::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('slug', function($row){
                        return str_replace("_"," ",$row->slug);
                    })
                    ->editColumn('language_id', function($row){
                        $lang = Language::find($row->language_id);
                        return $lang->title;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/pages/edit-page',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" onclick="delete_page('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','language_id','slug'])
                    ->make(true);
        }
        
        return view('admin.pages.about-us.index');
    }
    public function Add(Request $request)
    {
        
        if ($request->isMethod('post')) {

            
	        $validator = Validator::make($request->all(), [
                'title'             => 'required',
                'language'          => 'required',
	            'details_content'   => 'required',
	            'page_name'         => 'required',
            ]);
	        if($validator->passes()) {
	            
	            $page = new Page();
	            $page->slug         = $request->page_name;
	            $page->title        = $request->title;
	            $page->heading      = $request->heading;
	            $page->language_id  = $request->language;
	            $page->details      = $request->details_content;
	            if($page->save())
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
        
        $languages = Language::where('status',1)->get();
        
        return view('admin.pages.about-us.create',['languages'=>$languages]);
    }
    
    public function Edit(Request $request,$id)
    {
        $page = Page::where('id',$id)->first();
        if($page == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {

            
	        $validator = Validator::make($request->all(), [
                'title'             => 'required',
                'language'          => 'required',
	            'details_content'   => 'required',
	            'page_name'         => 'required',
            ]);
	        if($validator->passes()) {
	            
	            $page->slug         = $request->page_name;
	            $page->title        = $request->title;
	            $page->heading      = $request->heading;
	            $page->language_id  = $request->language;
	            $page->details      = $request->details_content;
	            if($page->update())
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
        $languages = Language::where('status',1)->get();
        
        return view('admin.pages.about-us.edit',['languages'=>$languages,'page'=>$page]);
    }
    
    public function destroy(Request $request)
    {
    	$msg = 2;
    	if ($request->isMethod('post')) {
    		$page =  Page::where('id',$request->id)->first();
    		if($page != false)
    		{
    			$page->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
}
