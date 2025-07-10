@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Manajemen User</h3>
        </div>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('admin.users.index', ['role' => 'Admin']) }}" 
               class="btn btn-{{ request('role') !== 'Mahasiswa' ? 'primary' : 'light' }} px-4 py-2">
                Admin
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'Mahasiswa']) }}" 
               class="btn btn-{{ request('role') === 'Mahasiswa' ? 'primary' : 'light' }} px-4 py-2">
                Mahasiswa
            </a>
        </div>
        
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 12px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">Daftar {{ request('role') === 'Mahasiswa' ? 'Mahasiswa' : 'Admin' }}</h5>
                        <div>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-3 py-2" title="Tambah User">
                                <i class="bi bi-plus-lg"></i> 
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status Login</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                @if((request('role') === 'Mahasiswa' && $user->roles === 'Mahasiswa') || (request('role') !== 'Mahasiswa' && $user->roles === 'Admin'))
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($user->profile_photo)
                                            <img src="{{ $user->profile_photo }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            @php
                                                // Generate initials based on the given rules
                                                $nameParts = explode(' ', trim($user->name));
                                                $initials = '';

                                                if (count($nameParts) === 1) {
                                                    // Single word: take first two letters (uppercase)
                                                    $initials = strtoupper(substr($nameParts[0], 0, 2));
                                                } else {
                                                    // Multiple words: take first letter of first and last word
                                                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                                                    $lastPart = end($nameParts);
                                                    $initials .= strtoupper(substr($lastPart, 0, 1));
                                                }
                                            @endphp
                                            <div class="avatar-initials rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px;">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->roles === 'Admin' ? 'bg-primary' : 'bg-success' }}">
                                            {{ $user->roles }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->status_login ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $user->status_login ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-outline-primary rounded-circle p-2" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle p-2 delete-btn" 
                                                        title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables and SweetAlert2 CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#userTable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100]
        });

        // SweetAlert2 for delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus user ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<style>
    .btn-group .btn {
        transition: all 0.3s ease;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .rounded-circle {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 50% !important;
        margin: 0 2px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border: none !important;
    }
    
    .avatar-initials {
        font-weight: bold;
        font-size: 14px;
    }
</style>
@endsection