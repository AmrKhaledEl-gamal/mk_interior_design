<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\ProjectController;
use App\Http\Controllers\Front\ApprovalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;


Route::group(['as' => 'front.', 'middleware' => ['auth']], function () {
    Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.notice');
    Route::post('/approval', [ApprovalController::class, 'store'])->name('approval.store');

    Route::group(['middleware' => ['is_approved']], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::resource('projects', ProjectController::class);
        Route::delete('projects/media/{id}', [ProjectController::class, 'deleteMedia'])->name('projects.media.destroy');
    });
});


Auth::routes();
