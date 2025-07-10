<!-- Main Footer -->
<footer class="footer mt-auto py-3" style="
    background: linear-gradient(135deg, #3E7FC5FF 0%, #3B578AFF 100%);
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
">
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Kolom Kiri (Copyright) -->
            <div class="col-md-6 text-start">
                <small style="
                    color: rgba(255, 255, 255, 0.7) !important;
                    font-size: 0.85rem;
                    font-weight: 400;
                ">
                    © {{ date('Y') }} <span style="font-weight: 500;">Sistem Informasi Pengarsipan & Showcase</span>. All rights reserved.
                </small>
            </div>
            
            <!-- Kolom Kanan (Version Info) -->
            <div class="col-md-6 text-end">
                <div class="d-inline-flex align-items-center gap-3">
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem;">
                        <i class="bi bi-github me-1" style="font-size: 0.9rem;"></i> v2.1.0
                    </span>
                    <span style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem;">
                        <i class="bi bi-clock-history me-1" style="font-size: 0.9rem;"></i> Last updated: {{ now()->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }

    .footer:hover small,
    .footer:hover span {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    @media (max-width: 768px) {
        .footer .row {
            flex-direction: column;
            gap: 0.5rem;
            text-align: center;
        }

        .footer .col-md-6 {
            width: 100% !important;
            justify-content: center !important;
        }

        .footer .text-end {
            text-align: center !important;
        }
    }
</style>
