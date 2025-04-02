<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('dashboard');
});

// Rota de teste para verificar se a aplicação está funcionando
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('quotes', QuoteController::class);
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'downloadPDF'])->name('quotes.pdf');
    Route::post('quotes/{quote}/duplicate', [QuoteController::class, 'duplicate'])->name('quotes.duplicate');
    
    Route::resource('clients', ClientController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    
    // Rotas de exportação e envio
    Route::get('quotes/export/excel', [QuoteController::class, 'exportExcel'])->name('quotes.export.excel');
    Route::get('quotes/{quote}/preview', [QuoteController::class, 'previewPDF'])->name('quotes.preview');
    Route::get('quotes/{quote}/whatsapp', [QuoteController::class, 'sendWhatsApp'])->name('quotes.whatsapp');
    Route::get('quotes/{quote}/email', [QuoteController::class, 'sendEmail'])->name('quotes.email');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
