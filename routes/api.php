<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FinancialController;
use App\Http\Controllers\Api\TextController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SuggestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Financial routes
    Route::apiResource('finance', FinancialController::class);
    
    // Text routes
    Route::apiResource('texts', TextController::class);
    
    // Reports routes
    Route::get('/reports/financial', [ReportController::class, 'financial']);
    
    // Suggestion routes
    Route::get('/suggest/titles', [SuggestionController::class, 'titles']);
});
