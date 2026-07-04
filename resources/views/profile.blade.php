<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <title>Profil Saya - {{ auth()->user()->name }}</title>
    <style>
        body {
            background-color: #f8fafc;
            color: #333;
            margin: 0;
        }

        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .profile-header {
            background: linear-gradient(135deg, #ffee00 0%, #ffcc00 100%);
            height: 120px;
            position: relative;
        }

        .profile-content {
            padding: 0 40px 40px 40px;
            text-align: center;
        }

        .avatar-wrapper {
            position: relative;
            margin-top: -60px;
            display: inline-block;
        }

        .avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 5px solid white;
            background: #eee;
            object-fit: cover;
            cursor: pointer;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-overlay-icon {
            font-size: 40px;
            color: white;
        }

        #avatarInput {
            display: none;
        }

        .upload-status {
            margin-top: 10px;
            font-size: 12px;
            text-align: center;
            min-height: 20px;
        }

        .upload-status.uploading {
            color: #3b82f6;
        }

        .upload-status.success {
            color: #22c55e;
        }

        .upload-status.error {
            color: #ef4444;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e2e8f0;
            margin-top: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            text-align: left;
        }

        .info-item {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item label {
            display: block;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        .info-item span {
            font-size: 16px;
            color: #1e293b;
        }

        .bio-section {
            margin-top: 20px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
            font-style: italic;
        }

        .btn-edit {
            margin-top: 30px;
            display: inline-block;
            padding: 12px 25px;
            background: #1a1a1a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-edit:hover {
            background: #333;
        }

        .btn-back-dashboard {
            margin-top: 30px;
            margin-left: 10px;
            display: inline-block;
            padding: 12px 25px;
            background: #e2e8f0;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: bold;
        }

        .btn-back-dashboard:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body>

    <x-header>
        <x-slot:title>
            Serahkan Sampahmu Disini
        </x-slot:title>
    </x-header>

    <div class="profile-container">
        <div class="profile-header"></div>

        <div class="profile-content">
            <div class="avatar-wrapper" id="avatarWrapper">
                <img src="{{ asset('assets/' . (auth()->user()->avatar ?? 'user.gif')) }}" alt="Avatar" class="avatar" id="avatarImage">
                <div class="avatar-overlay">
                    <span class="avatar-overlay-icon">📷</span>
                </div>
            </div>
            <div class="upload-status" id="uploadStatus"></div>
            <input type="file" id="avatarInput" accept="image/*">

            <h1 style="margin-top: 15px;">{{ auth()->user()->name }}</h1>
            <p style="color: #64748b;">@{{ auth()->user()->username }}</p>
            <span class="badge">{{ auth()->user()->role }}</span>

            @if (auth()->user()->bio)
                <div class="bio-section">
                    "{{ auth()->user()->bio }}"
                </div>
            @endif

            <div class="info-grid">
                <div class="info-item">
                    <label>Email</label>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="info-item">
                    <label>Lokasi</label>
                    <span>{{ auth()->user()->lokasi ?? 'Belum diatur' }}</span>
                </div>
                <div class="info-item">
                    <label>Koordinat</label>
                    <span>{{ auth()->user()->koordinat ?? 'Tidak tersedia' }}</span>
                </div>
                <div class="info-item">
                    <label>ID Pengguna</label>
                    <span>#{{ auth()->id() }}</span>
                </div>
            </div>

            <button onclick="triggerUpload()" class="btn-edit">Pilih Avatar</button>
            <button onclick="uploadAvatar()" class="btn-edit" id="btnUpload" style="display: none; background: #22c55e;">Upload Avatar</button>
            <a href="{{ auth()->user()->role === 'admin' ? route('agen.dashboard') : route('user.dashboard') }}" class="btn-back-dashboard">Dashboard</a>
        </div>
    </div>

    <x-footer></x-footer>

    <script>
        const avatarWrapper = document.getElementById('avatarWrapper');
        const avatarInput = document.getElementById('avatarInput');
        const avatarImage = document.getElementById('avatarImage');
        const uploadStatus = document.getElementById('uploadStatus');
        const btnUpload = document.getElementById('btnUpload');

        let selectedFile = null;

        // Click avatar or overlay to open file picker
        avatarWrapper.addEventListener('click', function() {
            avatarInput.click();
        });

        function triggerUpload() {
            avatarInput.click();
        }

        // Handle file selection
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (!file) return;

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                showStatus('Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF', 'error');
                return;
            }

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showStatus('Ukuran file terlalu besar. Maksimal 5MB', 'error');
                return;
            }

            selectedFile = file;
            avatarImage.src = URL.createObjectURL(file);
            btnUpload.style.display = 'inline-block';
            showStatus('Gambar dipilih. Klik tombol "Upload Avatar" untuk menyimpan.', 'uploading');
        });

        function uploadAvatar() {
            if (!selectedFile) {
                showStatus('Silahkan pilih gambar terlebih dahulu', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('avatar', selectedFile);
            formData.append('_token', '{{ csrf_token() }}');

            showStatus('Mengupload...', 'uploading');

            fetch('{{ route("profile.avatar") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatus('✓ Foto profil berhasil diperbarui', 'success');
                        btnUpload.style.display = 'none';
                        selectedFile = null;
                    } else {
                        showStatus('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showStatus('Terjadi kesalahan saat mengupload', 'error');
                });
        }

        function showStatus(message, type) {
            uploadStatus.textContent = message;
            uploadStatus.className = 'upload-status ' + type;

            if (type === 'success') {
                setTimeout(() => {
                    uploadStatus.textContent = '';
                    uploadStatus.className = 'upload-status';
                }, 3000);
            }
        }
    </script>

</body>

</html>