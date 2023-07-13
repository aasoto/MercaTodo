<?php

use App\Http\Controllers\Web\Admin\CityController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\ProductCategoryController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\ProductExportController;
use App\Http\Controllers\Web\Admin\ProductImportController;
use App\Http\Controllers\Web\Admin\ProductUploadImageController;
use App\Http\Controllers\Web\Admin\ReportController;
use App\Http\Controllers\Web\Admin\StateController;
use App\Http\Controllers\Web\Admin\TypeDocumentController;
use App\Http\Controllers\Web\Admin\UnitController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Client\OrderController;
use App\Http\Controllers\Web\Client\PaymentController;
use App\Http\Controllers\Web\Client\ShowcaseController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Showcase/Index', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [ShowcaseController::class, 'index'])->name('root');

Route::get('/user_disabled', function () {
    return Inertia::render('Auth/UserDisabled');
})->name('user-disabled');
Route::get('/405_method_not_allowed', function () {
    return Inertia::render('Auth/MethodNotAllowed');
})->name('405');
Route::get('/401_unauthorized', function () {
    return Inertia::render('Auth/Unauthorized');
})->name('401');

Route::get('/start', function () {
    return Inertia::render('Auth/NoRole');
})->name('start')->middleware('start');

Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
Route::get('/showcase/{slug}', [ShowcaseController::class, 'show'])->name('showcase.show');


