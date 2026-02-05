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
<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem; margin-bottom: 3rem;">
    <div style="grid-column: span 4; background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.5rem;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $total_jobs; ?></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem;">Active Jobs</div>
        </div>
    </div>
    
    <div style="grid-column: span 4; background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: rgba(108, 92, 231, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #6c5ce7; font-size: 1.5rem;">
            <i class="fas fa-users-viewfinder"></i>
        </div>
        <div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $total_apps; ?></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem;">Candidates</div>
        </div>
    </div>
    
    <div style="grid-column: span 4; background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: rgba(241, 196, 15, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #f39c12; font-size: 1.5rem;">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $pending_apps; ?></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem;">Pending Review</div>
        </div>
    </div>
</div>

<div class="premium-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--primary);">Recent Applicant Activity</h3>
        <a href="applications.php" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 700;">Full Talent Pipeline &rarr;</a>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--white);">
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Candidate</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Target Role</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Applied On</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Current Phase</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php if (empty($recent_apps)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-muted); font-style: italic;">No candidate applications received yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_apps as $app): ?>
                        <tr style="border-bottom: 1px solid #f8f9fa; transition: var(--transition);" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.25rem 2.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 800; font-size: 0.9rem;">
                                        <?php echo strtoupper(substr($app['seeker_name'], 0, 1)); ?>
                                    </div>
                                    <span style="font-weight: 700; color: var(--text-dark);"><?php echo htmlspecialchars($app['seeker_name']); ?></span>
                                </div>
                            </td>
                            <td style="padding: 1.25rem 2.5rem; color: var(--text-muted); font-weight: 600;"><?php echo htmlspecialchars($app['job_title']); ?></td>
                            <td style="padding: 1.25rem 2.5rem; color: var(--text-muted);"><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                            <td style="padding: 1.25rem 2.5rem;">
                                <span style="display: inline-block; padding: 0.4rem 0.75rem; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
                                    <?php 
                                        if($app['status'] === 'pending') echo 'background: #fff3e0; color: #e65100;';
                                        elseif($app['status'] === 'shortlisted') echo 'background: #e8f5e9; color: #2e7d32;';
                                        else echo 'background: #ffebee; color: #c62828;';
                                    ?>">
                                    <?php echo $app['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 1.25rem 2.5rem; text-align: right;">
                                <a href="application_detail.php?id=<?php echo $app['id']; ?>" class="btn-premium" style="padding: 0.5rem 1.25rem; font-size: 0.85rem; background: var(--white); color: var(--primary); border: 1px solid #e1e8ed; border-radius: 8px;">Review File</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
