<?php

use Illuminate\Http\Request;

###Right Permissions###
Route::get('onboarding-permissions', 'OnBoardingPermissionController@getOnBoardingPermissions')->name('onboarding-permissions');

Route::group(['prefix' => 'v1'], static function(){
    Route::get('test', static function(){
        $token = jwt()->attempt(['email'=>'necuqil5@mailinator.com','password' =>'admin123']);//fromUser(\App\Models\User::getUserById(6));
        dd($token);
    });


    Route::post('signup', 'AuthApiController@register')->name('signup');
    Route::post('login', 'AuthApiController@login')->name('login');


    Route::post('forgot-password', 'AuthApiController@ForgotPassword')->name('ForgotPassword');

    Route::group(['middleware' => 'jwt-auth'], function () {

        Route::get('auth/logout', 'AuthApiController@logout')->name('logout');

        Route::post('code-verify', 'AuthApiController@verifyAccountByEmailCode')->name('verifyAccountByEmailCode');
        Route::post('resend-code', 'AuthApiController@resendEmailCode')->name('resendEmailCode');

        Route::post('change-password','UserController@changePassword')->name('changePassword');

        Route::get('account/me', 'UserController@me')->name('me');


        Route::post('account/update', 'UserController@update')->name('update');

        /*Route::get('getNotifications', 'UserController@getNotifications')->name('getNotifications');
        Route::get('page', 'UserController@getPage')->name('getPage');*/
    });
});

