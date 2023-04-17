<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\NuvemshopController;
use App\Http\Controllers\AppShippingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

    Route::get('/tests',[PaymentsController::class, "customersGet"])->name('teste');

    Route::get('/policy', [AppController::class, 'policy'])->name('policy');

    Route::get('/payments', [PaymentsController::class, 'edit'])->name('payments.edit');
    Route::post('/payments', [PaymentsController::class, 'save'])->name('payments.save');
    Route::post('/payments/refresh', [PaymentsController::class, 'refresh'])->name('payments.refresh');
    // Route::post('/payments/customer', [PaymentsController::class, 'customersCreate'])->name('payments.customers.create');

    // Shipping
    Route::get('/shipping', [AppShippingController::class, 'index'])->middleware(['auth'])->name('shipping');
    Route::post('/shipping', [AppShippingController::class, 'save'])->middleware(['auth'])->name('shipping');
    // Route::get('/shipping/{id}', [AppShippingController::class, 'single'])->middleware(['auth'])->name('shipping.single');  
    Route::post('/shipping/import', [AppShippingController::class, 'importCSV']);

    Route::get('/install', [NuvemshopController::class, 'install'])->name('nuvemshop.install');

});

Route::post('/shipping/response', [AppShippingController::class, 'find']);

require __DIR__.'/auth.php';
