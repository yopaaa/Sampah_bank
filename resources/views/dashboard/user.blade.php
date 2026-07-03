<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <title>Serahkan Sampahmu - Bank Sampah Digital</title>
    <style>
        #blurbg {
            backdrop-filter: blur(15px);
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
    <div id="blurbg">
        <x-header>
            <x-slot:title>
                Serahkan Sampahmu Disini
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
        // Gunakan koordinat terakhir jika ada, jika tidak gunakan default Bangka
        var lastLat = {{ $lat_default }};
        var lastLng = {{ $lng_default }};

        var map = L.map('map').setView([lastLat, lastLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker;

        function enablePicker() {
            var center = map.getCenter();
            marker = L.marker(center, {
                draggable: true,
                autoPan: true
            }).addTo(map);

            marker.on('dragend', function(event) {
                updateForm(event.target.getLatLng());
            });
            updateForm(center);
        }

        function updateForm(latlng) {
            document.getElementById('lat').value = latlng.lat.toFixed(6);
            document.getElementById('lng').value = latlng.lng.toFixed(6);
            marker.bindPopup("Lokasi Penjemputan").openPopup();
        }

        function saveLocation() {
            const lat = document.getElementById('lat').value;
            const lng = document.getElementById('lng').value;
            const jumlah_plastik = document.getElementById('jumlah_plastik').value;
            const notes = document.getElementById('notes').value;

            if (!lat || !lng) {
                alert("Pilih lokasi di peta!");
                return;
            }

            if (!jumlah_plastik) {
                alert("Isi jumlah dan jenis plastik!");
                return;
            }

            const formData = new FormData();
            formData.append('latitude', lat);
            formData.append('longitude', lng);
            formData.append('jumlah_plastik', jumlah_plastik);
            formData.append('notes', notes);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("pickup.store") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Refresh halaman untuk melihat data terbaru
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan sistem.");
                });
        }

        // Render history requests menggunakan JavaScript
        function renderHistoryCard(item) {
            const date = new Date(item.created_at);
            const formattedDate = date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            let formattedDate_updated = '';
            if (item.updated_at) {
                const date_update = new Date(item.updated_at);
                formattedDate_updated = date_update.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            let actionLink = '';
            if (item.status === 'selesai') {
                actionLink = `<a href="/proses-pickup/${item.id}" class="link-bukti">📦 Lihat Bukti</a>`;
            }

            if (item.status === 'menunggu') {
                actionLink += `<a class="link-hapus" href="/request-pickup/delete/${item.id}">Hapus</a>`;
            }

            let status_selesai = ""
            if (item.status === 'selesai' && formattedDate_updated) {
                status_selesai = `<br><strong>Tanggal Selesai:</strong> ${formattedDate_updated}`;
            }

            return `
                <div class="status-card card-item ${item.status}">
                    <div class="card-container">
                        <div class="card-header">
                            <span class="date-text">${formattedDate}</span>
                            <div>
                                <span class="status-badge ${item.status}">${item.status}</span>
                                ${actionLink}
                            </div>
                        </div>
                        <p class="note-text">
                            <strong>Jumlah Plastik:</strong> ${item.jumlah_plastik} kantong<br>
                            <strong>Lokasi:</strong> ${item.lokasi}<br>
                            <strong>Catatan:</strong> ${item.notes}
                            ${status_selesai}
                        </p>
                    </div>
                </div>
            `;
        }

        // Data dari PHP/Laravel
        var requestsData = @json($requests);

        // Filter pending requests (menunggu)
        const pendingRequests = requestsData.filter(item => item.status === 'menunggu');
        const pendingContainer = document.getElementById('pending-requests');
        if (pendingRequests.length > 0) {
            pendingContainer.innerHTML = pendingRequests.map(item => renderHistoryCard(item)).join('');
        } else {
            pendingContainer.innerHTML = '<p class="empty-state">Belum ada request menunggu persetujuan.</p>';
        }

        // Filter accepted requests (disetujui)
        const acceptedRequests = requestsData.filter(item => item.status === 'disetujui');
        const acceptedContainer = document.getElementById('accepted-requests');
        if (acceptedRequests.length > 0) {
            acceptedContainer.innerHTML = acceptedRequests.map(item => renderHistoryCard(item)).join('');
        } else {
            acceptedContainer.innerHTML = '<p class="empty-state">Belum ada request yang disetujui.</p>';
        }

        // Filter completed requests (selesai)
        const completedRequests = requestsData.filter(item => item.status === 'selesai');
        const completedContainer = document.getElementById('completed-requests');
        if (completedRequests.length > 0) {
            completedContainer.innerHTML = completedRequests.map(item => renderHistoryCard(item)).join('');
        } else {
            completedContainer.innerHTML = '<p class="empty-state">Belum ada request yang selesai.</p>';
        }

        enablePicker();
    </script>
</body>

</html>