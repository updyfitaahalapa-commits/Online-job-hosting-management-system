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
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 2.25rem; font-weight: 900; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.75rem 0;">Application Pipeline</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500; margin: 0;">Monitor your institutional engagements and selection statuses in real-time.</p>
    </div>
</div>

<div class="premium-card" style="padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.8);">
    <div style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Active Submissions</h3>
        <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">
            Inventory: <?php echo count($applications); ?> Records
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Target Role</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Organization</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Pipeline Status</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Submission</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="4" style="padding: 8rem 2.5rem; text-align: center;">
                            <div style="width: 100px; height: 100px; background: radial-gradient(circle at center, var(--white), #f1f5f9); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2.5rem auto; color: #94a3b8; font-size: 2.5rem; border: 1px solid #e2e8f0;">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <h3 style="color: var(--text-dark); font-weight: 900; font-size: 1.5rem; margin-bottom: 0.75rem; letter-spacing: -0.5px;">Quiescent Pipeline</h3>
                            <p style="color: var(--text-muted); margin-bottom: 3rem; font-size: 1.1rem; font-weight: 500;">No active submissions identified. Discover your next engagement now.</p>
                            <a href="jobs.php" class="btn-premium btn-primary" style="padding: 1.1rem 3rem; border-radius: 18px; font-weight: 800; font-size: 1rem;">Explore Opportunities</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
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
