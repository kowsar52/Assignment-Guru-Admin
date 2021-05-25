<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Settings;
use App\Models\WriterOrder;
use App\Models\Order;
use App\Models\Review;
use App\Models\VerificationToken;
use Session;
use App\Helper;
use Auth,DB;
use App\Mail\MasterMail;
use Carbon\Carbon;
use App\Models\Referal;
use JWTAuth;
use Cache;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','getSettings','forgotPassword','updatePassword','mailVerify','resendConfirmMail']]);
    }

    // registration method 
    public function register(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 200);
        }
        
        if($this->ip_info() == null){
            $country = "Bangladesh";
        }else{
            $country = $this->ip_info($ip = null, $purpose = "country", $deep_detect = TRUE);
        }

        //affiliate checking part start
        if ($request->affiliate) {
            $affilate_user = User::where('username','=',$request->affiliate)->first();

			if(!empty($affilate_user))
			{
				if(Settings::getOption('is_affilate') == 1)

				{
                    $referal_userID = $affilate_user->id;

				}

			}else{
                $referal_userID = null;
            }
           
        } else {
            $referal_userID = null;
        }
        //affiliate checking part end

     
            $user = new User();
            $user->username = Helper::strRandom();
            $user->first_name = $request->first_name;
            $user->last_name =  $request->last_name;
            $user->country = $country;
            $user->email 	= $request->email;
            $user->password = Hash::make($request->password);
            $user->referal_username = $referal_userID;
            // $user->role = 'buyer';
            $user->avater = Settings::getOption('default_avater');
            $user->status = 0;
            if($user->save())
            {
                if ($request->affiliate) {
                    Referal::insert([
                        'user_id' => $user->id,
                        'inviter_id' => $user->referal_username,
                        'bonous_credit' => 0,
                        'created_at'     => Carbon::now(),
                      ]);                   
                }
           
                    $verification_token = bin2hex(random_bytes(20));
                    VerificationToken::insert([
                        'user_id' => $user->id,
                        'verification_token' => $verification_token,
                        'created_at' => Carbon::now(),
                    ]);

                    if(isset($user->email)){       
                        $mail_data = array(       
                            "email_type" => 'verification_mail', 
                            'from_mail'      => Settings::getOption('from_mail'),
                            'from_name'      => Settings::getOption('from_name'),
                            "name" => $user->name, 
                            "to_email" => $user->email,  
                            "verification_link" => Settings::getOption('app_url').'verify-account/'.$verification_token, 
        
                        );
                        $mail_return = MasterMail::masterMail($mail_data);
                    }

            }else{
                return response()->json([
                    'status' => 'error',
                    'error'=>'Registration failed!'
                ], 200);
            }

        return response()->json(['status' => 'success','message'=>'Verification link has been sent to your '.$user->email.'. Click the link to activate your account. '], 200);
    }

    //updateRole
    public function updateRole(Request $request){
        User::where('id',Auth::user()->id)->update([
            'role' => $request->role,
            'updated_at' => Carbon::now(),
        ]);
        return response()->json(['success' => true,'message'=>'Updated!'], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');
        if ($token = auth('api')->attempt($credentials, $request->remember_me)) {
            if($user->status == 1){
                $response = [
                    'status' => 'success',
                    'user' => $user,
                    'token' => $token ? JWTAuth::fromUser($user) : false
                ]; 
                return response($response, 200);
            }else if($user->status == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account is not verified.'
                ], 422);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account is deactive.'
                ], 422); 
            }
        }
    
  

        return response()->json([
            'status' => 'error',
            'message' => 'Credential does not match.'
        ], 422);


    }

    public function getSettings()
    {
        $data = Settings::get();
        $res = [];
        foreach($data as $item){
            $res[$item->name] = $item->value;
        }
        $res['asset_url'] = asset('/');
        return json_encode($res);
    }

    public function forgotPassword(Request $request)
    {

            $validatedData = $request->validate([
                'email'     => 'required|email',
            ]);

            $user_code = bin2hex(random_bytes(20));
            $user = User::where('email',$request->email)->first();
            if(!empty($user))
            {
                $user->remember_token = $user_code;
                $user->update();

                if(isset($user->email)){       

                    $mail_data = array(       
                        "email_type" => 'reset_password_mail', 
                        'from_mail'      => Settings::getOption('from_mail'),
                        'from_name'      => Settings::getOption('from_name'),
                        "name" => $user->name, 
                        "to_email" => $user->email,  
                        "password_reset_link" => Settings::getOption('app_url').'reset-password/'.$user_code, 
                    );
                    $mail_return = MasterMail::masterMail($mail_data);
                }
               

                return response()->json([
                    'status' => 'success',
                ], 200);
            }else{
                return response()->json(['error' => 'Email is not valid.'], 422);
            }   

    }

    public function updatePassword(Request $request)
    {
        $user = User::where('remember_token',$request->token)->first();
        if(empty($user))
        {
            return response()->json(['error' => 'Unauthorized access page.'], 422);
        }
        if ($request->isMethod('post')) {

            $validatedData = $request->validate([
                'password'          => 'required',
                'confirm_password'  => 'required',
            ]);

            if($request->password == $request->confirm_password)
            {
                $user->password        = Hash::make($request->password);
                $user->remember_token  = '';
                if($user->update())
                {
                    return response()->json([
                        'status' => 'success',
                    ], 200);
                }else{
                    return response()->json(['error' => 'Password update failed!'], 422);
                }
            }else{
                return response()->json(['error' => "Password and Confirm password don't matched!"], 422);
            }
        }    
    }
    //resendConfirmMail
    public function resendConfirmMail(Request $request){
        $user = User::where('email',$request->email)->where('email_verified_at','=',null)->where('status','!=',1)->first();
        if(!empty($user))
        {
                $verification_token = bin2hex(random_bytes(20));
                VerificationToken::insert([
                    'user_id' => $user->id,
                    'verification_token' => $verification_token,
                    'created_at' => Carbon::now(),
                ]);

                if(isset($user->email)){       
                    $mail_data = array(       
                        "email_type" => 'verification_mail', 
                        'from_mail'      => Settings::getOption('from_mail'),
                        'from_name'      => Settings::getOption('from_name'),
                        "name" => $user->name, 
                        "to_email" => $user->email,  
                        "verification_link" => Settings::getOption('app_url').'verify-account/'.$verification_token, 
    
                    );
                    $mail_return = MasterMail::masterMail($mail_data);
                }

                return response()->json([
                    'status' => 'success',
                ], 200);

        }else{
            return response()->json([
                'status' => 'error',
                'error'=>'Something wrong or already verified.'
            ], 200);
        }
    
    }

    public function mailVerify($v_token){
        $check_token = VerificationToken::where('verification_token',$v_token)->first();
     
        if(!empty($check_token)){
            User::where('id',$check_token->user_id)->update([
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                ]);
            $user = User::find($check_token->user_id);
            VerificationToken::where('verification_token',$v_token)->delete();
            if ($token = JWTAuth::fromUser($user)) {
                if($user->status == 1){
                    $response = [
                        'status' => 'success',
                        'user' => $user,
                        'token' => $token
                    ]; 
                    return response($response, 200);
                }else if($user->status == 0){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your account is not verified.'
                    ], 422);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your account is deactive.'
                    ], 422); 
                }
            }

            return response()->json([
                'status' => 'success',
            ], 200);
        }else{
            return response()->json(['error' => 'Mail verification Failed..Try again'], 422);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        if($user->role == 'buyer'){
            $total_orders = Order::where('customer',$user->id)->count();
            $accepted_orders = Order::where('customer',$user->id)->where('status',2)->count();
            $total_review = Review::where('user_id',$user->id)->where('type','writer')->count();
            $avg_rating = Review::where('user_id',$user->id)->where('type','writer')->avg('star');
            if (Cache::has('is-online-' . $user->id)) {
                $isOnline = true;
            } else {
                $isOnline = false;
            }
            $success_percentage = 99;
            $pay_rates = 99;
            $extra =[
                'total_orders' => $total_orders,
                'accepted_orders' => $accepted_orders,
                'isOnline' => $isOnline,
                'avg_rating' => round($avg_rating,1),
                'total_reviews' => $total_review,
                'pay_rates' => $pay_rates,
            ];
    
            $res = array_merge($user->toArray() , $extra);
        }else{ //writer
            $total_orders = Order::where('customer',$user->id)->count();
            $accepted_orders = Order::where('customer',$user->id)->where('status',2)->count();
            $total_review = Review::where('user_id',$user->id)->where('type','writer')->count();
            $avg_rating = Review::where('user_id',$user->id)->where('type','writer')->avg('star');
            if (Cache::has('is-online-' . $user->id)) {
                $isOnline = true;
            } else {
                $isOnline = false;
            }
            $success_percentage = 99;
            $pay_rates = 99;
            $extra =[
                'total_orders' => $total_orders,
                'accepted_orders' => $accepted_orders,
                'isOnline' => $isOnline,
                'avg_rating' => round($avg_rating,1),
                'total_reviews' => $total_review,
                'pay_rates' => $pay_rates,
            ];
    
            $res = array_merge($user->toArray() , $extra);
        }
        return response()->json($res);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }



    public function getUser()
    {
        return response()->json(auth()->user());
    }

 
    public function Profile(Request $request)
    {
        
        
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id)->first();
                    
            $validatedData = $request->validate([
                'name' 			=> 'required',
                'username'      => 'unique:users,username',
                'email'         => 'unique:users,email',
            ]);
            $user->name = $request->name;
            $user->timezone = $request->timezone;
            if($request->username != '')
            {
                $user->username = $request->username;
            }
            if($request->email != '')
            {
                $user->email = $request->email;
            }

            if ($user->image != $request->image) {
                if (isset($request->image) && strlen($request->image) > 1) {
                    
                  
                    if($user->image != ''){
                    
                        $file_path = str_replace(url('/'), public_path('/'), $user->image);
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                        
                    }
    
                    $photo_name = time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                    \Image::make($request->image)->save(public_path('uploads/users/') . $photo_name);
                    $user->image = url('/uploads/users/' . $photo_name);
                }
            }

            
            if($user->update())
            {
               $res = ['success'=>"update successfully!"];
            }else{
                $res = ['success'=>"update failed!"];
            }
 
       return json_encode($res,200);
    }
    
    public function passwordChange(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id)->first();
  
            $validator = \Validator::make($request->all(), [
                "password" => 'required|min:6',
            ]);
            if ($validator->fails()) {

                return json_encode(['error'=>"The password must be at least 6 characters."]);
    
            }

            // if($request->password == $request->confirm_password)
            // {
            	$user->password  = Hash::make($request->password);
            
            	if($user->update())
            	{
            		return json_encode(['success'=>"Password successfully updated"]);
            	}else{

            		return json_encode(['error'=>"Password update failed!"]);
            	}
            // }else{
            // 	return json_encode(['error'=>"Password and Confirm password don't matched!"]);
            // }
  
    }

    public function regenerateApiKey()
    {
        $user = Auth::user();
        $user->api_token = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(64))), 0, 64);
        $user->update();
        return json_encode(['success'=>"Key regenrate successfully!"]);
    }

    public function guard()
    {
        return Auth::guard();
    }

        //get user ip info 
        private function ip_info($ip = null, $purpose = "location", $deep_detect = TRUE) {
            $output = NULL;
            if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
                $ip = $_SERVER["REMOTE_ADDR"];
                if ($deep_detect) {
                    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
          
            $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
            $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
            $continents = array(
                "AF" => "Africa",
                "AN" => "Antarctica",
                "AS" => "Asia",
                "EU" => "Europe",
                "OC" => "Australia (Oceania)",
                "NA" => "North America",
                "SA" => "South America"
            );
            if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
                $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
                if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                    switch ($purpose) {
                        case "location":
                            $output = array(
                                "city"           => @$ipdat->geoplugin_city,
                                "state"          => @$ipdat->geoplugin_regionName,
                                "country"        => @$ipdat->geoplugin_countryName,
                                "country_code"   => @$ipdat->geoplugin_countryCode,
                                "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                                "continent_code" => @$ipdat->geoplugin_continentCode
                            );
                            break;
                        case "address":
                            $address = array($ipdat->geoplugin_countryName);
                            if (@strlen($ipdat->geoplugin_regionName) >= 1)
                                $address[] = $ipdat->geoplugin_regionName;
                            if (@strlen($ipdat->geoplugin_city) >= 1)
                                $address[] = $ipdat->geoplugin_city;
                            $output = implode(", ", array_reverse($address));
                            break;
                        case "city":
                            $output = @$ipdat->geoplugin_city;
                            break;
                        case "state":
                            $output = @$ipdat->geoplugin_regionName;
                            break;
                        case "region":
                            $output = @$ipdat->geoplugin_regionName;
                            break;
                        case "country":
                            $output = @$ipdat->geoplugin_countryName;
                            break;
                        case "countrycode":
                            $output = @$ipdat->geoplugin_countryCode;
                            break;
                    }
                }
            }
            return $output;
        }
}
