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

            @if (auth()->user()->bio)
                <div class="bio-section">
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

    <x-footer></x-footer>

    <script>
        // Set dynamic config from Laravel Blade to be used by public/js/profile.js
        window.profileConfig = {
            uploadUrl: '{{ route("profile.avatar") }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <script src="/js/profile.js"></script>

</body>

</html>