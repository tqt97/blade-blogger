<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/posts/{post}', [HomeController::class, 'show'])->name('posts.show');
Route::get('/', [HomeController::class, 'index'])->name('home');

// comments
// routes/web.php
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
// Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
