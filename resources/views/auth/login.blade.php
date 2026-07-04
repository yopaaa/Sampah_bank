<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/auth.css">
    <title>Login - Bank Sampah Digital</title>
</head>

<body>

    <x-header>
        <x-slot:title>
            Bank Sampah Digital
        </x-slot:title>
    </x-header>

    <div class="auth-container">
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <h1>Masuk ke akun</h1>

            @if ($errors->any())
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="auth-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="auth-input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="nama@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="auth-input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <button type="submit">Login</button>

            <p class="auth-link">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </form>
    </div>

</body>

</html>