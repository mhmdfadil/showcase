@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Dashboard</h3>
            <p class="text-muted mb-1" style="font-size: 18px">Selamat datang kembali, <span class="text-primary fw-medium">{{ $user->nama ?? 'Admin'}}!</span></p>
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

</div>

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