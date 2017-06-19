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
use App\Permission;


Route::get('/', function() {
    return redirect('/login');
});
Route::get('/admin/', function() {
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
    Route::get('/verification/{token}/resend', 'VerificationController@resendSMS');
    Route::get('/verification/{token}', 'VerificationController@getVerification');
    Route::post('/verification/{token}', 'VerificationController@postVerification');
    Route::get('/logout', 'LoginController@logout');
});


/**
 * Non-HHQ URIs
 */
Route::group(['middleware' => ['auth', 'role:client']], function () {

    /* Main */
    Route::get('overview', 'OverviewController@index');
    Route::get('overview/detail', 'OverviewController@viewFileDetail');
    Route::get('overview/documents/{id}/download', 'OverviewController@download');

    /* Logistics */
    Route::get('/logistics', 'LogisticsController@index');
    Route::get('/logistics/get', 'LogisticsController@getDispatches');

    /* Payment */
    Route::get('payment', 'PaymentController@index');
    Route::post('payment/pay', 'PaymentController@proceedPay');
    Route::get('payment/{id}/download', 'PaymentController@downloadReceipt');

    /* Support */
    Route::get('support', 'SupportController@index');
    Route::post('support/tickets/create', 'SupportController@createTicket');
    Route::get('support/tickets/{id}', 'SupportController@getTicket');
    Route::post('support/tickets/{id}/messages', 'SupportController@postMessage');

    /* Templates */
    Route::get('templates', 'TemplateController@index');
    Route::get('templates/get', 'TemplateController@getTemplatesAjax');
    Route::get('templates/{id}/download', 'TemplateController@download');

    /* Setting */
    Route::get('/setting', 'SettingController@index');
    Route::post('/setting', 'SettingController@postProfile');
    Route::post('setting/notification', 'SettingController@postNotificationSetting');
});


/**
 * SuperAdmin, Lawyer, Legal Staff URIs
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'role:admin|lawyer|staff']], function() {

    /* Dashboard */
    Route::get('dashboard', 'DashboardController@index');

    /* Main */
    Route::get('/overview', 'OverviewController@index');
    Route::get('overview/detail', 'OverviewController@viewFileDetail');
    Route::get('overview/documents/{id}/download', 'OverviewController@download');

    /* Users */
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('users', 'UserController@index');
        Route::post('users', 'UserController@postUser');
        Route::get('users/{id}', 'UserController@getUser');
        Route::get('users/{id}/allow', 'UserController@allowUser');
        // Create New
        Route::get('users/clients/create', 'UserController@getCreateClient');
        Route::get('users/admins/create', 'UserController@getCreateAdmin');
        Route::get('users/lawyers/create', 'UserController@getCreateLawyer');
        Route::get('users/logistic/create', 'UserController@getCreateLogistic');
        Route::get('users/billing/create', 'UserController@getCreateBilling');
        Route::get('users/staffs/create', 'UserController@getCreateStaff');
    });
    // Search Function
    Route::get('users/clients/search', 'UserController@findUserAjax');
    Route::get('users/hhq/search', 'UserController@findHHQStaffsAjax');

    /* Files */
    Route::get('files', 'FileController@index');
    Route::get('files/create', ['middleware' => ['role:admin|lawyer'], 'uses' => 'FileController@getFile']);
    Route::post('files/create', ['middleware' => ['role:admin|lawyer'], 'uses' => 'FileController@postFile']);
    Route::get('files/search', 'FileController@searchFileAjax');
    Route::get('files/subcategories', 'FileController@getSubCategoriesAjax');
    Route::get('files/{id}', 'FileController@getFile');
    Route::post('files/{id}', 'FileController@postFile');
    Route::get('files/{id}/close', 'FileController@closeFile');
    Route::get('files/{id}/detail', 'FileController@getFileDetail');
    Route::post('files/{id}/documents', 'FileController@postDocument');
    Route::post('files/{id}/cases/documents', 'FileController@postCaseDocument');
    Route::get('files/{id}/milestone', 'FileController@getMilestone');
    Route::post('files/{id}/milestone', 'FileController@createMilestone');
    Route::get('files/documents/{id}/download', 'FileController@download');
    Route::post('files/{id}/payments', 'FileController@createPayment');
    Route::get('files/{id}/payments/{pid}', 'FileController@verifiedPayment');

    /* Logistics */
    Route::get('logistics', 'LogisticsController@index');
    Route::get('logistics/get', 'LogisticsController@getDispatches');
    Route::get('logistics/create', 'LogisticsController@getCreate');
    Route::post('logistics/create', 'LogisticsController@postCreate');
    Route::get('logistics/{id}', 'LogisticsController@getDispatch');
    Route::post('logistics/{id}', 'LogisticsController@postDispatch');
    Route::get('logistics/{id}/delete', 'LogisticsController@deleteDispatch');

    /* Payment */
    Route::get('payments', 'PaymentController@index');
    Route::get('payments/{id}/download', 'PaymentController@downloadReceipt');
    Route::post('payments/{id}/upload', 'PaymentController@uploadReceipt');

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
    Route::get('templates/create', ['middleware' => ['role:admin|lawyer'], 'uses' => 'TemplateController@getCreateTemplate']);
    Route::post('templates/create', ['middleware' => ['role:admin|lawyer'], 'uses' => 'TemplateController@createTemplate']);
    Route::get('templates/{id}', ['middleware' => ['role:admin|lawyer'], 'uses' => 'TemplateController@getTemplate']);
    Route::post('templates/{id}', ['middleware' => ['role:admin|lawyer'], 'uses' => 'TemplateController@postTemplate']);
    Route::get('templates/{id}/delete', ['middleware' => ['role:admin|lawyer'], 'uses' => 'TemplateController@deleteTemplate']);
    Route::get('templates/{id}/download', 'TemplateController@download');

    /* Options */
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('options', 'OptionController@index');
    });

    /* Settings */
    Route::get('setting', 'SettingController@index');
    Route::post('setting', 'SettingController@postProfile');
    Route::post('setting/notification', 'SettingController@postNotificationSetting');

});


/**
 * Role Permission Template
 */
Route::get('test-role', function(){

//    $admin = Role::where('name', 'admin')->first();
//    $lawyer = Role::where('name', 'lawyer')->first();
//
//    $permission = new Permission();
//    $permission->name         = 'create-logistics';
//    $permission->display_name = 'create logistics';
//    $permission->description  = 'create logistics';
//    $permission->save();
//
//    $admin->attachPermission($permission);
//    $lawyer->attachPermission($permission);

});