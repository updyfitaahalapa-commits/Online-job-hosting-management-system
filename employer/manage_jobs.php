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

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.5rem 0;">Vacancies Ledger</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Orchestrate your talent acquisition pipeline and active job postings.</p>
    </div>
    <a href="post_job.php" class="btn-premium btn-primary" style="padding: 1rem 2rem; border-radius: 14px; font-weight: 700; text-decoration: none;">
        <i class="fas fa-plus"></i> Initiate Vacancy
    </a>
</div>

<?php if ($success): ?>
    <div style="background: #e8f5e9; color: #2e7d32; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(46, 204, 113, 0.2);">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $success; ?></span>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div style="background: #fff1f0; color: #cf1322; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid #ffa39e;">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo $error; ?></span>
    </div>
<?php endif; ?>

<div class="premium-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--primary);">Active Postings</h3>
        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">
            Inventory: <?php echo count($jobs); ?> Vacancies
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--white);">
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Vacancy Profile</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Deployment Details</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Status</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Posted</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0; text-align: right;">Manipulation</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php if (empty($jobs)): ?>
                    <tr>
                        <td colspan="5" style="padding: 6rem 2.5rem; text-align: center;">
                            <div style="width: 80px; height: 80px; background: #fafbfc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto; color: #ddd; font-size: 2rem; border: 1px dashed #ddd;">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3 style="color: var(--text-dark); font-weight: 800; margin-bottom: 0.5rem;">Expansion Awaits</h3>
                            <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 0.9rem;">No vacancies found. Begin your talent search now.</p>
                            <a href="post_job.php" class="btn-premium btn-primary" style="padding: 0.85rem 2rem; border-radius: 12px; font-weight: 700;">Post Your First Job</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr style="border-bottom: 1px solid #f8f9fa; transition: var(--transition);" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.25rem 2.5rem;">
                                <div style="font-weight: 800; color: var(--text-dark); font-size: 1rem;"><?php echo htmlspecialchars($job['title']); ?></div>
                                <div style="font-size: 0.75rem; color: var(--accent); font-weight: 700; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo htmlspecialchars($job['category']); ?></div>
                            </td>
                            <td style="padding: 1.25rem 2.5rem;">
                                <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-dark);"><?php echo htmlspecialchars($job['location']); ?> <span style="color: #ddd; margin: 0 0.5rem;">&bull;</span> <?php echo htmlspecialchars($job['job_type']); ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-top: 0.25rem;"><?php echo htmlspecialchars($job['salary_range']); ?></div>
                            </td>
                            <td style="padding: 1.25rem 2.5rem;">
                                <span style="display: inline-block; padding: 0.4rem 0.85rem; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; background: rgba(46, 204, 113, 0.05); color: #2ecc71;">
                                    <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.4rem; opacity: 0.7;"></i>
                                    <?php echo $job['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 1.25rem 2.5rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">
                                <?php echo date('M d, Y', strtotime($job['created_at'])); ?>
                            </td>
                            <td style="padding: 1.25rem 2.5rem; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                                    <a href="edit_job.php?id=<?php echo $job['id']; ?>" style="color: var(--primary); font-size: 1rem; transition: var(--transition);" class="hover:scale-110"><i class="fas fa-edit"></i></a>
                                    <a href="manage_jobs.php?delete=<?php echo $job['id']; ?>" onclick="return confirm('Confirm permanent removal?')" style="color: #ff4d4f; font-size: 1rem; opacity: 0.5; transition: var(--transition);" class="hover:opacity-100 hover:scale-110"><i class="fas fa-trash-alt"></i></a>
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
