<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentPaymentController;
use Illuminate\Support\Facades\Route;

// Public routes — no auth required
Route::redirect('/', '/admin/login')->name('dashboard');
Route::get('/pembayaran/{slug}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/api/search-student', [PaymentController::class, 'searchStudent'])->name('student.search');
Route::post('/api/create-bill', [PaymentController::class, 'createBill'])->name('payment.create');

// Auth routes (guests only)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('logout');

// Admin routes — requires authenticated user with 'admin' role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/laporan-pembayaran', [AdminController::class, 'paymentReport'])->name('payment-report');
    Route::post('/payment-types', [AdminController::class, 'storePaymentType'])->name('payment-types.store');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::post('/bills', [AdminController::class, 'storeBill'])->name('bills.store');
    Route::patch('/bills/{bill}/mark-paid', [AdminController::class, 'markBillAsPaid'])->name('bills.mark-paid');
    Route::patch('/bills/{bill}/verify', [AdminController::class, 'verifyPayment'])->name('bills.verify');
    Route::patch('/bills/{bill}/reject', [AdminController::class, 'rejectPayment'])->name('bills.reject');
});

// Siswa routes — requires authenticated user with 'siswa' role
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'studentDashboard'])->name('dashboard');
    Route::post('/bills/{bill}/pay', [StudentPaymentController::class, 'requestPayment'])->name('bills.pay');
    Route::post('/bills/{bill}/upload-proof', [StudentPaymentController::class, 'uploadProof'])->name('bills.upload-proof');
});
