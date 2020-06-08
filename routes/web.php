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

Route::get('t', function() {
    return view('front.layouts.master');
});

Route::get('/login-as/{user}', function(\Modules\Users\Entities\User $user) {
    (new \Modules\Users\Repositories\UserRepository())->loginAs($user);

    return redirect()->route('index', compact('user'));
});

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerController@index');