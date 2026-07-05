<header>
    <h1>{{ $title }}</h1>
    
</header>

<nav>
    <ul>
        <li><a href=".">Beranda</a></li>
        <li><a href="#">Layanan</a>
            <ul>
                <li><a href="#informasi">Informasi</a></li>
                <li><a href="#manfaat">Manfaat</a></li>
            </ul>
        </li>
        <li><a href="#">Dokumentasi</a>
            <ul>
                <li><a href="#">Foto Kegiatan</a></li>
                <li><a href="#">Video</a></li>
            </ul>
        </li>

        <li><a href="#">Kontak</a>
            <ul>
                <li><a href="mailto:">Email</a></li>
                <li><a href="tel:">Telepon</a></li>
                <li><a href="https://www.instagram.com/">Instagram</a></li>
            </ul>
        </li>
        @auth
            <li>
                <a href="/{{ auth()->user()->role }}" class="user-nav">
                    <img src="{{ asset('assets/' . (auth()->user()->avatar ?? 'user.gif')) }}" alt="Avatar" class="profile-user-avatar">
                    {{ auth()->user()->name ?? auth()->user()->email }}</a>
                <ul>
                    <li><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('profile') }}">Akun Saya</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </li>
        @else
            <li><a href="{{ route('login') }}">Login</a></li>
            <!-- <li><a href="{{ route('register') }}">Register</a></li> -->
        @endauth
    </ul>
</nav>