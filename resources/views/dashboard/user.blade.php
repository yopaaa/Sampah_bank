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
                    <input value="1" type="number" id="jumlah_plastik" name="jumlah_plastik" min="1" placeholder="Contoh: 5" required class="form-control" oninput="calculatePrice()">
                </div>
            </div>

            <div class="form-row" style="margin-top: 15px;">
                <div class="form-group new-request-group">
                    <label for="jenis_sampah" style="display: block; margin-bottom: 5px; font-weight: 600;">Jenis Sampah</label>
                    <select id="jenis_sampah" name="jenis_sampah" class="form-control" onchange="calculatePrice()">
                        <option value="plastik">Plastik (Rp 5.000 / kantong)</option>
                        <option value="kertas">Kertas (Rp 4.000 / kantong)</option>
                        <option value="logam">Logam (Rp 10.000 / kantong)</option>
                        <option value="makanan">Makanan / Organik (Rp 2.000 / kantong)</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
             <span>
                <span style="font-weight: 600; color: #64748b;">Saldo:</span>
                <span style="font-size: 20px; font-weight: bold; color: black;">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</span>
            </span>  
            <span>
                <span style="font-weight: 600; color: #64748b;">Estimasi Penggunaan Saldo:</span>
                <span id="price_display" style="font-size: 20px; font-weight: bold; color: #22c55e;">Rp 5.000</span>
            </span>    
            </div>
            <br>

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

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 15px;">
                <h2 class="history-title" style="margin: 0;">Request Selesai</h2>
                <a href="{{ route('pickup.export') }}" style="display: inline-block; background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 13px; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">Unduh Laporan CSV</a>
            </div>
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
            storeUrl: '/request-pickup',
            csrfToken: '{{ csrf_token() }}',
            requests: @json($requests)
        };
    </script>
    <script src="/js/dashboard_user.js"></script>
</body>

</html>