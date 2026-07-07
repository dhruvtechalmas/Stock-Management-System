<?php

use App\Http\Controllers\DashboardConroller;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Dashboard Route 
    Route::get('/stock-dashboard', [DashboardConroller::class, 'index'])->name('stocks.index');

    //Material Routes
    Route::resource('materials', MaterialController::class);

    //Material Category Routes
    Route::resource('material-category', MaterialCategoryController::class);

    //User Routes
    Route::resource('users', UserController::class);
    
    //Supplier Routes
    Route::resource('suppliers', SupplierController::class);

    //Purchase Routes
    Route::resource('purchases', PurchaseController::class);
});

require __DIR__.'/auth.php';
