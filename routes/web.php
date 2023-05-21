<?php

use App\Http\Controllers\Web\Admin\CityController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\StateController;
use App\Http\Controllers\Web\Admin\TypeDocumentController;
use App\Http\Controllers\Web\Admin\UnitController;
use App\Http\Controllers\Web\Admin\UserController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/user_disabled', function () {
    return Inertia::render('Auth/UserDisabled');
})->name('user-disabled');
Route::get('/405_method_not_allowed', function () {
    return Inertia::render('Auth/MethodNotAllowed');
})->name('405');

Route::middleware(['auth', 'verified', 'enabled'])->group(function () {

    Route::get('/start', function () {
        return Inertia::render('Auth/NoRole');
    })->name('start')->middleware('start');

    /** DASHBOARD */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    /** PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** USER */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/user/{role}', [UserController::class, 'index'])->name('user.index');
        Route::get('/user', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/user/edit/{id}', [UserController::class, 'update'])->name('user.update');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/type_documents', [TypeDocumentController::class, 'index'])->name('type_document.index');
        Route::get('/type_document', [TypeDocumentController::class, 'create'])->name('type_document.create');
        Route::post('/type_document/store', [TypeDocumentController::class, 'store'])->name('type_document.store');
        Route::get('/type_document/edit/{id}', [TypeDocumentController::class, 'edit'])->name('type_document.edit');
        Route::patch('/type_document/edit/{id}', [TypeDocumentController::class, 'update'])->name('type_document.update');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/cities', [CityController::class, 'index'])->name('city.index');
        Route::get('/city', [CityController::class, 'create'])->name('city.create');
        Route::post('/city/store', [CityController::class, 'store'])->name('city.store');
        Route::get('/city/edit/{id}', [CityController::class, 'edit'])->name('city.edit');
        Route::patch('/city/edit/{id}', [CityController::class, 'update'])->name('city.update');

    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/states', [StateController::class, 'index'])->name('state.index');
        Route::get('/state', [StateController::class, 'create'])->name('state.create');
        Route::post('/state/store', [StateController::class, 'store'])->name('state.store');
        Route::get('/state/edit/{id}', [StateController::class, 'edit'])->name('state.edit');
        Route::patch('/state/edit/{id}', [StateController::class, 'update'])->name('state.update');
    });

    /** PRODUCTS */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/product', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
        Route::get('/product/edit/{slug}', [ProductController::class, 'edit'])->name('product.edit');
        Route::patch('/product/edit/{id}/{files}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{slug}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/units', [UnitController::class, 'index'])->name('unit.index');
        Route::get('/unit', [UnitController::class, 'create'])->name('unit.create');
        Route::post('/unit/store', [UnitController::class, 'store'])->name('unit.store');
        Route::get('/unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
        Route::patch('/unit/edit/{id}', [UnitController::class, 'update'])->name('unit.update');
    });

    /** SHOWCASE */
    Route::middleware(['role:client'])->group( function () {
        Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
        Route::get('/showcase/{slug}', [ShowcaseController::class, 'show'])->name('showcase.show');
        Route::get('/order', function () {
            return Inertia::render('Order/Index');
        })->name('order');
    });
});


require __DIR__.'/auth.php';
