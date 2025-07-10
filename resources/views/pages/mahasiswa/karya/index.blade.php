@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Karya Saya</h3>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card shadow-sm mb-4" style="border-radius: 12px; border: none;">
        <div class="card-body p-3">
            <div class="btn-group shadow-sm" role="group">
                <a href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'all']) }}" 
                   class="btn btn-{{ request('tab', 'all') === 'all' ? 'primary' : 'light' }} px-4 py-2">
                    Semua
                </a>
                <a href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'menunggu']) }}" 
                   class="btn btn-{{ request('tab') === 'menunggu' ? 'primary' : 'light' }} px-4 py-2">
                    Menunggu
                </a>
                <a href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'disetujui']) }}" 
                   class="btn btn-{{ request('tab') === 'disetujui' ? 'primary' : 'light' }} px-4 py-2">
                    Diterima
                </a>
                <a href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'ditolak']) }}" 
                   class="btn btn-{{ request('tab') === 'ditolak' ? 'primary' : 'light' }} px-4 py-2">
                    Ditolak
                </a>
                <a href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'perbaikan']) }}" 
                   class="btn btn-{{ request('tab') === 'perbaikan' ? 'primary' : 'light' }} px-4 py-2">
                    Perbaikan
                </a>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-{{ in_array(request('tab'), ['video', 'dokumen', 'gambar']) ? 'primary' : 'light' }} px-4 py-2 dropdown-toggle" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Jenis Karya
                    </button>
                    <ul class="dropdown-menu" style="z-index:9999999;">
                        <li>
                            <a class="dropdown-item {{ request('tab') === 'video' ? 'active' : '' }}" 
                               href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'video']) }}">
                                Video
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('tab') === 'dokumen' ? 'active' : '' }}" 
                               href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'dokumen']) }}">
                                Dokumen
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('tab') === 'gambar' ? 'active' : '' }}" 
                               href="{{ route('mahasiswa.mahasiswa.karya.index', ['tab' => 'gambar']) }}">
                                Gambar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <style>
        .text-gradient-primary {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .media-preview {
            position: relative;
            overflow: hidden;
        }
        
        .media-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
        }
        
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3rem;
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .media-preview:hover .play-button {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }
        
        .avatar-initials {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-weight: 600;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            color: #e9ecef;
            margin-bottom: 1rem;
        }
        
        .carousel-control-prev, .carousel-control-next {
            width: 30px;
            height: 30px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
        }
        
        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }
    </style>
    <!-- Karya Grid -->
    <div class="row g-4">
        @forelse($karyas as $karya)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm hover-lift" style="border-radius: 12px; border: none;">
                <!-- Media Preview -->
                @if($karya->jenis_karya === 'video')
                <div class="card-img-top position-relative media-preview" style="height: 180px;">
                    <div class="w-100 h-100 bg-dark rounded-top" style="overflow: hidden;">
                        <video class="w-100 h-100 object-fit-cover" style="opacity: 0.9;">
                            <source src="{{ $karya->video_karya }}" type="video/mp4">
                        </video>
                        <div class="play-button">
                            <i class="bi bi-play-circle-fill"></i>
                        </div>
                    </div>
                    <span class="media-badge">
                        <i class="bi bi-camera-reels me-1"></i>VIDEO
                    </span>
                </div>
                @elseif($karya->jenis_karya === 'dokumen')
                <div class="card-img-top media-preview d-flex align-items-center justify-content-center" 
                     style="height: 180px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="text-center p-4">
                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <span class="media-badge">
                        <i class="bi bi-file-earmark-text me-1"></i>DOKUMEN
                    </span>
                </div>
                @elseif($karya->jenis_karya === 'gambar' && $karya->gambarKaryas->isNotEmpty())
                <div id="carousel-{{ $karya->id }}" class="carousel slide card-img-top media-preview" style="height: 180px;">
                    <div class="carousel-inner h-100 rounded-top">
                        @foreach($karya->gambarKaryas as $index => $gambar)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100">
                            <img src="{{ $gambar->nama_gambar }}" class="d-block w-100 h-100 object-fit-cover" 
                                 alt="Gambar Karya {{ $karya->judul }}">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $karya->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $karya->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <span class="media-badge">
                        <i class="bi bi-images me-1"></i>GAMBAR
                    </span>
                </div>
                @endif

                <!-- Card Body -->
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-2">{{ Str::limit($karya->judul, 50) }}</h5>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-xs me-2">
                            @if($karya->user->profile_photo_path)
                            <img src="{{ $karya->user->profile_photo_path }}" class="rounded-circle" width="24" height="24">
                            @else
                            <span class="avatar-initials bg-primary text-white">
                                @php
                                    // Generate initials based on the given rules
                                    $nameParts = explode(' ', trim($karya->user->name));
                                    $initials = '';

                                    if (count($nameParts) === 1) {
                                        // Single word: take first two letters (uppercase)
                                        $initials = strtoupper(substr($nameParts[0], 0, 2));
                                    } else {
                                        // Multiple words: take first letter of first and last word
                                        $initials = strtoupper(substr($nameParts[0], 0, 1));
                                        $lastPart = end($nameParts);
                                        $initials .= strtoupper(substr($lastPart, 0, 1));
                                    }
                                    echo $initials;
                                @endphp
                            </span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $karya->user->name }}</small>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <small class="text-muted">Dilihat</small>
                            <p class="mb-0 fw-bold">{{ $karya->views }} kali</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Rating</small>
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="rating-stars me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= floor($karya->avg_rating) ? '-fill' : ($i - 0.5 <= $karya->avg_rating ? '-half' : '') }} text-warning"></i>
                                    @endfor
                                </div>
                                <span class="fw-bold">{{ number_format($karya->avg_rating, 1) }}/5.0</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge rounded-pill bg-light text-dark">
                            <i class="bi bi-calendar me-1"></i>
                            @php
                                \Carbon\Carbon::setLocale('id');
                            @endphp
                            {{ $karya->tanggal_pengajuan->translatedFormat('d M Y') }}
                        </span>
                        <a href="{{ route('mahasiswa.karya.show', $karya) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i>Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 12px; border: none;">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon">
                        <i class="bi bi-collection"></i>
                    </div>
                    <h5 class="mt-3 text-muted">Tidak ada karya</h5>
                    <p class="text-muted">Belum ada karya yang tersedia untuk kategori ini</p>
                  
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $karyas->links() }}
    </div>
</div>

<style>
    .btn-group .btn {
        transition: all 0.3s ease;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .carousel-control-prev, .carousel-control-next {
        width: 30px;
        height: 30px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
    }
    
    .badge {
        font-weight: 500;
        padding: 5px 10px;
    }
</style>
@endsection