<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/user.css">
    <!-- Menambahkan library Leaflet CSS untuk peta jika diperlukan resmi dari project -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <title>Dashboard - Bank Sampah Digital</title>

</head>

<body>

    <x-header>
        <x-slot:title>
            Serahkan Sampahmu Disini
        </x-slot:title>
    </x-header>

    <main style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
        <div class="card-container">
            <h2 style="margin: 0 0 5px; font-size: 22px;">Buat Request Baru</h2>
            
            <!-- Elemen Peta Lokasi Penjemputan -->
            <div id="map"></div>
            <p style="color: #64748b; font-size: 12px; margin: -5px 0 15px;">Geser penanda ke lokasi penjemputan</p>

            <!-- Form Request Pickup -->
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label for="jumlah_kantong">Jumlah Kantong Plastik</label>
                    <input type="number" id="jumlah_kantong" name="jumlah_kantong" class="form-control" value="1" min="1">
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan Tambahan</label>
                    <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Contoh: Plastik bening, depan pagar hitam"></textarea>
                </div>

                <button type="submit" class="btn-block">Kirim Request Pickup</button>
            </form>

            <!-- Bagian Pemantauan Status Request -->
            <div class="status-section">
                <div class="status-title">Request Menunggu Persetujuan</div>
                <div class="status-empty">Belum ada request menunggu persetujuan</div>

                <div class="status-title">Request Disetujui (Menunggu Pickup)</div>
                <div class="status-empty">Belum ada request yang disetujui</div>

                <div class="status-title">Request Selesai</div>
                <div class="status-empty">Belum ada request yang selesai</div>
            </div>
        </div>
    </main>

    <x-footer></x-footer>

    <!-- Menambahkan library Leaflet JS untuk fungsi interaksi Peta -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta sederhana otomatis mengarah ke koordinat default penjemputan
        var map = L.map('map').setView([-2.12, 106.11], 13); // Sesuaikan koordinat kota target
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        var marker = L.marker([-2.12, 106.11], {draggable: true}).addTo(map)
            .bindPopup('Lokasi Penjemputan').openPopup();
    </script>

</body>

</html>