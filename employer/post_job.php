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

<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 2.5rem; font-weight: 900; color: var(--primary); letter-spacing: -2px; margin: 0 0 0.75rem 0;">Initialize Vacancy</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500; margin: 0;">Broadcast your requirements to the elite Talent Network.</p>
        </div>
    </div>

    <?php if ($error): ?>
        <div style="background: #fef2f2; color: #991b1b; padding: 1.25rem 2rem; border-radius: 20px; margin-bottom: 3rem; font-weight: 800; display: flex; align-items: center; gap: 1rem; border: 1px solid #fee2e2; font-size: 0.95rem;">
            <i class="fas fa-circle-exclamation"></i>
            <span><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: #f0fdf4; color: #166534; padding: 1.25rem 2rem; border-radius: 20px; margin-bottom: 3rem; font-weight: 800; display: flex; align-items: center; gap: 1rem; border: 1px solid #dcfce7; font-size: 0.95rem;">
            <i class="fas fa-circle-check"></i>
            <span><?php echo $success; ?></span>
        </div>
    <?php endif; ?>

    <form action="post_job.php" method="POST">
        <div class="premium-card" style="padding: 4rem; display: flex; flex-direction: column; gap: 3rem; border: 1px solid rgba(255,255,255,0.8);">
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted);">Vacancy Title</label>
                <div style="position: relative;">
                    <i class="fas fa-briefcase" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); opacity: 0.5;"></i>
                    <input type="text" name="title" required placeholder="e.g. Principal System Architect" 
                        style="width: 100%; padding: 1.1rem 1.1rem 1.1rem 3.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1.05rem; background: #f8fafc; font-weight: 700;"
                        onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Domain / Vertical</label>
                    <select name="category" required 
                        style="width: 100%; padding: 1.1rem 1.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 700; cursor: pointer;"
                        onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                        <option value="IT & Software">Technology & Systems</option>
                        <option value="Marketing">Communications & Brand</option>
                        <option value="Finance">Capital & Treasury</option>
                        <option value="Sales">Revenue Generation</option>
                        <option value="Customer Service">Client Success</option>
                        <option value="Design">Product & Creative</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Operating Model</label>
                    <select name="job_type" required 
                        style="width: 100%; padding: 1.1rem 1.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 700; cursor: pointer;"
                        onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                        <option value="Full-time">Institutional (Full-time)</option>
                        <option value="Part-time">Fractional (Part-time)</option>
                        <option value="Contract">Strategic (Contract)</option>
                        <option value="Remote">Distributed (Remote)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Operational Base</label>
                    <div style="position: relative;">
                        <i class="fas fa-location-dot" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); opacity: 0.5;"></i>
                        <input type="text" name="location" required placeholder="Mogadishu, SO" 
                            style="width: 100%; padding: 1.1rem 1.1rem 1.1rem 3.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 700;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Budgetary Allocation</label>
                    <div style="position: relative;">
                        <i class="fas fa-money-bill-transfer" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); opacity: 0.5;"></i>
                        <input type="text" name="salary_range" required placeholder="e.g. $2,000 - $4,500" 
                            style="width: 100%; padding: 1.1rem 1.1rem 1.1rem 3.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 700;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                    </div>
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1.25rem;">Mission Description & Requirements</label>
                <textarea name="description" rows="10" required 
                    style="width: 100%; padding: 1.75rem; border: 1px solid #e2e8f0; border-radius: 20px; outline: none; font-size: 1.05rem; font-weight: 600; font-family: 'Inter', sans-serif; line-height: 1.8; transition: var(--transition); background: #f8fafc;" 
                    placeholder="Delineate the core responsibilities and preferred candidate profile..." 
                    onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';"></textarea>
            </div>

            <div style="padding-top: 2rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 2rem; align-items: center;">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; opacity: 0.7;">System verified submission.</span>
                <button type="submit" class="btn-premium btn-primary" style="padding: 1.25rem 4rem; font-size: 1.1rem; border-radius: 20px; font-weight: 900; box-shadow: 0 15px 30px rgba(59, 130, 246, 0.2);">
                    Commit to Network
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
