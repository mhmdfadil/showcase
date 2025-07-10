@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Tambah Kategori Baru</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 text-primary">Form Kategori</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.kategori.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                <input id="nama_kategori" type="text" 
                                       class="form-control rounded-3 @error('nama_kategori') is-invalid @enderror" 
                                       name="nama_kategori" 
                                       value="{{ old('nama_kategori') }}" 
                                       required autocomplete="nama_kategori" autofocus
                                       placeholder="Masukkan nama kategori">

                                @error('nama_kategori')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('admin.kategori-tag.index', ['tab' => 'kategori']) }}" 
                               class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border-radius: 8px !important;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
    }
</style>
@endsection