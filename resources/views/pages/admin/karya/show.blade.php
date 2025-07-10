@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="m-0 font-weight-bold text-gradient">Detail Karya</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.admin.karya.index') }}">Karya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.admin.karya.index') }}" class="btn btn-gradient-secondary rounded-pill px-4 py-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Karya Preview -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4">
                    <!-- Media Preview Section -->
                    <div class="media-preview-container mb-5">
                        @if($karya->jenis_karya === 'video')
                        <div class="video-preview-wrapper rounded-lg">
                            <video controls class="w-100 rounded-lg shadow">
                                <source src="{{ asset($karya->video_karya) }}" type="video/mp4">
                                Browser tidak mendukung video.
                            </video>
                            <div class="video-overlay"></div>
                        </div>
                        @elseif($karya->jenis_karya === 'dokumen')
                        <div class="pdf-preview-wrapper rounded-lg shadow-sm">
                            <div class="pdf-preview-container">
                                <iframe src="{{ asset($karya->dokumen_karya) }}#toolbar=0&view=fitH" class="w-100 rounded-lg" style="height: 500px;"></iframe>
                            </div>
                            <div class="pdf-actions mt-3 text-center">
                                <a href="{{ asset($karya->dokumen_karya) }}" target="_blank" class="btn btn-gradient-primary rounded-pill px-4 py-2">
                                    <i class="bi bi-fullscreen me-1"></i> Buka Fullscreen
                                </a>
                                <a href="{{ asset($karya->dokumen_karya) }}" download class="btn btn-outline-primary rounded-pill px-4 py-2 ms-2">
                                    <i class="bi bi-download me-1"></i> Unduh Dokumen
                                </a>
                            </div>
                        </div>
                        @elseif($karya->jenis_karya === 'gambar' && $karya->gambarKaryas->isNotEmpty())
                        <div id="karyaCarousel" class="carousel slide gallery-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-lg shadow">
                                @foreach($karya->gambarKaryas as $index => $gambar)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset($gambar->nama_gambar) }}" class="d-block w-100" 
                                         style="max-height: 500px; object-fit: contain; background: #f8f9fa;" 
                                         alt="Gambar Karya {{ $karya->judul }}">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#karyaCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#karyaCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <div class="carousel-indicators-container">
                                <ol class="carousel-indicators">
                                    @foreach($karya->gambarKaryas as $index => $gambar)
                                    <li data-bs-target="#karyaCarousel" data-bs-slide-to="{{ $index }}" 
                                        class="{{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset($gambar->nama_gambar) }}" class="d-block w-100" 
                                             alt="Thumbnail {{ $index }}">
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Karya Information -->
                    <div class="karya-header mb-4">
                        <h2 class="mb-3 text-gradient">{{ $karya->judul }}</h2>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge rounded-pill bg-primary bg-gradient">{{ $karya->jenis_karya }}</span>
                            <span class="badge rounded-pill bg-{{ 
                                $karya->status === 'DISETUJUI' ? 'success' : 
                                ($karya->status === 'DITOLAK' ? 'danger' : 
                                ($karya->status === 'PERBAIKAN' ? 'warning' : 'secondary')) 
                            }} bg-gradient">
                                {{ $karya->status }}
                            </span>
                            @if($karya->kategori)
                            <span class="badge rounded-pill bg-info bg-gradient">{{ $karya->kategori->nama_kategori }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <!-- Pastikan CDN Trix sudah dimuat -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

                    <div class="karya-description mb-5">
                        <h5 class="mb-3 text-gradient">Deskripsi Karya</h5>
                        <div class="border rounded p-4 bg-light bg-opacity-10 trix-content">
                            {!! $karya->deskripsi !!}
                        </div>
                    </div>

                    <style>
                        /* Styling untuk konten Trix */
                        .trix-content {
                            font-family: inherit;
                            font-size: 1rem;
                            line-height: 1.5;
                            color: #333;
                        }
                        
                        .trix-content div, 
                        .trix-content p {
                            margin-bottom: 1em;
                        }
                        
                        .trix-content h1, 
                        .trix-content h2, 
                        .trix-content h3, 
                        .trix-content h4 {
                            margin: 1.5em 0 1em;
                            font-weight: bold;
                        }
                        
                        .trix-content h1 { font-size: 1.8em; }
                        .trix-content h2 { font-size: 1.5em; }
                        .trix-content h3 { font-size: 1.3em; }
                        .trix-content h4 { font-size: 1.1em; }
                        
                        .trix-content ul, 
                        .trix-content ol {
                            padding-left: 2em;
                            margin-bottom: 1em;
                        }
                        
                        .trix-content ul { list-style-type: disc; }
                        .trix-content ol { list-style-type: decimal; }
                        
                        .trix-content strong { font-weight: bold; }
                        .trix-content em { font-style: italic; }
                        
                        .trix-content a {
                            color: #3b82f6;
                            text-decoration: underline;
                        }
                        
                        .trix-content img {
                            max-width: 100%;
                            height: auto;
                            margin: 0.5em 0;
                        }
                    </style>

                    <!-- Info Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 info-card">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3"><i class="bi bi-person-circle me-2"></i> Informasi Pengunggah</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($karya->user->name) }}&background=random" 
                                                 class="rounded-circle" width="50" alt="Avatar">
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $karya->user->name }}</h5>
                                            <small class="text-muted d-block">{{ $karya->user->email }}</small>
                                            <small class="text-muted">Member since {{ $karya->user->created_at->format('M Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 info-card">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3"><i class="bi bi-info-circle me-2"></i> Informasi Karya</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                                                    <i class="bi bi-calendar"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Tanggal Pengajuan</small>
                                                    <p class="mb-0 fw-bold">
                                                        @php
                                                            \Carbon\Carbon::setLocale('id');
                                                        @endphp
                                                        {{ $karya->tanggal_pengajuan->translatedFormat('d F Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        @if($karya->tanggal_publish)
                                        <li class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                                                    <i class="bi bi-calendar-check"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Tanggal Publikasi</small>
                                                    <p class="mb-0 fw-bold">
                                                        {{ $karya->tanggal_publish->format('d F Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        <li class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-info bg-opacity-10 text-info me-3">
                                                    <i class="bi bi-eye"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Dilihat</small>
                                                    <p class="mb-0 fw-bold">{{ $karya->views }} kali</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                                                    <i class="bi bi-star"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Rating</small>
                                                    <div class="d-flex align-items-center">
                                                        <div class="rating-stars me-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="bi bi-star{{ $i <= floor($karya->avg_rating) ? '-fill' : ($i - 0.5 <= $karya->avg_rating ? '-half' : '') }} text-warning"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="fw-bold">{{ number_format($karya->avg_rating, 1) }}/5.0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($karya->status === 'DISETUJUI' && $karya->tags->isNotEmpty())
                     <div class="mt-3">
                        <h6 class="small text-muted mb-2">Tags Terpilih:</h6>
                        <div class="d-flex flex-wrap gap-2">
                           @foreach($karya->tags as $tag)
                           <span class="badge rounded-pill" style="background-color: blue; color: white;">
                                 <i class="bi bi-tag-fill me-1"></i>{{ $tag->nama_tag }}
                           </span>
                           @endforeach
                        </div>
                     </div>
                     @endif

                    @if($karya->status === 'PERBAIKAN' && $karya->keterangan_revisi)
                    <div class="revision-notes mb-4">
                        <div class="alert alert-warning border-0 shadow-sm">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <h5 class="mb-0">Keterangan Revisi</h5>
                            </div>
                            <div class="border rounded p-3 bg-white mt-2">
                                {!! $karya->keterangan_revisi !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Form -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-gradient"><i class="bi bi-patch-check me-2"></i>Validasi Karya</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.karya.update-status', $karya) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Status Saat Ini</label>
                            <div class="alert alert-{{ 
                                $karya->status === 'DISETUJUI' ? 'success' : 
                                ($karya->status === 'DITOLAK' ? 'danger' : 
                                ($karya->status === 'PERBAIKAN' ? 'warning' : 'secondary')) 
                            }} mb-0 d-flex align-items-center" style="border-radius: 12px;">
                                <i class="bi bi-{{ 
                                    $karya->status === 'DISETUJUI' ? 'check-circle' : 
                                    ($karya->status === 'DITOLAK' ? 'x-circle' : 
                                    ($karya->status === 'PERBAIKAN' ? 'exclamation-circle' : 'clock')) 
                                }} me-2"></i>
                                {{ $karya->status }}
                            </div>
                        </div>

                        @if($karya->status === 'MENUNGGU')
                        <div class="mb-4">
                            <label for="status" class="form-label">Ubah Status</label>
                            <select class="form-select rounded-pill shadow-sm" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="DISETUJUI">Disetujui</option>
                                <option value="DITOLAK">Ditolak</option>
                                <option value="PERBAIKAN">Perlu Perbaikan</option>
                            </select>
                        </div>

                        <!-- Form untuk Disetujui -->
                        <div id="disetujuiForm" class="mb-4" style="display: none;">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3 text-gradient"><i class="bi bi-calendar-plus me-2"></i>Pengaturan Publikasi</h6>
                                    <div class="mb-3">
                                        <label for="tanggal_publish" class="form-label">Tanggal Publikasi</label>
                                        <input type="date" class="form-control rounded-pill shadow-sm" id="tanggal_publish" name="tanggal_publish">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kategori_id" class="form-label">Kategori</label>
                                        <select class="form-select rounded-pill shadow-sm" id="kategori_id" name="kategori_id">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" 
                                                {{ $karya->kategori_id == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="mb-3 text-gradient"><i class="bi bi-tags me-2"></i>Tags (Maksimal 5)</h6>
                                    <div class="row g-2">
                                        @foreach($tags as $tag)
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input shadow-sm" type="checkbox" 
                                                       name="tags[]" value="{{ $tag->id }}"
                                                       id="tag-{{ $tag->id }}"
                                                       {{ $karya->tags->contains($tag->id) ? 'checked' : '' }}>
                                                <label class="form-check-label d-flex align-items-center" for="tag-{{ $tag->id }}">
                                                    <span class="tag-badge me-2" style="background-color: {{ $tag->warna }}"></span>
                                                    {{ $tag->nama_tag }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form untuk Perbaikan -->
                        <div id="perbaikanForm" class="mb-4" style="display: none;">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="mb-3 text-gradient"><i class="bi bi-chat-square-text me-2"></i>Keterangan Revisi</h6>
                                    <label for="keterangan_revisi" class="form-label">Berikan instruksi perbaikan yang jelas</label>
                                    <input id="keterangan_revisi" type="hidden" name="keterangan_revisi">
                                    <trix-editor input="keterangan_revisi" class="trix-content rounded shadow-sm"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <!-- Form untuk Ditolak -->
                        <div id="ditolakForm" class="mb-4" style="display: none;">
                            <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                        <h6 class="mb-0 text-danger">Konfirmasi Penolakan</h6>
                                    </div>
                                    <p class="mb-0 small">Karya ini akan ditolak dan tidak akan dipublikasikan. Pastikan Anda telah mempertimbangkan dengan matang.</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gradient-primary w-100 rounded-pill py-2 shadow-sm mt-3">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                        @else
                        <div class="card border-0 shadow-sm bg-light">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-check2-circle text-success display-5 mb-3"></i>
                                <h5 class="mb-2">Status Sudah Divalidasi</h5>
                                <p class="small text-muted mb-0">Karya ini telah melalui proses validasi dengan status {{ $karya->status }}.</p>
                                
                           
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trix Editor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
<!-- Trix Editor JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        --secondary-gradient: linear-gradient(135deg, #8e9eab 0%, #eef2f3 100%);
        --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f46b45 0%, #eea849 100%);
    }
    
    .text-gradient {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        display: inline-block;
    }
    
    .btn-gradient-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(58, 123, 213, 0.3);
    }
    
    .btn-gradient-secondary {
        background: var(--secondary-gradient);
        color: #495057;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-gradient-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(142, 158, 171, 0.3);
    }
    
    .badge.bg-gradient {
        color: white;
        font-weight: 500;
        padding: 6px 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .bg-primary.bg-gradient {
        background: var(--primary-gradient) !important;
    }
    
    .bg-success.bg-gradient {
        background: var(--success-gradient) !important;
    }
    
    .bg-danger.bg-gradient {
        background: var(--danger-gradient) !important;
    }
    
    .bg-warning.bg-gradient {
        background: var(--warning-gradient) !important;
    }
    
    .bg-info.bg-gradient {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%) !important;
    }
    
    .media-preview-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .video-preview-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .video-preview-wrapper video {
        display: block;
        background: #000;
    }
    
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 100%);
        pointer-events: none;
    }
    
    .pdf-preview-wrapper {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 15px;
        background: #f8f9fa;
    }
    
    .pdf-preview-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    
    .gallery-carousel {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .gallery-carousel .carousel-inner {
        border-radius: 12px;
        background: #f8f9fa;
    }
    
    .gallery-carousel .carousel-control-prev,
    .gallery-carousel .carousel-control-next {
        width: 40px;
        height: 40px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.2);
        border-radius: 50%;
        opacity: 0.8;
    }
    
    .gallery-carousel .carousel-control-prev:hover,
    .gallery-carousel .carousel-control-next:hover {
        opacity: 1;
    }
    
    .carousel-indicators-container {
        position: relative;
        margin-top: 15px;
    }
    
    .carousel-indicators {
        position: static;
        margin: 0;
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    
    .carousel-indicators li {
        width: 60px;
        height: 60px;
        margin-right: 10px;
        margin-bottom: 10px;
        border-radius: 8px;
        overflow: hidden;
        opacity: 0.7;
        transition: all 0.3s;
        border: none;
    }
    
    .carousel-indicators li:hover, 
    .carousel-indicators li.active {
        opacity: 1;
        transform: scale(1.05);
    }
    
    .carousel-indicators li img {
        height: 100%;
        object-fit: cover;
    }
    
    .info-card {
        border-radius: 12px;
        transition: all 0.3s;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .tag-badge {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }
    
    .rating-stars {
        font-size: 1rem;
        letter-spacing: 2px;
    }
    
    .trix-content {
        min-height: 150px;
        background: white;
        border: 1px solid #dee2e6 !important;
        border-radius: 12px !important;
        padding: 12px !important;
    }
    
    .trix-button-group--file-tools {
        display: none !important;
    }
    
    .rounded-lg {
        border-radius: 12px !important;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-select, .form-control {
        border-radius: 50px !important;
        padding: 10px 20px;
    }
    
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const disetujuiForm = document.getElementById('disetujuiForm');
    const perbaikanForm = document.getElementById('perbaikanForm');
    const ditolakForm = document.getElementById('ditolakForm');
    const statusForm = document.getElementById('statusForm');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            disetujuiForm.style.display = 'none';
            perbaikanForm.style.display = 'none';
            ditolakForm.style.display = 'none';
            
            if (this.value === 'DISETUJUI') {
                disetujuiForm.style.display = 'block';
            } else if (this.value === 'PERBAIKAN') {
                perbaikanForm.style.display = 'block';
            } else if (this.value === 'DITOLAK') {
                ditolakForm.style.display = 'block';
            }
        });
        
        // Set default date for tanggal_publish to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_publish').value = today;
    }
    
    // Limit tags selection to 5
    const tagCheckboxes = document.querySelectorAll('input[name="tags[]"]');
    tagCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="tags[]"]:checked').length;
            if (checkedCount > 5) {
                this.checked = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Maksimal 5 tag yang dapat dipilih',
                    confirmButtonColor: '#0d6efd',
                });
            }
        });
    });

    // Form submission with SweetAlert
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const status = document.getElementById('status').value;
            
            Swal.fire({
                title: 'Konfirmasi Perubahan Status',
                html: `<div class="text-center py-3">
                          <i class="bi bi-exclamation-circle display-4 text-${ 
                              status === 'DISETUJUI' ? 'success' : 
                              status === 'DITOLAK' ? 'danger' : 'warning'
                          } mb-3"></i>
                          <p>Anda yakin ingin mengubah status karya menjadi <strong>${status}</strong>?</p>
                       </div>`,
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});
</script>
@endsection