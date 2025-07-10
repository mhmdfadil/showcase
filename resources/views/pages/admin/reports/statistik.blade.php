@extends('layouts.app')

@section('title', 'Statistik dan Laporan')

@section('content')
<div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Statistik dan Laporan</h3>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-funnel me-2"></i>Filter Laporan</h5>
            <form action="{{ route('admin.laporan.statistik') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="chart_type" class="form-label">Jenis Grafik:</label>
                    <select name="chart_type" id="chart_type" class="form-select">
                        <option value="bar" {{ request('chart_type') == 'bar' ? 'selected' : '' }}>Bar</option>
                        <option value="pie" {{ request('chart_type') == 'pie' ? 'selected' : '' }}>Pie</option>
                        <option value="line" {{ request('chart_type') == 'line' ? 'selected' : '' }}>Line</option>
                        <option value="radar" {{ request('chart_type') == 'radar' ? 'selected' : '' }}>Radar</option>
                        <option value="doughnut" {{ request('chart_type') == 'doughnut' ? 'selected' : '' }}>Doughnut</option>
                        <option value="polarArea" {{ request('chart_type') == 'polarArea' ? 'selected' : '' }}>Polar Area</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_by" class="form-label">Filter By:</label>
                    <select name="filter_by" id="filter_by" class="form-select">
                        <option value="status" {{ request('filter_by') == 'status' ? 'selected' : '' }}>Status Karya</option>
                        <option value="kategori" {{ request('filter_by') == 'kategori' ? 'selected' : '' }}>Kategori</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat me-2"></i>Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-pie-chart me-2"></i>Grafik Statistik</h5>
                    <canvas id="statistikChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-info-circle me-2"></i>Ringkasan Statistik</h5>
                    
                    <div class="stat-summary mb-4 text-center">
                        <h6><i class="bi bi-collection me-2"></i>Total Karya</h6>
                        <h3 class="display-6">{{ number_format($totalKarya) }}</h3>
                    </div>
                    
                    <hr>
                    
                    <h6><i class="bi bi-tags me-2"></i>Karya per Status:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($karyaPerStatus as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{ $item->status }}
                            <span class="badge bg-primary rounded-pill">{{ $item->total }}</span>
                        </li>
                        @endforeach
                    </ul>
                    
                    <hr>
                    
                    <h6><i class="bi bi-folder me-2"></i>Karya per Kategori:</h6>
                    <ul class="list-group list-group-flush">
                        @foreach($karyaPerKategori as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{ $item->nama_kategori ?? '-' }}
                            <span class="badge bg-success rounded-pill">{{ $item->karyas_count }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Data Karya</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.laporan.export.pdf') }}?{{ http_build_query(request()->query()) }}">
                                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.laporan.export.csv') }}?{{ http_build_query(request()->query()) }}">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i> CSV
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pembuat</th>
                            <th>Status</th>
                            <th>Tahun</th>
                            <th>Views</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyas as $karya)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $karya->judul }}</td>
                            <td>{{ $karya->kategori->nama_kategori ?? '-'}}</td>
                            <td>{{ $karya->user->name }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'MENUNGGU' => 'warning',
                                        'PERBAIKAN' => 'info',
                                        'DISETUJUI' => 'success',
                                        'DITOLAK' => 'danger'
                                    ][$karya->status];
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $karya->status }}
                                </span>
                            </td>
                            <td>{{ $karya->tahun }}</td>
                            <td>{{ number_format($karya->views) }}</td>
                            <td>{{ number_format($karya->avg_rating, 1) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data karya</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart
        const chartData = @json($chartData);
        const chartType = '{{ $chartType }}';
        const filterBy = '{{ $filterBy }}';
        
        // Siapkan data untuk ChartJS
        const labels = chartData.map(item => item.label);
        const data = chartData.map(item => item.value);
        
        // Warna untuk chart
        const backgroundColors = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 205, 86, 0.7)'
        ];
        
        // Konfigurasi chart
        const ctx = document.getElementById('statistikChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: `Jumlah Karya (${filterBy})`,
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} karya`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .stat-summary {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
    }
    .table-responsive {
        min-height: 300px;
    }
</style>
@endsection

