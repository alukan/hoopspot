<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourtCommentController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameMessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create')->middleware('auth');
Route::post('/courts', [CourtController::class, 'store'])->name('courts.store')->middleware('auth');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/create', [GameController::class, 'create'])->name('games.create')->middleware('auth');
Route::post('/games', [GameController::class, 'store'])->name('games.store')->middleware('auth');
Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
Route::post('/games/{game}/join', [GameController::class, 'join'])->name('games.join')->middleware('auth');
Route::delete('/games/{game}/leave', [GameController::class, 'leave'])->name('games.leave')->middleware('auth');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/users/{user}/friend', [FriendController::class, 'toggle'])->name('friends.toggle');
    Route::delete('/users/{user}/friend', [FriendController::class, 'destroy'])->name('friends.destroy');

    Route::post('/courts/{court}/comments', [CourtCommentController::class, 'store'])->name('court-comments.store');
    Route::delete('/comments/{comment}', [CourtCommentController::class, 'destroy'])->name('court-comments.destroy');

    Route::post('/games/{game}/messages', [GameMessageController::class, 'store'])->name('game-messages.store');
    Route::delete('/game-messages/{message}', [GameMessageController::class, 'destroy'])->name('game-messages.destroy');

    Route::get('/messages/{friendRequest}', [DirectMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{friendRequest}', [DirectMessageController::class, 'store'])->name('messages.store');
});
