<?php
// seeker/dashboard.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

$seeker_id = $_SESSION['user_id'];

// Seeker Stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE seeker_id = ?");
$stmt->execute([$seeker_id]);
$total_apps = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE seeker_id = ? AND status = 'shortlisted'");
$stmt->execute([$seeker_id]);
$shortlisted_apps = $stmt->fetchColumn();

// My Recent Applications
$stmt = $pdo->prepare("
    SELECT a.*, j.title as job_title, u.name as company_name 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    JOIN users u ON j.employer_id = u.id 
    WHERE a.seeker_id = ? 
    ORDER BY a.applied_at DESC LIMIT 5
");
$stmt->execute([$seeker_id]);
$recent_apps = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="margin-bottom: 3rem;">
    <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">Candidate Dashboard</h1>
    <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Welcome back, <?php echo explode(' ', $_SESSION['name'])[0]; ?>! Track your journey and discover new paths.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem; margin-bottom: 3rem;">
    <!-- Stat Card: Total Applications -->
    <div style="grid-column: span 3; background: var(--white); padding: 1.75rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 50px; height: 50px; background: rgba(26, 42, 108, 0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.25rem;">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $total_apps; ?></div>
            <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.25rem;">Apps Sent</div>
        </div>
    </div>
    
    <!-- Stat Card: Shortlisted -->
    <div style="grid-column: span 3; background: var(--white); padding: 1.75rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 50px; height: 50px; background: rgba(30, 215, 96, 0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #1ed760; font-size: 1.25rem;">
            <i class="fas fa-star"></i>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $shortlisted_apps; ?></div>
            <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.25rem;">Shortlisted</div>
        </div>
    </div>

    <!-- Quick Action Banner -->
    <div style="grid-column: span 6; background: linear-gradient(135deg, var(--primary) 0%, #2c3e50 100%); padding: 1.75rem 2.5rem; border-radius: 24px; color: var(--white); display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
        <div style="position: relative; z-index: 1;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem 0; font-style: italic;">Find Your Next Role</h3>
            <div style="display: flex; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 12px; padding: 0.35rem;">
                <input type="text" placeholder="I'm looking for..." style="background: transparent; border: none; outline: none; padding: 0.5rem 1rem; color: var(--white); font-size: 0.9rem; min-width: 200px;" class="placeholder:color:rgba(255,255,255,0.5)">
                <button class="btn-premium btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem; border-radius: 8px;">Explore</button>
            </div>
        </div>
        <i class="fas fa-rocket" style="font-size: 3.5rem; opacity: 0.1; position: absolute; right: 1.5rem; top: 1.5rem; transform: rotate(15deg);"></i>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem;">
    <!-- Main Content: Recent Applications -->
    <div style="grid-column: span 8;">
        <div style="background: var(--white); border-radius: 24px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0;">Application Tracking</h2>
                <a href="my_applications.php" style="font-size: 0.85rem; font-weight: 700; color: var(--accent); text-decoration: none;">View Detail History &rarr;</a>
            </div>
            
            <div style="padding: 2rem;">
                <?php if (empty($recent_apps)): ?>
                    <div style="text-align: center; padding: 3rem 0;">
                        <i class="fas fa-folder-open" style="font-size: 3rem; color: #f0f0f0; margin-bottom: 1.5rem;"></i>
                        <p style="color: var(--text-muted); font-size: 0.95rem; font-weight: 600;">No active applications found.</p>
                        <a href="jobs.php" style="color: var(--primary); font-weight: 700; font-size: 0.85rem; margin-top: 0.5rem; display: block;">Start Searching Now</a>
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php foreach ($recent_apps as $app): ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem; border-radius: 16px; background: #fcfcfc; border: 1px solid rgba(0,0,0,0.02); transition: var(--transition);" 
                                 onmouseover="this.style.background='var(--white)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='rgba(26, 42, 108, 0.1)'"
                                 onmouseout="this.style.background='#fcfcfc'; this.style.boxShadow='none'; this.style.borderColor='rgba(0,0,0,0.02)'">
                                <div style="display: flex; align-items: center; gap: 1.25rem;">
                                    <div style="width: 48px; height: 48px; background: var(--white); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 800; font-size: 1.2rem; border: 1px solid #f0f0f0;">
                                        <?php echo strtoupper(substr($app['company_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-dark); margin: 0 0 0.25rem 0;"><?php echo $app['job_title']; ?></h4>
                                        <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;"><?php echo $app['company_name']; ?> &bull; Applied <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="display: inline-block; padding: 0.4rem 0.75rem; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: <?php echo $app['status'] === 'shortlisted' ? '#e8f5e9' : ($app['status'] === 'rejected' ? '#ffebee' : '#e3f2fd'); ?>; color: <?php echo $app['status'] === 'shortlisted' ? '#2e7d32' : ($app['status'] === 'rejected' ? '#c62828' : '#1565c0'); ?>;">
                                        <?php echo $app['status']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar: User Profile & Quick Actions -->
    <div style="grid-column: span 4; display: flex; flex-direction: column; gap: 2rem;">
        <div style="background: var(--white); border-radius: 24px; padding: 2.5rem; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); text-align: center;">
            <div style="position: relative; width: 100px; height: 100px; margin: 0 auto 1.5rem auto;">
                <div style="width: 100%; height: 100%; border-radius: 50%; background: rgba(26, 42, 108, 0.05); border: 4px solid var(--white); box-shadow: var(--shadow-md); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <i class="fas fa-user-tie" style="font-size: 3rem; color: rgba(26, 42, 108, 0.1);"></i>
                </div>
                <a href="profile.php" style="position: absolute; bottom: 0; right: 0; width: 32px; height: 32px; background: var(--white); border-radius: 50%; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: center; color: var(--primary); text-decoration: none;">
                    <i class="fas fa-pen" style="font-size: 0.8rem;"></i>
                </a>
            </div>
            
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin: 0 0 0.5rem 0;"><?php echo $_SESSION['name']; ?></h3>
            <p style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2rem;">Expert Candidate</p>
            
            <div style="display: flex; flex-direction: column; gap: 1rem; text-align: left; padding: 1.5rem; background: #fcfcfc; border-radius: 16px;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-check-circle" style="color: #2ecc71; font-size: 1rem;"></i>
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-dark);">Identity Verified</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-file-invoice" style="color: var(--accent); font-size: 1rem;"></i>
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-dark);">CV Portfolio Updated</span>
                </div>
            </div>
            
            <button class="btn-premium btn-primary" style="width: 100%; padding: 1rem; border-radius: 14px; margin-top: 2rem; justify-content: center; font-size: 0.9rem;">
                Manage Portfolio
            </button>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
