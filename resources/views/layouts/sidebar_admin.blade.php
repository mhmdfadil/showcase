<div id="sidebar" class="d-flex flex-column" style="background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); min-height: 100vh; width: 280px; font-family: 'Inter', sans-serif;">
    <!-- Brand Section with Modern Design -->
    <div class="p-4" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="sidebar-brand d-flex align-items-center">
            <div class="icon-wrapper rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" 
                 style="width: 42px; height: 42px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); box-shadow: 0 4px 20px rgba(67, 97, 238, 0.3);">
                <i class="bi bi-collection-play fs-5 text-white"></i>
            </div>
            <div>
                <span class="fs-5 fw-bold text-white" style="letter-spacing: 0.5px; display: block;">SI SHOWCASE</span>
                <small class="text-muted" style="color: rgba(255, 255, 255, 0.6) !important; font-size: 0.7rem; display: block; margin-top: -2px;">Content Management</small>
            </div>
        </div>
    </div>
    
<!-- User Profile with Dynamic Avatar -->
<div class="px-4 py-3 user-info" style="
    margin: 1rem; 
    background: rgba(255, 255, 255, 0.151); 
    border-radius: 12px; 
    border: 1px solid rgba(255, 255, 255, 0.05);
">
    <div class="d-flex align-items-center">
        <div class="user-icon me-3">
            @if(Auth::user()->profile_photo)
                <img src="{{ asset(Auth::user()->profile_photo) }}" 
                     class="avatar rounded-circle" 
                     style="
                         width: 42px; 
                         height: 42px; 
                         object-fit: cover;
                         border: 2px solid rgba(255, 255, 255, 0.1);
                         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
                         width: 42px; 
                         height: 42px; 
                         background: {{ $bgColor }};
                         color: white;
                         font-weight: 600;
                         font-size: 1rem;
                         border: 2px solid rgba(255, 255, 255, 0.1);
                         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                     ">
                    {{ $initials }}
                </div>
            @endif
        </div>
        <div style="line-height: 1.3;">
            <div class="fw-semibold text-white" style="font-size: 0.95rem; letter-spacing: 0.3px;">
                {{ Auth::user()->name ?? 'Admin' }}
               
            </div>
            <div class="d-flex align-items-center mt-1">
                
                @if(Auth::user()->roles)
                    <span class="badge rounded-pill" style="
                        background: rgba(67, 97, 238, 0.2);
                        color: #4361ee;
                        font-size: 0.65rem;
                        font-weight: 600;
                        padding: 0.25rem 0.5rem;
                        border: 1px solid rgba(67, 97, 238, 0.3);
                    ">
                        {{ Auth::user()->roles }}
                    </span>
                @endif
                
            </div>
             
        </div>
    </div>
</div>

<style>
    /* Avatar hover effect */
    .user-info:hover .avatar {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    
    /* User info hover effect */
    .user-info {
        transition: all 0.3s ease;
    }
    
    .user-info:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
</style>
    
    <!-- Navigation Menu -->
    <div class="flex-grow-1 px-3 overflow-auto" style="scrollbar-width: thin; padding-top: 0.5rem;">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(67, 97, 238, 0.15); border-radius: 8px;">
                        <i class="bi bi-speedometer2" style="font-size: 1rem; color: #EE6543FF;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Dashboard</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #EE6543FF; border-radius: 2px; opacity: {{ request()->routeIs('admin.dashboard') ? '1' : '0' }};"></div>
                </a>
            </li>

            <!-- Unggah Karya -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.karya.create') ? 'active' : '' }}" href="{{ route('admin.karya.create') }}" 
                style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                        style="width: 32px; height: 32px; background: rgba(74, 144, 226, 0.15); border-radius: 8px;">
                        <i class="bi bi-cloud-upload" style="font-size: 1rem; color: #4FE24AFF;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Unggah Karya</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #4FE24AFF; border-radius: 2px; opacity: {{ request()->routeIs('admin.karya.create') ? '1' : '0' }};"></div>
                </a>
            </li>
            
            <!-- Validasi Karya -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.admin.karya.index') || request()->routeIs('admin.karya.show') ? 'active' : '' }}" href="{{ route('admin.admin.karya.index') }}" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(247, 37, 133, 0.15); border-radius: 8px;">
                        <i class="bi bi-check-circle" style="font-size: 1rem; color: #f72585;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Validasi Karya</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #f72585; border-radius: 2px; opacity: {{ request()->routeIs('admin.admin.karya.index') || request()->routeIs('admin.karya.show') ? '1' : '0' }};"></div>
                </a>
            </li>
            
            <!-- Semua Karya -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.akarya.*') ? 'active' : '' }}" href="{{ route('admin.akarya.index') }}" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(72, 199, 142, 0.15); border-radius: 8px;">
                        <i class="bi bi-collection" style="font-size: 1rem; color: #48c78e;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Semua Karya</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #48c78e; border-radius: 2px; opacity: {{ request()->routeIs('admin.akarya.*') ? '1' : '0' }};"></div>
                </a>
            </li>
            
            <!-- Kategori & Tag -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.kategori-tag.*') || request()->routeIs('admin.kategori.*') || request()->routeIs('admin.tag.*') ? 'active' : '' }}" href="{{ route('admin.kategori-tag.index') }}" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(255, 171, 0, 0.15); border-radius: 8px;">
                        <i class="bi bi-tags" style="font-size: 1rem; color: #ffab00;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Kategori & Tag</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #ffab00; border-radius: 2px; opacity: {{ request()->routeIs('admin.kategori-tag.*') || request()->routeIs('admin.kategori.*') || request()->routeIs('admin.tag.*')  ? '1' : '0' }};"></div>
                </a>
            </li>
            
            <!-- Manajemen User -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(100, 108, 255, 0.15); border-radius: 8px;">
                        <i class="bi bi-people" style="font-size: 1rem; color: #646cff;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Manajemen User</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #646cff; border-radius: 2px; opacity: {{ request()->routeIs('users.*') ? '1' : '0' }};"></div>
                </a>
            </li>
            
            <!-- Statistik & Laporan -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.statistik') }}" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(0, 200, 255, 0.15); border-radius: 8px;">
                        <i class="bi bi-bar-chart" style="font-size: 1rem; color: #00c8ff;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Statistik & Laporan</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #00c8ff; border-radius: 2px; opacity: {{ request()->routeIs('admin.laporan.*') ? '1' : '0' }};"></div>
                </a>
            </li>
            
            <!-- Pengaturan Sistem -->
            {{-- <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 " href="" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.25s ease;">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(108, 99, 255, 0.15); border-radius: 8px;">
                        <i class="bi bi-gear" style="font-size: 1rem; color: #6c63ff;"></i>
                    </div>
                    <span style="font-size: 0.925rem; font-weight: 500;">Pengaturan Sistem</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #6c63ff; border-radius: 2px; opacity: {{ request()->routeIs('settings.*') ? '1' : '0' }};"></div>
                </a>
            </li> --}}
        </ul>
    </div>
    
    <!-- Footer with Logout Button -->
    <div class="p-3" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn w-100 d-flex align-items-center justify-content-center py-2" 
               style="border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.8); transition: all 0.25s ease;">
                <i class="bi bi-box-arrow-left me-2" style="color: rgba(255, 255, 255, 0.6);"></i> 
                <span style="font-size: 0.9rem; font-weight: 500;">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Custom CSS for Sidebar -->
<style>
    /* Base styles */
    #sidebar {
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    /* Menu item hover and active states */
    .nav-link {
        position: relative;
        margin-bottom: 4px;
    }
    
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: white !important;
    }
    
    .nav-link:hover .icon-wrapper {
        transform: scale(1.05);
    }
    
    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }
    .nav-link.active .active-indicator {
        opacity: 1 !important;
    }
    .nav-link.active .icon-wrapper {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
    
    /* Icon animations */
    .icon-wrapper {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Active indicator animation */
    .active-indicator {
        transition: opacity 0.25s ease, transform 0.25s ease;
    }
    
    .nav-link.active .active-indicator {
        opacity: 1 !important;
        transform: scaleY(1.1);
    }
    
    /* Scrollbar styling */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    #sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.03);
    }
    
    #sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 3px;
    }
    
    #sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }
    
    /* Logout button hover effect */
    .btn:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
    }
    
    .btn:hover i {
        color: white !important;
    }
