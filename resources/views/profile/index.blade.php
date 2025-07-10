@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Profil Saya</h3>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 text-primary">Pengaturan Akun</h5>
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
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <div class="avatar-upload mb-3 position-relative">
                                        <div class="avatar-preview" style="
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
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control rounded-3" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control rounded-3" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control rounded-3" id="nik" name="nik" value="{{ old('nik', Auth::user()->nik) }}">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control rounded-3" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth', Auth::user()->place_of_birth) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control rounded-3" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select rounded-3" id="gender" name="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-Laki" {{ old('gender', Auth::user()->gender) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="Perempuan" {{ old('gender', Auth::user()->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="Lainnya" {{ old('gender', Auth::user()->gender) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="religion" class="form-label">Agama</label>
                                    <input type="text" class="form-control rounded-3" id="religion" name="religion" value="{{ old('religion', Auth::user()->religion) }}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Alamat</label>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <select class="form-select rounded-3" id="provincesId" name="provincesId">
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                         <input type="hidden" id="provinces" name="provinces">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select rounded-3" id="regenciesId" name="regenciesId" disabled>
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                        <input type="hidden" id="regencies" name="regencies">
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <select class="form-select rounded-3" id="districtsId" name="districtsId" disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        <input type="hidden" id="districts" name="districts">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select rounded-3" id="villagesId" name="villagesId" disabled>
                                            <option value="">Pilih Desa/Kelurahan</option>
                                        </select>
                                        <input type="hidden" id="villages" name="villages">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div id="password-section" class="section-content" style="display: none;">
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-3" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-3" id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 8 karakter dengan kombinasi huruf dan angka</div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-3" id="new_password_confirmation" name="new_password_confirmation" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    // Preview foto profil with hover effect
    const avatarPreview = document.querySelector('.avatar-preview');
    const avatarOverlay = document.querySelector('.avatar-overlay');
    
    avatarPreview.addEventListener('mouseenter', () => {
        avatarOverlay.style.opacity = '1';
    });
    
    avatarPreview.addEventListener('mouseleave', () => {
        avatarOverlay.style.opacity = '0';
    });

    document.getElementById('profile_photo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.avatar-preview').style.backgroundImage = `url('${e.target.result}')`;
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

    // Improved AJAX handling
    $(document).ready(function() {
        const API_BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api/';
        const PROXY_URL = 'https://api.allorigins.win/raw?url=';
        
        // Function to get URL with fallback
        function getApiUrl(endpoint) {
            return PROXY_URL + encodeURIComponent(API_BASE_URL + endpoint);
        }
        
        // Function to fetch with retry
        async function fetchWithRetry(url, retries = 3, delay = 1000) {
            try {
                const response = await $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000
                });
                return response;
            } catch (error) {
                if (retries <= 0) throw error;
                await new Promise(resolve => setTimeout(resolve, delay));
                return fetchWithRetry(url, retries - 1, delay * 2);
            }
        }
        
        // Load Provinces
        async function loadProvinces() {
            try {
                const provinces = await fetchWithRetry(getApiUrl('provinces.json'));
                $('#provincesId').html('<option value="">Pilih Provinsi</option>');
                
                provinces.forEach(province => {
                    $('#provincesId').append(
                        `<option value="${province.id}" 
                         data-name="${province.name}"
                         ${province.id === '{{ Auth::user()->provincesId }}' ? 'selected' : ''}>
                            ${province.name}
                        </option>`
                    );
                });
                
                if ('{{ Auth::user()->provincesId }}') {
                    const selectedProvince = provinces.find(p => p.id === '{{ Auth::user()->provincesId }}');
                    if (selectedProvince) {
                        $('#provinces').val(selectedProvince.name);
                        await loadRegencies(selectedProvince.id);
                    }
                }
            } catch (error) {
                console.error('Error loading provinsi:', error);
                $('#provincesId').html('<option value="">Gagal memuat provinsi. Coba lagi.</option>');
                $('#provincesId').after('<button id="retryProvince" class="btn btn-sm btn-warning mt-2">Coba Lagi</button>');
                $('#retryProvince').click(loadProvinces);
            }
        }
        
        // Load Regencies
        async function loadRegencies(provinceId) {
            try {
                $('#regenciesId').html('<option value="">Memuat kabupaten/kota...</option>').prop('disabled', true);
                $('#districtsId').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
                $('#villagesId').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);
                
                const regencies = await fetchWithRetry(getApiUrl(`regencies/${provinceId}.json`));
                $('#regenciesId').html('<option value="">Pilih Kabupaten/Kota</option>').prop('disabled', false);
                
                regencies.forEach(regency => {
                    $('#regenciesId').append(
                        `<option value="${regency.id}" 
                         data-name="${regency.name}"
                         ${regency.id === '{{ Auth::user()->regenciesId }}' ? 'selected' : ''}>
                            ${regency.name}
                        </option>`
                    );
                });
                
                if ('{{ Auth::user()->regenciesId }}') {
                    const selectedRegency = regencies.find(r => r.id === '{{ Auth::user()->regenciesId }}');
                    if (selectedRegency) {
                        $('#regencies').val(selectedRegency.name);
                        await loadDistricts(selectedRegency.id);
                    }
                }
            } catch (error) {
                console.error('Error loading kabupaten:', error);
                $('#regenciesId').html('<option value="">Gagal memuat kabupaten</option>').prop('disabled', false);
            }
        }
        
        // Load Districts
        async function loadDistricts(regencyId) {
            try {
                $('#districtsId').html('<option value="">Memuat kecamatan...</option>').prop('disabled', true);
                $('#villagesId').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);
                
                const districts = await fetchWithRetry(getApiUrl(`districts/${regencyId}.json`));
                $('#districtsId').html('<option value="">Pilih Kecamatan</option>').prop('disabled', false);
                
                districts.forEach(district => {
                    $('#districtsId').append(
                        `<option value="${district.id}" 
                         data-name="${district.name}"
                         ${district.id === '{{ Auth::user()->districtsId }}' ? 'selected' : ''}>
                            ${district.name}
                        </option>`
                    );
                });
                
                if ('{{ Auth::user()->districtsId }}') {
                    const selectedDistrict = districts.find(d => d.id === '{{ Auth::user()->districtsId }}');
                    if (selectedDistrict) {
                        $('#districts').val(selectedDistrict.name);
                        await loadVillages(selectedDistrict.id);
                    }
                }
            } catch (error) {
                console.error('Error loading kecamatan:', error);
                $('#districtsId').html('<option value="">Gagal memuat kecamatan</option>').prop('disabled', false);
            }
        }
        
        // Load Villages
        async function loadVillages(districtId) {
            try {
                $('#villagesId').html('<option value="">Memuat desa/kelurahan...</option>').prop('disabled', true);
                
                const villages = await fetchWithRetry(getApiUrl(`villages/${districtId}.json`));
                $('#villagesId').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', false);
                
                villages.forEach(village => {
                    $('#villagesId').append(
                        `<option value="${village.id}" 
                         data-name="${village.name}"
                         ${village.id === '{{ Auth::user()->villagesId }}' ? 'selected' : ''}>
                            ${village.name}
                        </option>`
                    );
                });
                
                if ('{{ Auth::user()->villagesId }}') {
                    const selectedVillage = villages.find(v => v.id === '{{ Auth::user()->villagesId }}');
                    if (selectedVillage) {
                        $('#villages').val(selectedVillage.name);
                    }
                }
            } catch (error) {
                console.error('Error loading desa:', error);
                $('#villagesId').html('<option value="">Gagal memuat desa</option>').prop('disabled', false);
            }
        }
        
        // Event handlers
        $('#provincesId').change(async function() {
            const provinceId = $(this).val();
            const provinceName = $(this).find('option:selected').data('name');
            $('#provinces').val(provinceName);
            
            if (provinceId) {
                await loadRegencies(provinceId);
            } else {
                $('#regenciesId').html('<option value="">Pilih Kabupaten/Kota</option>').prop('disabled', true);
                $('#regencies').val('');
                $('#districtsId').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
                $('#districts').val('');
                $('#villagesId').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);
                $('#villages').val('');
            }
        });
        
        $('#regenciesId').change(async function() {
            const regencyId = $(this).val();
            const regencyName = $(this).find('option:selected').data('name');
            $('#regencies').val(regencyName);
            
            if (regencyId) {
                await loadDistricts(regencyId);
            } else {
                $('#districtsId').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
                $('#districts').val('');
                $('#villagesId').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);
                $('#villages').val('');
            }
        });
        
        $('#districtsId').change(async function() {
            const districtId = $(this).val();
            const districtName = $(this).find('option:selected').data('name');
            $('#districts').val(districtName);
            
            if (districtId) {
                await loadVillages(districtId);
            } else {
                $('#villagesId').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);
                $('#villages').val('');
            }
        });
        
        $('#villagesId').change(function() {
            $('#villages').val($(this).find('option:selected').data('name'));
        });
        
        // Initialize
        loadProvinces();
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
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection