<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\TrackEventController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ar');

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::post('/track', TrackEventController::class)
    ->middleware('throttle:60,1')
    ->name('track');

Route::get('/locale/{locale}', [HomeController::class, 'switchLocale'])
    ->whereIn('locale', ['ar', 'en'])
    ->name('locale.switch');

Route::prefix('{locale}')
    ->whereIn('locale', ['ar', 'en'])
    ->group(function () {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
        Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
    });
