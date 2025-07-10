@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 py-3">
        <div>
            <h3 class="m-0 font-weight-bold text-primary">Karya Publik</h3>
            <p class="m-0 text-muted">Karya yang sudah dipublikasikan</p>
        </div>
    </div>

    <!-- Kategori Grid -->
    <div class="row g-4">
        @forelse($kategoris as $kategori)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card card-hover h-100 border-0 shadow-sm">
                <a href="{{ route('mahasiswa.akarya.byKategori', $kategori) }}" class="text-decoration-none text-dark">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-light-primary rounded-circle p-3 me-3">
                                <i class="bi bi-folder-fill text-primary fs-2"></i>
                                <span class="position-absolute top-0 end-0 mt-4 translate-middle badge rounded-pill bg-success">
                                    {{ $kategori->karyas_count }}
                                    <span class="visually-hidden">jumlah karya</span>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">{{ $kategori->nama_kategori }}</h5>
                                <p class="card-text text-muted small mb-0">{{ $kategori->karyas_count }} karya publik</p>
                            </div>
                            <div class="ms-2 text-muted">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        @php
                            \Carbon\Carbon::setLocale('id');
                        @endphp
                        <small class="text-muted">Terakhir diupdate: {{ $kategori->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-file-earmark-check display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada karya publik</h5>
                    <p class="text-muted">Belum ada karya yang dipublikasikan</p>
                    <a href="#" class="btn btn-primary rounded-pill px-4 mt-2">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Karya
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination (if needed) -->
    @if($kategoris->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item {{ $kategoris->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link rounded-pill me-1" href="{{ $kategoris->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                @foreach(range(1, $kategoris->lastPage()) as $i)
                    <li class="page-item {{ $kategoris->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link rounded-circle mx-1" href="{{ $kategoris->url($i) }}">{{ $i }}</a>
                    </li>
                @endforeach
                <li class="page-item {{ !$kategoris->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link rounded-pill ms-1" href="{{ $kategoris->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    @endif
</div>

@push('styles')
<style>
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    
    .icon-wrapper {
        position: relative;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .btn-outline-secondary {
        border-width: 2px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .pagination .page-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
@endsection