        </div>
    </main>
    <footer style="background-color: var(--white); border-top: 1px solid #eee; padding: 4rem 2rem; text-align: center; width: 100%; box-sizing: border-box;">
        <div class="content-container">
            <div style="margin-bottom: 2rem; display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                <div class="logo-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <span style="font-weight: 800; color: var(--primary); font-size: 1.5rem; letter-spacing: -0.5px;">JHMS</span>
            </div>
            
            <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 600px; margin: 0 auto 2.5rem auto; line-height: 1.6;">
                Somalia's premier job hunting platform connecting top talent with industry-leading employers. 
                Experience a new standard of professional recruitment.
            </p>

            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; margin-bottom: 2.5rem;">
                <a href="<?php echo BASE_URL; ?>seeker/jobs.php" style="color: var(--text-dark); text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: var(--transition);" class="hover:text-blue-600">Browse Jobs</a>
                <a href="#" style="color: var(--text-dark); text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: var(--transition);" class="hover:text-blue-600">Company Catalog</a>
                <a href="#" style="color: var(--text-dark); text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: var(--transition);" class="hover:text-blue-600">Terms of Service</a>
                <a href="#" style="color: var(--text-dark); text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: var(--transition);" class="hover:text-blue-600">Privacy Policy</a>
            </div>

            <div style="font-size: 0.8rem; color: var(--text-muted); border-top: 1px solid #f8f9fa; padding-top: 2rem;">
                &copy; <?php echo date('Y'); ?> <span style="font-weight: 700; color: var(--primary);">Online Job Hunting Management System</span>. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const body = document.body;
            
            function toggleSidebar() {
                body.classList.toggle('sidebar-open');
                const isOpen = body.classList.contains('sidebar-open');
                // Optional: Prevent body scroll when sidebar is open
                body.style.overflow = isOpen ? 'hidden' : '';
            }

            if (toggle) {
                toggle.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && body.classList.contains('sidebar-open')) {
                    toggleSidebar();
                }
            });

            // Smooth Scroll (Optional but Premium)
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>
