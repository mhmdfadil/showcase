@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Tambah User Baru</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 text-primary">Form Tambah User</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="avatar-upload mb-3 position-relative">
                                    <div class="avatar-preview" id="imagePreview" style="
                                        width: 150px;
                                        height: 150px;
                                        border-radius: 50%;
                                        background-size: cover;
                                        background-position: center;
                                        margin: 0 auto;
                                        border: 3px solid #e0e0e0;
                                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                                        background-image: url('https://ui-avatars.com/api/?name=New+User&color=7F9CF5&background=EBF4FF');
                                    ">
                                      
                                    </div>
                                    <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="document.getElementById('profile_photo').click()">
                                        <i class="bi bi-camera me-1"></i> Pilih Foto
                                    </button>
                                    @error('profile_photo')
                                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required
                                           placeholder="Masukkan alamat email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="roles" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select rounded-3 @error('roles') is-invalid @enderror" id="roles" name="roles" required>
                                    <option value="">Pilih Role</option>
                                    <option value="Admin" {{ old('roles') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Mahasiswa" {{ old('roles') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control rounded-3 @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="Masukkan nomor telepon">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-start-3 @error('password') is-invalid @enderror" 
                                           id="password" name="password" required
                                           placeholder="Buat password">
                                    <button class="btn btn-outline-secondary toggle-password rounded-end-3" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 8 karakter</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-start-3" 
                                           id="password_confirmation" name="password_confirmation" required
                                           placeholder="Konfirmasi password">
                                    <button class="btn btn-outline-secondary toggle-password rounded-end-3" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control rounded-3 @error('nik') is-invalid @enderror" 
                                       id="nik" name="nik" value="{{ old('nik') }}"
                                       placeholder="Masukkan NIK">
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control rounded-3 @error('place_of_birth') is-invalid @enderror" 
                                       id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}"
                                       placeholder="Masukkan tempat lahir">
                                @error('place_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control rounded-3 @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-select rounded-3 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki" {{ old('gender') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    <option value="Lainnya" {{ old('gender') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="religion" class="form-label">Agama</label>
                                <input type="text" class="form-control rounded-3 @error('religion') is-invalid @enderror" 
                                       id="religion" name="religion" value="{{ old('religion') }}"
                                       placeholder="Masukkan agama">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2 me-2 rounded-pill">
                                <i class="bi bi-x-circle me-1"></i> Batal
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

<script>
    // Avatar hover effect
    const avatarPreview = document.getElementById('imagePreview');
    const avatarOverlay = avatarPreview.querySelector('.avatar-overlay');
    
    avatarPreview.addEventListener('mouseenter', () => {
        avatarOverlay.style.opacity = '1';
    });
    
    avatarPreview.addEventListener('mouseleave', () => {
        avatarOverlay.style.opacity = '0';
    });

    // Preview selected profile photo
    document.getElementById('profile_photo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.style.backgroundImage = `url('${e.target.result}')`;
            }
            reader.readAsDataURL(file);
        }
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>

<style>
    .form-control, .form-select {
        border-radius: 8px !important;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .avatar-overlay {
        transition: opacity 0.3s ease;
    }
    
    .input-group .form-control:not(:first-child) {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    
    .input-group .btn:not(:last-child) {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    
    hr {
        opacity: 0.1;
        margin: 1.5rem 0;
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
        color: #dc3545;
        font-size: 0.875em;
    }
    
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
        margin-top: 0.25rem;
    }
</style>
@endsection