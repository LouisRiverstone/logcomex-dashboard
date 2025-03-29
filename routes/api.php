<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/sales', [DashboardController::class, 'getSales']);
        Route::get('/sales-dashboard', [DashboardController::class, 'getSalesDashboard']);
        Route::get('/statistics', [DashboardController::class, 'getStatistics']);
        Route::get('/visitors', [DashboardController::class, 'getVisitorsData']);
        Route::get('/revenue', [DashboardController::class, 'getRevenueData']);
        Route::get('/sales-by-category', [DashboardController::class, 'getSalesByCategoryData']);
        Route::get('/user-distribution', [DashboardController::class, 'getUserDistributionData']);
        Route::get('/traffic-sources', [DashboardController::class, 'getTrafficSourcesData']);
        Route::get('/product-rating', [DashboardController::class, 'getProductRatingData']);
        Route::get('/tasks', [DashboardController::class, 'getTasks']);
        Route::get('/transactions', [DashboardController::class, 'getTransactions']);
        Route::get('/sale-details/{id}', [DashboardController::class, 'getSaleDetails']);
    });
});
