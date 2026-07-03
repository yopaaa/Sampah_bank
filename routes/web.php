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

Route::get('/user', function () {
    $requests = auth()->user()->pickupRequests()->orderBy('created_at', 'desc')->get();
    
    $lat_default = -1.849578;
    $lng_default = 106.1188564;

    if ($requests->count() > 0) {
        $latest = $requests->first();
        if (!empty($latest->koordinat)) {
            $coords = explode(',', $latest->koordinat);
            $lat_default = trim($coords[0] ?? $lat_default);
            $lng_default = trim($coords[1] ?? $lng_default);
        }
    }

    return view('dashboard.user', compact('requests', 'lat_default', 'lng_default'));
})->middleware(['auth', 'role:user'])->name('user.dashboard');

Route::post('/request-pickup', function (\Illuminate\Http\Request $request) {
    $lat = $request->input('latitude') ?? '';
    $lng = $request->input('longitude') ?? '';
    $jumlah_plastik = $request->input('jumlah_plastik') ?? 1;
    $notes_input = $request->input('notes') ?? '';
    
    // Format catatan dengan informasi plastik
    $final_notes = "Jumlah: $jumlah_plastik kantong | Pesan: $notes_input";
    
    \App\Models\PickupRequest::create([
        'user_id' => auth()->id(),
        'notes' => $final_notes,
        'lokasi' => 'bangka',
        'koordinat' => "$lat, $lng",
        'jumlah_plastik' => $jumlah_plastik,
        'status' => 'menunggu',
    ]);
    
    return response()->json([
        'status' => 'success',
        'message' => 'Permintaan pickup berhasil dikirim'
    ]);
})->middleware(['auth', 'role:user'])->name('pickup.store');

Route::get('/request-pickup/delete/{id}', function ($id) {
    $pickup = auth()->user()->pickupRequests()->findOrFail($id);
    $pickup->delete();
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'role:user'])->name('pickup.delete');

Route::get('/proses-pickup/{id}', function ($id) {
    $pickup = \App\Models\PickupRequest::findOrFail($id);
    return view('dashboard.proses_pickup', compact('pickup'));
})->middleware('auth')->name('pickup.process');

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
