<?php

use Modules\Users\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    ForgotPasswordController,
    ResetPasswordController,
    SocialiteController
};

Route::group(['middleware' => ['web']], function() {

    Route::group(['prefix' => 'oauth/{provider}', 'as' => 'oauth.', 'middleware' => 'guest'], function() {
        Route::get('redirect', [SocialiteController::class, 'redirect'])->name('redirect');
        Route::get('callback', [SocialiteController::class, 'callback'])->name('callback');
    });

//    // Authentication Routes...
//    Route::group(['prefix' => 'login'], function() {
//        Route::get('', [LoginController::class, 'showLoginForm'])->name('login');
//        Route::post('', [LoginController::class, 'login']);
//    });
//
    Route::get('login', function() { return redirect()->route('oauth.redirect', ['provider' => 'vkontakte']); })
        ->name('login');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
//
//    // Registration Routes...
//    Route::group(['prefix' => 'register'], function() {
//        Route::get('', [RegisterController::class, 'showRegistrationForm'])->name('register');
//        Route::post('', [RegisterController::class, 'register']);
//    });
//
//    // Password Reset Routes...
//    Route::group(['prefix' => 'password', 'as' => 'password.'], function() {
//        Route::get('reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
//        Route::post('email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('email');
//
//        Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('reset');
//        Route::post('reset', [ResetPasswordController::class, 'reset']);
//    });

});

