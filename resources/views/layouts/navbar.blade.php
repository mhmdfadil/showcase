<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #02b3ff 0%, #0596c7 50%, #015579 100%); border-bottom: 1px solid rgba(255, 255, 255, 0.1); height: 60px;">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-link d-lg-none" onclick="toggleSidebar()" style="color: rgba(255, 255, 255, 0.8);">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        
        
        <!-- User Dropdown -->
        <div class="d-flex align-items-center ms-auto">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                   id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false"
                   style="color: rgba(255, 255, 255, 0.9); transition: all 0.3s ease;">
                    <div class="user-icon me-2">
                        <div class="avatar rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 36px; height: 36px; background: linear-gradient(45deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 4px 8px rgba(17, 153, 142, 0.3);">
                            <i class="bi bi-person text-white"></i>
                        </div>
                    </div>
                    <span class="d-none d-sm-inline fw-medium">{{ Auth::user()->nama ?? 'Admin' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser" 
                    style="background: rgba(15, 32, 39, 0.95); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(12px);">
                    <li>
                        <a class="dropdown-item text-white" href="" style="transition: all 0.2s ease;">
                            <i class="bi bi-person me-2" style="color: #38ef7d;"></i> Profile
                        </a>
                    </li>
               
                    <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                    <li>
                        <a class="dropdown-item text-white" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           style="transition: all 0.2s ease;">
                            <i class="bi bi-box-arrow-right me-2" style="color: #ff6d38;"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- JavaScript for Toggle Sidebar -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.style.display === 'none' || sidebar.style.display === '') {
            sidebar.style.display = 'block';
        } else {
            sidebar.style.display = 'none';
        }
    }
</script>

<style>
    /* Navbar Hover Effects */
    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        transform: translateX(3px);
    }
    
    .dropdown-toggle:hover {
        opacity: 0.9;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .navbar {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>