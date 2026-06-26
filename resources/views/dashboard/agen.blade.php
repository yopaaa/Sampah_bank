<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <title>Dashboard Agen - Bank Sampah Digital</title>
</head>

<body>

    <x-header>
        <x-slot:title>
            Serahkan Sampahmu Disini
        </x-slot:title>
    </x-header>

    <main style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
        <div style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h2 style="margin: 0 0 10px;">Halo, Agen {{ auth()->user()->name }}! 🚛</h2>
            <p style="color: #64748b; margin: 0 0 20px;">Selamat datang di dashboard agen. Username kamu: <b>{{ auth()->user()->username }}</b></p>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('profile') }}" class="btn btn-primary">Profil Saya</a>
                <a href="{{ route('logout') }}" class="btn btn-outline">Logout</a>
            </div>
        </div>
    </main>

    <x-footer></x-footer>

</body>

</html>
