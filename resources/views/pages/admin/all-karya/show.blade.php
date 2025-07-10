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
                            <div class="ratio ratio-16x9">
                                <video controls class="w-100 rounded-lg shadow">
                                    <source src="{{ asset($karya->video_karya) }}" type="video/mp4">
                                    Browser tidak mendukung video.
                                </video>
                            </div>
                            <div class="video-overlay"></div>
                        </div>
                        @elseif($karya->jenis_karya === 'dokumen')
                        <div class="pdf-preview-wrapper rounded-lg shadow-sm">
                            <div class="text-center py-5 border rounded" style="background: #f8f9fa;">
                                <i class="bi bi-file-earmark-pdf-fill text-danger display-1"></i>
                                <div class="pdf-actions mt-4">
                                    <a href="{{ asset($karya->dokumen_karya) }}" target="_blank" class="btn btn-gradient-primary rounded-pill px-4 py-2">
                                        <i class="bi bi-fullscreen me-1"></i> Buka Fullscreen
                                    </a>
                                    <a href="{{ asset($karya->dokumen_karya) }}" download class="btn btn-outline-primary rounded-pill px-4 py-2 ms-2">
                                        <i class="bi bi-download me-1"></i> Unduh Dokumen
                                    </a>
                                </div>
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
                            @foreach($karya->tags as $tag)
                            <span class="badge rounded-pill" style="background-color: blue; color: white;">
                                <i class="bi bi-tag-fill me-1"></i>{{ $tag->nama_tag }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    @auth
                        @if(!$userRating)
                            <!-- Rating Button -->
                            <button class="btn btn-outline-warning rounded-pill px-3 py-1 mb-3" data-bs-toggle="modal" data-bs-target="#ratingModal">
                                <i class="bi bi-star-fill me-1"></i> Beri Rating
                            </button>
                        @else
                            <!-- Show user's rating -->
                            <button class="btn btn-warning rounded-pill px-3 py-1 mb-3" disabled>
                                <i class="bi bi-star-fill me-1"></i> Anda memberi {{ $userRating->nilai_rate }} bintang
                            </button>
                        @endif

                        <!-- Rating Modal -->
                        <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ratingModalLabel">Beri Rating Karya Ini</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.akarya.rate', $karya) }}" method="POST">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <div class="rating-stars mb-3" style="font-size: 2rem;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star cursor-pointer" data-rating="{{ $i }}"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="selectedRating" required>
                                            <p class="text-muted">Pilih rating dari 1 (buruk) sampai 5 (sempurna)</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Rating</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const stars = document.querySelectorAll('.rating-stars .bi-star');
                        const selectedRating = document.getElementById('selectedRating');
                        
                        stars.forEach(star => {
                            star.addEventListener('click', function() {
                                const rating = this.getAttribute('data-rating');
                                selectedRating.value = rating;
                                
                                // Update star display
                                stars.forEach((s, index) => {
                                    if (index < rating) {
                                        s.classList.remove('bi-star');
                                        s.classList.add('bi-star-fill', 'text-warning');
                                    } else {
                                        s.classList.remove('bi-star-fill', 'text-warning');
                                        s.classList.add('bi-star');
                                    }
                                });
                            });
                            
                            star.addEventListener('mouseover', function() {
                                const rating = this.getAttribute('data-rating');
                                
                                stars.forEach((s, index) => {
                                    if (index < rating) {
                                        s.classList.add('text-warning');
                                    } else {
                                        s.classList.remove('text-warning');
                                    }
                                });
                            });
                            
                            star.addEventListener('mouseout', function() {
                                const currentRating = selectedRating.value;
                                
                                stars.forEach((s, index) => {
                                    if (!currentRating || index >= currentRating) {
                                        s.classList.remove('text-warning');
                                    }
                                });
                            });
                        });
                    });
                    </script>

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
                                                @php
                                                            \Carbon\Carbon::setLocale('id');
                                                        @endphp
                                            <small class="text-muted">Member since {{ $karya->user->created_at->translatedFormat('M Y') }}</small>
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
                                                    <small class="text-muted">Tanggal Publikasi</small>
                                                    <p class="mb-0 fw-bold">
                                                        @php
                                                            \Carbon\Carbon::setLocale('id');
                                                        @endphp
                                                        {{ $karya->tanggal_publish->translatedFormat('d F Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
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

                    <!-- Comments Section -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 text-gradient"><i class="bi bi-chat-square-text me-2"></i>Komentar</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Comments List -->
                            <div class="comments-list mb-4">
                                @foreach($karya->komentars as $comment)
                                <div class="comment mb-4 pb-3 border-bottom" id="comment-{{ $comment->id }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random" 
                                                 class="rounded-circle" width="50" alt="Avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0 fw-bold">{{ $comment->user->name }}</h6>
                                                <small class="text-muted">
                                                    @php
                                                        \Carbon\Carbon::setLocale('id');
                                                        echo $comment->waktu_komen->diffForHumans();
                                                    @endphp
                                                </small>
                                            </div>
                                            <p class="mb-2">{{ $comment->komentar }}</p>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-secondary reply-btn" 
                                                        data-comment-id="{{ $comment->id }}">
                                                    <i class="bi bi-reply me-1"></i>Balas
                                                </button>
                                            </div>

                                            <!-- Replies Section -->
                                            @if($comment->balasan->count() > 0)
                                            <div class="mt-3">
                                                <a href="#" class="toggle-replies text-decoration-none" 
                                                   data-comment-id="{{ $comment->id }}">
                                                    <i class="bi bi-chevron-down me-1"></i>
                                                    Lihat {{ $comment->balasan->count() }} balasan
                                                </a>
                                                
                                                <div class="replies-container mt-2 ps-4 border-start" 
                                                     id="replies-{{ $comment->id }}" style="display: none;">
                                                    @foreach($comment->balasan->take(5) as $reply)
                                                    <div class="reply mb-3 pb-2 border-bottom" id="reply-{{ $reply->id }}">
                                                        <div class="d-flex">
                                                            <div class="flex-shrink-0 me-3">
                                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random" 
                                                                     class="rounded-circle" width="40" alt="Avatar">
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $reply->user->name }}</h6>
                                                                    <small class="text-muted">
                                                                        @php
                                                                            echo $reply->waktu_komen->diffForHumans();
                                                                        @endphp
                                                                    </small>
                                                                </div>
                                                                <p class="mb-1" style="font-size: 0.9rem;">{{ $reply->komentar }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                    @if($comment->balasan->count() > 5)
                                                    <div class="text-center mt-2">
                                                        <a href="#" class="load-more-replies text-decoration-none" 
                                                           data-comment-id="{{ $comment->id }}" data-offset="5">
                                                            <i class="bi bi-chevron-down me-1"></i>Lihat Selengkapnya
                                                        </a>
                                                        <a href="#" class="hide-replies text-decoration-none d-none" 
                                                           data-comment-id="{{ $comment->id }}">
                                                            <i class="bi bi-chevron-up me-1"></i>Sembunyikan
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($karya->komentars->isEmpty())
                                <div class="text-center py-4">
                                    <i class="bi bi-chat-square-text display-5 text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada komentar</p>
                                </div>
                                @endif
                            </div>

                            @auth
                            <!-- Comment Form -->
                            <div class="comment-form-container">
                                <div class="default-comment-form" id="defaultCommentForm">
                                    <h6 class="mb-3 text-gradient"><i class="bi bi-pencil-square me-2"></i>Tulis Komentar</h6>
                                    <form action="{{ route('admin.akarya.comment', $karya) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="parent_id" id="parent_id" value="">
                                        <div class="form-group mb-3">
                                            <textarea class="form-control" name="komentar" rows="3" placeholder="Tulis komentarmu di sini..." required></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim Komentar</button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Reply Form (hidden by default) -->
                                <div class="reply-form-container" id="replyFormContainer" style="display: none;">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center py-2">
                                            <h6 class="mb-0 text-primary"><i class="bi bi-reply-fill me-2"></i>Membalas komentar</h6>
                                            <button type="button" class="btn-close btn-close-reply" aria-label="Close"></button>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('admin.akarya.comment', $karya) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="parent_id" id="reply_parent_id" value="">
                                                <div class="form-group mb-3">
                                                    <textarea class="form-control" name="komentar" rows="3" placeholder="Tulis balasanmu di sini..." required></textarea>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 me-2 btn-cancel-reply">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim Balasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <a href="{{ route('login') }}" class="text-primary">Login</a> untuk meninggalkan komentar.
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Karya -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-gradient"><i class="bi bi-collection me-2"></i>Karya Terkait</h5>
                </div>
                <div class="card-body p-4">
                    @forelse($relatedKaryas as $related)
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        @if($related->jenis_karya === 'gambar' && $related->gambarKaryas->isNotEmpty())
                        <img src="{{ asset($related->gambarKaryas->first()->nama_gambar) }}" 
                             class="rounded me-3 shadow-sm" style="width: 80px; height: 60px; object-fit: cover;">
                        @elseif($related->jenis_karya === 'video')
                        <div class="bg-dark rounded me-3 d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 80px; height: 60px;">
                            <i class="bi bi-play-fill text-white fs-5"></i>
                        </div>
                        @elseif($related->jenis_karya === 'dokumen')
                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 80px; height: 60px; border: 1px solid #dee2e6;">
                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                        </div>
                        @endif
                        <div>
                            <h6 class="mb-1">
                                <a href="{{ route('admin.akarya.show', $related) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($related->judul, 40) }}
                                </a>
                            </h6>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-2">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $related->tanggal_publish->format('d M Y') }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i>{{ $related->views }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-light border shadow-sm text-center py-4">
                        <i class="bi bi-info-circle-fill text-primary fs-4 mb-2"></i>
                        <p class="mb-0">Tidak ada karya terkait</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>


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
    
    .rating-stars {
        font-size: 1rem;
        letter-spacing: 2px;
    }
    
    .rounded-lg {
        border-radius: 12px !important;
    }
    
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    
    /* Enhanced Comment Styles */
    .comments-list .comment {
        transition: all 0.2s;
    }
    
    .comments-list .comment:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }
    
    .reply {
        position: relative;
    }
    
    .reply:before {
        content: '';
        position: absolute;
        left: -20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .reply-btn {
        transition: all 0.2s;
    }
    
    .reply-btn:hover {
        transform: translateX(3px);
    }
    
    .toggle-replies {
        font-size: 0.85rem;
    }
    
    .toggle-replies:hover {
        text-decoration: underline !important;
    }
    
    .comment-form-container {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
    }
    
    .reply-form-container {
        margin-top: 1rem;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .reply-form-container .card {
        border-left: 3px solid var(--primary);
    }
    
    .btn-close-reply {
        font-size: 0.75rem;
    }
    
    .btn-cancel-reply:hover {
        background-color: #f8f9fa;
    }
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle reply button click
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            
            // Hide default form and show reply form
            document.getElementById('defaultCommentForm').style.display = 'none';
            document.getElementById('replyFormContainer').style.display = 'block';
            
            // Set parent ID for the reply
            document.getElementById('reply_parent_id').value = commentId;
            
            // Scroll to reply form
            document.getElementById('replyFormContainer').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
    });

    // Handle cancel reply button
    document.querySelectorAll('.btn-cancel-reply, .btn-close-reply').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('defaultCommentForm').style.display = 'block';
            document.getElementById('replyFormContainer').style.display = 'none';
            document.getElementById('reply_parent_id').value = '';
        });
    });

    // Toggle replies visibility
    document.querySelectorAll('.toggle-replies').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const repliesContainer = document.getElementById(`replies-${commentId}`);
            
            if (repliesContainer.style.display === 'none') {
                repliesContainer.style.display = 'block';
                this.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Sembunyikan balasan';
            } else {
                repliesContainer.style.display = 'none';
                this.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Lihat balasan';
            }
        });
    });

    // Load more replies
    document.querySelectorAll('.load-more-replies').forEach(link => {
        link.addEventListener('click', async function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const offset = parseInt(this.getAttribute('data-offset'));
            
            try {
                const response = await fetch(`/komentar/${commentId}/balasan?offset=${offset}`);
                const data = await response.json();
                
                if (data.success) {
                    const repliesContainer = document.getElementById(`replies-${commentId}`);
                    
                    // Append new replies
                    data.replies.forEach(reply => {
                        const replyHtml = `
                            <div class="reply mb-3 pb-2 border-bottom" id="reply-${reply.id}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(reply.user.name)}&background=random" 
                                             class="rounded-circle" width="40" alt="Avatar">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">${reply.user.name}</h6>
                                            <small class="text-muted">${reply.waktu_komen}</small>
                                        </div>
                                        <p class="mb-1" style="font-size: 0.9rem;">${reply.komentar}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
                    });
                    
                    // Update offset or hide load more if no more replies
                    if (data.replies.length < 5) {
                        this.style.display = 'none';
                    } else {
                        this.setAttribute('data-offset', offset + 5);
                    }
                    
                    // Show hide button
                    this.nextElementSibling.classList.remove('d-none');
                }
            } catch (error) {
                console.error('Error loading more replies:', error);
            }
        });
    });

    // Hide replies
    document.querySelectorAll('.hide-replies').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const repliesContainer = document.getElementById(`replies-${commentId}`);
            
            repliesContainer.style.display = 'none';
            this.classList.add('d-none');
            this.previousElementSibling.style.display = 'inline';
        });
    });
});
</script>

@endsection