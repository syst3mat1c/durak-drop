<?php

use Modules\Admin\Services\Settings\Http\Controllers\SettingsController;

Route::group(['middleware' => ['web', 'auth', \Modules\Admin\Http\Middleware\AdminAccessMiddleware::class],
    'as' => 'admin.', 'prefix' => 'admin'], function() {
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function() {
        Route::get('', [SettingsController::class, 'index'])->name('index');

        Route::group(['prefix' => 'groups', 'as' => 'groups.'], function() {
            Route::get('{group}', [SettingsController::class, 'show'])->name('show');
            Route::post('{group}', [SettingsController::class, 'save'])->name('save');

            Route::delete('{group}/items/{item}', [SettingsController::class, 'reset'])->name('items.destroy');
        });
    });
});
