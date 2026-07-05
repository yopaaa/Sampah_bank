<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon/truck.ico">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/proses_pickup.css">
    <title>Proses Pickup - {{ $pickup->user->name }}</title>
</head>

<body>
    <x-header>
        <x-slot:title>
            Bank Sampah Digital
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

    <script src="/js/proses_pickup.js"></script>
</body>

</html>
