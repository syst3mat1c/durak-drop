<?php

use App\Services\Billing\Controllers\BillingController;
use App\Services\Billing\Middleware\BillingAccessMiddleware;

Route::group(['prefix' => 'billing', 'as' => 'billing.'], function() {
    Route::post('callback', [BillingController::class, 'callback'])
        ->middleware(BillingAccessMiddleware::class)
        ->name('callback');
});
