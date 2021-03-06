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
    if (Auth::check()) {
        if (Auth::user()->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } else if (Auth::user()->hasRole('client')) {
            return redirect('/overview');
        } else {
            return redirect('/admin/overview');
        }
    } else {
        return redirect('/login');
    }
});
Route::get('/admin/', function() {
    if (Auth::check()) {
        if (Auth::user()->hasRole('admin')) {
            return redirect('/dashboard');
        } else {
            return redirect('/overview');
        }
    } else {
        return redirect('/login');
    }
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
    Route::get('payment/{id}/invoice/download', 'TemplateController@downloadInvoice');

    /* Support */
    Route::get('support', 'SupportController@index');
    Route::post('support/tickets/create', 'SupportController@createTicket');
    Route::get('support/tickets/{id}', 'SupportController@getTicket');
    Route::post('support/tickets/{id}/messages', 'SupportController@postMessage');
    Route::get('support/download', 'SupportController@download');

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
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'role:admin|lawyer|staff|billing|logistic']], function() {

    /* Dashboard */
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('dashboard', 'DashboardController@index');
        Route::get('dashboard/dispatches', 'DashboardController@getDispatches');
        Route::get('dashboard/payments', 'DashboardController@getPayments');
        Route::get('dashboard/users', 'DashboardController@getUsers');
        Route::get('dashboard/tickets', 'DashboardController@getTickets');
    });

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
    Route::get('files', ['middleware' => ['role:admin'], 'uses' => 'FileController@index']);
    Route::get('files/create', ['middleware' => ['role:admin'], 'uses' => 'FileController@getFile']);
    Route::post('files/create', ['middleware' => ['role:admin'], 'uses' => 'FileController@postFile']);
    Route::get('files/search', 'FileController@searchFileAjax');
    Route::get('files/seek', 'FileController@seekFileAjax');
    Route::get('files/clients', 'FileController@getFileClientsAjax');
    Route::get('files/conflict', 'FileController@checkConflict');
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
    Route::get('payments/{id}/invoice/download', 'PaymentController@downloadInvoice');
    Route::get('payments/{id}/resend', 'PaymentController@resendRequest');
    Route::post('payments/{id}/upload', 'PaymentController@uploadReceipt');

    /* Tickets */
    Route::get('tickets', 'TicketController@index');
    Route::get('tickets/get', ['middleware' => ['role:admin'], 'uses' => 'TicketController@getActiveTicketsAjax']);
    Route::get('tickets/complete', ['middleware' => ['role:admin'], 'uses' => 'TicketController@getCompletedTickets']);
    Route::get('tickets/complete/get', ['middleware' => ['role:admin'], 'uses' => 'TicketController@getCompletedTicketsAjax']);
    Route::get('tickets/pending', ['middleware' => ['role:admin'], 'uses' => 'TicketController@getPendingTickets']);
    Route::get('tickets/pending/get', ['middleware' => ['role:admin'], 'uses' => 'TicketController@getPendingTicketsAjax']);
    Route::get('tickets/create', ['middleware' => ['role:admin'], 'uses' => 'TicketController@getCreateTicket']);
    Route::post('tickets/create', ['middleware' => ['role:admin'], 'uses' => 'TicketController@postCreateTicket']);
    Route::get('tickets/download', 'TicketController@download');
    Route::get('tickets/{id}', 'TicketController@getTicket');
    Route::post('tickets/{id}', 'TicketController@postTicket');
    Route::post('tickets/{id}/messages', 'TicketController@sendMessage');
    Route::get('tickets/{id}/delete', ['middleware' => ['role:admin'], 'uses' => 'TicketController@deleteTicket']);
    Route::get('tickets/{id}/complete', ['middleware' => ['role:admin'], 'uses' => 'TicketController@completeTicket']);
    Route::get('tickets/{id}/open', ['middleware' => ['role:admin'], 'uses' => 'TicketController@reopenTicket']);

    /* Announcements */
    Route::group(['middleware' => ['role:admin']], function() {
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
    });

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
        Route::post('options/offices', 'OptionController@postOffice');
        Route::post('options/offices/{id}', 'OptionController@postOffice');
        Route::post('options/offices/{id}/delete', 'OptionController@deleteOffice');
        Route::post('options/file_types', 'OptionController@postFileType');
        Route::post('options/file_types/{id}', 'OptionController@postFileType');
        Route::post('options/file_types/{id}/delete', 'OptionController@deleteFileType');
        Route::post('options/categories', 'OptionController@postCategory');
        Route::post('options/categories/{id}', 'OptionController@postCategory');
        Route::post('options/categories/{id}/delete', 'OptionController@deleteCategory');
        Route::post('options/subcategories', 'OptionController@postSubCategory');
        Route::post('options/subcategories/{id}', 'OptionController@postSubCategory');
        Route::post('options/subcategories/{id}/delete', 'OptionController@deleteSubCategory');
        Route::post('options/couriers', 'OptionController@postCourier');
        Route::post('options/couriers/{id}', 'OptionController@postCourier');
        Route::post('options/couriers/{id}/delete', 'OptionController@deleteCourier');
        Route::post('options/ticket_categories', 'OptionController@postTicketCategory');
        Route::post('options/ticket_categories/{id}', 'OptionController@postTicketCategory');
        Route::post('options/ticket_categories/{id}/delete', 'OptionController@deleteTicketCategory');
        Route::post('options/template_categories', 'OptionController@postTemplateCategory');
        Route::post('options/template_categories/{id}', 'OptionController@postTemplateCategory');
        Route::post('options/template_categories/{id}/delete', 'OptionController@deleteTemplateCategory');
    });

    /* milestones */
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('milestones', 'MilestoneTemplate@index');
        Route::post('milestones', 'MilestoneTemplate@postMilestone');
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