<?php
// employer/manage_jobs.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$employer_id = $_SESSION['user_id'];
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Handle Delete
if (isset($_GET['delete'])) {
    $job_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ? AND employer_id = ?");
        $stmt->execute([$job_id, $employer_id]);
        $_SESSION['success'] = "Job deleted successfully.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to delete job.";
    }
    redirect('manage_jobs.php');
}

// Fetch all jobs for this employer
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE employer_id = ? ORDER BY created_at DESC");
$stmt->execute([$employer_id]);
$jobs = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 2.25rem; font-weight: 900; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.75rem 0;">Vacancies Ledger</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500; margin: 0;">Orchestrate your talent acquisition pipeline and active job postings.</p>
    </div>
    <a href="post_job.php" class="btn-premium btn-primary" style="padding: 1rem 2.5rem; border-radius: 18px; font-weight: 800; text-decoration: none; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.15);">
        <i class="fas fa-plus"></i> Initiate Vacancy
    </a>
</div>

<?php if ($success): ?>
    <div style="background: #f0fdf4; color: #166534; padding: 1.25rem 2rem; border-radius: 20px; margin-bottom: 3rem; font-weight: 800; display: flex; align-items: center; gap: 1rem; border: 1px solid #dcfce7; font-size: 0.95rem;">
        <i class="fas fa-circle-check"></i>
        <span><?php echo $success; ?></span>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div style="background: #fff1f0; color: #cf1322; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid #ffa39e;">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo $error; ?></span>
    </div>
<?php endif; ?>

<div class="premium-card" style="padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.8);">
    <div style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Active Deployments</h3>
        <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
            Inventory: <?php echo count($jobs); ?> Vacancies
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Vacancy Profile</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Parameters</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Status</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Intelligence</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9; text-align: right;">Manipulation</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php if (empty($jobs)): ?>
                    <tr>
                        <td colspan="5" style="padding: 8rem 2.5rem; text-align: center;">
                            <div style="width: 100px; height: 100px; background: radial-gradient(circle at center, var(--white), #f1f5f9); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2.5rem auto; color: #94a3b8; font-size: 2.5rem; border: 1px solid #e2e8f0; box-shadow: var(--shadow-sm);">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3 style="color: var(--text-dark); font-weight: 900; font-size: 1.5rem; margin-bottom: 0.75rem; letter-spacing: -0.5px;">Institutional Expansion</h3>
                            <p style="color: var(--text-muted); margin-bottom: 3rem; font-size: 1.1rem; font-weight: 500;">No vacancies identified. Begin your institutional recruitment strategy now.</p>
                            <a href="post_job.php" class="btn-premium btn-primary" style="padding: 1.1rem 3rem; border-radius: 18px; font-weight: 800; font-size: 1rem;">Initiate First Vacancy</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.75rem 2.5rem;">
                                <div style="font-weight: 900; color: var(--text-dark); font-size: 1.1rem; letter-spacing: -0.3px;"><?php echo htmlspecialchars($job['title']); ?></div>
                                <div style="font-size: 0.75rem; color: var(--accent); font-weight: 800; margin-top: 0.5rem; text-transform: uppercase; letter-spacing: 1px;"><?php echo htmlspecialchars($job['category']); ?></div>
                            </td>
                            <td style="padding: 1.75rem 2.5rem;">
                                <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-location-dot" style="opacity: 0.5; font-size: 0.8rem;"></i> <?php echo htmlspecialchars($job['location']); ?>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-top: 0.5rem;"><?php echo htmlspecialchars($job['job_type']); ?> <span style="margin: 0 0.5rem; opacity: 0.3;">|</span> <?php echo htmlspecialchars($job['salary_range']); ?></div>
                            </td>
                            <td style="padding: 1.75rem 2.5rem;">
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 12px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; background: #f0fdf4; color: #166534; border: 1px solid #dcfce7;">
                                    <div style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></div>
                                    <?php echo $job['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 1.75rem 2.5rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span><?php echo date('M d, Y', strtotime($job['created_at'])); ?></span>
                                    <span style="font-size: 0.7rem; opacity: 0.6; font-weight: 600;">Deployment Date</span>
                                </div>
                            </td>
                            <td style="padding: 1.75rem 2.5rem; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 1.25rem;">
                                    <a href="edit_job.php?id=<?php echo $job['id']; ?>" style="width: 38px; height: 38px; border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1rem; transition: var(--transition);" class="hover:bg-primary hover:text-white hover:border-primary"><i class="fas fa-pen-to-square"></i></a>
                                    <a href="manage_jobs.php?delete=<?php echo $job['id']; ?>" onclick="return confirm('Confirm permanent removal?')" style="width: 38px; height: 38px; border-radius: 10px; background: #fef2f2; border: 1px solid #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1rem; transition: var(--transition);" class="hover:bg-red-500 hover:text-white hover:border-red-500"><i class="fas fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
