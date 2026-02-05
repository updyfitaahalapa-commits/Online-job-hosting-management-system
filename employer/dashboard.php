<?php
// employer/dashboard.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$employer_id = $_SESSION['user_id'];

// Stats for Employer
$stmt = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE employer_id = ?");
$stmt->execute([$employer_id]);
$total_jobs = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ?");
$stmt->execute([$employer_id]);
$total_apps = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ? AND a.status = 'pending'");
$stmt->execute([$employer_id]);
$pending_apps = $stmt->fetchColumn();

// Recent Applications
$stmt = $pdo->prepare("
    SELECT a.*, j.title as job_title, u.name as seeker_name 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    JOIN users u ON a.seeker_id = u.id 
    WHERE j.employer_id = ? 
    ORDER BY a.applied_at DESC LIMIT 5
");
$stmt->execute([$employer_id]);
$recent_apps = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">Recruitment Console</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Streamline your hiring process and manage your active campaigns.</p>
    </div>
    <a href="post_job.php" class="btn-premium btn-primary" style="padding: 1rem 2rem; border-radius: 14px; box-shadow: var(--shadow-md);">
        <i class="fas fa-plus-circle"></i> Post New Vacancy
    </a>
</div>

<!-- Premium Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2.5rem; margin-bottom: 4rem;">
    <div class="premium-card" style="grid-column: span 4; display: flex; align-items: center; gap: 2rem; border: 1px solid rgba(255,255,255,0.8);">
        <div style="width: 70px; height: 70px; background: rgba(59, 130, 246, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.75rem; box-shadow: inset 0 2px 4px rgba(59, 130, 246, 0.05);">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: 900; color: var(--text-dark); line-height: 1; letter-spacing: -1px;"><?php echo $total_jobs; ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.75rem;">Active Mandates</div>
        </div>
    </div>
    
    <div class="premium-card" style="grid-column: span 4; display: flex; align-items: center; gap: 2rem; border: 1px solid rgba(255,255,255,0.8);">
        <div style="width: 70px; height: 70px; background: rgba(139, 92, 246, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: #8b5cf6; font-size: 1.75rem; box-shadow: inset 0 2px 4px rgba(139, 92, 246, 0.05);">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: 900; color: var(--text-dark); line-height: 1; letter-spacing: -1px;"><?php echo $total_apps; ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.75rem;">Applications</div>
        </div>
    </div>
    
    <div class="premium-card" style="grid-column: span 4; display: flex; align-items: center; gap: 2rem; border: 1px solid rgba(255,255,255,0.8);">
        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.75rem; box-shadow: inset 0 2px 4px rgba(16, 185, 129, 0.05);">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: 900; color: var(--text-dark); line-height: 1; letter-spacing: -1px;"><?php echo count($recent_apps); ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.75rem;">Recent Activity</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2.5rem;">
    <!-- Recent Applications Table -->
    <div style="grid-column: span 12;">
        <div class="premium-card" style="padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.8);">
            <div style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Candidate Pipeline</h3>
                <a href="applications.php" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;" class="hover:gap-3 transition-all">Full Tracking <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Candidate</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Applied Role</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Status</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Submission</th>
                        </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
