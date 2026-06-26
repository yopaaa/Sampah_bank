<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index', [
        "title" => "Bank Sampah Digital",
    ]);
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::view('/register/admin', 'auth.register_admin')->name('register.admin');
Route::post('/register/admin', [AuthController::class, 'registerAdmin']);

Route::view('/profile', 'profile')->middleware('auth')->name('profile');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
