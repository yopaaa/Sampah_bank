<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link class="profile-user-avatar" rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <title>Serahkan Sampahmu - Bank Sampah Digital</title>
</head>

<body>
    <div id="blurbg">
        <x-header>
            <x-slot:title>
                Bank Sampah Digital
            </x-slot:title>
        </x-header>

        <div class="container">
            <h2>Buat Request Baru</h2>
            <div id="map"></div>
            <p class="empty-state" style="padding: 5px 0; margin: -5px 0 15px; text-align: center; color: #666; font-size: 13px;">
                Geser pin merah ke lokasi penjemputan
            </p>

            <input type="hidden" id="lat" name="latitude">
            <input type="hidden" id="lng" name="longitude">

            <div class="form-row">
                <div class="form-group new-request-group">
                    <label for="jumlah_plastik" style="display: block; margin-bottom: 5px; font-weight: 600;">Jumlah Kantong Plastik</label>
                    <input value="1" type="number" id="jumlah_plastik" name="jumlah_plastik" min="1" placeholder="Contoh: 5" required class="form-control">
                </div>
            </div>

            <div class="form-group new-request-group">
                <label for="notes" style="display: block; margin-bottom: 5px; font-weight: 600;">Catatan Tambahan</label>
                <textarea id="notes" name="notes" placeholder="Contoh: Plastik bening, depan pagar hitam" class="form-control" style="min-height: 100px; resize: vertical;"></textarea>
            </div>

            <button class="btn-block" onclick="saveLocation()">Kirim Request Pickup</button>

            <hr class="divider">

            <h2 class="history-title">Request Menunggu Persetujuan</h2>
            <div class="scroll-container" id="pending-requests">
                <!-- Diisi oleh JavaScript -->
            </div>

            <h2 class="history-title">Request Disetujui (Menunggu Pickup)</h2>
            <div class="scroll-container" id="accepted-requests">
                <!-- Diisi oleh JavaScript -->
            </div>

            <h2 class="history-title">Request Selesai</h2>
            <div class="scroll-container" id="completed-requests">
                <!-- Diisi oleh JavaScript -->
            </div>
        </div>

        <x-footer></x-footer>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Set dynamic config from Laravel Blade to be used by public/js/dashboard_user.js
        window.dashboardUserConfig = {
            latDefault: {{ $lat_default }},
            lngDefault: {{ $lng_default }},
            storeUrl: '{{ route("pickup.store") }}',
            csrfToken: '{{ csrf_token() }}',
            requests: @json($requests)
        };
    </script>
    <script src="/js/dashboard_user.js"></script>
</body>

</html>