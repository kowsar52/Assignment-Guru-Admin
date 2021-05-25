<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//clear cache route
Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    return back()->with('success','Cache Cleared Successfully!');
});

Route::get('/checkout/payment/cancle', 'Front\PaymentController@paypalCancel')->name('payment.cancle');
Route::get('/checkout/payment/notify', 'Front\PaymentController@paypalNotify')->name('payment.notify');


//admin routes
Route::any('admin/login', 'Admin\AdminController@login')->name('admin/login');
Route::get('admin/logout', 'Admin\AdminController@logout')->name('admin/logout');
Route::any('admin/reset-password', 'Admin\AdminController@resetPassword')->name('admin/reset-password');
Route::any('admin/update-password/{code}', 'Admin\AdminController@updatePassword')->name('admin/update-password');

Route::group(['middleware'=>['admin']],function(){
    Route::get('admin/dashboard', 'Admin\AdminController@dashboard')->name('admin/dashboard');
    Route::any('admin/setting', 'SettingController@setting')->name('admin/setting');
    Route::any('admin/users', 'Admin\AdminController@Users')->name('users');
    Route::get('admin/edit-user', 'Admin\AdminController@EditUser')->name('admin/edit-user');
    Route::get('admin/delete-user/{id}', 'Admin\AdminController@deleteUser')->name('admin/delete-user');
    Route::post('admin/update-user', 'Admin\AdminController@UpdateUser')->name('admin/update-user');
    Route::any('admin/profile', 'Admin\AdminController@Profile')->name('admin/profile');
    Route::any('admin/password-change', 'Admin\AdminController@passwordChange')->name('admin/password-change');
    
    Route::any('admin/email-template','Admin\EmailTemplateController@emailTemplate')->name('admin/email-template');
    Route::get('admin/email-template/{id}','Admin\EmailTemplateController@emailTemplateEdit');
    Route::post('admin/email-template/update','Admin\EmailTemplateController@emailTemplateUpdate');
    
    //product management route
    Route::any('admin/products', 'Admin\OrderController@products');
    Route::post('admin/product/save', 'Admin\OrderController@productSave');
    Route::get('admin/product/edit', 'Admin\OrderController@editProduct');
    Route::get('admin/product/delete/{id}', 'Admin\OrderController@deleteProduct');
    //sercvice management route
    Route::any('admin/services', 'Admin\OrderController@services');
    Route::post('admin/service/save', 'Admin\OrderController@serviceSave');
    Route::get('admin/service/edit', 'Admin\OrderController@editService');
    Route::get('admin/service/delete/{id}', 'Admin\OrderController@deleteService');
    //levels management route
    Route::any('admin/levels', 'Admin\OrderController@levels');
    Route::post('admin/level/save', 'Admin\OrderController@levelSave');
    Route::get('admin/level/edit', 'Admin\OrderController@editLevel');
    Route::get('admin/level/delete/{id}', 'Admin\OrderController@deleteLevel');

    //deadlines management route
    Route::any('admin/deadlines', 'Admin\OrderController@deadlines');
    Route::post('admin/deadline/save', 'Admin\OrderController@deadlineSave');
    Route::get('admin/deadline/edit', 'Admin\OrderController@editDeadline');
    Route::get('admin/deadline/delete/{id}', 'Admin\OrderController@deleteDeadline');

    //languages management route
    Route::any('admin/languages', 'Admin\OrderController@languages');
    Route::post('admin/language/save', 'Admin\OrderController@languageSave');
    Route::get('admin/language/edit', 'Admin\OrderController@editLanguage');
    Route::get('admin/language/delete/{id}', 'Admin\OrderController@deleteLanguage');

    // frontend template
    Route::get('admin/template-translation', 'Admin\TemplateController@TemplateTranslations');
    Route::any('admin/add-template-translation', 'Admin\TemplateController@AddTemplateTranslation');
    Route::any('admin/edit-template-translation/{id}', 'Admin\TemplateController@EditTemplateTranslation');
    Route::post('admin/delete-translation', 'Admin\TemplateController@DeleteTemplateTranslation');
    Route::get('admin/get-template-page-slug', 'Admin\TemplateController@GetTemplatePageSlug');
    Route::get('admin/template-slug', 'Admin\TemplateController@TemplateSlugs');
    Route::any('admin/add-template-slug', 'Admin\TemplateController@AddTemplateSlugs');
    Route::any('admin/edit-template-slug/{id}', 'Admin\TemplateController@EditTemplateSlugs');
    Route::post('admin/delete-template-slug', 'Admin\TemplateController@DeleteTemplateSlug');
    //pages
    Route::get('admin/pages', 'Admin\PagesController@Index');
    Route::any('admin/pages/add-page', 'Admin\PagesController@Add');
    Route::any('admin/pages/edit-page/{id}', 'Admin\PagesController@Edit');
    Route::post('admin/pages/delete-page', 'Admin\PagesController@destroy');
    
    //theme home page writing service features
    Route::get('admin/home/writing-service-features', 'Admin\ThemeController@HomeWritingFeatures');
    Route::any('admin/home/add-writing-service-features', 'Admin\ThemeController@AddHomeWritingFeatures');
    Route::any('admin/home/edit-writing-service-features/{id}', 'Admin\ThemeController@EditHomeWritingFeatures');
    Route::post('admin/home/delete-writing-service-features', 'Admin\ThemeController@DeleteHomeWritingFeatures');
    
    //theme home page frequently asked questions
    Route::get('admin/home/frequently-asked-questions', 'Admin\ThemeController@FrequentlyAskedQuestions');
    Route::any('admin/home/add-frequently-asked-question', 'Admin\ThemeController@AddFrequentlyAskedQuestion');
    Route::any('admin/home/edit-frequently-asked-question/{id}', 'Admin\ThemeController@EditFrequentlyAskedQuestion');
    Route::post('admin/home/delete-frequently-asked-question', 'Admin\ThemeController@DeleteFrequentlyAskedQuestion');
    
    //theme management Order Page
    Route::get('admin/theme/order-page-contents', 'Admin\ThemeController@OrderPages');
    Route::any('admin/theme/add-order-page-content', 'Admin\ThemeController@AddOrderPageContent');
    Route::any('admin/theme/edit-order-page-content/{id}', 'Admin\ThemeController@EditOrderPageContent');
    Route::post('admin/theme/delete-order-page-content', 'Admin\ThemeController@DeleteOrderPageContent');

    //theme management Honor Code
    Route::get('admin/theme/honor-codes', 'Admin\ThemeController@HonorCodes');
    Route::any('admin/theme/add-honor-code', 'Admin\ThemeController@AddHonorCode');
    Route::any('admin/theme/edit-honor-code/{id}', 'Admin\ThemeController@EditHonorCode');
    Route::post('admin/theme/delete-honor-code', 'Admin\ThemeController@DeleteHonorCode');
    
    // orders
    Route::get('admin/orders/orders', 'Admin\OrderController@Orders');
    Route::any('admin/orders/order/edit', 'Admin\OrderController@EditOrder');
    Route::get('admin/orders/order/delete/{id}', 'Admin\OrderController@DeleteOrder');
    
    // bids
    Route::get('admin/orders/bids', 'Admin\OrderController@Bids');
    Route::any('admin/orders/bids/edit', 'Admin\OrderController@EditBid');
    Route::get('admin/orders/bids/delete/{id}', 'Admin\OrderController@DeleteBid');
    
    //invitation
    Route::get('admin/orders/invitations', 'Admin\OrderController@Invitations');
    Route::any('admin/orders/invitations/edit', 'Admin\OrderController@EditInvitation');
    Route::get('admin/orders/invitations/delete/{id}', 'Admin\OrderController@DeleteInvitation');
    
    //coupons
    Route::get('admin/coupons', 'Admin\CouponController@Index');
    Route::any('admin/coupon/add', 'Admin\CouponController@Add');
    Route::any('admin/coupon/edit/{id}', 'Admin\CouponController@Edit');
    Route::any('admin/coupon/delete/{id}', 'Admin\CouponController@Destroy');
    
    //reviews
    Route::get('admin/reviews', 'Admin\ReviewController@Index');
    Route::get('admin/review/delete/{id}', 'Admin\ReviewController@Destroy');
    
    //payment getway
    Route::get('admin/payment-getways', 'Admin\ReviewController@PaymentGetWay');

    	//Withdrawals
 	Route::get('admin/withdrawals','Admin\WithdrawlController@withdrawals');
 	Route::get('admin/withdrawal/{id}','Admin\WithdrawlController@withdrawalsView');
 	Route::get('admin/withdrawals/paid/{id}','Admin\WithdrawlController@withdrawalsPaid');
});
