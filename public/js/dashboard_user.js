// Gunakan koordinat terakhir jika ada, jika tidak gunakan default Bangka
var lastLat = window.dashboardUserConfig.latDefault;
var lastLng = window.dashboardUserConfig.lngDefault;

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

const hargaList = {
    'plastik': 5000,
    'kertas': 4000,
    'logam': 10000,
    'makanan': 2000
};

function calculatePrice() {
    const jumlahInput = document.getElementById('jumlah_plastik');
    const jenisSelect = document.getElementById('jenis_sampah');
    const priceDisplay = document.getElementById('price_display');

    if (jumlahInput && jenisSelect && priceDisplay) {
        const jumlah = parseInt(jumlahInput.value) || 0;
        const jenis = jenisSelect.value;
        const hargaPerKantong = hargaList[jenis] || 5000;
        const total = hargaPerKantong * jumlah;

        priceDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}

function saveLocation() {
    const lat = document.getElementById('lat').value;
    const lng = document.getElementById('lng').value;
    const jumlah_plastik = document.getElementById('jumlah_plastik').value;
    const jenis_sampah = document.getElementById('jenis_sampah').value;
    const notes = document.getElementById('notes').value;

    if (!lat || !lng) {
        alert("Pilih lokasi di peta!");
        return;
    }

    if (!jumlah_plastik) {
        alert("Isi jumlah kantong!");
        return;
    }

    const formData = new FormData();
    formData.append('latitude', lat);
    formData.append('longitude', lng);
    formData.append('jumlah_plastik', jumlah_plastik);
    formData.append('jenis_sampah', jenis_sampah);
    formData.append('notes', notes);
    formData.append('_token', window.dashboardUserConfig.csrfToken);

    fetch(window.dashboardUserConfig.storeUrl, {
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

    const totalHargaFormated = 'Rp ' + (item.total_harga || 0).toLocaleString('id-ID');
    const jenisSampahCapitalized = (item.jenis_sampah || 'plastik').charAt(0).toUpperCase() + (item.jenis_sampah || 'plastik').slice(1);

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
                    <strong>Jenis Sampah:</strong> ${jenisSampahCapitalized}<br>
                    <strong>Jumlah:</strong> ${item.jumlah_plastik} kantong<br>
                    <strong>Estimasi Saldo:</strong> <span style="color:#22c55e; font-weight:bold;">${totalHargaFormated}</span><br>
                    <strong>Lokasi:</strong> ${item.lokasi}<br>
                    <strong>Catatan:</strong> ${item.notes}
                    ${status_selesai}
                </p>
            </div>
        </div>
    `;
}

// Data dari PHP/Laravel config
var requestsData = window.dashboardUserConfig.requests;

// Filter pending requests (menunggu)
const pendingRequests = requestsData.filter(item => item.status === 'menunggu');
const pendingContainer = document.getElementById('pending-requests');
if (pendingContainer) {
    if (pendingRequests.length > 0) {
        pendingContainer.innerHTML = pendingRequests.map(item => renderHistoryCard(item)).join('');
    } else {
        pendingContainer.innerHTML = '<p class="empty-state">Belum ada request menunggu persetujuan.</p>';
    }
}

// Filter accepted requests (disetujui)
const acceptedRequests = requestsData.filter(item => item.status === 'disetujui');
const acceptedContainer = document.getElementById('accepted-requests');
if (acceptedContainer) {
    if (acceptedRequests.length > 0) {
        acceptedContainer.innerHTML = acceptedRequests.map(item => renderHistoryCard(item)).join('');
    } else {
        acceptedContainer.innerHTML = '<p class="empty-state">Belum ada request yang disetujui.</p>';
    }
}

// Filter completed requests (selesai)
const completedRequests = requestsData.filter(item => item.status === 'selesai');
const completedContainer = document.getElementById('completed-requests');
if (completedContainer) {
    if (completedRequests.length > 0) {
        completedContainer.innerHTML = completedRequests.map(item => renderHistoryCard(item)).join('');
    } else {
        completedContainer.innerHTML = '<p class="empty-state">Belum ada request yang selesai.</p>';
    }
}

enablePicker();
calculatePrice();
