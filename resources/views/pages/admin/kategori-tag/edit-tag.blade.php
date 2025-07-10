@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Edit Tag</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 text-primary">Form Edit Tag</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.tag.update', $tag->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="nama_tag" class="form-label">Nama Tag</label>
                                <input id="nama_tag" type="text" 
                                       class="form-control rounded-3 @error('nama_tag') is-invalid @enderror" 
                                       name="nama_tag" 
                                       value="{{ old('nama_tag', $tag->nama_tag) }}" 
                                       required 
                                       autocomplete="nama_tag" 
                                       autofocus
                                       placeholder="Masukkan nama tag">

                                @error('nama_tag')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('admin.kategori-tag.index', ['tab' => 'tag']) }}" 
                               class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                <i class="bi bi-check-circle me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #4d90fe;
        box-shadow: 0 0 0 0.2rem rgba(77, 144, 254, 0.25);
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .btn-outline-secondary {
        border: 1px solid #6c757d;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    
    .card {
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
</style>
@endsection