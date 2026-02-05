<!-- Premium Hero Section -->
<div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 12rem 2rem; text-align: center; position: relative; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.03);">
    <!-- High-Impact Background Glows -->
    <div style="position: absolute; top: -200px; right: -100px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%); z-index: 0; filter: blur(60px);"></div>
    <div style="position: absolute; bottom: -150px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%); z-index: 0; filter: blur(40px);"></div>
    
    <div class="content-container" style="position: relative; z-index: 1;">
        <div style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: rgba(255, 255, 255, 0.03); border-radius: 100px; color: var(--accent); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 3rem; backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <i class="fas fa-bolt"></i>
            <span>The Future of Talent Acquisition</span>
        </div>
        
        <h1 style="font-family: 'Poppins', sans-serif; font-size: clamp(3.5rem, 8vw, 6rem); font-weight: 900; color: var(--white); margin-bottom: 2rem; letter-spacing: -3px; line-height: 1; transform: skewX(-1deg);">
            Find Your Dream Job <br><span style="background: linear-gradient(to right, #60a5fa, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">in Somalia.</span>
        </h1>
        
        <p style="font-size: 1.4rem; color: #94a3b8; line-height: 1.8; max-width: 900px; margin: 0 auto 5rem auto; font-weight: 500;">
            JHMS is the next-generation ecosystem connecting Africa's brightest minds <br class="hidden md:block">
            with world-class opportunities through intelligent automation.
        </p>
        
        <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 6rem;">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn-premium btn-primary" style="padding: 1.5rem 3.5rem; font-size: 1.25rem; border-radius: 20px; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3); transform: translateY(0); transition: var(--transition);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-sign-in-alt"></i> Login Now
                </a>
                <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn-premium" style="padding: 1.5rem 3.5rem; font-size: 1.25rem; border-radius: 20px; background: rgba(255,255,255,0.03); color: var(--white); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); transition: var(--transition);" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>seeker/jobs.php" class="btn-premium btn-primary" style="padding: 1.5rem 3.5rem; font-size: 1.25rem; border-radius: 20px; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-search"></i> Explore Roles
                </a>
                <a href="<?php echo BASE_URL; ?>employer/post_job.php" class="btn-premium" style="padding: 1.5rem 3.5rem; font-size: 1.25rem; border-radius: 20px; background: rgba(255,255,255,0.03); color: var(--white); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                    <i class="fas fa-plus"></i> Post Opportunity
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
