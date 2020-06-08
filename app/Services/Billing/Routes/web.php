<?php

use App\Services\Billing\Controllers\BillingController;
use App\Services\Billing\Middleware\BillingAccessMiddleware;

Route::group(['prefix' => 'billing', 'as' => 'billing.', 'middleware' => 'auth'], function() {
    Route::get('get-form', [BillingController::class, 'getForm'])->name('get_form');
    Route::get('get-link', [BillingController::class, 'getLink'])->name('get_link');
});
