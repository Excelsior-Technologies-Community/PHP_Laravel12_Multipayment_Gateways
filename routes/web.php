<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class, 'index']);

Route::post('/payment-process', [PaymentController::class, 'process'])
    ->name('payment.process');

Route::get('/payment-history', [PaymentController::class, 'history'])
    ->name('payment.history');