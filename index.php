<?php
// index.php
require_once 'config/db.php';
require_once 'includes/functions.php';

session_start();

// Fetch latest jobs
$stmt = $pdo->query("SELECT j.*, u.name as company_name FROM jobs j JOIN users u ON j.employer_id = u.id WHERE j.status = 'active' ORDER BY j.created_at DESC LIMIT 6");
$latest_jobs = $stmt->fetchAll();

require_once 'includes/header.php';
?>



<!-- Latest Jobs Section -->
<div style="padding: 6rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h2 style="font-size: 2.25rem; font-weight: 800; color: var(--primary); margin: 0 0 0.5rem 0; letter-spacing: -1px;">Opportunities for You</h2>
            <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Explore the most recent active vacancies in our network.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>seeker/jobs.php" style="color: var(--accent); text-decoration: none; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;" class="hover:gap-3 transition-all">
            Browse All Jobs <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 2rem;">
            <?php if (empty($latest_jobs)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 5rem; color: var(--text-muted); background: var(--white); border-radius: 24px; border: 1px dashed #ddd;">
                    <i class="fas fa-briefcase" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.2;"></i>
                    <p style="font-size: 1.1rem; font-weight: 600;">No active job listings found at this moment.</p>
                    <p style="font-size: 0.9rem;">Check back later or refine your search.</p>
                </div>
            <?php else: ?>
                <?php foreach ($latest_jobs as $job): ?>
                    <div class="premium-card" style="display: flex; flex-direction: column; gap: 1.5rem; border: 1px solid rgba(0,0,0,0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.25rem; font-weight: 800;">
                                <?php echo strtoupper(substr($job['company_name'], 0, 1)); ?>
                            </div>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 0.4rem 0.8rem; background: #e3f2fd; color: var(--primary); border-radius: 100px;">
                                <?php echo htmlspecialchars($job['job_type']); ?>
                            </span>
                        </div>

                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin: 0 0 0.5rem 0;"><?php echo htmlspecialchars($job['title']); ?></h3>
                            <div style="display: flex; align-items: center; gap: 1rem; color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">
                                <span><i class="fas fa-building" style="margin-right: 0.25rem;"></i> <?php echo htmlspecialchars($job['company_name']); ?></span>
                                <span><i class="fas fa-map-marker-alt" style="margin-right: 0.25rem;"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                            </div>
                        </div>

                        <div style="margin-top: auto; pt: 1.5rem; border-top: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Annual Salary</div>
                                <div style="font-weight: 800; color: #2ecc71; font-size: 1.1rem;"><?php echo htmlspecialchars($job['salary_range']); ?></div>
                            </div>
                            <a href="<?php echo BASE_URL; ?>seeker/apply.php?id=<?php echo $job['id']; ?>" class="btn-premium btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.85rem; border-radius: 10px;">
                                Apply Now
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
