<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <title>Proses Pickup - {{ $pickup->user->name }}</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --success-color: #10b981;
            --warn-color: #ffee00;
            --danger-color: #ef4444;
            --bg-color: #f8fafc;
            --text-color: #1e293b;
            --border-color: #e2e8f0;
            --gray-color: #64748b;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #ffee00 0%, #ffcc00 100%);
            padding: 30px;
            text-align: center;
        }

        .card-header h1 {
            font-size: 26px;
            color: #333;
            margin-bottom: 5px;
        }

        .card-header p {
            color: #666;
            font-size: 14px;
        }

        .card-body {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f1f5f9;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .avatar-small {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            margin-right: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-color);
            text-transform: capitalize;
            margin-bottom: 5px;
        }

        .user-detail {
            font-size: 13px;
            color: var(--gray-color);
            line-height: 1.6;
        }

        .user-detail strong {
            color: var(--text-color);
            font-weight: 600;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .detail-item {
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .detail-item label {
            display: block;
            font-size: 11px;
            color: var(--gray-color);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .detail-item span {
            display: block;
            font-size: 14px;
            color: var(--text-color);
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .status-badge.menunggu {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-badge.disetujui {
            background-color: #dbeafe;
            color: #0c4a6e;
        }

        .status-badge.selesai {
            background-color: #d1fae5;
            color: #065f46;
        }

        .bukti-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
        }

        .btn-back {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: var(--border-color);
            color: var(--text-color);
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #cbd5e1;
        }

        /* Styles for upload form */
        .form-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid var(--border-color);
        }

        .form-section h3 {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-color);
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: block;
            padding: 40px;
            background: #f1f5f9;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            color: var(--gray-color);
        }

        .file-input-wrapper:hover .file-input-label {
            background: #e2e8f0;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .file-input-wrapper input[type="file"]:focus+.file-input-label {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .file-preview {
            margin-top: 15px;
            display: none;
        }

        .file-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .file-name {
            margin-top: 8px;
            font-size: 13px;
            color: var(--gray-color);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .button-group button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .button-group button[type="submit"] {
            background-color: var(--success-color);
            color: white;
        }

        .button-group button[type="submit"]:hover {
            background-color: #059669;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .button-group button[type="button"] {
            background-color: var(--border-color);
            color: var(--text-color);
        }

        .button-group button[type="button"]:hover {
            background-color: #cbd5e1;
        }

        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <x-header>
        <x-slot:title>
            Serahkan Sampahmu Disini
        </x-slot:title>
    </x-header>

    <div class="container">
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h1>📦 Detail/Proses Pickup</h1>
                <p>Status dan bukti penjemputan sampah</p>
            </div>

            <div class="card-body">
                <div class="info-section">
                    <div class="info-row">
                        <img class="avatar-small" src="{{ asset('assets/' . ($pickup->user->avatar ?? 'avatar_5.jpeg')) }}" alt="{{ $pickup->user->name }}">
                        <div class="user-info">
                            <div class="user-name">{{ $pickup->user->name }}</div>
                            <div class="user-detail">
                                <strong>Email:</strong> {{ $pickup->user->email }}<br>
                                <strong>Lokasi:</strong> {{ $pickup->lokasi }}<br>
                                <strong>Koordinat:</strong> {{ $pickup->koordinat }}
                            </div>
                            <span class="status-badge {{ strtolower($pickup->status) }}">Status: {{ $pickup->status }}</span>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Tanggal Permintaan</label>
                            <span>{{ $pickup->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Catatan</label>
                            <span>{{ $pickup->notes }}</span>
                        </div>
                        @if ($pickup->status === 'selesai' && $pickup->updated_at)
                            <div class="detail-item">
                                <label>Waktu Selesai</label>
                                <span>{{ $pickup->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    @if ($pickup->status === 'selesai' && $pickup->bukti)
                        <div style="margin-top: 25px;">
                            <h3 style="font-size: 16px; margin-bottom: 10px;">Bukti Pickup</h3>
                            <img class="bukti-img" src="{{ asset('assets/bukti_pickup/' . $pickup->bukti) }}" alt="Bukti Pickup">
                        </div>
                    @endif
                </div>

                @if ($pickup->status !== 'selesai' && auth()->user()->role === 'admin')
                    <form method="POST" action="{{ route('pickup.process.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-section">
                            <h3>Unggah Bukti Pickup</h3>
                            
                            <input type="hidden" name="pickup_id" value="{{ $pickup->id }}">

                            <div class="input-group">
                                <label>Foto Bukti Pickup *</label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="bukti" name="bukti" accept="image/*" required onchange="previewFile(this)">
                                    <label for="bukti" class="file-input-label">
                                        📸 Klik atau seret gambar ke sini<br>
                                        <span style="font-size: 12px; color: var(--gray-color);">JPG, PNG, atau GIF (Max 5MB)</span>
                                    </label>
                                </div>
                                <div class="file-preview" id="preview">
                                    <img id="previewImg" src="" alt="Preview">
                                    <div class="file-name" id="fileName"></div>
                                </div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" onclick="window.location.href='{{ route('agen.dashboard') }}'">Batal</button>
                            <button type="submit">✓ Unggah Bukti Pickup</button>
                        </div>
                    </form>
                @else
                    <a href="{{ auth()->user()->role === 'admin' ? route('agen.dashboard') : route('user.dashboard') }}" class="btn-back">Kembali ke Dashboard</a>
                @endif
            </div>
        </div>
    </div>

    <x-footer></x-footer>

    <script>
        function previewFile(input) {
            const preview = document.getElementById('preview');
            const previewImg = document.getElementById('previewImg');
            const fileName = document.getElementById('fileName');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    fileName.textContent = 'File: ' + input.files[0].name + ' (' + (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Drag and drop support
        const fileInputWrapper = document.querySelector('.file-input-wrapper');
        const fileInput = document.getElementById('bukti');

        if (fileInputWrapper && fileInput) {
            fileInputWrapper.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileInputWrapper.querySelector('.file-input-label').style.background = '#e2e8f0';
                fileInputWrapper.querySelector('.file-input-label').style.borderColor = '#2563eb';
            });

            fileInputWrapper.addEventListener('dragleave', (e) => {
                e.preventDefault();
                fileInputWrapper.querySelector('.file-input-label').style.background = '#f1f5f9';
                fileInputWrapper.querySelector('.file-input-label').style.borderColor = '#e2e8f0';
            });

            fileInputWrapper.addEventListener('drop', (e) => {
                e.preventDefault();
                fileInputWrapper.querySelector('.file-input-label').style.background = '#f1f5f9';
                fileInputWrapper.querySelector('.file-input-label').style.borderColor = '#e2e8f0';
                fileInput.files = e.dataTransfer.files;
                previewFile(fileInput);
            });
        }
    </script>
</body>

</html>
