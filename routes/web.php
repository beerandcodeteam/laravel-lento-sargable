<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/orders/good', [OrderController::class, 'good'])->name('orders.good');
Route::get('/orders/bad', [OrderController::class, 'bad'])->name('orders.bad');
