<?php

use App\Http\Controllers\BusinessRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BusinessRecordController::class, 'index'])->name('home');
Route::post('/records', [BusinessRecordController::class, 'store'])->name('records.store');
Route::post('/businesses', [BusinessRecordController::class, 'storeBusiness'])->name('businesses.store');
Route::put('/businesses/{business}', [BusinessRecordController::class, 'updateBusiness'])->name('businesses.update');
Route::put('/records/{record}', [BusinessRecordController::class, 'updateRecord']);