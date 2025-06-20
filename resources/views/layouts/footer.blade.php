<!-- Main Footer (outside sidebar) -->
<footer class="footer mt-auto py-3 bg-dark" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <small class="text-muted" style="color: rgb(255, 255, 255) !important;">
                    © {{ date('Y') }} Manajemen Desa Gampong Baro. All rights reserved.
                </small>
            </div>
            
        </div>
    </div>
</footer>

<style>
    /* Existing styles remain the same */
    
    .footer {
        background: linear-gradient(135deg, #02b3ff 0%, #0596c7 50%, #015579 100%) !important;
    }
    
    /* Smooth transition for footer text */
    .footer small {
        transition: color 0.3s ease;
    }
    
    .footer:hover small {
        color: rgba(0, 0, 0, 0.8) !important;
    }
</style>