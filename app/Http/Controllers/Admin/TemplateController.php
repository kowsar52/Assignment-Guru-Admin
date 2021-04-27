<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\TemplatePage;
use App\Models\TemplatePageSlug;
use App\Models\PageTranslation;
use Validator,DataTables;
class TemplateController extends Controller
{
    
    public function TemplateSlugs(Request $request)
    {
        if ($request->ajax()) {
            $data = TemplatePageSlug::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('template_page_id', function($row){
                        $page = TemplatePage::find($row->template_page_id);
                        return $page->name;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/edit-template-slug',$row->id).'" class="btn btn-warning btn-sm">Edit</a>'; 
                        return $btn;
                        // <a href="#" onclick="delete_slug('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>
                    })
                    ->rawColumns(['action','template_page_id'])
                    ->make(true);
        }
        
        return view('admin.templates.slug.index');
    }
    public function AddTemplateSlugs(Request $request)
    {
        $data['templatepages']  = TemplatePage::all();
        if ($request->isMethod('post')) {
            
	        $validator = Validator::make($request->all(), [
                'page_name'     => 'required',
                'name'          => 'required',
            ]);
	        if($validator->passes()) {
	            
	            $ex = TemplatePageSlug::where('template_page_id',$request->page_name)->where('name',$request->name)->first();
	            if($ex != false)
	            {
	                    $error['error']     = true;
                        $error['check']     = false;
                        $error['message']   = 'Already added this slug';
	            }else{
	                $slug = new TemplatePageSlug();
	                $slug->template_page_id     = $request->page_name;
    	            $slug->slug                 = strtolower(str_replace(" ","_",mb_substr($request->name, 0, 50)));
    	            $slug->name                 = $request->name;
    	            if($slug->save())
    	            {
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Successfully Saved';
    	            }else{
    	                
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Save failed';
    	            }
	            }
                
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
        }
        return view('admin.templates.slug.create',$data);
    }
    public function EditTemplateSlugs(Request $request,$id)
    {
        $data['templatepages']  = TemplatePage::all();
        $slug                   = TemplatePageSlug::where('id',$id)->first();
        if($slug == false)
        {
            return redirect()->back();
        }
        $data['slug']           = $slug;
        if ($request->isMethod('post')) {
            
	        $validator = Validator::make($request->all(), [
                'page_name'     => 'required',
                'name'          => 'required',
            ]);
	        if($validator->passes()) {
	            
    	            $slug->template_page_id     = $request->page_name;
    	            // $slug->slug                 = strtolower(str_replace(" ","_",mb_substr($request->name, 0, 50)));
    	            $slug->name                 = $request->name;
    	            if($slug->update())
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
        return view('admin.templates.slug.edit',$data);
    }
     public function DeleteTemplateSlug(Request $request)
    {
        $msg = 2;
    	if ($request->isMethod('post')) {
    		$slug = TemplatePageSlug::where('id',$request->id)->first();
    		if($slug != false)
    		{
    			$slug->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function TemplateTranslations(Request $request)
    {
        
         if ($request->ajax()) {
            $data = PageTranslation::select();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('language', function($row){
                        $lang = Language::find($row->language_id);
                        return $lang->title;
                    })
                    ->editColumn('page_name', function($row){
                        $page = TemplatePage::find($row->page_id);
                        return $page->name;
                    })
                    ->editColumn('slug_name', function($row){
                        $slug = TemplatePageSlug::where('slug',$row->slug)->first();
                        return $slug->name;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group">
                        <a href="'.url('admin/edit-template-translation',$row->id).'" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" onclick="delete_translation('.$row->id.')" class="btn btn-danger btn-sm">Delete</a>'; 
                        return $btn;
                    })
                    ->rawColumns(['action','language','slug_name','page_name'])
                    ->make(true);
        }
        
        return view('admin.templates.home.index');
    }
    public function AddTemplateTranslation(Request $request)
    {
        
        if ($request->isMethod('post')) {

            
	        $validator = Validator::make($request->all(), [
                'page_name'     => 'required',
                'language'      => 'required',
	            'slug'          => 'required',
	            'translation'   => 'required',
            ]);
	        if($validator->passes()) {
	            $ex = PageTranslation::where('page_id',$request->page_name)->where('language_id',$request->language)->where('slug',$request->slug)->first();
	            if($ex != false)
	            {
	                    $error['error']     = true;
                        $error['check']     = false;
                        $error['message']   = 'Already added this translation';
	            }else{
	                $translation = new PageTranslation();
    	            $translation->page_id           = $request->page_name;
    	            $translation->language_id       = $request->language;
    	            $translation->slug              = $request->slug;
    	            $translation->translation       = $request->translation;
    	            if($translation->save())
    	            {
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Successfully Saved';
    	            }else{
    	                
    	                $error['error']     = false;
                        $error['check']     = false;
                        $error['message']   = 'Save failed';
    	            }
	            }
	            
	            
                
	        }else{
	            
                $error['error']     = true;
                $error['check']     = true;
                $error['message']   = $validator->errors()->getMessages();
            }
            
            return response()->json($error);
        }
        $data['templatepages'] = TemplatePage::all();
        $data['languages'] = Language::where('status',1)->get();
        return view('admin.templates.home.create',$data);
    }
    
    public function EditTemplateTranslation(Request $request,$id)
    {
        $translation = PageTranslation::where('id',$id)->first();
        if($translation == false)
        {
            return redirect()->back();
        }
        if ($request->isMethod('post')) {

            
	        $validator = Validator::make($request->all(), [
                'page_name'     => 'required',
                'language'      => 'required',
	            'slug'          => 'required',
	            'translation'   => 'required',
            ]);
	        if($validator->passes()) {
	            
	                
    	            $translation->page_id           = $request->page_name;
    	            $translation->language_id       = $request->language;
    	            $translation->slug              = $request->slug;
    	            $translation->translation       = $request->translation;
    	            if($translation->update())
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
        $data['translation']        = $translation;
        $data['templatepages']      = TemplatePage::all();
        $data['slugs']              = TemplatePageSlug::where('template_page_id',$translation->page_id)->orderBy('id','ASC')->get();
        $data['languages']          = Language::where('status',1)->get();
        return view('admin.templates.home.edit',$data);
    }
    
    public function DeleteTemplateTranslation(Request $request)
    {
        $msg = 2;
    	if ($request->isMethod('post')) {
    		$translation = PageTranslation::where('id',$request->id)->first();
    		if($translation != false)
    		{
    			$translation->delete();
    			$msg = 1;
    		}else{
    			$msg = 2;
    		}
    	}
    	echo $msg;
    	die;
    }
    public function GetTemplatePageSlug(Request $request)
    {
        $msg['error']   = true;
        $msg['msg']     = '';
        $msg['data']    = '';
        if(!isset($request->id))
        {
            $msg['msg']     = 'Please provide template page name';
        }else{
            if($request->id == '')
            {
                $msg['msg']     = 'Please provide template page name';
            }else{
                $slugs  = TemplatePageSlug::where('template_page_id',$request->id)->orderBy('id','ASC')->get();
                if($slugs != false)
                {
                    $msg['error']   = false;
                    $msg['msg']     = 'Success';
                    $html           = '<option value="">Select Slug</option>';
                    foreach($slugs as $slug)
                    {
                        $html .= '<option value="'.$slug->slug.'">'.$slug->name.'</option>';
                    }
                    $msg['data']    = $html;
                }else{
                    $msg['msg']     = 'Slugs not found for this page name';
                }
            }
        }
        return response()->json($msg);
        
    }
}
