<?php
// seeker/my_applications.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

$seeker_id = $_SESSION['user_id'];

// Fetch all applications for this seeker
$stmt = $pdo->prepare("
    SELECT a.*, j.title as job_title, j.location, u.name as company_name 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    JOIN users u ON j.employer_id = u.id 
    WHERE a.seeker_id = ? 
    ORDER BY a.applied_at DESC
");
$stmt->execute([$seeker_id]);
$applications = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="margin-bottom: 30px;">
    <a href="dashboard.php" style="color: #888; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 10px;">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    <h1 style="font-size: 28px; color: #2c3e50; margin: 0;">My Applications</h1>
    <p style="color: #666; font-size: 14px; margin-top: 5px;">Track the status of your job applications.</p>
</div>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <a href="dashboard.php" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
            <i class="fas fa-arrow-left"></i> Return to Console
        </a>
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.5rem 0;">Aspiration Track</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Real-time status monitoring of your active career pursuits.</p>
    </div>
</div>

<div class="premium-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--primary);">Active Applications</h3>
        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">
            Total: <?php echo count($applications); ?> Entries
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--white);">
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Opportunity</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Enterprise</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Applied On</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Pipeline Status</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="5" style="padding: 6rem 2.5rem; text-align: center;">
                            <div style="width: 80px; height: 80px; background: #fafbfc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto; color: #ddd; font-size: 2rem; border: 1px dashed #ddd;">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h3 style="color: var(--text-dark); font-weight: 800; margin-bottom: 0.5rem;">Launch Your Career</h3>
                            <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 0.9rem;">You haven't submitted any applications yet. Explore our curated listings.</p>
                            <a href="jobs.php" class="btn-premium btn-primary" style="padding: 0.85rem 2rem; border-radius: 12px; font-weight: 700;">Find Opportunities</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                        <tr style="border-bottom: 1px solid #f8f9fa; transition: var(--transition);" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.25rem 2.5rem;">
                                <div style="font-weight: 800; color: var(--text-dark); font-size: 1rem;"><?php echo htmlspecialchars($app['job_title']); ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 0.25rem;">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 0.4rem; color: var(--accent);"></i> <?php echo htmlspecialchars($app['location']); ?>
                                </div>
                            </td>
                            <td style="padding: 1.25rem 2.5rem; font-weight: 700; color: var(--primary);"><?php echo htmlspecialchars($app['company_name']); ?></td>
                            <td style="padding: 1.25rem 2.5rem; color: var(--text-muted); font-weight: 600;"><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                            <td style="padding: 1.25rem 2.5rem;">
                                <span style="display: inline-block; padding: 0.4rem 0.85rem; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
                                    <?php 
                                        if($app['status'] === 'pending') echo 'background: #fff8e1; color: #f57f17;';
                                        elseif($app['status'] === 'shortlisted') echo 'background: #e8f5e9; color: #2e7d32;';
                                        else echo 'background: #ffebee; color: #c62828;';
                                    ?>">
                                    <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.4rem; opacity: 0.7;"></i>
                                    <?php echo $app['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 1.25rem 2.5rem; text-align: right;">
                                <button class="btn-premium" style="background: none; border: 1px solid #e1e8ed; color: var(--text-muted); font-size: 0.75rem; font-weight: 700; padding: 0.5rem 1rem; border-radius: 8px; cursor: default;">
                                    Monitoring
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
