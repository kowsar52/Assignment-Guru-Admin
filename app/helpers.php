<?php 

use App\Models\Admin;
use App\Models\Settings;
function get_admin()
{
	$admin_id = session('admin');
    $admin = Admin::where('id',$admin_id)->first();
    return $admin;
}

function get_setting()
{
    $settings = Settings::all();
    $setting =  array();
    
    foreach($settings as $key=>$v)
    {
        $setting[$v->name] = $v->value;
        
    }
    
    return $setting;
}
