<?php

use App\Http\Controllers\AdCampaignController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ad-campaigns', [AdCampaignController::class, 'index']);
    Route::post('/ad-campaigns', [AdCampaignController::class, 'store']);
    Route::get('/ad-campaigns/{adCampaign}', [AdCampaignController::class, 'show']);
    Route::put('/ad-campaigns/{adCampaign}', [AdCampaignController::class, 'update']);
    Route::delete('/ad-campaigns/{adCampaign}', [AdCampaignController::class, 'destroy']);
    Route::post('/ad-campaigns/{adCampaign}/restore', [AdCampaignController::class, 'restore'])->withTrashed();

    Route::delete('/ad-campaigns/{adCampaign}/media/{media}', [MediaController::class, 'destroy']);
});
