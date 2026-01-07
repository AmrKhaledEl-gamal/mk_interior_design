<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;



use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RedirectIfAdminAuthenticated;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => RedirectIfAdminAuthenticated::class], function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });
});

Route::group(['prefix' => 'admin',  'as' => 'admin.', 'middleware' => ['auth:admin', AdminMiddleware::class]], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get('calendar', 'calendar')->name('calendar');
        Route::get('chatmessage', 'chatMessage')->name('chatMessage');
        Route::get('chatempty', 'chatempty')->name('chatempty');
        Route::get('email', 'email')->name('email');
        Route::get('error', 'error1')->name('error');
        Route::get('faq', 'faq')->name('faq');
        Route::get('gallery', 'gallery')->name('gallery');
        Route::get('kanban', 'kanban')->name('kanban');
        Route::get('pricing', 'pricing')->name('pricing');
        Route::get('termscondition', 'termsCondition')->name('termsCondition');
        Route::get('widgets', 'widgets')->name('widgets');
        Route::get('chatprofile', 'chatProfile')->name('chatProfile');
        Route::get('veiwdetails', 'veiwDetails')->name('veiwDetails');
        Route::get('blankPage', 'blankPage')->name('blankPage');
        Route::get('comingSoon', 'comingSoon')->name('comingSoon');
        Route::get('maintenance', 'maintenance')->name('maintenance');
        Route::get('starred', 'starred')->name('starred');
        Route::get('testimonials', 'testimonials')->name('testimonials');
    });

    // Content Management Routes

    // Authentication
    Route::prefix('authentication')->group(function () {
        Route::controller(AuthenticationController::class)->group(function () {
            Route::get('/forgotpassword', 'forgotPassword')->name('forgotPassword');
            Route::get('/signin', 'signin')->name('signin');
            Route::get('/signup', 'signup')->name('signup');
        });
    });


    // Componentpage


    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/index2', 'index2')->name('index2');
            Route::get('/index3', 'index3')->name('index3');
            Route::get('/index4', 'index4')->name('index4');
            Route::get('/index5', 'index5')->name('index5');
            Route::get('/index6', 'index6')->name('index6');
            Route::get('/index7', 'index7')->name('index7');
            Route::get('/index8', 'index8')->name('index8');
            Route::get('/index9', 'index9')->name('index9');
            Route::get('/index10', 'index10')->name('index10');
            Route::get('/wallet', 'wallet')->name('wallet');
        });
    });


    // Settings
    Route::prefix('settings')->group(function () {
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/company', 'company')->name('company');
            Route::get('/currencies', 'currencies')->name('currencies');
            Route::get('/language', 'language')->name('language');
            Route::get('/notification', 'notification')->name('notification');
            Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
            Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
            Route::get('/theme', 'theme')->name('theme');
        });
    });

    // Users

});
