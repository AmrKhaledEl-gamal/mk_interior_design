<?php

use App\Http\Controllers\Admin\AboutController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\RedirectIfAdminAuthenticated;



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => RedirectIfAdminAuthenticated::class], function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });
});

Route::group(['prefix' => 'admin',  'as' => 'admin.', 'middleware' => ['auth:admin', AdminMiddleware::class]], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('users/pending', [UserController::class, 'pending'])->name('users.pending');
    Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::resource('users', UserController::class);

    // Projects
    Route::controller(\App\Http\Controllers\Admin\ProjectController::class)->prefix('projects')->as('projects.')->group(function () {
        Route::get('inactive', 'inactive')->name('inactive');
        Route::get('{project}', 'show')->name('show');
        Route::post('{project}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    // Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });




    // Content Management Routes



    // Settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        // General Settings
        Route::controller(GeneralSettingsController::class)->group(function () {
            Route::get('general', 'index')->name('general.index');
            Route::put('general', 'update')->name('general.update');
            Route::delete('general/remove-logo', 'removeLogo')->name('general.remove-logo');
            Route::delete('general/remove-favicon', 'removeFavicon')->name('general.remove-favicon');
        });
    });
});
