<?php
// employer/post_job.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employer_id = $_SESSION['user_id'];
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $location = sanitize($_POST['location']);
    $salary_range = sanitize($_POST['salary_range']);
    $job_type = sanitize($_POST['job_type']);

    try {
        $stmt = $pdo->prepare("INSERT INTO jobs (employer_id, title, description, category, location, salary_range, job_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$employer_id, $title, $description, $category, $location, $salary_range, $job_type]);
        $success = "Job posted successfully!";
    } catch (Exception $e) {
        $error = "Failed to post job. Please try again.";
    }
}

require_once '../includes/header.php';
?>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <a href="dashboard.php" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <i class="fas fa-arrow-left"></i> Return to Console
            </a>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.5rem 0;">Initialize Vacancy</h1>
            <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Broadcast your requirements to Somalia's elite talent pool.</p>
        </div>
    </div>

    <?php if ($error): ?>
        <div style="background: #fff1f0; color: #cf1322; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid #ffa39e;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(46, 204, 113, 0.2);">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $success; ?></span>
        </div>
    <?php endif; ?>

    <form action="post_job.php" method="POST">
        <div class="premium-card" style="padding: 3rem; display: flex; flex-direction: column; gap: 2.5rem;">
            <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Job Designation</label>
                    <input type="text" name="title" required placeholder="e.g. Lead Platform Architect" style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 12px; outline: none; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Industry Sector</label>
                    <select name="category" required style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 12px; outline: none; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; background: var(--white); cursor: pointer;">
                        <option value="IT & Software">IT & Software</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Finance">Finance</option>
                        <option value="Sales">Sales</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Design">Design</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Employment Model</label>
                    <select name="job_type" required style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 12px; outline: none; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; background: var(--white); cursor: pointer;">
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Remote">Remote</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Operational Base / Location</label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt" style="position: absolute; left: 1rem; top: 1.15rem; color: var(--text-muted); font-size: 0.9rem;"></i>
                        <input type="text" name="location" required placeholder="e.g. Mogadishu, SO" style="width: 100%; padding: 1rem 1rem 1rem 2.75rem; border: 1px solid #e2e8f0; border-radius: 12px; outline: none; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Compensation Architecture</label>
                    <div style="position: relative;">
                        <i class="fas fa-coins" style="position: absolute; left: 1rem; top: 1.15rem; color: var(--text-muted); font-size: 0.9rem;"></i>
                        <input type="text" name="salary_range" required placeholder="e.g. $1,200 - $2,500" style="width: 100%; padding: 1rem 1rem 1rem 2.75rem; border: 1px solid #e2e8f0; border-radius: 12px; outline: none; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Strategic Description / Requirements</label>
                <textarea name="description" rows="8" required style="width: 100%; padding: 1.25rem; border: 1px solid #e2e8f0; border-radius: 16px; outline: none; font-size: 0.95rem; font-weight: 500; font-family: 'Inter', sans-serif; line-height: 1.6; transition: border-color 0.2s;" placeholder="Delineate the core responsibilities and preferred candidate profile..." onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
            </div>

            <div style="padding-top: 1rem; border-top: 1px solid #f8f9fa; display: flex; justify-content: flex-end; gap: 1.5rem; align-items: center;">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Deployment items are subject to audit.</span>
                <button type="submit" class="btn-premium btn-primary" style="padding: 1.15rem 3.5rem; font-size: 1rem; border-radius: 16px; box-shadow: var(--shadow-md);">
                    Commit Listing
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
