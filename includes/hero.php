<!-- Premium Hero Section -->
<div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 10rem 2rem; text-align: center; position: relative; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.05);">
    <!-- Abstract Background Elements -->
    <div style="position: absolute; top: -150px; right: -150px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%); z-index: 0;"></div>
    <div style="position: absolute; bottom: -100px; left: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%); z-index: 0;"></div>
    
    <div class="content-container" style="position: relative; z-index: 1;">
        <div style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.5rem; background: rgba(59, 130, 246, 0.1); border-radius: 100px; color: var(--accent); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 2.5rem; backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, 0.2);">
            <i class="fas fa-rocket"></i>
            <span>Premium Career Gateway</span>
        </div>
        
        <h1 style="font-family: 'Poppins', sans-serif; font-size: clamp(3rem, 6vw, 5rem); font-weight: 900; color: var(--white); margin-bottom: 1.5rem; letter-spacing: -2px; line-height: 1.05;">
            Find Your Dream Job <br><span style="background: linear-gradient(to right, #60a5fa, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">in Somalia.</span>
        </h1>
        
        <p style="font-size: 1.25rem; color: #94a3b8; line-height: 1.8; max-width: 800px; margin: 0 auto 4rem auto;">
            JHMS connects elite Somali talent with industry leaders. <br class="hidden md:block">
            Take the next step in your professional evolution with our intelligent recruitment platform.
        </p>
        
        <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 5rem;">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn-premium btn-primary" style="padding: 1.25rem 2.5rem; font-size: 1.1rem; border-radius: 16px; box-shadow: 0 10px 20px rgba(26, 42, 108, 0.2);">
                    <i class="fas fa-sign-in-alt"></i> Login to Account
                </a>
                <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn-premium" style="padding: 1.25rem 2.5rem; font-size: 1.1rem; border-radius: 16px; background: var(--white); color: var(--primary); border: 2px solid var(--primary);">
                    <i class="fas fa-user-plus"></i> Join the Platform
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>seeker/jobs.php" class="btn-premium btn-primary" style="padding: 1.25rem 2.5rem; font-size: 1.1rem; border-radius: 16px; box-shadow: 0 10px 20px rgba(26, 42, 108, 0.2);">
                    <i class="fas fa-search"></i> Find My Move
                </a>
                <a href="<?php echo BASE_URL; ?>employer/post_job.php" class="btn-premium" style="padding: 1.25rem 2.5rem; font-size: 1.1rem; border-radius: 16px; background: var(--white); color: var(--primary); border: 2px solid var(--primary);">
                    <i class="fas fa-plus"></i> Post a Vacancy
                </a>
            <?php endif; ?>
        </div>

        <!-- Premium Search Bar -->
        <div style="max-width: 1000px; margin: 0 auto; margin-top: -3.5rem;">
            <form action="<?php echo BASE_URL; ?>seeker/jobs.php" method="GET" 
                  style="background: var(--white); padding: 1rem; border-radius: 24px; box-shadow: var(--shadow-lg); display: grid; grid-template-columns: 2fr 1fr 150px; gap: 0.75rem; align-items: center;">
                <div style="position: relative; border-right: 1px solid #f0f0f0; padding: 0.5rem 1rem;">
                    <i class="fas fa-briefcase" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="keyword" placeholder="Job title, keywords, or company..." 
                        style="width: 100%; border: none; outline: none; padding: 0.5rem 0.5rem 0.5rem 2rem; font-size: 0.95rem; font-weight: 500;">
                </div>
                <div style="position: relative; padding: 0.5rem 1rem;">
                    <i class="fas fa-map-marker-alt" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="location" placeholder="City or Region..." 
                        style="width: 100%; border: none; outline: none; padding: 0.5rem 0.5rem 0.5rem 2rem; font-size: 0.95rem; font-weight: 500;">
                </div>
                <button type="submit" class="btn-premium btn-primary" style="justify-content: center; height: 50px; border-radius: 16px;">
                    Search
                </button>
            </form>
        </div>
    </div>
</div>