Route::middleware(['auth', 'verified', 'enabled'])->group(function () {

    /** DASHBOARD */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('role:admin');

    /** PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** USER */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/user/{role}', [UserController::class, 'index'])->name('user.index')->middleware('permission:consult-users');
        Route::get('/user', [UserController::class, 'create'])->name('user.create')->middleware('permission:create-users');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store')->middleware('permission:create-users');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware(['permission:update-users', 'permission:disable-users']);
        Route::patch('/user/edit/{id}', [UserController::class, 'update'])->name('user.update')->middleware(['permission:update-users', 'permission:disable-users']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/type_documents', [TypeDocumentController::class, 'index'])->name('type_document.index')->middleware('permission:consult-users');
        Route::get('/type_document', [TypeDocumentController::class, 'create'])->name('type_document.create')->middleware('permission:create-users');
        Route::post('/type_document/store', [TypeDocumentController::class, 'store'])->name('type_document.store')->middleware('permission:create-users');
        Route::get('/type_document/edit/{id}', [TypeDocumentController::class, 'edit'])->name('type_document.edit')->middleware('permission:update-users');
        Route::patch('/type_document/edit/{id}', [TypeDocumentController::class, 'update'])->name('type_document.update')->middleware('permission:update-users');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/cities', [CityController::class, 'index'])->name('city.index')->middleware('permission:consult-users');
        Route::get('/city', [CityController::class, 'create'])->name('city.create')->middleware('permission:create-users');
        Route::post('/city/store', [CityController::class, 'store'])->name('city.store')->middleware('permission:create-users');
        Route::get('/city/edit/{id}', [CityController::class, 'edit'])->name('city.edit')->middleware('permission:update-users');
        Route::patch('/city/edit/{id}', [CityController::class, 'update'])->name('city.update')->middleware('permission:update-users');

    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/states', [StateController::class, 'index'])->name('state.index')->middleware('permission:consult-users');
        Route::get('/state', [StateController::class, 'create'])->name('state.create')->middleware('permission:create-users');
        Route::post('/state/store', [StateController::class, 'store'])->name('state.store')->middleware('permission:create-users');
        Route::get('/state/edit/{id}', [StateController::class, 'edit'])->name('state.edit')->middleware('permission:update-users');
        Route::patch('/state/edit/{id}', [StateController::class, 'update'])->name('state.update')->middleware('permission:update-users');
    });

    /** PRODUCTS */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:consult-products');
        Route::get('/product', [ProductController::class, 'create'])->name('product.create')->middleware('permission:create-products');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store')->middleware('permission:create-products');
        Route::get('/product/export', ProductExportController::class)->name('product.export')->middleware('permission:export-excel-data');
        Route::get('/product/import', [ProductImportController::class, 'create'])->name('product.import.create')->middleware('permission:import-excel-data');
        Route::post('/product/import', [ProductImportController::class, 'store'])->name('product.import.store')->middleware('permission:import-excel-data');
        Route::get('/product/image', [ProductUploadImageController::class, 'create'])->name('product.image.create')->middleware('permission:import-excel-data');
        Route::post('/product/image', [ProductUploadImageController::class, 'store'])->name('product.image.store')->middleware('permission:import-excel-data');
        Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show')->middleware('permission:show-products');
        Route::get('/product/edit/{slug}', [ProductController::class, 'edit'])->name('product.edit')->middleware(['permission:update-products', 'permission:disable-products']);
        Route::patch('/product/edit/{id}/{files}', [ProductController::class, 'update'])->name('product.update')->middleware(['permission:update-products', 'permission:disable-products']);
        Route::delete('/product/{slug}', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('permission:delete-products');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/product_categories', [ProductCategoryController::class, 'index'])->name('product_category.index')->middleware('permission:consult-products');
        Route::get('/product_category', [ProductCategoryController::class, 'create'])->name('product_category.create')->middleware('permission:create-products');
        Route::post('/product_category/store', [ProductCategoryController::class, 'store'])->name('product_category.store')->middleware('permission:create-products');
        Route::get('/product_category/edit/{id}', [ProductCategoryController::class, 'edit'])->name('product_category.edit')->middleware('permission:update-products');
        Route::patch('/product_category/edit/{id}', [ProductCategoryController::class, 'update'])->name('product_category.update')->middleware('permission:update-products');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/units', [UnitController::class, 'index'])->name('unit.index')->middleware('permission:consult-products');
        Route::get('/unit', [UnitController::class, 'create'])->name('unit.create')->middleware('permission:create-products');
        Route::post('/unit/store', [UnitController::class, 'store'])->name('unit.store')->middleware('permission:create-products');
        Route::get('/unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit')->middleware('permission:update-products');
        Route::patch('/unit/edit/{id}', [UnitController::class, 'update'])->name('unit.update')->middleware('permission:update-products');
    });

    Route::middleware(['role:admin', 'permission:generate-reports'])->group(function () {
        Route::get('/reporting', [ReportController::class, 'index'])->name('report.index');
        Route::get('/user_report', [ReportController::class, 'create_user'])->name('user.report.create');
        Route::post('/user_report_export', [ReportController::class, 'export_user'])->name('user.report.export');
        Route::get('/product_report', [ReportController::class, 'create_product'])->name('product.report.create');
        Route::post('/product_report_export', [ReportController::class, 'export_product'])->name('product.report.export');
        Route::get('/order_report', [ReportController::class, 'create_order'])->name('order.report.create');
        Route::post('/order_report_export', [ReportController::class, 'export_order'])->name('order.report.export');

    });

    /** ORDERS */
    Route::middleware(['role:client'])->group( function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('order.index')->middleware('permission:client-history-orders');
        Route::get('/order', [OrderController::class, 'create'])->name('order.create')->middleware('permission:client-create-order');
        Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show')->middleware('permission:client-detail-order');
        Route::post('/order/store', [OrderController::class, 'store'])->name('order.store')->middleware('permission:client-create-order');
    });

    Route::middleware(['role:client', 'permission:client-payment-process'])->group( function () {
        Route::get('/payment/show/{code}', [PaymentController::class, 'show'])->name('payment.show');
        Route::get('/payment/response/{code}', [PaymentController::class, 'process_response'])->name('payment.response');
        Route::get('/payment/canceled/{code}', [PaymentController::class, 'process_canceled'])->name('payment.canceled');
        Route::get('/payment/error/{status}', [PaymentController::class, 'process_error'])->name('payment.error');
        Route::patch('/payment/{id}', [PaymentController::class, 'update'])->name('payment.update');
    });
});


require __DIR__.'/auth.php';
