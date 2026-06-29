<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentController::class, 'dashboard'])->name('dashboard');
Route::get('/pembayaran/{slug}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/api/search-student', [PaymentController::class, 'searchStudent'])->name('student.search');
Route::post('/api/create-bill', [PaymentController::class, 'createBill'])->name('payment.create');
