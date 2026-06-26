<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/index.css">
    <title>Profile</title>
</head>

<body>
    <header>
        <h1>Profil Saya</h1>
    </header>
    <main style="padding: 40px; text-align: center;">
        @auth
            <p>Nama: {{ auth()->user()->name ?? auth()->user()->email }}</p>
            <p>Email: {{ auth()->user()->email }}</p>
            <p><a href="{{ route('logout') }}" class="btn btn-primary">Logout</a></p>
        @else
            <p>Anda belum login.</p>
            <p><a href="{{ route('login') }}" class="btn btn-outline">Login</a></p>
        @endauth
    </main>
</body>

</html>