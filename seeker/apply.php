<?php
// seeker/apply.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

$job_id = $_GET['id'] ?? null;
if (!$job_id) redirect('jobs.php');

$seeker_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Check if already applied
$stmt = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?");
$stmt->execute([$job_id, $seeker_id]);
$already_applied = $stmt->fetch();

// Fetch job details
$stmt = $pdo->prepare("SELECT j.*, u.name as company_name FROM jobs j JOIN users u ON j.employer_id = u.id WHERE j.id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job) redirect('jobs.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already_applied) {
    $cover_letter = sanitize($_POST['cover_letter']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO applications (job_id, seeker_id, cover_letter) VALUES (?, ?, ?)");
        $stmt->execute([$job_id, $seeker_id, $cover_letter]);
        $success = "Application submitted successfully!";
        $already_applied = true; // Update state after submission
    } catch (Exception $e) {
        $error = "Failed to submit application. Please try again.";
    }
}

require_once '../includes/header.php';
?>

<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 2.25rem; font-weight: 900; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.75rem 0;">Initiate Engagement</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500; margin: 0;">Submit your credentials for the <span style="color: var(--accent); font-weight: 800;"><?php echo htmlspecialchars($job['title']); ?></span> mandate.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 3rem;">
        <!-- Application Form -->
        <div style="display: flex; flex-direction: column; gap: 2.5rem;">
            <?php if ($success): ?>
                <div class="premium-card" style="background: #f0fdf4; border: 1px solid #dcfce7; text-align: center; padding: 4rem 2rem;">
                    <div style="width: 80px; height: 80px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto; color: #166534; font-size: 2rem; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 900; color: #166534; margin-bottom: 1rem;">Submission Secure</h3>
                    <p style="color: #15803d; font-weight: 600; margin-bottom: 2rem;">Your professional credentials have been successfully transmitted to the Employer.</p>
                    <a href="my_applications.php" class="btn-premium btn-primary" style="padding: 1rem 2.5rem; border-radius: 16px;">Track Engagement Status</a>
                </div>
            <?php elseif ($already_applied): ?>
                <div class="premium-card" style="background: #f8fafc; border: 1px solid #e2e8f0; text-align: center; padding: 4rem 2rem;">
                    <div style="width: 80px; height: 80px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto; color: var(--accent); font-size: 2rem; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 900; color: var(--primary); margin-bottom: 1rem;">Existing Engagement</h3>
                    <p style="color: var(--text-muted); font-weight: 600; margin-bottom: 2rem;">You have already initiated an application for this vacancy.</p>
                    <a href="my_applications.php" class="btn-premium btn-primary" style="padding: 1rem 2.5rem; border-radius: 16px;">Review Submission</a>
                </div>
            <?php else: ?>
                <form action="apply.php?id=<?php echo $job_id; ?>" method="POST" class="premium-card" style="padding: 3rem; border: 1px solid rgba(255,255,255,0.8); display: flex; flex-direction: column; gap: 2.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1.25rem;">Strategic Cover Note (Optional)</label>
                        <textarea name="cover_letter" rows="8" 
                            style="width: 100%; padding: 1.5rem; border-radius: 20px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500; line-height: 1.7;"
                            placeholder="Delineate your strategic fit for this institutional role..."
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';"></textarea>
                    </div>
                    
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 20px; border: 1px dashed #cbd5e1; display: flex; align-items: center; gap: 1.25rem;">
                        <div style="width: 50px; height: 50px; background: var(--white); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #ef4444; border: 1px solid #fee2e2; font-size: 1.25rem; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.9rem; font-weight: 800; color: var(--text-dark);">Verified Resume Attachment</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">System will attach your primary CV dossier.</div>
                        </div>
                        <a href="profile.php" style="font-size: 0.75rem; font-weight: 800; color: var(--accent); text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Update</a>
                    </div>

                    <button type="submit" class="btn-premium btn-primary" style="justify-content: center; padding: 1.25rem; border-radius: 20px; font-weight: 900; font-size: 1.1rem; box-shadow: 0 15px 30px rgba(59, 130, 246, 0.2);">
                        Transmit Application
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="premium-card" style="padding: 3rem; border: 1px solid rgba(255,255,255,0.8);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 36px; height: 36px; background: rgba(59, 130, 246, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent);">
                        <i class="fas fa-align-left" style="font-size: 0.9rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Mandate Specifications</h3>
                </div>
                <div style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.8; font-weight: 500;">
                    <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                </div>
            </div>
        </div>

        <!-- Job Sidebar Info -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <div class="premium-card" style="padding: 2.5rem; border: 1px solid rgba(255,255,255,0.8);">
                <h3 style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 2rem;">Institutional Intel</h3>
                
                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <div style="width: 45px; height: 45px; background: #f0fdf4; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #166534; font-size: 1.1rem;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Remuneration</div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-dark);"><?php echo htmlspecialchars($job['salary_range']); ?></div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <div style="width: 45px; height: 45px; background: #f5f3ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #5b21b6; font-size: 1.1rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Engagement</div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-dark);"><?php echo htmlspecialchars($job['job_type']); ?></div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <div style="width: 45px; height: 45px; background: #eff6ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #1e40af; font-size: 1.1rem;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Vertical</div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-dark);"><?php echo htmlspecialchars($job['category']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="background: var(--primary); padding: 3rem 2.5rem; border-radius: 24px; color: var(--white); position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
                <i class="fas fa-shield-halved" style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.05; transform: rotate(15deg);"></i>
                <h3 style="font-size: 1.25rem; font-weight: 900; margin: 0 0 1rem 0; letter-spacing: -0.5px;">Institutional Safety</h3>
                <p style="font-size: 0.9rem; color: #94a3b8; font-weight: 500; line-height: 1.6; margin: 0;">Never disclose sensitive financial data or transmit currency during the application phase. Our network maintains strict professional auditing.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