</style>

<!-- JavaScript for active state handling -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle submenu active state
        function setActiveStates() {
            // Landing Menu
            const landingActive = window.location.pathname.includes('/social-media') || 
                                 window.location.pathname.includes('/setting') || 
                                 window.location.pathname.includes('/slides') || 
                                 window.location.pathname.includes('/acceptances');
            
            if (landingActive) {
                document.querySelector('#landingMenu').classList.add('show');
                document.querySelector('a[href="#landingMenu"]').classList.add('active');
                document.querySelector('a[href="#landingMenu"] .active-indicator').style.opacity = '1';
                document.querySelector('a[href="#landingMenu"] .bi-chevron-down').style.transform = 'rotate(180deg)';
            }
            
            // Profile Menu
            const profileActive = window.location.pathname.includes('/histories') || 
                                window.location.pathname.includes('/detailhistories') || 
                                window.location.pathname.includes('/vision') || 
                                window.location.pathname.includes('/missions') || 
                                window.location.pathname.includes('/wilayah');
            
            if (profileActive) {
                document.querySelector('#profileMenu').classList.add('show');
                document.querySelector('a[href="#profileMenu"]').classList.add('active');
                document.querySelector('a[href="#profileMenu"] .active-indicator').style.opacity = '1';
                document.querySelector('a[href="#profileMenu"] .bi-chevron-down').style.transform = 'rotate(180deg)';
            }
        }
        
        // Initialize active states
        setActiveStates();
        
        // Handle menu clicks
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // For non-collapsible links
                if (!this.getAttribute('data-bs-toggle')) {
                    navLinks.forEach(l => {
                        if (l !== this) {
                            l.classList.remove('active');
                            const indicator = l.querySelector('.active-indicator');
                            if (indicator) indicator.style.opacity = '0';
                        }
                    });
                    
                    this.classList.add('active');
                    const indicator = this.querySelector('.active-indicator');
                    if (indicator) indicator.style.opacity = '1';
                }
            });
        });
        
        // Handle submenu toggle
        const collapseTriggers = document.querySelectorAll('[data-bs-toggle="collapse"]');
        collapseTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const target = this.getAttribute('href');
                const targetElement = document.querySelector(target);
                
                // Rotate icon
                const icon = this.querySelector('.bi-chevron-down');
                if (targetElement.classList.contains('show')) {
                    icon.style.transform = 'rotate(0deg)';
                    this.classList.remove('active');
                    this.querySelector('.active-indicator').style.opacity = '0';
                } else {
                    icon.style.transform = 'rotate(180deg)';
                    
                    // Check if any submenu item is active
                    const subLinks = targetElement.querySelectorAll('.nav-link');
                    let hasActive = false;
                    subLinks.forEach(link => {
                        if (link.classList.contains('active')) {
                            hasActive = true;
                        }
                    });
                    
                    if (hasActive) {
                        this.classList.add('active');
                        this.querySelector('.active-indicator').style.opacity = '1';
                    }
                }
            });
        });
    });
</script>