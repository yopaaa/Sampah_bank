<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <title>Dashboard Agen - Bank Sampah Digital</title>
    
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .reply-btn:hover {
            text-decoration: underline;
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 25px;
        }

        .info {
            margin-bottom: 15px;
        }

        .profile-marker-avatar {
            width: 50px;
            height: 50px;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
            object-fit: cover;
            display: block;
            margin: 0 auto;
            border: 3px solid #ddd;
        }

        .profile-marker-name {
            display: inline-block;
            margin-top: 5px;
            padding: 2px 8px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            font-weight: bold;
            font-size: 12px;
            font-family: sans-serif;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
            text-transform: capitalize;
        }

        .profile-marker-container {
            text-align: center;
            background: none !important;
            border: none !important;
        }

        .btn-setujui {
            background: #22c55e;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s;
            white-space: nowrap;
            margin-left: 6px;
        }

        .btn-setujui:hover {
            background-color: #16a34a;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-right: 30px;
        }

        .avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 5px solid white;
            background: #eee;
            object-fit: cover;
        }

        #blurbg {
            backdrop-filter: blur(15px);
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

            <h2 class="history-title">Request Selesai</h2>
            <div class="scroll-container" id="completed-requests">
                <!-- Diisi oleh JavaScript -->
            </div>
        </div>

        <x-footer></x-footer>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([-1.849578, 106.1188564], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        function createProfileIcon(avatarUrl, name, status) {
            let border_color = '#28a745'; // Default border color
            if (status === 'menunggu') {
                border_color = '#ffd500'; // Kuning untuk menunggu
            }

            return L.divIcon({
                html: `
                    <div class="profile-marker-container">
                        <img src="/assets/${avatarUrl}" class="profile-marker-avatar" alt="${name}" style="border-color: ${border_color};">
                        <div class="profile-marker-name" style="background-color: ${border_color};">${name}</div>
                    </div>
                `,
                className: '',
                iconSize: [60, 80],
                iconAnchor: [30, 75]
            });
        }

        var dataPickup = @json($locations);

        // Filter data untuk peta (hanya menunggu atau disetujui)
        var filteredData = dataPickup.filter(function(item) {
            return item.status === 'menunggu' || item.status === 'disetujui';
        });

        // Render peta dengan data menunggu
        filteredData.forEach(function(item) {
            if (item.koordinat) {
                var coords = item.koordinat.split(',');
                var lat = parseFloat(coords[0]);
                var lng = parseFloat(coords[1]);

                var name = item.user ? item.user.name : 'Warga';
                var imgFile = item.user && item.user.avatar ? item.user.avatar : 'user.gif';
                var customIcon = createProfileIcon(imgFile, name, item.status);

                var marker = L.marker([lat, lng], {
                    icon: customIcon
                }).addTo(map);

                var popupButtonText = item.status === 'menunggu' ? 'Setujui' : 'Pickup Sekarang';
                var popupButtonColor = item.status === 'menunggu' ? '#22c55e' : '#ffee00';
                var popupTextColor = item.status === 'menunggu' ? '#ffffff' : '#333';
                var popupUrl = item.status === 'menunggu' ? 'javascript:void(0)' : '/proses-pickup/' + item.id;
                var popupOnclick = item.status === 'menunggu' ? `acceptPickup(${item.id})` : '';

                var popupContent = `
                    <div style="text-align:center; min-width:150px;">
                        <strong style="text-transform:capitalize; font-size:16px;">${name}</strong><br>
                        <p style="font-size:12px; color:#666; margin: 5px 0;">${item.notes}</p>
                        <hr style="border:0; border-top:1px solid #eee; margin:10px 0;">
                        <a href="${popupUrl}" 
                           onclick="${popupOnclick}"
                           style="background:${popupButtonColor}; color:${popupTextColor}; padding:10px; border-radius:8px; 
                                  text-decoration:none; font-weight:bold; display:block; border:1px solid #ddd; cursor:pointer;">
                           ${popupButtonText}
                        </a>
                    </div>
                `;

                marker.bindPopup(popupContent);
            }
        });

        function acceptPickup(pickupId) {
            if (confirm('Apakah Anda yakin ingin menyetujui pickup ini?')) {
                fetch('{{ route("pickup.accept") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: `pickup_id=${pickupId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses permintaan.');
                    });
            }
        }

        // Fungsi untuk render card item
        function renderCard(item) {
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

            const name = item.user ? item.user.name : 'Warga';
            const avatar = item.user && item.user.avatar ? item.user.avatar : 'user.gif';

            let actionButton = '';
            if (item.status === 'selesai') {
                actionButton = `<div style="display: flex; gap: 6px; align-items: center;">
                    <span class="status-badge ${item.status}">selesai</span>
                    <a href="/proses-pickup/${item.id}" class="link-bukti">📦 Lihat Bukti</a>
                </div>`;
            } else if (item.status === 'disetujui') {
                actionButton = `<div style="display: flex; gap: 6px; align-items: center;">
                    <span class="status-badge ${item.status}">disetujui</span>
                    <a href="/proses-pickup/${item.id}" 
                       style="background:#fbbf24; color:#333; padding:6px 12px; border-radius:8px; 
                              text-decoration:none; font-weight:bold; border:1px solid #f59e0b; display:inline-block;">
                       📦 Pickup
                    </a>
                </div>`;
            } else if (item.status === 'menunggu') {
                actionButton = `<div style="display: flex; gap: 6px; align-items: center;">
                    <span class="status-badge ${item.status}">menunggu</span>
                    <button onclick="acceptPickup(${item.id})" class="btn-setujui">
                        Setujui
                    </button>
                </div>`;
            }

            let status_selesai = ""
            if (item.status === 'selesai' && formattedDate_updated) {
                status_selesai = `<br><strong>Tanggal Selesai:</strong> ${formattedDate_updated}`;
            }

            return `
                <div class="status-card card-item ${item.status}">
                    <span class="avatar-wrapper"><img class="avatar" src="/assets/${avatar}" alt="${name}"></span>
                    <span class="card-container">
                        <div class="card-header">
                            <span class="date-text">${formattedDate}</span>
                            ${actionButton}
                        </div>
                        <p class="note-text">
                        <h2 style="text-transform: capitalize; margin: 5px 0;">${name}</h2>
                        <strong>Lokasi:</strong> ${item.lokasi}<br>
                        <strong>Koordinat:</strong> ${item.koordinat}<br>
                        <strong>Jumlah Plastik:</strong> ${item.jumlah_plastik} kantong<br>
                        <strong>Catatan:</strong> ${item.notes}
                        ${status_selesai}
                        </p>
                    </span>
                </div>
            `;
        }

        // Filter dan render request disetujui (Rumah yang harus di-pickup)
        const acceptedRequests = dataPickup.filter(item => item.status === 'disetujui');
        const acceptedContainer = document.getElementById('accepted-requests');
        if (acceptedRequests.length > 0) {
            acceptedContainer.innerHTML = acceptedRequests.map(item => renderCard(item)).join('');
        } else {
            acceptedContainer.innerHTML = '<p class="empty-state">Belum ada rumah yang harus di-pickup.</p>';
        }

        // Filter dan render request menunggu
        const pendingRequests = dataPickup.filter(item => item.status === 'menunggu');
        const pendingContainer = document.getElementById('pending-requests');
        if (pendingRequests.length > 0) {
            pendingContainer.innerHTML = pendingRequests.map(item => renderCard(item)).join('');
        } else {
            pendingContainer.innerHTML = '<p class="empty-state">Belum ada permintaan pickup menunggu.</p>';
        }

        // Filter dan render request selesai
        const completedRequests = dataPickup.filter(item => item.status === 'selesai');
        const completedContainer = document.getElementById('completed-requests');
        if (completedRequests.length > 0) {
            completedContainer.innerHTML = completedRequests.map(item => renderCard(item)).join('');
        } else {
            completedContainer.innerHTML = '<p class="empty-state">Belum ada permintaan pickup selesai.</p>';
        }
    </script>
</body>

</html>
