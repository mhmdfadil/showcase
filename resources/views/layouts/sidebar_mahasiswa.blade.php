<div id="sidebar" class="d-flex flex-column bg-dark text-white" style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); min-height: 100vh; width: 280px;">
    <!-- Brand Section with Elegant Design -->
    <div class="p-3" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="sidebar-brand d-flex align-items-center">
            <div class="icon-wrapper bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" 
                 style="width: 40px; height: 40px; background: linear-gradient(45deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);">
                <i class="bi bi-house-door fs-5 text-white"></i>
            </div>
            <span class="fs-6 fw-bold" style="letter-spacing: 1px; font-family: 'Poppins', sans-serif; background: linear-gradient(to right, #ffffff, #d1d1d1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">MAHASISWA</span>
        </div>
    </div>
    
    <!-- User Profile with Glassmorphism Effect -->
    <div class="px-4 py-2 user-info" style="margin: 1rem; background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(12px); border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="d-flex align-items-center">
            <div class="user-icon me-3">
                <div class="avatar rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 40px; height: 40px; background: linear-gradient(45deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 4px 10px rgba(17, 153, 142, 0.4);">
                    <i class="bi bi-person-fill text-white fs-5"></i>
                </div>
            </div>
            <div>
                <div class="fw-bold text-white" style="font-size: 0.95rem;">{{ Auth::user()->nama ?? 'Admin' }}</div>
                <small class="text-muted" style="color: rgba(255, 255, 255, 0.7) !important; font-size: 0.8rem;">{{ Auth::user()->no_hp ?? '+62 123 4567 8910' }}</small>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu with Modern Icons and Hover Effects -->
    <div class="flex-grow-1 px-3 overflow-auto" style="scrollbar-width: thin;">
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center py-2 px-3 rounded-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="" 
                   style="color: rgba(255, 255, 255, 0.85); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                    <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background: rgba(17, 153, 142, 0.15); border-radius: 8px;">
                        <i class="bi bi-speedometer2" style="font-size: 1rem; color: #38ef7d;"></i>
                    </div>
                    <span style="font-size: 0.95rem;">Dashboard</span>
                    <div class="active-indicator ms-auto" style="width: 4px; height: 24px; background: #38ef7d; border-radius: 2px; opacity: {{ request()->routeIs('dashboard') ? '1' : '0' }};"></div>
                </a>
            </li>
            
            
        </ul>
    </div>
    
    <!-- Footer with Logout Button -->
    <div class="p-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <form method="POST" action="">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center py-2" 
               style="border-radius: 8px; border-color: rgba(255, 255, 255, 0.2); transition: all 0.3s ease;">
                <i class="bi bi-box-arrow-left me-2"></i> 
                <span style="font-size: 0.9rem;">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Custom CSS for Sidebar -->
<style>
    /* Smooth animations and transitions */
    .icon-wrapper {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .nav-link {
        position: relative;
        margin-bottom: 2px;
    }
    
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }
    
    .nav-link:hover .icon-wrapper {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    
    /* Submenu active state */
    .nav-link.collapsed .bi-chevron-down {
        transform: rotate(0deg) !important;
    }
    
    .nav-link:not(.collapsed) .bi-chevron-down {
        transform: rotate(180deg) !important;
    }
    
    /* Scrollbar styling */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    #sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    
    #sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
    
    #sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Gradient text for brand */
    @supports (-webkit-background-clip: text) {
        .gradient-text {
            background: linear-gradient(to right, #38ef7d, #11998e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
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