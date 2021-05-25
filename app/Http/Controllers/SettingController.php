<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Config,Artisan;
class SettingController extends Controller
{
    public function setting(Request $request)
    {
    	if ($request->isMethod('post')) {
        
    		foreach($request->setting as $key=>$set)
    		{
    			if($key == 'favicon' || $key == 'logo' || $key == 'default_avater')
    			{
    		
    				$image_name = time().'-'.rand(10000,99999).'.'.$set->getClientOriginalExtension();
                	$set->move(public_path("assets/logo"), $image_name);
                	$image_path = "assets/logo/" . $image_name;
    				$setting = Settings::where('name',$key)->first();
			        $photo_path = public_path().'/'.$setting->value;
			        if (file_exists($photo_path)){
			            unlink($photo_path);
			        }
    				$setting->value = $image_path;
    				$setting->update();
    			}else{

    				$setting = Settings::where('name',$key)->first();
    				$setting->value = $set;
    				$setting->update();
    			}
    			
    		}
    		$path = base_path('.env');
    		
    		if (file_exists($path)) {
                    $file_content ='APP_NAME='.'"'.get_setting()['site_name'].'"'.PHP_EOL;
                    $file_content .='APP_ENV=local'.PHP_EOL;
                    $file_content .='APP_KEY=base64:0WuprjN8nu8eUPzFv+wL/5NKC1iro5qFuwpB8g/DJeU='.PHP_EOL;
                    $file_content .='APP_URL='.get_setting()['app_url'].PHP_EOL;
                    $file_content .='MIX_APP_URL='.get_setting()['app_url'].PHP_EOL;
                    $file_content .='BASE_URL='.get_setting()['app_url'].PHP_EOL;
                    $file_content .='APP_DEBUG=true'.PHP_EOL;
                    $file_content .='LOG_CHANNEL=stack'.PHP_EOL.PHP_EOL;
                    $file_content .='DB_CONNECTION=mysql'.PHP_EOL;
                    $file_content .='DB_HOST=127.0.0.1'.PHP_EOL;
                    $file_content .='DB_PORT=3306'.PHP_EOL;
                    $file_content .='DB_DATABASE='.config('database.connections.mysql.database').PHP_EOL; //change it
                    $file_content .='DB_USERNAME='.config('database.connections.mysql.username').PHP_EOL;
                    $file_content .='DB_PASSWORD='.config('database.connections.mysql.password').PHP_EOL.PHP_EOL;
                    $file_content .='BROADCAST_DRIVER=log'.PHP_EOL;
                    $file_content .='BROADCAST_DRIVER=log'.PHP_EOL;
                    $file_content .='CACHE_DRIVER=file'.PHP_EOL;
                    $file_content .='QUEUE_CONNECTION=sync'.PHP_EOL;
                    $file_content .='SESSION_DRIVER=file'.PHP_EOL;
                    $file_content .='SESSION_LIFETIME=120'.PHP_EOL.PHP_EOL;
                    $file_content .='REDIS_HOST=127.0.0.1'.PHP_EOL;
                    $file_content .='REDIS_PASSWORD=null'.PHP_EOL;
                    $file_content .='REDIS_PORT=6379'.PHP_EOL.PHP_EOL;

                    $file_content .='MAIL_DRIVER='.get_setting()['mail_driver'].PHP_EOL;
                    $file_content .='MAIL_HOST='.get_setting()['mail_host'].PHP_EOL;
                    $file_content .='MAIL_PORT='.get_setting()['mail_port'].PHP_EOL;
                    $file_content .='MAIL_USERNAME='.get_setting()['mail_username'].PHP_EOL;
                    $file_content .='MAIL_PASSWORD='.get_setting()['mail_password'].PHP_EOL;
                    $file_content .='MAIL_ENCRYPTION='.get_setting()['mail_encryption'].PHP_EOL;
                    $file_content .='MAIL_FROM_ADDRESS='.get_setting()['from_mail'].PHP_EOL;
                    $file_content .='MAIL_FROM_NAME='.'"'.get_setting()['from_name'].'"'.PHP_EOL;

                    $file_content .='TIMEZONE=GMT'.PHP_EOL;
                    $file_content .='LOCALE=en'.PHP_EOL;
                    $file_content .='FALLBACK_LOCALE=en'.PHP_EOL.PHP_EOL;
                    $file_content .='JWT_SECRET=rAMhWrth9mxrstqLVk3UNT2cVXqS0XM54PZhPSv4DE45X54OWNcHnXr3UrjlSqpj'.PHP_EOL.PHP_EOL;
                    
                    file_put_contents($path,$file_content);

                    
                }
    		//config cache
            Artisan::call('config:cache');
            Artisan::call('cache:clear');
    		return redirect()->back()->with(['success'=>'Setting Update Seccessfully.']);
    	}
    	$setting = Settings::all();
    	return view('admin.setting',['settings'=>$setting]);
    }
}
