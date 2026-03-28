<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ProductController;

Route::get('products', [ProductController::class,'index'])->name('products.index');
Route::get('products/data', [ProductController::class,'data'])->name('products.data');