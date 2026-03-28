<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/update-role', [RoleController::class, 'update'])->name('role.update');
    
    // Library Routes
    Route::resource('books', BookController::class);
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/active', [LoanController::class, 'active'])->name('loans.active');
    Route::get('/history', [LoanController::class, 'history'])->name('loans.history');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::patch('/loans/{loan}/condition', [LoanController::class, 'updateCondition'])->name('loans.update-condition');
    

});



Route::get('/admin', function () {
    return 'Admin Only';
})->middleware(['auth', 'role:admin']);

require __DIR__.'/auth.php';
