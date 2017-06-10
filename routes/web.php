<?php

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
use App\Role;

Route::get('/', function() {
    return redirect('/login');
});
Route::get('/admin/', function() {
    return redirect('login');
});
Route::get('/hhq/', function() {
    return redirect('login');
});

/* captcha validation url */
//Route::get('captcha-form-validation',array('as'=>'google.get-recaptcha-validation-form','uses'=>'FileController@getCaptchaForm')) ;
//Route::post('captcha-form-validation',array('as'=>'google.post-recaptcha-validation','uses'=>'FileController@postCaptchaForm')) ;


/**
 * Authentication URIs
 */
Auth::routes();
Route::group(['namespace' => 'Auth'], function () {
    Route::post('/login', 'LoginController@postLogin');
    Route::get('/register', 'RegisterController@getRegister');
    Route::post('/register', 'RegisterController@postRegister');
    Route::get('/verification/{token}', 'VerificationController@getVerification');
    Route::post('/verification/{token}', 'VerificationController@postVerification');
    Route::get('/logout', 'LoginController@logout');
    // Route::post('/password/forgot', 'ForgotPasswordController@postForgot');
    // Route::get('/password/forgot', 'ForgotPasswordController@getForgot');
    // Route::post('/password/reset', 'ResetPasswordController@postReset');
    // Route::get('/password/reset', 'ResetPasswordController@getReset');
//    Route::get('/register/verify/{confirmation_code}', 'RegisterController@confirm');
    Route::get('/test', function(){
        Auth::user()->attachRole(Role::find(1));
    });
});


/**
 * Non-HHQ URIs
 */
Route::group(['middleware' => ['auth', 'role:client']], function () {
//Route::group(['middleware' => ['auth']], function () {

    /* Main */
    Route::get('/overview', 'OverviewController@index');
    Route::get('/overview/index', 'OverviewController@index');

    /* Logistics */
    Route::get('/logistics', 'LogisticsController@index');
    Route::get('/logistics/index', 'LogisticsController@index');
    Route::get('/logistics/get', 'LogisticsController@getDispatches');

    /* Payment */
    Route::get('payment', 'PaymentController@index');
    Route::get('payment/index', 'PaymentController@index');
    Route::post('payment/pay', 'PaymentController@proceedPay');

    /* Support */
    Route::get('support', 'SupportController@index');
    Route::get('support/index', 'SupportController@index');
    Route::post('support/tickets/create', 'SupportController@createTicket');

    /* Templates */
    Route::get('templates', 'TemplateController@index');
    Route::get('templates/index', 'TemplateController@index');
    Route::get('templates/get', 'TemplateController@getTemplatesAjax');
    Route::get('templates/{id}/download', 'TemplateController@download');

    /* Setting */
    Route::get('/setting', 'SettingController@index');
    Route::get('/setting/index', 'SettingController@index');
    Route::post('/setting', 'SettingController@postProfile');
    Route::post('setting/notification', 'SettingController@postNotificationSetting');
});


/**
 * HHQ URIs
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'hhq', 'middleware' => ['auth', 'role:lawyer|role:staff']], function() {

    /* Dashboard */
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/dashboard/index', 'DashboardController@index');

});


