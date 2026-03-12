<?php

use App\Http\Controllers\CourtController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
