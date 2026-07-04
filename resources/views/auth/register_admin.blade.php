<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/auth.css">
    <title>Daftar Agen - Bank Sampah Digital</title>
</head>

<body>

    <x-header>
        <x-slot:title>
            Bank Sampah Digital
        </x-slot:title>
    </x-header>

    <div class="auth-container">
        <form class="auth-form" method="POST" action="{{ route('register.admin') }}">
            @csrf
            <h1>Daftar Sebagai Agen</h1>

            @if ($errors->any())
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="auth-input-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
            </div>

            <div class="auth-input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="nama@contoh.com" value="{{ old('email') }}" required>
            </div>

            <div class="auth-input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="auth-input-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password" required>
            </div>

            <button type="submit">Daftar Sekarang</button>

            <p class="auth-link">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
        </form>
    </div>

</body>

</html>
