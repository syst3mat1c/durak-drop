<?php

use Modules\Admin\Http\Controllers\{
    PagesController, QuestionController, ChanceController, UserController, PromocodeController, BoxController,
    CategoryController, BoxItemController, WithdrawController
};

Route::get('', [PagesController::class, 'index'])->name('index');

/** Gambling start */
Route::resource('boxes', BoxController::class)->only(['index', 'create', 'store', 'edit', 'update']);

Route::group(['prefix' => 'box-items', 'as' => 'box_items.'], function() {
    Route::get('create', [BoxItemController::class, 'create'])->name('create');
    Route::post('create', [BoxItemController::class, 'store'])->name('store');

    Route::group(['prefix' => '{boxItem}'], function() {
        Route::get('', [BoxItemController::class, 'edit'])->name('edit');
        Route::put('', [BoxItemController::class, 'update'])->name('update');
        Route::delete('', [BoxItemController::class, 'destroy'])->name('destroy');
    });
});

Route::resource('withdraws', WithdrawController::class)->except(['show', 'create', 'store']);

Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
/** Gambling end */

//Route::get('profit', [PagesController::class, 'profit'])->name('profit');
Route::get('referrals', [PagesController::class, 'referrals'])->name('referrals');

Route::resource('chances', ChanceController::class)->only(['index', 'store', 'destroy']);
Route::group(['prefix' => 'chances/{chance}', 'as' => 'chances.'], function() {
    Route::post('set-status', [ChanceController::class, 'setStatus'])->name('status.set');
});

Route::resource('promocodes', PromocodeController::class)->only(['index', 'store', 'destroy']);

Route::resource('users', UserController::class)->only(['show']);
Route::group(['prefix' => 'users', 'as' => 'users.'], function() {
    Route::get('provider-id/{user}', [UserController::class, 'searchByProviderId'])
        ->name('provider.search')->where('user', '[0-9]+');

    Route::group(['prefix' => '{user}'], function() {
        Route::post('add-money', [UserController::class, 'addMoney'])->name('money.add');
    });
});


Route::resource('questions', QuestionController::class)->except(['show']);
