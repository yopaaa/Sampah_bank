<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        "title" => "Bank Sampah Digital",
    ]);
});

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::view('/register/admin', 'auth.register_admin')->name('register.admin');
    Route::post('/register/admin', [AuthController::class, 'registerAdmin']);
});

Route::get('/user', function () {
    $requests = Auth::user()->pickupRequests()->orderBy('created_at', 'desc')->get();
    
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
    $jumlah_plastik = (int) ($request->input('jumlah_plastik') ?? 1);
    $jenis_sampah = $request->input('jenis_sampah') ?? 'plastik';
    $notes_input = $request->input('notes') ?? '';
    
    // Daftar harga per kantong berdasarkan jenis sampah
    $harga_list = [
        'plastik' => 5000,
        'kertas' => 4000,
        'logam' => 10000,
        'makanan' => 2000,
    ];
    $harga_per_kantong = $harga_list[$jenis_sampah] ?? 5000;
    $total_harga = $harga_per_kantong * $jumlah_plastik;

    $user = Auth::user();
    if ($user->saldo < $total_harga) {
        return response()->json([
            'status' => 'error',
            'message' => 'Saldo Anda tidak mencukupi (Kurang Rp ' . number_format($total_harga - $user->saldo, 0, ',', '.') . '). Silakan lakukan top up di halaman profil.'
        ]);
    }

    // Kurangi saldo warga
    $user->decrement('saldo', $total_harga);

    // Format catatan dengan informasi plastik dan jenis sampah
    $final_notes = "Jenis: " . ucfirst($jenis_sampah) . " | Jumlah: $jumlah_plastik kantong | Pesan: $notes_input";
    
    \App\Models\PickupRequest::create([
        'user_id' => $user->id,
        'notes' => $final_notes,
        'lokasi' => 'bangka',
        'koordinat' => "$lat, $lng",
        'jumlah_plastik' => $jumlah_plastik,
        'jenis_sampah' => $jenis_sampah,
        'total_harga' => $total_harga,
        'status' => 'menunggu',
    ]);
    
    return response()->json([
        'status' => 'success',
        'message' => 'Permintaan pickup berhasil dikirim'
    ]);
})->middleware(['auth', 'role:user'])->name('pickup.store');

Route::get('/request-pickup/delete/{id}', function ($id) {
    $pickup = Auth::user()->pickupRequests()->findOrFail($id);
    $pickup->delete();
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'role:user'])->name('pickup.delete');

Route::get('/proses-pickup/{id}', function ($id) {
    $pickup = \App\Models\PickupRequest::findOrFail($id);
    return view('dashboard.proses_pickup', compact('pickup'));
})->middleware('auth')->name('pickup.process');
Route::post('/proses-pickup', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'pickup_id' => 'required|exists:pickup_requests,id',
        'bukti' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    $pickupId = $request->input('pickup_id');
    $pickup = \App\Models\PickupRequest::findOrFail($pickupId);

    if ($request->hasFile('bukti')) {
        $file = $request->file('bukti');
        $uploadPath = public_path('assets/bukti_pickup');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = "bukti_" . time() . "_" . rand(1000, 9999) . "." . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);

        $pickup->update([
            'status' => 'selesai',
            'bukti' => $filename,
        ]);

        // Tambah saldo ke agen yang memproses
        if ($pickup->agent) {
            $pickup->agent->increment('saldo', $pickup->total_harga);
        }

        return redirect()->route('agen.dashboard')->with('success', 'Bukti pickup berhasil diunggah!');
    }

    return back()->withErrors(['bukti' => 'Gagal mengunggah file.']);
})->middleware(['auth', 'role:admin'])->name('pickup.process.store');
Route::get('/agen', function () {
    $locations = \App\Models\PickupRequest::with('user')->orderBy('updated_at', 'desc')->get();
    return view('dashboard.agen', compact('locations'));
})->middleware(['auth', 'role:admin'])->name('agen.dashboard');

Route::post('/accept-pickup', function (\Illuminate\Http\Request $request) {
    $pickupId = $request->input('pickup_id');
    $pickup = \App\Models\PickupRequest::findOrFail($pickupId);
    
    $pickup->update([
        'status' => 'disetujui',
        'agent_id' => Auth::id(), // simpan agen yang memproses pickup
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Pickup request accepted successfully.'
    ]);
})->middleware(['auth', 'role:admin'])->name('pickup.accept');
// ->name('agen.dashboard')

// memberi nama route agen.dashboard
// ini memudahkan pemanggilan URL di Blade atau kode PHP:
// route('agen.dashboard')
// redirect()->route('agen.dashboard')


Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::post('/upload-avatar', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    $user = Auth::user();

    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        
        // Hapus file avatar lama jika ada
        if ($user->avatar && $user->avatar !== 'user.gif' && $user->avatar !== 'images.png') {
            $oldFilePath = public_path('assets/' . $user->avatar);
            if (file_exists($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        // Simpan avatar baru
        $filename = 'avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets'), $filename);

        // Update database
        $user->update([
            'avatar' => $filename
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil diperbarui',
            'filename' => $filename
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Gagal menyimpan file'
    ], 400);
})->middleware('auth')->name('profile.avatar');

Route::post('/profile/topup', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'amount' => 'required|integer|min:10000|max:10000000',
    ]);
    
    $user = Auth::user();
    $user->increment('saldo', $request->input('amount'));
    
    return response()->json([
        'success' => true,
        'message' => 'Top up saldo sebesar Rp ' . number_format($request->input('amount'), 0, ',', '.') . ' berhasil!',
        'new_saldo' => number_format($user->saldo, 0, ',', '.')
    ]);
})->middleware('auth')->name('profile.topup');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