/**
 * SuperAdmin URIs
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function() {

    /* Dashboard */
    Route::get('dashboard', 'DashboardController@index');
    Route::get('dashboard/index', 'DashboardController@index');

    /* Files */
    Route::get('files', 'FileController@index');
    Route::get('files/index', 'FileController@index');
    Route::get('files/create', 'FileController@getFile');
    Route::post('files/create', 'FileController@postFile');
    Route::get('files/search', 'FileController@searchFileAjax');
    Route::get('files/{id}', 'FileController@getFile');
    Route::post('files/{id}', 'FileController@postFile');
    Route::get('files/{id}/detail', 'FileController@getFileDetail');
    Route::post('files/{id}/documents', 'FileController@postDocument');
    Route::get('files/documents/{id}/download', 'FileController@download');
    Route::post('files/{id}/payments', 'FileController@createPayment');

    /* Logistics */
    Route::get('logistics', 'LogisticsController@index');
    Route::get('logistics/get', 'LogisticsController@getDispatches');
    Route::get('logistics/create', 'LogisticsController@getCreate');
    Route::post('logistics/create', 'LogisticsController@postCreate');
    Route::get('logistics/{id}', 'LogisticsController@getDispatch');
    Route::post('logistics/{id}', 'LogisticsController@postDispatch');
    Route::post('logistics/{id}/delete', 'LogisticsController@deleteDispatch');

    /* Payment */
    Route::get('payment', 'PaymentController@index');

    /* Users */
    Route::get('users', 'UserController@index');
    Route::get('users/index', 'UserController@index');
    Route::get('users/clients/search', 'UserController@findUserAjax');
    Route::get('users/hhq/search', 'UserController@findHHQStaffsAjax');

    Route::get('users/admin', 'UserController@getAdmin');
    Route::get('users/lawyer', 'UserController@getLawyer');
    Route::get('users/staff', 'UserController@getStaff');
    Route::get('users/client', 'UserController@getClientAjax');
    Route::get('users/get', 'UserController@getUser');
    Route::post('users', 'UserController@postUser');

    /* Tickets */
    Route::get('tickets', 'TicketController@index');
    Route::get('tickets/get', 'TicketController@getActiveTicketsAjax');
    Route::get('tickets/complete', 'TicketController@getCompletedTickets');
    Route::get('tickets/complete/get', 'TicketController@getCompletedTicketsAjax');
    Route::get('tickets/pending', 'TicketController@getPendingTickets');
    Route::get('tickets/pending/get', 'TicketController@getPendingTicketsAjax');
    Route::get('tickets/create', 'TicketController@getCreateTicket');
    Route::post('tickets/create', 'TicketController@postCreateTicket');
    Route::get('tickets/{id}', 'TicketController@getTicket');
    Route::post('tickets/{id}/messages', 'TicketController@sendMessage');
    Route::get('tickets/{id}/delete', 'TicketController@deleteTicket');
    Route::get('tickets/{id}/complete', 'TicketController@completeTicket');
    Route::get('tickets/{id}/open', 'TicketController@reopenTicket');
    Route::post('tickets/{id}', 'TicketController@postTicket');

    /* Announcements */
    Route::get('announcements', 'AnnouncementController@index');
    Route::get('announcements/get', 'AnnouncementController@getAnnouncementAjax');
    Route::get('announcements/close', 'AnnouncementController@getClosedAnnouncement');
    Route::get('announcements/close/get', 'AnnouncementController@getClosedAnnouncementAjax');
    Route::get('announcements/create', 'AnnouncementController@getCreateAnnouncement');
    Route::post('announcements/create', 'AnnouncementController@createAnnouncement');
    Route::get('announcements/{id}', 'AnnouncementController@getAnnouncement');
    Route::post('announcements/{id}', 'AnnouncementController@postAnnouncement');
    Route::get('announcements/{id}/close', 'AnnouncementController@closeAnnouncement');
    Route::get('announcements/{id}/delete', 'AnnouncementController@deleteAnnouncement');

    /* Legal Templates */
    Route::get('templates', 'TemplateController@index');
    Route::get('templates/get', 'TemplateController@getTemplatesAjax');
    Route::get('templates/create', 'TemplateController@getCreateTemplate');
    Route::post('templates/create', 'TemplateController@createTemplate');
    Route::get('templates/{id}', 'TemplateController@getTemplate');
    Route::post('templates/{id}', 'TemplateController@postTemplate');
    Route::get('templates/{id}/delete', 'TemplateController@deleteTemplate');
    Route::get('templates/{id}/download', 'TemplateController@download');

    /* Settings */
    Route::get('setting', 'SettingController@index');
    Route::post('setting', 'SettingController@postProfile');
    Route::post('setting/notification', 'SettingController@postNotificationSetting');

});

