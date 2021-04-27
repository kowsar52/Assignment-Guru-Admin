<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB,App;
use DataTables;

class EmailTemplateController extends Controller
{
    public function emailTemplate(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('email_templates')->get();
       
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<div aria-label="..." role="group" class="btn-group btn-group"><a href="'.url('/admin/email-template/'.$row->id).'" class="btn btn-success btn-sm  kt-font-light editBtn" ><i class="flaticon-edit"></i>&nbsp;'.__('Edit').'</a></div>'; 
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  

        return view('admin.email_template.index');
    }

    public function emailTemplateEdit($id)
    {
   
        $data = DB::table('email_templates')->where('id',$id)->first();
        return view('admin.email_template.edit',compact('data'));
    }


    public function emailTemplateUpdate(Request $request)
    {
        $data = DB::table('email_templates')->where('id',$request->id)->update([
            'email_subject' => $request->email_subject,
            'email_body' => $request->email_body,
        ]);

        return back()->with('success',__('Email Template Updated Successfully!'));
    }
}
