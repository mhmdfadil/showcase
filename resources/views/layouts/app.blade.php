<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pengarsipan & Showcase - @yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary: #2c7a3e;
            --primary-light: #4caf50;
            --primary-dark: #1e5631;
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        #app-container {
            display: flex;
            flex: 1;
        }
        
        #sidebar {
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }
        
        #main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        #content-wrapper {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .nav-link.active {
            color: var(--primary) !important;
            font-weight: 500;
            background-color: rgba(44, 122, 62, 0.1);
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .sidebar-brand {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .user-info {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        
        .user-icon {
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        footer {
            background-color: white;
            padding: 0 var(--sidebar-width) 5px 10px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
           
        }
        
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            #sidebar.show {
                transform: translateX(0);
            }
          
        }
    </style>
</head>
<body>
    <div id="app-container">
       @include('sweetalert::alert')
        <!-- Sidebar -->
       @auth
            @if(auth()->user()->roles === 'Admin')
                @include('layouts.sidebar_admin')
            @elseif(auth()->user()->roles === 'Mahasiswa')
                @include('layouts.sidebar_mahasiswa')
            @endif
        @endauth
        
        <div id="main-content">
            <!-- Navbar -->
            @include('layouts.navbar')
            
            <!-- Content -->
            <div id="content-wrapper">
                @yield('content')
            </div>
            
            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <!-- SweetAlert for Session Messages -->
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                icon: 'success',
                title: '{{ session("success") }}',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: '{{ session("error") }}',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        });
    </script>
    @endif
    
    <!-- Toggle Sidebar for Mobile -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
</body>
</html>