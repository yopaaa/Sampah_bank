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

Route::view('/user', 'dashboard.user')->middleware(['auth', 'role:user'])->name('user.dashboard');
Route::view('/agen', 'dashboard.agen')->middleware(['auth', 'role:admin'])->name('agen.dashboard');
// ->name('agen.dashboard')

// memberi nama route agen.dashboard
// ini memudahkan pemanggilan URL di Blade atau kode PHP:
// route('agen.dashboard')
// redirect()->route('agen.dashboard')


Route::view('/profile', 'profile')->middleware('auth')->name('profile');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
