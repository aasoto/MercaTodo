<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Product\ProductController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\ProfileController;
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

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user_disabled', function () {
    return Inertia::render('Auth/UserDisabled');
})->name('user-disabled');
Route::get('/405_method_not_allowed', function () {
    return Inertia::render('Auth/MethodNotAllowed');
})->name('405');

Route::middleware(['auth', 'verified', 'enabled'])->group(function () {

    /** DASHBOARD */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    /** PRODUCTS */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/product', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/edit/{slug}', [ProductController::class, 'edit'])->name('product.edit');
    });
});

require __DIR__.'/auth.php';
