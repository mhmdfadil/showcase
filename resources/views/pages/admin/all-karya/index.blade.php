@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 py-3">
        <div>
            <h3 class="m-0 font-weight-bold text-primary">Semua Karya</h3>
            <p class="m-0 text-muted">Koleksi karya terbaik dari berbagai kategori</p>
        </div>
    </div>

    <!-- Main Cards (Draft & Publish) -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <a href="{{ route('admin.akarya.draft') }}" class="card card-hover h-100 text-decoration-none border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-light-primary rounded-circle p-3 me-3">
                            <i class="bi bi-file-earmark-lock text-primary fs-2"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Draft Karya</h5>
                            <p class="card-text text-muted small">Karya yang akan dipublikasikan di masa depan</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('admin.akarya.publish') }}" class="card card-hover h-100 text-decoration-none border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-light-success rounded-circle p-3 me-3">
                            <i class="bi bi-file-earmark-check text-success fs-2"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Karya Publik</h5>
                            <p class="card-text text-muted small">Karya yang sudah dipublikasikan</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Kategori Section -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0 font-weight-bold">Kategori Karya</h5>
            </div>

            <div class="row g-3">
                @forelse($kategoris as $kategori)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="{{ route('admin.akarya.byKategori', $kategori) }}" class="card card-hover h-100 text-decoration-none border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-light-info rounded-circle p-2 me-3">
                                    <i class="bi bi-folder-fill text-info fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-0">{{ $kategori->nama_kategori }}</h6>
                                    <small class="text-muted">{{ $kategori->karyas_count }} karya</small>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-light border text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                        <p class="mb-0">Belum ada kategori dengan karya yang dipublikasikan</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Karya Section -->
    <div class="card shadow-sm border-0">
     
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    .icon-wrapper {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-outline-primary {
        border-width: 2px;
    }
    
    .alert-light {
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection