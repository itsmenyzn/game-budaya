<?php

use App\Http\Controllers\BudayaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return redirect()->route('budaya.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/soal-tebak-musik', [QuestionController::class, 'getSoalTebakAlatMusik']);
Route::get('/soal-tebak-gambar', [QuestionController::class, 'getSoalTebakGambar']);
Route::get('/soal-pilihan-ganda', [QuestionController::class, 'getSoalPilihanGanda']);
Route::get('/budaya', [QuestionController::class, 'LoadBudayaData']);

Route::middleware('auth')->group(function () {
    Route::resource('budaya',BudayaController::class);
});

require __DIR__.'/auth.php';