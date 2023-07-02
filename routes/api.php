<?php

use App\Http\Controllers\Api\Admin\ProductExportController;
use App\Http\Controllers\Api\Admin\ProductImportController;
use App\Http\Controllers\Api\Admin\ProductUploadImageController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {
    Route::post('login', LoginController::class)->name('api.login');
    Route::post('register', RegisterController::class)->name('api.register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/product/export', ProductExportController::class)->name('api.product.export');
        Route::post('/product/import', ProductImportController::class)->name('api.product.import.store');
        Route::post('/product/image', ProductUploadImageController::class)->name('product.image.store');
    });

});
