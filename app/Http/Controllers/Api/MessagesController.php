<?php

namespace App\Http\Controllers\Api;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Conversations;
use App\Models\Messages;
use App\Models\User;
use App\Models\Settings;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class MessagesController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Display all messages inbox
     *
     * @return Response
     */
    public function inbox()
    {
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 6;
        $messages = Conversations::with('messages')->where('user_1', Auth::user()->id)
            ->orWhere('user_2', Auth::user()->id)
            ->orderBy('updated_at', 'DESC')
            ->paginate($per_page);

        $res = [];
        foreach ($messages as $msg) {
            if ($msg->last()->from_user_id == Auth::user()->id && $msg->last()->to()->id != Auth::user()->id) {

                $avater = $msg->last()->to()->avater;

                $first_name = $msg->last()->to()->first_name;
                $last_name = $msg->last()->to()->last_name;

                $userID = $msg->last()->to()->id;

                $icon = $msg->last()->status == 'readed' ? '<small><i class="la la-check-double mr-1 text-muted"></i></small>' : '<small><i class="la la-reply mr-1 text-muted"></i></small>';
                if (Cache::has('is-online-' . $userID)) {
                    $userOnlineStatus = true;
                } else {
                    $userOnlineStatus = false;
                }

            } else if ($msg->last()->from_user_id == Auth::user()->id) {

                $avater = $msg->last()->to()->avater;

                $first_name = $msg->last()->to()->first_name;
                $last_name = $msg->last()->to()->last_name;

                $userID = $msg->last()->to()->id;

                $icon = null;
                if (Cache::has('is-online-' . $userID)) {
                    $userOnlineStatus = true;
                } else {
                    $userOnlineStatus = false;
                }

            } else {

                $avater = $msg->last()->from()->avater;

                $first_name = $msg->last()->from()->first_name;
                $last_name = $msg->last()->from()->last_name;

                $userID = $msg->last()->from()->id;


                $icon = null;
                if (Cache::has('is-online-' . $userID)) {
                    $userOnlineStatus = true;
                } else {
                    $userOnlineStatus = false;
                }

            }

            switch ($msg->last()->format) {

                case 'image':

                    $iconMedia = '<i class="feather las la-image"></i> ' . trans('Image');

                    break;

                case 'doc':

                    $iconMedia = '<i class="feather las la-doc"></i> ' . trans('Document');

                    break;

                case 'music':

                    $iconMedia = '<i class="feather las la-mic"></i> ' . trans('Audio');

                    break;

                case 'zip':

                    $iconMedia = '<i class="las la-file-archive"></i> ' . trans('File');

                    break;

                default:

                    $iconMedia = null;

            }

/* New - Readed */

            if ($msg->last()->status == 'new' && $msg->last()->from()->id != Auth::user()->id) {

                $styleStatus = ' active';

            } else {

                $styleStatus = null;

            }
            
            $messagesCount = Messages::where('from_user_id', $userID)->where('to_user_id', Auth::user()->id)->where('status','new')->count();
            $res[] = [
                'avater' => $avater,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'userID' => $userID,
                'icon' => $icon,
                'last_message' => substr($msg->last()->message,0,25),
                'styleStatus' => $styleStatus,
                'iconMedia' => $iconMedia,
                'messagesCount' => $messagesCount,
                'created_at' => $msg->last()->created_at->diffForHumans() ,
                'isOnline' =>  $userOnlineStatus,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'data' => $res,
            ],
        ], 200);

    } //<--- End Method inbox

    /**
     * Section chat
     *
     * @param int  $id
     * @return Response
     */
    public function messages($id)//user id
    {
        $skip = $_GET['skip'];

        $user = User::whereId($id)->where('id', '<>', Auth::user()->id)->firstOrFail();

        $allMessages = Messages::where('to_user_id', Auth::user()->id)
            ->where('from_user_id', $id)
            ->orWhere('from_user_id', Auth::user()->id)
            ->where('to_user_id', $id)
            ->orderBy('messages.created_at', 'ASC')
            ->get();

        $messages = Messages::where('to_user_id', Auth::user()->id)
            ->where('from_user_id', $id)
            ->orWhere('from_user_id', Auth::user()->id)
            ->where('to_user_id', $id)
            ->skip($skip)
            ->take(10)
            ->orderBy('messages.created_at', 'DESC')
            ->get();

        $data = [];

        if ($messages->count()) {
            $data['reverse'] = collect($messages->values())->reverse();
        } else {
            $data['reverse'] = $messages;
        }

        $messages = $data['reverse'];
        $counter = ($allMessages->count() - 10 - $skip);

        //UPDATE MESSAGE 'READED'
        Messages::where('from_user_id', $id)
            ->where('to_user_id', Auth::user()->id)
            ->where('status', 'new')
            ->update(['status' => 'readed']);


        $messages_res = [];
        foreach($messages as $msg){

            if ($msg->from_user_id  == Auth::user()->id) {
            $avater   = $msg->to()->avater;
            $first_name     = $msg->to()->first_name;
            $last_name     = $msg->to()->last_name;
            $userID   = $msg->to()->id;
            // $username = $msg->to()->username;

            } else if ($msg->to_user_id  == Auth::user()->id) {
            $avater   = $msg->from()->avater;
            $first_name     = $msg->from()->first_name;
            $last_name     = $msg->from()->last_name;
            $userID   = $msg->from()->id;
            // $username = $msg->from()->username;
            }

            if ( ! request()->ajax()) {
                $classInvisible = null;
            } else {
                 $classInvisible = null;
            }

            // $imageMsg = url('files/messages', $msg->id).'/'.$msg->file;
            $imageMsg = asset('storage/'.config('path.messages').$msg->file);
           

            if ($msg->file != '' && $msg->format == 'image') {
            $messageChat = '<a href="'.$imageMsg.'" data-group="gallery'.$msg->id.'" class="js-smartPhoto">
            <img src="'.$imageMsg.'" width="200px" height="auto"/></div>
            </a>';
            } elseif ($msg->file != '' && $msg->format == 'video') {
            $messageChat = '<div class="container-media-msg"><video class="js-player '.$classInvisible.'" controls>
                <source src="'. $imageMsg.'" type="video/mp4" />
            </video></div>
            ';
            } elseif ($msg->file != '' && $msg->format == 'music') {
            $messageChat = '<div class="container-media-music"><audio class="js-player '.$classInvisible.'" controls>
                <source src="'. $imageMsg.'" type="audio/mp3">
                Your browser does not support the audio tag.
            </audio></div>';
            } elseif ($msg->file != '' && $msg->format == 'zip') {
            $messageChat = '<a href="'.url('/api/download/message/file', $msg->id).'" class="d-block text-decoration-none">
            <div class="card">
                <div class="row no-gutters">
                <div class="col-md-3 text-center bg-primary">
                    <i class="la la-file-archive m-2 text-white" style="font-size: 40px;"></i>
                </div>
                <div class="col-md-9">
                    <div class="card-body py-2 px-4">
                    <h6 class="card-title text-primary text-truncate mb-0">
                        '.$msg->original_name.'.zip
                    </h6>
                    <p class="card-text">
                        <small class="text-muted">'.$msg->size.'</small>
                    </p>
                    </div>
                </div>
                </div>
            </div>
            </a>';
            } elseif ($msg->order == 'yes') {
            $messageChat = '<div class="card">
                <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="card-body py-2 px-4">
                    <h6 class="card-title text-primary text-truncate mb-0">
                        <i class="la la-donate mr-1"></i> '.__('general.tip'). ' -- ' .'order'.'
                    </h6>
                    </div>
                </div>
                </div>
            </div>';
            } else {
                $messageChat = Helper::linkText(Helper::checkText($msg->message));
            }

            $messages_res[] = [
                'id' => $msg->id,
                'avater' => $avater,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'userID' => $userID,
                'classInvisible' => $classInvisible,
                'from_user_id' => $msg->from_user_id,
                'login_user_id' => Auth::user()->id,
                'created_at' => $msg->created_at ,
                'messageChat' => $messageChat,
            ];


        }
        return response()->json([
            'messages' => $messages_res,
            'user' => $user,
            'counter' => $counter,
            'allMessages' => $allMessages->count(),
        ]);

    } //<--- End Method messages

    /**
     * Load More Messages
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function loadmore(Request $request)
    {
        $id = $request->input('id');
        $skip = $request->input('skip');

        $user = User::whereId($id)->where('id', '<>', Auth::user()->id)->firstOrFail();

        $allMessages = Messages::where('to_user_id', Auth::user()->id)
            ->where('from_user_id', $id)
            ->orWhere('from_user_id', Auth::user()->id)
            ->where('to_user_id', $id)
            ->orderBy('messages.created_at', 'ASC')
            ->get();

        $messages = Messages::where('to_user_id', Auth::user()->id)
            ->where('from_user_id', $id)
            ->orWhere('from_user_id', Auth::user()->id)
            ->where('to_user_id', $id)
            ->skip($skip)
            ->take(10)
            ->orderBy('messages.created_at', 'DESC')
            ->get();

        $data = [];

        if ($messages->count()) {
            $data['reverse'] = collect($messages->values())->reverse();
        } else {
            $data['reverse'] = $messages;
        }

        $messages = $data['reverse'];
        $counter = ($allMessages->count() - 10 - $skip);

        return view('includes.messages-chat', [
            'messages' => $messages,
            'user' => $user,
            'counter' => $counter,
            'allMessages' => $allMessages->count(),
        ])->render();

    } //<--- End Method

    public function send(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(array('session_null' => true));
        }

        // $settings = AdminSettings::first();

        // PATHS
        $path = config('path.messages');

        $sizeAllowed = Settings::getOption('file_size_allowed') * 1024;

        // Find user in Database
        $user = User::findOrFail($request->get('id_user'));

        if ($request->hasFile('photo')) {

            $requiredMessage = null;

            $originalExtension = strtolower($request->file('photo')->getClientOriginalExtension());
            $getMimeType = $request->file('photo')->getMimeType();

            if ($originalExtension == 'mp3' && $getMimeType == 'application/octet-stream') {
                $audio = ',application/octet-stream';
            } else {
                $audio = null;
            }

            if ($originalExtension == 'mp4'
                || $originalExtension == 'mov'
                || $originalExtension == 'mp3'
            ) {
                $isImage = null;
            } else {
                $isImage = true;
            }
        } else {
            $isImage = null;
            $audio = null;
            $originalExtension = null;
            $requiredMessage = 'required|';
        }
        
        if ($request->hasFile('zip')) {
            $requiredMessage = null;
        }

        // Setup the validator
        $rules = [
            'photo' => 'mimetypes:image/jpeg,image/gif,image/png,video/mp4,video/quicktime,audio/mpeg' . $audio . '|max:' .  Settings::getOption('file_size_allowed') . ',' . $isImage . '',
            'message' => $requiredMessage . '|min:1|max:' . Settings::getOption('comment_length') . '',
            'zip' => 'mimes:zip|max:' .Settings::getOption('file_size_allowed') . '',
        ];

        $messages = [
            "required" => trans('validation.required'),
            "message.max" => trans('validation.max.string'),
            'photo.dimensions' => trans('general.validate_dimensions'),
            'photo.mimetypes' => trans('general.formats_available'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ));
        }

        // Upload File Zip
        if ($request->hasFile('zip')) {

            $fileZip = $request->file('zip');
            $extension = $fileZip->getClientOriginalExtension();
            $size = Helper::formatBytes($fileZip->getSize(), 1);
            $originalName = Helper::fileNameOriginal($fileZip->getClientOriginalName());
            $file = strtolower(Auth::user()->id . time() . Str::random(20) . '.' . $extension);
            $format = 'zip';

            $fileZip->storePubliclyAs($path, $file);

        }

        //============= Upload Media
        if ($request->hasFile('photo') && $isImage) {

            $photo = $request->file('photo');
            $extension = $photo->getClientOriginalExtension();
            $mimeType = $request->file('photo')->getMimeType();
            $widthHeight = getimagesize($photo);
            $file = strtolower(Auth::user()->id . time() . Str::random(20) . '.' . $extension);
            $size = Helper::formatBytes($request->file('photo')->getSize(), 1);
            $format = 'image';
            $originalName = $request->file('photo')->getClientOriginalName();
            $url = ucfirst(Helper::urlToDomain(url('/')));

            set_time_limit(0);
            ini_set('memory_limit', '512M');

            if ($extension == 'gif' && $mimeType == 'image/gif') {
                $request->file('photo')->storePubliclyAs($path, $file);
            } else {
                //=============== Image Large =================//
                $img = Image::make($photo);

                $width = $img->width();
                $height = $img->height();
                $max_width = $width < $height ? 800 : 1400;

                if ($width > $max_width) {
                    $scale = $max_width;
                } else {
                    $scale = $width;
                }

                // Calculate font size
                if ($width >= 400 && $width < 900) {
                    $fontSize = 16;
                } elseif ($width >= 800 && $width < 1200) {
                    $fontSize = 20;
                } elseif ($width >= 1200 && $width < 2000) {
                    $fontSize = 24;
                } elseif ($width >= 2000) {
                    $fontSize = 32;
                } else {
                    $fontSize = 0;
                }

                if (Settings::getOption('watermark') == 'on') {
                    $imageResize = $img->orientate()->resize($scale, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->text($url . '/' . auth()->user()->username, $img->width() - 20, $img->height() - 10, function ($font)
                         use ($fontSize) {
                            $font->file(public_path('webfonts/arial.TTF'));
                            $font->size($fontSize);
                            $font->color('#eaeaea');
                            $font->align('right');
                            $font->valign('bottom');
                        })->encode($extension);
                } else {
                    $imageResize = $img->orientate()->resize($scale, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode($extension);
                }

                // Storage Image
                Storage::put($path . $file, $imageResize, 'public');
            }

        } //<====== End Upload Image

        //<----------- UPLOAD VIDEO
        if ($request->hasFile('photo')
            && $isImage == null
            && $originalExtension == 'mp4'
            || $originalExtension == 'mov'
        ) {

            $extension = $request->file('photo')->getClientOriginalExtension();
            $file = strtolower(Auth::user()->id . time() . Str::random(20) . '.' . $extension);
            $size = Helper::formatBytes($request->file('photo')->getSize(), 1);
            $format = 'video';
            $originalName = $request->file('photo')->getClientOriginalName();
            set_time_limit(0);

            //======= Storage Video
            $request->file('photo')->storePubliclyAs($path, $file);

        } //<====== End UPLOAD VIDEO

        //<----------- UPLOAD MUSIC
        if ($request->hasFile('photo')
            && $isImage == null
            && $originalExtension == 'mp3'
        ) {

            $extension = $request->file('photo')->getClientOriginalExtension();
            $file = strtolower(Auth::user()->id . time() . Str::random(20) . '.' . $extension);
            $size = Helper::formatBytes($request->file('photo')->getSize(), 1);
            $format = 'music';
            $originalName = $request->file('photo')->getClientOriginalName();
            set_time_limit(0);

            //======= Storage Video
            $request->file('photo')->storePubliclyAs($path, $file);

        } //<====== End UPLOAD MUSIC

        // Verify Conversation Exists
        $conversation = Conversations::where('user_1', Auth::user()->id)
            ->where('user_2', $request->get('id_user'))
            ->orWhere('user_1', $request->get('id_user'))
            ->where('user_2', Auth::user()->id)->first();

        $time = Carbon::now();

        if (!isset($conversation)) {
            $newConversation = new Conversations;
            $newConversation->user_1 = Auth::user()->id;
            $newConversation->user_2 = $request->get('id_user');
            $newConversation->updated_at = $time;
            $newConversation->save();

            $conversationID = $newConversation->id;

        } else {
            $conversation->updated_at = $time;
            $conversation->save();

            $conversationID = $conversation->id;
        }

        if ($request->hasFile('photo') || $request->hasFile('zip')) {
            $message = new Messages;
            $message->conversations_id = $conversationID;
            $message->from_user_id = Auth::user()->id;
            $message->to_user_id = $request->get('id_user');
            $message->message = '';
            $message->file = $file;
            $message->original_name = $originalName;
            $message->format = $format;
            $message->size = $size;
            $message->updated_at = $time;
            $message->save();

            return response()->json(array(
                'success' => true,
                'last_id' => $message->id,
            ), 200);
        }

        if ($request->get('message')) {
            $message = new Messages;
            $message->conversations_id = $conversationID;
            $message->from_user_id = Auth::user()->id;
            $message->to_user_id = $request->get('id_user');
            $message->message = trim(Helper::checkTextDb($request->get('message')));
            $message->updated_at = $time;
            $message->save();

            return response()->json(array(
                'success' => true,
                'last_id' => $message->id,
            ), 200);
        }
    } //<<--- End Method send()

    public function ajaxChat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(array('session_null' => true));
        }

        $_sql = $request->get('first_msg') == 'true' ? '=' : '>';

        $message = Messages::where('to_user_id', Auth::user()->id)
            ->where('from_user_id', $request->get('user_id'))
            ->where('id', $_sql, $request->get('last_id'))
            ->orWhere('from_user_id', Auth::user()->id)
            ->where('to_user_id', $request->get('user_id'))
            ->where('id', $_sql, $request->get('last_id'))
            ->orderBy('messages.created_at', 'ASC')
            ->get();
            
        $count = $message->count();
        $_array = array();

        $messages_res = [];
        if ($count != 0) {

            foreach($message as $msg){
    
                if ($msg->from_user_id  == Auth::user()->id) {
                $avater   = $msg->to()->avater;
                $first_name     = $msg->to()->first_name;
                $last_name     = $msg->to()->last_name;
                $userID   = $msg->to()->id;
                // $username = $msg->to()->username;
    
                } else if ($msg->to_user_id  == Auth::user()->id) {
                $avater   = $msg->from()->avater;
                $first_name     = $msg->from()->first_name;
                $last_name     = $msg->from()->last_name;
                $userID   = $msg->from()->id;
                // $username = $msg->from()->username;
                }
    
                if ( ! request()->ajax()) {
                    $classInvisible = 'invisible';
                } else {
                     $classInvisible = null;
                }
    
                $imageMsg = asset('storage/'.config('path.messages').$msg->file);
           

                if ($msg->file != '' && $msg->format == 'image') {
                $messageChat = '<a href="'.$imageMsg.'" data-group="gallery'.$msg->id.'" class="js-smartPhoto">
                <img src="'.$imageMsg.'" width="200px" height="auto"/></div>
                </a>';
                } elseif ($msg->file != '' && $msg->format == 'video') {
                $messageChat = '<div class="container-media-msg"><video class="js-player '.$classInvisible.'" controls>
                    <source src="'. $imageMsg.'" type="video/mp4" />
                </video></div>
                ';
                } elseif ($msg->file != '' && $msg->format == 'music') {
                $messageChat = '<div class="container-media-music"><audio class="js-player '.$classInvisible.'" controls>
                    <source src="'. $imageMsg.'" type="audio/mp3">
                    Your browser does not support the audio tag.
                </audio></div>';
                }elseif ($msg->file != '' && $msg->format == 'zip') {
                $messageChat = '<a href="'.url('download/message/file', $msg->id).'" class="d-block text-decoration-none">
                <div class="card">
                    <div class="row no-gutters">
                    <div class="col-md-3 text-center bg-primary">
                        <i class="la la-file-archive m-2 text-white" style="font-size: 40px;"></i>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body py-2 px-4">
                        <h6 class="card-title text-primary text-truncate mb-0">
                            '.$msg->original_name.'.zip
                        </h6>
                        <p class="card-text">
                            <small class="text-muted">'.$msg->size.'</small>
                        </p>
                        </div>
                    </div>
                    </div>
                </div>
                </a>';
                } elseif ($msg->order == 'yes') {
                $messageChat = '<div class="card">
                    <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="card-body py-2 px-4">
                        <h6 class="card-title text-primary text-truncate mb-0">
                            <i class="la la-donate mr-1"></i> '.__('general.tip'). ' -- ' .'order'.'
                        </h6>
                        </div>
                    </div>
                    </div>
                </div>';
                } else {
                $messageChat = Helper::linkText(Helper::checkText($msg->message));
                }
    
                $messages_res[] = [
                    'id' => $msg->id,
                    'avater' => $avater,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'userID' => $userID,
                    'classInvisible' => $classInvisible,
                    'from_user_id' => $msg->from_user_id,
                    'login_user_id' => Auth::user()->id,
                    'created_at' => $msg->created_at ,
                    'messageChat' => $messageChat,
                ];
    
    
            }
        } //<--- IF != 0

        // Check User Online
        if (Cache::has('is-online-' . $request->get('user_id'))) {
            $userOnlineStatus = true;
        } else {
            $userOnlineStatus = false;
        }

        $user = User::findOrFail($request->get('user_id'));

        return response()->json(array(
            'total' => $count,
            'messages' => $messages_res,
            'success' => true,
            'to' => $request->get('user_id'),
            'userOnline' => $userOnlineStatus,
            'last_seen' => date('c', strtotime($user->last_seen ?? $user->date)),
        ), 200);
    } //<--- End Method ajaxChat

    public function deleteConversation($id)
    {
     $path = config('path.messages');

     $messages = Messages::where('to_user_id', Auth::user()->id)
           ->where('from_user_id', $id)
           ->orWhere( 'from_user_id', Auth::user()->id )
           ->where('to_user_id', $id)
           ->get();

     if ($messages->count() != 0) {

       foreach ($messages as $msg) {

         Storage::delete($path.$msg->file);

         $msg->delete();
       }

         $conversation = Conversations::find($messages[0]->conversations_id);
         $conversation->delete();

         return response()->json(array(
            'success' => true,
            'response' => "general.messages_deleted_successfully",
        ), 200);

     } else {
        return response()->json(array(
            'success' => true,
            'response' => "general.no_messages_found!",
        ), 200);
     }
   }//<--- End Method delete

    public function searchCreator(Request $request)
    {
        $settings = AdminSettings::first();
        $query = $request->get('user');
        $data = "";

        if ($query != '' && strlen($query) >= 2) {
            $sql = User::where('status', 'active')
                ->where('username', 'LIKE', '%' . $query . '%')
                ->where('id', '<>', Auth::user()->id)
                ->whereVerifiedId('yes')
                ->where('id', '<>', $settings->hide_admin_profile == 'on' ? 1 : 0)
                ->orWhere('status', 'active')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->where('id', '<>', Auth::user()->id)
                ->whereVerifiedId('yes')
                ->where('id', '<>', $settings->hide_admin_profile == 'on' ? 1 : 0)
                ->orderBy('id', 'desc')
                ->take(10)
                ->get();

            if ($sql) {
                foreach ($sql as $user) {

                    if (Cache::has('is-online-' . $user->id)) {
                        $userOnlineStatus = 'user-online';
                    } else {
                        $userOnlineStatus = 'user-offline';
                    }

                    $data .= '<div class="card mb-2">
             <div class="list-group list-group-sm list-group-flush">
               <a href="' . url('messages/' . $user->id, $user->username) . '" class="list-group-item list-group-item-action text-decoration-none p-2">
                 <div class="media">
                  <div class="media-left mr-3 position-relative ' . $userOnlineStatus . '">
                      <img class="media-object rounded-circle" src="' . Storage::url(config('path.avater') . $user->avater) . '" width="45" height="45">
                  </div>
                  <div class="media-body overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center">
                     <h6 class="media-heading mb-0 text-truncate">
                          ' . $user->name . '
                      </h6>
                    </div>
                    <p class="text-truncate m-0 w-100 text-left">
                    <small>@' . $user->username . '</small>
                    </p>
                  </div>
              </div>
                </a>
             </div>
           </div>';
                }
                return $data;
            }
        }
    } // End Method

    // Download File
    public function downloadFileZip($id)
    {
        $msg = Messages::findOrFail($id);

        $pathFile = config('path.messages') . $msg->file;
        $headers = [
            'Content-Type:' => ' application/x-zip-compressed',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return Storage::download($pathFile, $msg->original_name . '.zip', $headers);

    }

    public function messagesImage($id, $path)
    {
    
        // try {

            $server = ServerFactory::create([
                'response' => new LaravelResponseFactory(app('request')),
                'source' => Storage::disk()->getDriver(),
                'cache' => Storage::disk()->getDriver(),
                'source_path_prefix' => '/uploads/messages/',
                'cache_path_prefix' => '.cache',
                'base_url' => '/uploads/messages/',
            ]);

            $response = Messages::whereId($id)
                ->whereFromUserId(auth()->user()->id)
                ->orWhere('id', '=', $id)->where('to_user_id', '=', auth()->user()->id)
                ->firstOrFail();

            $server->outputImage($path, $this->request->all());
            $server->deleteCache($path);

        // } catch (\Exception $e) {

        //     abort(404);
        //     $server->deleteCache($path);
        // }
    } //<--- End Method

}
