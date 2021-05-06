<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


    Route::get('/get-trans/{lang_id}/{page}', 'Api\LanguageController@get');

    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
    Route::post('forgot-password', 'Api\AuthController@forgotPassword');
    Route::post('update-password', 'Api\AuthController@updatePassword')->name('update-password');
    Route::post('account-role/update', 'Api\AuthController@updateRole')->name('update-role');
    Route::get('logout', 'Api\AuthController@logout');
    Route::get('refresh', 'Api\AuthController@refresh');
    Route::get('get-user', 'Api\AuthController@me');
    Route::get('account-verify/{token}', 'Api\AuthController@mailVerify');
    Route::post('resend-confirmation/', 'Api\AuthController@resendConfirmMail');
    Route::get('settings', 'Api\AuthController@getSettings');
    Route::get('order-requirements', 'Api\OrderController@getOrderRequirements');
    Route::get('get-languages', 'Api\FrontendController@getLanguage');
    Route::post('/getCalculatorPrice', 'Api\FrontendController@getCalculatorPrice');
    Route::get('/get-page-content/{slug}', 'Api\FrontendController@getPageContent');

    Route::get('/get-writers', 'Api\FrontendController@getWriters');
    Route::get('/get-services', 'Api\FrontendController@getServices');
    Route::get('/get-service-types', 'Api\FrontendController@getServiceTypes');
    Route::get('/get-reviews', 'Api\FrontendController@getReviews');
    Route::get('/get-writing-service-features', 'Api\FrontendController@getWritingServiceFeatures');
    Route::get('/get-FAQs', 'Api\FrontendController@getFAQs');
    Route::get('/get-how_to_orders', 'Api\FrontendController@getHowToOrders');
    Route::get('/user/writers/{status}', 'Api\WriterController@getWriters');
    Route::get('user/get-user/{id}', 'Api\UserController@getUser');
    
    Route::middleware('auth:api')->prefix('user')->group(function () {
        Route::get('/get', 'Api\DashboardController@getUser');
        Route::post('/getPrice', 'Api\OrderController@getPrice');
        Route::post('/create-order', 'Api\OrderController@create')->middleware('role:buyer');
        Route::get('/getOrder/{id}', 'Api\OrderController@getOrder')->middleware('role:buyer');
        Route::get('/get-order-details/{id}', 'Api\OrderController@getOrderDetails');
        Route::get('/get-orders/{status}', 'Api\OrderController@getOrders');
        Route::get('/delete-order/{id}', 'Api\OrderController@deleteOrder')->middleware('role:buyer');
        Route::get('/get-order-delivery-files/{id}', 'Api\OrderController@getOrderDeliveryFiles');
        Route::get('/order/mark-as-complete/{id}', 'Api\OrderController@completeOrder');

        Route::get('/order/get-bids/{id}/{type}', 'Api\BidController@getBidders')->middleware('role:buyer');
        Route::get('/get-bid/{id}', 'Api\BidController@getBid');
        Route::get('/order/invite-writer/{id}/{order_id}', 'Api\BidController@inviteWriter')->middleware('role:buyer');
        Route::get('/order/cancel-invitation/{inviation_id}', 'Api\BidController@cancelInviteWriter')->middleware('role:buyer');
        Route::get('/bid/add-to-shortlist/{bid_id}', 'Api\BidController@addToShortList')->middleware('role:buyer');
        Route::get('/bid/remove-from-shortlist/{bid_id}', 'Api\BidController@removeFromShortList')->middleware('role:buyer');
        Route::get('/bid/decline/{bid_id}', 'Api\BidController@declineBid')->middleware('role:buyer');
        Route::get('/bid/undo-decline/{bid_id}', 'Api\BidController@undoDeclineBid')->middleware('role:buyer');

       
        Route::get('/invite-writers/{status}/{order_id}', 'Api\WriterController@getInviteWriters')->middleware('role:buyer');

        Route::post('/update-profile', 'Api\UserController@updateProfile');
        Route::get('/account/delete', 'Api\UserController@deleteAccount');

        

        // Messages
        Route::get('messages', 'Api\MessagesController@inbox');
        // Message Chat
        Route::get('messages/{id}', 'Api\MessagesController@messages')->where(array('id' => '[0-9]+'));
        Route::get('loadmore/messages', 'Api\MessagesController@loadmore');
        Route::post('message/send', 'Api\MessagesController@send');
        Route::get('messages/ajax/chat', 'Api\MessagesController@ajaxChat');
        Route::get('messages/search/creator', 'Api\MessagesController@searchCreator');
        Route::get('conversation/delete/{id}', 'Api\MessagesController@deleteConversation');
        
        Route::get('files/messages/{id}/{path}', 'Api\MessagesController@messagesImage')->where(['id' =>'[0-9]+', 'path' => '.*']);

        //writer api
        Route::get('writer/find-orders', 'Api\Writer\OrderController@findOrders')->middleware('role:writer');
        Route::post('writer/submit-proposal', 'Api\Writer\OrderController@SubmitProposal')->middleware('role:writer');
        Route::get('writer/save-order/{id}', 'Api\Writer\OrderController@saveOrder')->middleware('role:writer');
        Route::get('writer/get-bids/{status}', 'Api\Writer\OrderController@getBids')->middleware('role:writer');
        Route::get('/writer/bid/decline/{bid_id}', 'Api\Writer\OrderController@declineBid')->middleware('role:writer');
        Route::get('/writer/edit-bid/{bid_id}', 'Api\Writer\OrderController@editBid')->middleware('role:writer');
        Route::post('/writer/upload-delivery', 'Api\Writer\OrderController@uploadDelivery')->middleware('role:writer');
        
        //payment route
        Route::get('/payment-getway', 'Api\PaymentController@getPaymentGetway');
        Route::post('/stipe-charge-create', 'Api\PaymentController@stripeChargeCreate');
        Route::post('paypal-payment-create', 'Api\PaymentController@paypalPaymentCreate');
        
        //download route
        Route::get('download/delivery-file/{id}', 'Api\DownloadController@downloadDeliveryFile');
        
        //feedback
        Route::get('order/get-feedbacks/{id}', 'Api\FeedbackController@getOrderFeedback');
        Route::post('order/feedback-submit', 'Api\FeedbackController@submitFeedback');
        
        //notifications
        Route::get('get-notifications', 'Api\UserController@notifications');
        Route::get('check-new-notifications', 'Api\UserController@checkNewNotifications');
        
    });
    Route::get('download/message/file/{id}','Api\MessagesController@downloadFileZip');

