<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;


Route::fallback(function () {
    return view('home');
});

// Auth::routes(['verify' => true]);

Route::get('/login', function() { return view('home');})->name('login');
Route::post('/login', [LoginController::class , 'login'])->name('login');
Route::post('/logout', [LoginController::class , 'logout'])->name('logout');
Route::post('/register', [RegisterController::class , 'register'])->name('register');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class,'verify'])->name('verification.verify');
Route::get('/reset/password/{token}', function() {
    return view('home');
})->name('password.reset');
Route::get('/link/{post:short_link}', ShortLinkController::class);

