<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Karya - {{ date('d-m-Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #0d6efd;
            margin-bottom: 5px;
        }
        .header p {
            margin: 0;
            color: #6c757d;
        }
        .info-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #0d6efd;
            color: white;
            text-align: left;
            padding: 8px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        .badge-info {
            background-color: #0dcaf0;
            color: #000;
        }
        .badge-success {
            background-color: #198754;
            color: #fff;
        }
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Karya</h1>
        <p>Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="info-box">
        <div class="info-item">
            <span class="info-label">Filter Status:</span>
            <span>{{ $filter['status'] }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Filter Kategori:</span>
            <span>{{ $filter['kategori'] }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Filter Tahun:</span>
            <span>{{ $filter['tahun'] }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Data:</span>
            <span>{{ $karyas->count() }} karya</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Pembuat</th>
                <th>Status</th>
                <th>Tahun</th>
                <th>Tanggal Pengajuan</th>
                <th>Views</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyas as $karya)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $karya->judul }}</td>
                <td>{{ $karya->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $karya->user->name }}</td>
                <td>
                    @php
                        $statusText = [
                            'MENUNGGU' => 'Menunggu',
                            'PERBAIKAN' => 'Perbaikan',
                            'DISETUJUI' => 'Disetujui',
                            'DITOLAK' => 'Ditolak'
                        ][$karya->status] ?? $karya->status;
                        
                        $badgeClass = [
                            'MENUNGGU' => 'badge-warning',
                            'PERBAIKAN' => 'badge-info',
                            'DISETUJUI' => 'badge-success',
                            'DITOLAK' => 'badge-danger'
                        ][$karya->status];
                    @endphp
                    <span class="{{ $badgeClass }}">{{ $statusText }}</span>
                </td>
                <td>{{ $karya->tahun }}</td>
                <td>{{ $karya->tanggal_pengajuan->format('d/m/Y') }}</td>
                <td>{{ number_format($karya->views) }}</td>
                <td>{{ number_format($karya->avg_rating, 1) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh Sistem Pengarsipan & Showcase &copy; {{ date('Y') }}
    </div>
</body>
</html>