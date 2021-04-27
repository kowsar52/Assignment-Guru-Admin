<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\TemplatePage;
use App\Models\TemplatePageSlug;
use App\Models\PageTranslation;

class LanguageController extends Controller
{
    //get transalation
    public function get($lang_code, $page_slug){
        $page = TemplatePage::where('slug',$page_slug)->first();
        $g_page = TemplatePage::where('slug','general')->first();
        if($lang_code == "en" || $lang_code == null || $lang_code == 'null'){
            $lang_code = "en";
            $data = TemplatePageSlug::select('slug','name as trans')->where('template_page_id',$page->id)->get();
            $general_data = TemplatePageSlug::select('slug','name as trans')->where('template_page_id',$g_page->id)->get();
        }else{
             $lang = Language::where('code',$lang_code)->first();
            $data = PageTranslation::select('slug','translation as trans')->where('language_id',$lang->id)->where('page_id',$page->id)->get();
            $general_data = PageTranslation::select('slug','translation as trans')->where('language_id',$lang->id)->where('page_id',$g_page->id)->get();
        }
        $res = [];
        foreach($data as $value){
            $res[$page_slug][$value->slug] = $value->trans;
        }
        $g_res = [];
        foreach($general_data as $value){
            $g_res['general'][$value->slug] = $value->trans;
        }

        $response = array_merge($res,$g_res);

        return response()->json([
            'locale' => $lang_code,
            'messages' => $response,
        ]);
    }
}
