<?php

use App\Http\Controllers\Api\V1\ContentController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Middleware\EnsureSiteIsActive;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(['auth:sanctum', EnsureSiteIsActive::class, 'throttle:hub-api'])
    ->group(function () {
        Route::get('/content', ContentController::class);
        Route::post('/leads', LeadController::class);
        Route::post('/events', EventController::class);
    });
