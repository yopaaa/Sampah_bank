<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="/css/dashboard_agen.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <title>Dashboard Agen - Bank Sampah Digital</title>
</head>

<body>
    <div id="blurbg">
        <x-header>
            <x-slot:title>
                Bank Sampah Digital
            </x-slot:title>
        </x-header>

        <div class="container">
            <!-- Tampilan Saldo Agen -->
            <div style=" color: white; padding: 15px 25px; border-radius: 12px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 600; font-size: 16px; color: black;">Dompet Saldo Agen:</span>
                <span style="font-size: 22px; font-weight: 800; color: black;">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</span>
            </div>

            <div class="info">
                <h2>Peta Lokasi Permintaan Pickup</h2>
                <p>Klik penanda (marker) untuk melihat informasi tempat.</p>
            </div>

            <div id="map"></div>

            <h2 class="history-title">Request Menunggu Pickup</h2>
            <div class="scroll-container" id="pending-requests">
                <!-- Diisi oleh JavaScript -->
            </div>

            <h2 class="history-title">Rumah Yang harus di Pickup</h2>
            <div class="scroll-container" id="accepted-requests">
                <!-- Diisi oleh JavaScript -->
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 15px;">
                <h2 class="history-title" style="margin: 0;">Request Selesai</h2>
                <a href="{{ route('pickup.export') }}" style="display: inline-block; background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 13px; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">Unduh Laporan</a>
            </div>
            <div class="scroll-container" id="completed-requests">
                <!-- Diisi oleh JavaScript -->
            </div>
        </div>

        <x-footer></x-footer>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Set dynamic config from Laravel Blade to be used by public/js/dashboard_agen.js
        window.dashboardAgenConfig = {
            locations: @json($locations),
            acceptUrl: '{{ route("pickup.accept") }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <script src="/js/dashboard_agen.js"></script>
</body>

</html>
