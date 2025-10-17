<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CodelistController;
use App\Http\Controllers\Api\SalesmanController;
use Illuminate\Http\Response;
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

Route::prefix('v1')->group(function () {
    // Salesmen routes
    Route::post('salesmen', [SalesmanController::class, 'store']);
    Route::get('salesmen', [SalesmanController::class, 'index']);
    Route::get('salesmen/{id}', [SalesmanController::class, 'show']);
    Route::put('salesmen/{id}', [SalesmanController::class, 'update']);
    Route::delete('salesmen/{id}', [SalesmanController::class, 'destroy']);

    // Codelists route
    Route::get('codelists', CodelistController::class);
});

// Health check endpoint
Route::get('health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
});
