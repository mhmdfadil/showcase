<nav class="navbar navbar-expand-lg navbar-dark" style="
    background: linear-gradient(135deg, #3E7FC5FF 0%, #3B578AFF 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    height: 60px;
">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-link d-lg-none" onclick="toggleSidebar()" style="
            color: rgba(255, 255, 255, 0.8);
            padding: 0.25rem 0.5rem;
        ">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <!-- Navbar Brand/Title -->
        {{-- <div class="navbar-brand d-none d-lg-flex align-items-center ms-3" style="
            font-size: 1.1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
        ">
            <i class="bi bi-speedometer2 me-2" style="color: #4361ee;"></i>
            Dashboard
        </div> --}}
        
        <!-- User Dropdown -->
        <div class="d-flex align-items-center ms-auto">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                   id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false"
                   style="
                       color: rgba(255, 255, 255, 0.9); 
                       transition: all 0.3s ease;
                       padding: 0.5rem 0.75rem;
                       border-radius: 8px;
                   ">
                    <div class="user-icon me-2">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset(Auth::user()->profile_photo) }}" 
                                 class="avatar rounded-circle" 
                                 style="
                                     width: 36px; 
                                     height: 36px; 
                                     object-fit: cover;
                                     border: 2px solid rgba(255, 255, 255, 0.1);
                                 ">
                        @else
                            @php
                               // Generate initials from name
                                $name = Auth::user()->name ?? 'Admin';
                                $words = array_filter(explode(' ', trim($name))); // Split and remove empty parts
                                $initials = '';

                                if (count($words) === 1) {
                                    // Single word: take first 2 letters (e.g., "John" → "JO")
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                } elseif (count($words) >= 2) {
                                    // Multiple words: take first letter of first and last word (e.g., "John Doe Smith" → "JS")
                                    $initials = strtoupper(substr($words[0], 0, 1)) . strtoupper(substr(end($words), 0, 1));
                                }

                                // Color generation based on name hash (same as navbar)
                                $colors = [
                                    '#4361ee', '#3f37c9', '#4895ef', '#4cc9f0', '#f72585',
                                    '#b5179e', '#7209b7', '#560bad', '#480ca8', '#3a0ca3'
                                ];
                                $colorIndex = crc32($name) % count($colors);
                                $bgColor = $colors[$colorIndex];
                            @endphp
                            
                            <div class="avatar rounded-circle d-flex align-items-center justify-content-center" 
                                 style="
                                     width: 36px; 
                                     height: 36px; 
                                     background: {{ $bgColor }};
                                     color: white;
                                     font-weight: 600;
                                     font-size: 0.9rem;
                                     border: 2px solid rgba(255, 255, 255, 0.1);
                                 ">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                    <span class="d-none d-sm-inline fw-medium" style="font-size: 0.925rem;">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser" 
                    style="
                        background: rgba(15, 23, 42, 0.98);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(12px);
                        min-width: 220px;
                        border-radius: 8px;
                        overflow: hidden;
                    ">
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.index') }}" 
                           style="
                               color: rgba(255, 255, 255, 0.9);
                               transition: all 0.2s ease;
                               font-size: 0.925rem;
                           ">
                            <i class="bi bi-person me-2" style="color: #4361ee; width: 20px;"></i> 
                            <span>Profil Saya</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           style="
                               color: rgba(255, 255, 255, 0.9);
                               transition: all 0.2s ease;
                               font-size: 0.925rem;
                           ">
                            <i class="bi bi-box-arrow-right me-2" style="color: #f72585; width: 20px;"></i> 
                            <span>Logout</span>
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
        const sidebarState = sidebar.style.transform || 'translateX(0)';
        
        if (sidebarState === 'translateX(0px)' || sidebarState === 'translateX(0)') {
            sidebar.style.transform = 'translateX(-100%)';
        } else {
            sidebar.style.transform = 'translateX(0)';
        }
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.navbar button[onclick="toggleSidebar()"]');
        
        if (window.innerWidth < 992 && 
            !sidebar.contains(event.target) && 
            event.target !== toggleBtn && 
            !toggleBtn.contains(event.target)) {
            sidebar.style.transform = 'translateX(-100%)';
        }
    });
</script>

<style>
    /* Navbar Hover Effects */
    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        padding-left: 1.25rem !important;
    }
    
    .dropdown-toggle:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    
    /* Avatar transition */
    .avatar {
        transition: all 0.3s ease;
    }
    
    .dropdown-toggle:hover .avatar {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .navbar {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        #sidebar {
            position: fixed;
            z-index: 1040;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        #sidebar.show {
            transform: translateX(0);
        }
    }
</style>