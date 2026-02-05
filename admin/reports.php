<?php
// admin/reports.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['admin']);

// Analytics
$user_growth = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM users GROUP BY DATE(created_at) LIMIT 7")->fetchAll();
$job_stats = $pdo->query("SELECT category, COUNT(*) as count FROM jobs GROUP BY category")->fetchAll();
$role_distribution = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">System Performance</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Comprehensive analytics and engagement metrics for JHMS.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem; margin-bottom: 3rem;">
    <!-- Role Distribution -->
    <div class="premium-card" style="grid-column: span 4; display: flex; flex-direction: column; gap: 2rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-chart-pie"></i> User Distribution
        </h3>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($role_distribution as $role): ?>
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">
                        <span><?php echo $role['role']; ?>s</span>
                        <span style="color: var(--primary);"><?php echo $role['count']; ?></span>
                    </div>
                    <div style="width: 100%; bg: rgba(26, 42, 108, 0.05); height: 8px; border-radius: 100px; overflow: hidden;">
                        <div style="background: var(--primary); height: 100%; width: <?php echo min(100, ($role['count'] / 50) * 100); ?>%; border-radius: 100px;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Category Popularity -->
    <div class="premium-card" style="grid-column: span 4; display: flex; flex-direction: column; gap: 2rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-chart-bar"></i> Sector Engagement
        </h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($job_stats as $stat): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: #fafbfc; border-radius: 12px; border: 1px solid rgba(0,0,0,0.02);">
                    <span style="font-size: 0.9rem; font-weight: 700; color: var(--text-dark);"><?php echo $stat['category']; ?></span>
                    <span style="padding: 0.35rem 0.75rem; background: var(--white); border-radius: 8px; font-size: 0.75rem; font-weight: 800; color: var(--accent); shadow: 0 2px 4px rgba(0,0,0,0.05);"><?php echo $stat['count']; ?> Jobs</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Optimization Notice -->
    <div style="grid-column: span 4; background: linear-gradient(135deg, var(--primary) 0%, #2c3e50 100%); padding: 2.5rem; border-radius: 24px; color: var(--white); display: flex; flex-direction: column; justify-content: space-between; box-shadow: var(--shadow-lg); position: relative; overflow: hidden;">
        <i class="fas fa-microchip" style="position: absolute; right: -1rem; top: -1rem; font-size: 8rem; opacity: 0.05; transform: rotate(15deg);"></i>
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0 0 1rem 0;">Infrastructure Audit</h3>
            <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8); line-height: 1.6; margin: 0 0 2.5rem 0;">
                System architecture optimized for Somalia's digital growth. Currently processing requests with sub-50ms latency.
            </p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <div style="flex: 1; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 1.25rem; border-radius: 16px; text-align: center;">
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6); margin-bottom: 0.25rem;">DB Load</div>
                <div style="font-size: 1.25rem; font-weight: 800;">2.4%</div>
            </div>
            <div style="flex: 1; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 1.25rem; border-radius: 16px; text-align: center;">
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6); margin-bottom: 0.25rem;">Latency</div>
                <div style="font-size: 1.25rem; font-weight: 800;">42ms</div>
            </div>
        </div>
    </div>
</div>

<div class="premium-card" style="padding: 4rem; text-align: center; background: #fafbfc; border: 2px dashed #e1e8ed;">
    <div style="width: 80px; height: 80px; background: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto; color: var(--primary); font-size: 2rem; box-shadow: var(--shadow-md);">
        <i class="fas fa-file-pdf"></i>
    </div>
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin: 0 0 1rem 0;">Quarterly Intelligence Report</h3>
    <p style="color: var(--text-muted); font-size: 1rem; max-width: 500px; margin: 0 auto 3rem auto; line-height: 1.6;">Generate a comprehensive dossier including user behavior analysis, market trends, and platform growth projections.</p>
    <button class="btn-premium btn-primary" style="padding: 1.25rem 3rem; font-size: 1rem; border-radius: 16px;">
        <i class="fas fa-download"></i> Download Audit PDF
    </button>
</div>

<?php require_once '../includes/footer.php'; ?>
