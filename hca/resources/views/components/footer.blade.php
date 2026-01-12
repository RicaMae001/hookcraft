{{-- resources/views/components/footer.blade.php --}}
<footer class="footer-component py-4 text-center bg-dark text-white mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-md-start">
                <p class="mb-0">
                    <span id="staffLoginTrigger" class="footer-hidden-link text-white" style="cursor: default;">
                        &copy;
                    </span> 
                    2025 Hookcraft Avenue. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    <a href="#" class="text-white me-3" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-white me-3" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-white" aria-label="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer-component {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
}

.footer-hidden-link {
    opacity: 0.3;
    transition: opacity 0.3s ease;
    user-select: none;
}

.footer-hidden-link:hover {
    opacity: 1;
}

.footer-hidden-link.clicked {
    animation: pulse 0.3s ease;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

.footer-component .social-links a {
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.footer-component .social-links a:hover {
    color: #3b5998 !important;
    transform: translateY(-3px);
}

.footer-component .social-links a:nth-child(2):hover {
    color: #e4405f !important;
}

.footer-component .social-links a:nth-child(3):hover {
    color: #1da1f2 !important;
}
</style>

<script src="{{ asset('js/forfooter/footer-handler.js') }}" defer></script>