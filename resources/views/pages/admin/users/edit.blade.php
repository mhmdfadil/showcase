@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Edit User - {{ $user->name }}</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 text-primary">Pengaturan User</h5>
                </div>

                <div class="card-body p-4">
                    <!-- Switch Navigation -->
                    <div class="d-flex justify-content-center mb-5">
                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" id="profile-switch" class="btn btn-light active px-4 py-2" onclick="switchSection('profile')">
                                <i class="bi bi-person me-2"></i> Informasi Profil
                            </button>
                            <button type="button" id="password-switch" class="btn btn-light px-4 py-2" onclick="switchSection('password')">
                                <i class="bi bi-lock me-2"></i> Ubah Password
                            </button>
                        </div>
                    </div>

                    <!-- Profile Information Section -->
                    <div id="profile-section" class="section-content">
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                            background-image: url('{{ $user->profile_photo ? $user->profile_photo : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}');
                                        ">
                                           
                                        </div>
                                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="document.getElementById('profile_photo').click()">
                                            <i class="bi bi-camera me-1"></i> Ubah Foto
                                        </button>
                                        @error('profile_photo')
                                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
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
                                        <option value="Admin" {{ old('roles', $user->roles) == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="Mahasiswa" {{ old('roles', $user->roles) == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    </select>
                                    @error('roles')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control rounded-3 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control rounded-3 @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $user->nik) }}">
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control rounded-3 @error('place_of_birth') is-invalid @enderror" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth', $user->place_of_birth) }}">
                                    @error('place_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control rounded-3 @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select rounded-3 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-Laki" {{ old('gender', $user->gender) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="Lainnya" {{ old('gender', $user->gender) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="religion" class="form-label">Agama</label>
                                    <input type="text" class="form-control rounded-3 @error('religion') is-invalid @enderror" id="religion" name="religion" value="{{ old('religion', $user->religion) }}">
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
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div id="password-section" class="section-content" style="display: none;">
                        <form method="POST" action="{{ route('admin.users.update-password', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-start-3 @error('password') is-invalid @enderror" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password rounded-end-3" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 8 karakter dengan kombinasi huruf dan angka</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-start-3" id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary toggle-password rounded-end-3" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="bi bi-key me-1"></i> Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to switch between sections
    function switchSection(section) {
        // Hide all sections
        document.querySelectorAll('.section-content').forEach(el => {
            el.style.display = 'none';
        });
        
        // Show selected section
        document.getElementById(section + '-section').style.display = 'block';
        
        // Update active button
        document.querySelectorAll('.btn-group button').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-light');
        });
        
        const activeBtn = document.getElementById(section + '-switch');
        activeBtn.classList.remove('btn-light');
        activeBtn.classList.add('active', 'btn-primary');
    }

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
    .btn-group button.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .btn-group button {
        transition: all 0.3s ease;
    }
    
    .form-control, .form-select {
        border-radius: 8px !important;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .section-content {
        animation: fadeIn 0.3s ease;
    }
    
    .avatar-overlay {
        transition: opacity 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .input-group .form-control:not(:first-child) {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    
    .input-group .btn:not(:last-child) {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
</style>
@endsection