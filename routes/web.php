<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index', [
        "title" => "Bank Sampah Digital",
    ]);
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/profile', 'profile')->middleware('auth')->name('profile');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
