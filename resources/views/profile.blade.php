<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/profile.css">
    <title>Profil Saya - {{ auth()->user()->name }}</title>
</head>

<body>

    <x-header>
        <x-slot:title>
            Bank Sampah Digital
        </x-slot:title>
    </x-header>

    <div class="profile-container">
        <div class="profile-header"></div>

        <div class="profile-content">
            <div class="avatar-wrapper" id="avatarWrapper">
                <img src="{{ asset('assets/' . (auth()->user()->avatar ?? 'user.gif')) }}" alt="Avatar" class="avatar" id="avatarImage">
                <div class="avatar-overlay">
                    <span class="avatar-overlay-icon">📷</span>
                </div>
            </div>
            <div class="upload-status" id="uploadStatus"></div>
            <input type="file" id="avatarInput" accept="image/*">

            <h1 style="margin-top: 15px;">{{ auth()->user()->name }}</h1>
            <p style="color: #64748b;">@ {{ auth()->user()->username }}</p>
            <span class="badge">{{ auth()->user()->role }}</span>

            <!-- Saldo Container -->
            <div style=" padding: 15px; margin: 20px 0; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                <div>
                    <span style="font-size: 12px; color: #64748b; font-weight: bold; text-transform: uppercase;">Saldo Dompet</span>
                    <h2 id="saldoDisplay" style="margin: 5px 0 0; font-size: 24px; font-weight: 800;">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</h2>
                </div>
                <div>
                    <button onclick="openTopupModal()" class="btn-edit" style="margin-top: 0;">Top Up</button>
                </div>
            </div>

            @if (auth()->user()->bio)
                <div class="bio-section" style="margin-top: 10px;">
                    "{{ auth()->user()->bio }}"
                </div>
            @endif

            <div class="info-grid">
                <div class="info-item">
                    <label>Email</label>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="info-item">
                    <label>Lokasi</label>
                    <span>{{ auth()->user()->lokasi ?? 'Belum diatur' }}</span>
                </div>
                <div class="info-item">
                    <label>Koordinat</label>
                    <span>{{ auth()->user()->koordinat ?? 'Tidak tersedia' }}</span>
                </div>
                <div class="info-item">
                    <label>ID Pengguna</label>
                    <span>#{{ auth()->id() }}</span>
                </div>
            </div>

            <button onclick="triggerUpload()" class="btn-edit">Pilih Avatar</button>
            <button onclick="uploadAvatar()" class="btn-edit" id="btnUpload" style="display: none; background: #22c55e;">Upload Avatar</button>
            <a href="{{ auth()->user()->role === 'admin' ? route('agen.dashboard') : route('user.dashboard') }}" class="btn-back-dashboard">Dashboard</a>
        </div>
    </div>

    <!-- Modal Top Up -->
    <div id="topupModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeTopupModal()">&times;</span>
            <h3>Top Up Saldo</h3>
            
            <p style="font-size: 13px; color: #64748b; margin-top: -10px; margin-bottom: 15px;">Pilih nominal instan atau ketik nominal sendiri.</p>

            <div class="nominal-options">
                <button type="button" class="nominal-btn" onclick="selectNominal(10000, this)">Rp 10k</button>
                <button type="button" class="nominal-btn" onclick="selectNominal(20000, this)">Rp 20k</button>
                <button type="button" class="nominal-btn" onclick="selectNominal(50000, this)">Rp 50k</button>
                <button type="button" class="nominal-btn" onclick="selectNominal(100000, this)">Rp 100k</button>
                <button type="button" class="nominal-btn" onclick="selectNominal(200000, this)">Rp 200k</button>
                <button type="button" class="nominal-btn" onclick="selectNominal(500000, this)">Rp 500k</button>
            </div>

            <label for="topupAmount" style="font-size: 12px; font-weight: bold; color: #64748b; text-transform: uppercase;">Nominal Custom (Min Rp 10.000)</label>
            <input type="number" id="topupAmount" class="modal-input" placeholder="Contoh: 150000" min="10000">

            <button onclick="submitTopup()" class="btn-edit" style="width: 100%; margin-top: 10px;">Konfirmasi Top Up</button>
        </div>
    </div>

    <x-footer></x-footer>

    <script>
        // Set dynamic config from Laravel Blade to be used by public/js/profile.js
        window.profileConfig = {
            uploadUrl: '{{ route("profile.avatar") }}',
            topupUrl: '{{ route("profile.topup") }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <script src="/js/profile.js"></script>

</body>

</html>