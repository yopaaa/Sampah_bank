<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <title>{{ $title ?? 'Bank Sampah Digital' }}</title>
</head>

<body>

    <x-header>
        <x-slot:title>
            {{ $title ?? 'Bank Sampah Digital' }}
        </x-slot:title>
    </x-header>

    <section class="hero">
        <span>
            <h2>Kelola Sampah Jadi Lebih Mudah</h2>
            <p>Platform digital yang menghubungkan warga dengan agen pengangkut sampah terpercaya untuk lingkungan yang lebih bersih.</p>

            <div class="cta-buttons">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('agen.dashboard') : route('user.dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Mulai Sekarang</a>
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk ke Akun</a>
                @endauth
            </div>
        </span>
    </section>

    <section id="informasi" class="how-it-works">
        <h2>Cara Kerja Angkut Sampah</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">
                    <img src="/assets/icon/location.png" alt="location" class="icon-box">
                </div>
                <h4>Tentukan Lokasi</h4>
                <p>Pilih lokasi penjemputan di peta yang tersedia.</p>
            </div>
            <div class="step">
                <div class="step-number">
                    <img src="/assets/icon/request.png" alt="request" class="icon-box">
                </div>
                <h4>Kirim Request</h4>
                <p>Isi catatan tambahan dan kirim permintaanmu.</p>
            </div>
            <div class="step">
                <div class="step-number">
                    <img src="/assets/icon/pickup.png" alt="pickup" class="icon-box">
                </div>
                <h4>Agen Menjemput</h4>
                <p>Agen terdekat akan mendapatkan notifikasi dan datang ke lokasi.</p>
            </div>
            <div class="step">
                <div class="step-number">
                    <img src="/assets/icon/complete.png" alt="complete" class="icon-box">
                </div>
                <h4>Selesai</h4>
                <p>Sampah terangkut, lingkungan jadi bersih kembali!</p>
            </div>
        </div>
    </section>

    <section id="manfaat" class="features">
        <h2>Apa Manfaat Yang Didapatkan?</h2>
        <span>
            <div class="feature-card">
                <img src="/assets/icon/resident.png" alt="resident" class="icon-box">
                <h3>Untuk Warga</h3>
                <p>Gak perlu repot cari tukang sampah. Cukup tandai lokasi di peta, kirim permintaan, dan tunggu agen datang menjemput sampahmu.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline">Daftar Jadi Warga</a>
                @endguest
            </div>

            <div class="feature-card">
                <img src="/assets/icon/truck.png" alt="truck" class="icon-box">
                <h3>Untuk Agen</h3>
                <p>Dapatkan penghasilan tambahan dengan menjadi agen pengangkut. Terima orderan di sekitar lokasimu dan bantu bersihkan kota.</p>
                @guest
                    <a href="{{ route('register.admin') }}" class="btn btn-outline">Daftar Jadi Agen</a>
                @endguest
            </div>
        </span>
    </section>

    <x-footer></x-footer>

</body>

</html>
