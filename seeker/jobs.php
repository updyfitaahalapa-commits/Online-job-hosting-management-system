<?php
// seeker/jobs.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$location = isset($_GET['location']) ? sanitize($_GET['location']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Build query
$query = "SELECT j.*, u.name as company_name FROM jobs j JOIN users u ON j.employer_id = u.id WHERE j.status = 'active'";
$params = [];

if ($search) {
    $query .= " AND (j.title LIKE ? OR j.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($location) {
    $query .= " AND j.location LIKE ?";
    $params[] = "%$location%";
}

if ($category) {
    $query .= " AND j.category = ?";
    $params[] = $category;
}

$query .= " ORDER BY j.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$jobs = $stmt->fetchAll();

// Fetch categories for filter
$categories = $pdo->query("SELECT DISTINCT category FROM jobs WHERE status = 'active'")->fetchAll(PDO::FETCH_COLUMN);

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">Market Opportunities</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Explore premium vacancies and ignite your professional journey.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 2rem;">
    <!-- Filters Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="premium-card">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 0 0 1.5rem 0; padding-bottom: 1rem; border-bottom: 1px solid #f8f9fa; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-filter"></i> Search Filter
            </h3>
            
            <form action="jobs.php" method="GET" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Keywords</label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Job title or company..." style="width: 100%; border: 1px solid #e1e8ed; outline: none; padding: 0.85rem 1rem 0.85rem 2.5rem; font-size: 0.9rem; font-weight: 600; border-radius: 12px; font-family: 'Inter', sans-serif;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Location</label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="City or Region..." style="width: 100%; border: 1px solid #e1e8ed; outline: none; padding: 0.85rem 1rem 0.85rem 2.5rem; font-size: 0.9rem; font-weight: 600; border-radius: 12px; font-family: 'Inter', sans-serif;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Industry Sector</label>
                    <select name="category" style="width: 100%; border: 1px solid #e1e8ed; outline: none; padding: 0.85rem 1rem; font-size: 0.9rem; font-weight: 600; border-radius: 12px; background: var(--white); cursor: pointer; font-family: 'Inter', sans-serif;">
                        <option value="">All Sectors</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-premium btn-primary" style="justify-content: center; width: 100%; padding: 1rem; border-radius: 14px; font-weight: 700;">Apply Filters</button>
                <a href="jobs.php" style="display: block; text-align: center; font-size: 0.85rem; color: var(--text-muted); text-decoration: none; font-weight: 700;" class="hover:text-blue-600">Reset Search</a>
            </form>
        </div>
    </div>

    <!-- Job Listings -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <?php if (empty($jobs)): ?>
            <div class="premium-card" style="text-align: center; padding: 6rem; background: #fafbfc; border: 2px dashed #eee;">
                <i class="fas fa-briefcase" style="font-size: 3rem; color: #ddd; margin-bottom: 2rem; opacity: 0.3;"></i>
                <h3 style="color: var(--text-dark); font-weight: 800;">No vacancies found</h3>
                <p style="color: var(--text-muted);">Adjust your criteria to discover new opportunities.</p>
            </div>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
                <div class="premium-card" style="display: flex; gap: 2rem; align-items: center; border: 1px solid rgba(0,0,0,0.02);">
                    <div style="width: 65px; height: 65px; background: #f8f9fa; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 900; font-size: 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02);">
                        <?php echo strtoupper(substr($job['company_name'], 0, 1)); ?>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: var(--text-dark);"><?php echo htmlspecialchars($job['title']); ?></h3>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 0.4rem 0.8rem; background: rgba(46, 204, 113, 0.05); color: #2ecc71; border-radius: 100px;">
                                <?php echo htmlspecialchars($job['job_type']); ?>
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">
                            <span style="color: var(--primary);"><i class="fas fa-building" style="margin-right: 0.4rem;"></i> <?php echo htmlspecialchars($job['company_name']); ?></span>
                            <span><i class="fas fa-map-marker-alt" style="margin-right: 0.4rem;"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                            <span><i class="fas fa-folder-open" style="margin-right: 0.4rem;"></i> <?php echo htmlspecialchars($job['category']); ?></span>
                        </div>
                        <div style="margin-top: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                           <div style="display: flex; align-items: center; gap: 0.5rem;">
                               <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Package:</span>
                               <span style="font-weight: 800; color: #2ecc71; font-size: 1rem;"><?php echo htmlspecialchars($job['salary_range']); ?></span>
                           </div>
                           <a href="apply.php?id=<?php echo $job['id']; ?>" class="btn-premium btn-primary" style="padding: 0.65rem 1.5rem; font-size: 0.85rem; border-radius: 10px;">Apply Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
