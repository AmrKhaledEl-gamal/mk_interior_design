<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProjectAnalyticsController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['api.secret', 'api.locale'])->group(function () {
    Route::apiResource('projects', ProjectController::class)
        ->only(['index', 'show'])
        ->parameters([
            'projects' => 'project:slug',
        ]);
    Route::apiResource('users', UserController::class)
        ->only(['index', 'show'])
        ->parameters([
            'users' => 'user:slug',
        ]);

    Route::prefix('projects/{slug}')->group(function () {
        // Route::post('views/increment', [ProjectAnalyticsController::class, 'increaseViews']);
        Route::post('like', [ProjectAnalyticsController::class, 'like']);
        Route::post('unlike', [ProjectAnalyticsController::class, 'unlike']);
    });
});
