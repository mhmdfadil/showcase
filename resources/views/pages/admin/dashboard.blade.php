@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Dashboard</h3>
            <p class="text-muted mb-1" style="font-size: 18px">Selamat datang kembali, <span class="text-primary fw-medium">{{ $user->name ?? 'Admin'}}!</span></p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2 d-flex align-items-center">
                <i class="bi bi-calendar me-2 text-primary fs-5"></i>
                <span class="fw-semibold">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Pengguna</h6>
                            <h3 class="mb-0">{{ $totalUsers }}</h3>
                        </div>
                        <div class="bg-light-primary rounded-circle p-3">
                            <i class="bi bi-people-fill fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light-success text-success">
                            <i class="bi bi-arrow-up"></i> {{ $activeUsers }} aktif
                        </span>
                        <span class="badge bg-light-primary text-primary ms-1">
                            <i class="bi bi-person-plus"></i> {{ $newUsers }} baru
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Karya</h6>
                            <h3 class="mb-0">{{ $totalKarya }}</h3>
                        </div>
                        <div class="bg-light-warning rounded-circle p-3">
                            <i class="bi bi-journal-bookmark-fill fs-3 text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light-success text-success">
                            <i class="bi bi-check-circle"></i> {{ $publishedKarya }} publish
                        </span>
                        <span class="badge bg-light-warning text-warning ms-1">
                            <i class="bi bi-hourglass"></i> {{ $pendingKarya }} pending
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Kategori</h6>
                            <h3 class="mb-0">{{ $jumlahKategori }}</h3>
                        </div>
                        <div class="bg-light-info rounded-circle p-3">
                            <i class="bi bi-tags-fill fs-3 text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light-info text-info">
                            Top: {{ $categories->first()->nama_kategori ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 hover-scale">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Rating Rata-rata</h6>
                            <h3 class="mb-0">{{ $averageRating }}/5</h3>
                        </div>
                        <div class="bg-light-danger rounded-circle p-3">
                            <i class="bi bi-star-fill fs-3 text-danger"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light-danger text-danger">
                            Top: {{ $topRatedKarya->first()->judul ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Karya Status Chart -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Karya</h6>
                    <div>
                        <a href="{{route('admin.laporan.statistik')}}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-bar-chart-line me-1"></i> Laporan Lengkap
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="karyaStatusChart" height="250"></canvas>
                </div>
            </div>
            
            <!-- Recent Karya -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Karya Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                               
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentKarya as $karya)
                                <tr>
                                    <td>{{ Str::limit($karya->judul, 30) }}</td>
                                    <td>{{ $karya->kategori->nama_kategori ?? '-' }}</td>
                                    <td>
                                        @if($karya->status == 'DISETUJUI')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif($karya->status == 'MENUNGGU')
                                            <span class="badge bg-info text-dark">Menunggu</span>
                                        @elseif($karya->status == 'PERBAIKAN')
                                            <span class="badge bg-warning text-dark">Perbaikan</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $karya->created_at->format('d M Y') }}</td>
                                   
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada karya terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Popular Categories -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori Populer</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-folder me-2 text-primary"></i>
                                {{ $category->nama_kategori }}
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $category->karyas_count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Recent Comments -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Komentar Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentComments as $comment)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                <small class="text-muted">{{ $comment->waktu_komen->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit($comment->komentar, 50) }}</p>
                            <small class="text-muted">On: {{ $comment->karya->judul }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- User Activity -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Anda</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($userActivity as $activity)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Login</h6>
                                <small class="text-muted">{{ Carbon\Carbon::createFromTimestamp($activity->last_activity)->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-muted small">
                                <i class="bi bi-globe me-1"></i> {{ $activity->ip_address }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Karya Status Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('karyaStatusChart').getContext('2d');
        const karyaStatusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Published', 'Pending', 'Rejected'],
                datasets: [{
                    label: 'Jumlah Karya',
                    data: [{{ $publishedKarya }}, {{ $pendingKarya }}, {{ $rejectedKarya }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>


<style>
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.05) !important;
    }
    .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.05) !important;
    }
    .bg-pink {
        background-color: #ec4899 !important;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }
    .card-title {
        font-weight: 600;
    }
    .table th {
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
</style>
@endsection