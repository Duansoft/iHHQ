<?php

use Illuminate\Http\Request;

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


/* Restful API Version 1 */

Route::group(['prefix' => 'v1'], function () {

    Route::post('/users/me', 'RestfulAPIController@login');
    Route::post('/users/me/password', 'RestfulAPIController@forgotPassword');

    /**
     * Routes base on JWT Token
     */
    Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function(){

        /* User Module */
        Route::post('/users/me/setting', 'RestfulAPIController@postUserProfile');

        /* files module */
        Route::get('/files', 'RestfulAPIController@getFiles');
        Route::get('/files/{id}/documents', 'RestfulAPIController@getFileDocuments');
        Route::post('/files/{id}/documents', 'RestfulAPIController@postFileDocuments');
        Route::get('/files/{id}/payments', 'RestfulAPIController@getFilePayments');
        Route::get('/files/{id}/contacts', 'RestfulAPIController@getFileContacts');
        Route::get('/files/{id}/tickets', 'RestfulAPIController@getFileTickets');

        /* Dispatch module */
        Route::get('/dispatches', 'RestfulAPIController@getDispatches');
        Route::post('/dispatches/{id}/scan', 'RestfulAPIController@postQRCode');

        /* Ticket Module */
        Route::get('/tickets', 'RestfulAPIController@getTickets');
        Route::post('/tickets', 'RestfulAPIController@createTicket');
        Route::get('/tickets/categories', 'RestfulAPIController@getTicketCategories');
        Route::get('/tickets/{id}/messages', 'RestfulAPIController@getTicketMessages');
        Route::post('/tickets/{id}/messages', 'RestfulAPIController@postTicketMessages');

        /* Notification module */
        Route::get('/notifications', 'RestfulAPIController@getNotifications');
    });
});


