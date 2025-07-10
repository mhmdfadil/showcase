@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0 mt-2 mb-2 font-weight-bold text-primary">Kategori & Tag</h3>
        </div>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('mahasiswa.kategori-tag.index', ['tab' => 'kategori']) }}" 
               class="btn btn-{{ $activeTab === 'kategori' ? 'primary' : 'light' }} px-4 py-2">
                Kategori
            </a>
            <a href="{{ route('mahasiswa.kategori-tag.index', ['tab' => 'tag']) }}" 
               class="btn btn-{{ $activeTab === 'tag' ? 'primary' : 'light' }} px-4 py-2">
                Tag
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 12px; border: none;">
                <div class="card-header bg-transparent py-3">
                  <div class="d-flex justify-content-between align-items-center">
                     <h5 class="mb-0 text-primary">{{ $activeTab === 'kategori' ? 'Daftar Kategori' : 'Daftar Tag' }}</h5>
                    
                  </div>
               </div>
                
                <div class="card-body p-4">
                    {{-- @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif --}}

                    @if($activeTab === 'kategori')
                        <div class="table-responsive">
                            <table id="kategoriTable" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                  
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kategoris as $kategori)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="tagTable" class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Tag</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tags as $tag)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tag->nama_tag }}</td>
                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
        $('#kategoriTable, #tagTable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            },
            // dom: '<"top"f>rt<"bottom"lip><"clear">',
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100]
        });

        // SweetAlert2 for delete confirmation
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const itemType = "{{ $activeTab === 'kategori' ? 'kategori' : 'tag' }}";
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${itemType} ini?`,
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
</style>
@endsection

