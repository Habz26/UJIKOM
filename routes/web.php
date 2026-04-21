<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Common routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/categories', [ProfileController::class, 'categories'])->name('profile.categories');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('members', MemberController::class);
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/books', [ReportController::class, 'books'])->name('books');
        Route::get('/loans', [ReportController::class, 'loans'])->name('loans');
        Route::get('/members', [ReportController::class, 'members'])->name('members');
    });
});

// Siswa routes
Route::middleware(['auth', 'verified', 'role:siswa'])->group(function () {
    Route::get('/dashboardsiswa', [DashboardController::class, 'siswa'])->name('dashboard.siswa');
});

// All roles loans view (with internal role checks)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('books', BookController::class)->only(['index', 'show']);
    Route::get('/loans', [LoanController::class, 'history'])->name('loans.history');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::get('/loans/active', [LoanController::class, 'active'])->name('loans.active');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::patch('/loans/{loan}/verify', [LoanController::class, 'verifyReturn'])->name('loans.verify');
    Route::patch('/loans/{loan}/condition', [LoanController::class, 'verifyReturn'])->name('loans.update-condition'); // Legacy
    Route::post('/loans/{loan}/pay-fine', [LoanController::class, 'payFine'])->name('loans.pay-fine');
});
require __DIR__.'/auth.php';

