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
    </style>
</head>

<body>
    <x-header>
        <x-slot:title>
            Serahkan Sampahmu Disini
        </x-slot:title>
    </x-header>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>📦 Detail Pickup</h1>
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

                <a href="{{ auth()->user()->role === 'admin' ? route('agen.dashboard') : route('user.dashboard') }}" class="btn-back">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <x-footer></x-footer>
</body>

</html>
