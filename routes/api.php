<?php

use App\Http\Controllers\Api\Admin\CityController;
use App\Http\Controllers\Api\Admin\ProductCategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\ProductExportController;
use App\Http\Controllers\Api\Admin\ProductImportController;
use App\Http\Controllers\Api\Admin\ProductUploadImageController;
use App\Http\Controllers\Api\Admin\StateController;
use App\Http\Controllers\Api\Admin\TypeDocumentController;
use App\Http\Controllers\Api\Admin\UnitController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Client\ShowcaseController;
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
    Route::post('login', LoginController::class)->name('login');
    Route::post('logout', LogoutController::class)->name('logout');
    Route::post('register/{role:client}', RegisterController::class)->name('register.client');

    Route::prefix('v1')->group(function () {

        Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
        Route::get('/showcase/{slug}', [ShowcaseController::class, 'show'])->name('showcase.show');

        Route::get('/type_documents', [TypeDocumentController::class, 'index'])->name('type_document.index');
        Route::get('/states', [StateController::class, 'index'])->name('state.index');
        Route::get('/cities', [CityController::class, 'index'])->name('city.index');

        Route::middleware('auth:sanctum')->group(function () {

            Route::middleware(['ability:admin'])->group(function () {
                Route::post('register/{role:admin}', RegisterController::class)->name('register.admin');

                Route::get('/users', [UserController::class, 'index'])->name('user.index');

                Route::get('/product/export', ProductExportController::class)->name('product.export');
                Route::post('/product/import', ProductImportController::class)->name('product.import');
                Route::post('/product/image', ProductUploadImageController::class)->name('product.image');
                Route::get('/products', [ProductController::class, 'index'])->name('product.index');
                Route::post('/product', [ProductController::class, 'store'])->name('product.store');
                Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
                Route::patch('/product/edit/{id}', [ProductController::class, 'update'])->name('product.update');
                Route::delete('/product/{slug}', [ProductController::class, 'destroy'])->name('product.destroy');

                Route::get('/units', [UnitController::class, 'index'])->name('product.unit.index');
                Route::post('/unit', [UnitController::class, 'store'])->name('product.unit.store');
                Route::get('/unit/{code}', [UnitController::class, 'show'])->name('product.unit.show');
                Route::patch('/unit/edit/{id}', [UnitController::class, 'update'])->name('product.unit.update');

                Route::get('/product_categories', [ProductCategoryController::class, 'index'])->name('product.category.index');
                Route::post('/product_category', [ProductCategoryController::class, 'store'])->name('product.category.store');
                Route::get('/product_category/{id}', [ProductCategoryController::class, 'show'])->name('product.category.show');
                Route::patch('/product_category/edit/{id}', [ProductCategoryController::class, 'update'])->name('product.category.update');
            });

        });

    });

});
