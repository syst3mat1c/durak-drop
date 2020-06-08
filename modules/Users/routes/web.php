<?php

use Modules\Users\Http\Controllers\{
    ProfileController, ItemController
};

Route::group(['middleware' => 'web'], function() {
    Route::group(['prefix' => 'profile/{user}', 'as' => 'profiles.'], function() {
        Route::get('', [ProfileController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'profile-actions', 'as' => 'profile_actions.', 'middleware' => 'auth'], function() {
        Route::post('withdraw-coins', [ProfileController::class, 'withdrawCoins'])->name('withdraw_coins');
        Route::post('withdraw-credits', [ProfileController::class, 'withdrawCredits'])->name('withdraw_credits');
    });

    Route::group(['prefix' => 'items/{item}', 'as' => 'items.'], function() {
        Route::post('sell', [ItemController::class, 'sell'])->name('sell');
    });
});
