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

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 2.25rem; font-weight: 900; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.75rem 0;">Market Opportunities</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500; margin: 0;">Explore premium vacancies curated for Africa's elite talent.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 340px 1fr; gap: 3rem;">
    <!-- Advanced Intelligence Filters -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="premium-card" style="padding: 2.5rem; border: 1px solid rgba(255,255,255,0.8);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                <div style="width: 32px; height: 32px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent);">
                    <i class="fas fa-sliders" style="font-size: 0.9rem;"></i>
                </div>
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--primary); margin: 0; text-transform: uppercase; letter-spacing: 1px;">Intelligence Filter</h3>
            </div>
            
            <form action="jobs.php" method="GET" style="display: flex; flex-direction: column; gap: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Core Keywords</label>
                    <div style="position: relative;">
                        <i class="fas fa-magnifying-glass" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); opacity: 0.5;"></i>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Title or identity..." 
                            style="width: 100%; border: 1px solid #e2e8f0; outline: none; padding: 1rem 1rem 1rem 3.5rem; font-size: 0.95rem; font-weight: 600; border-radius: 16px; font-family: 'Inter', sans-serif; background: #f8fafc; transition: var(--transition);"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Geographic Focus</label>
                    <div style="position: relative;">
                        <i class="fas fa-location-crosshairs" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); opacity: 0.5;"></i>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="Mogadishu..." 
                            style="width: 100%; border: 1px solid #e2e8f0; outline: none; padding: 1rem 1rem 1rem 3.5rem; font-size: 0.95rem; font-weight: 600; border-radius: 16px; font-family: 'Inter', sans-serif; background: #f8fafc; transition: var(--transition);"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem;">Vertical Market</label>
                    <select name="category" style="width: 100%; border: 1px solid #e2e8f0; outline: none; padding: 1rem 1.25rem; font-size: 0.95rem; font-weight: 700; border-radius: 16px; background: #f8fafc; cursor: pointer; font-family: 'Inter', sans-serif; transition: var(--transition);"
                        onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)';">
                        <option value="">Consolidated View</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-premium btn-primary" style="justify-content: center; width: 100%; padding: 1.1rem; border-radius: 18px; font-weight: 800; font-size: 1rem; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.15);">Execute Search</button>
                <a href="jobs.php" style="display: block; text-align: center; font-size: 0.85rem; color: var(--text-muted); text-decoration: none; font-weight: 800;" class="hover:underline">Clear Protocol</a>
            </form>
        </div>
    </div>

    <!-- Job Pulse Stream -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <?php if (empty($jobs)): ?>
            <div class="premium-card" style="text-align: center; padding: 8rem; border: 2px dashed #e2e8f0; background: transparent;">
                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #94a3b8; margin: 0 auto 2rem auto; font-size: 2rem;">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <h3 style="color: var(--text-dark); font-weight: 900; font-size: 1.5rem; margin-bottom: 0.5rem; letter-spacing: -0.5px;">No matches found</h3>
                <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 500;">We couldn't find any opportunities matching these specific criteria.</p>
            </div>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
                <div class="premium-card" style="display: flex; gap: 2.5rem; align-items: center; border: 1px solid rgba(255,255,255,0.8); position: relative; transition: var(--transition);">
                    <div style="width: 75px; height: 75px; background: radial-gradient(circle at center, var(--white), #f1f5f9); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 900; font-size: 1.75rem; border: 1px solid #e2e8f0; box-shadow: var(--shadow-sm); flex-shrink: 0;">
                        <?php echo strtoupper(substr($job['company_name'], 0, 1)); ?>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 900; color: var(--text-dark); letter-spacing: -0.5px;"><?php echo htmlspecialchars($job['title']); ?></h3>
                            <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.6rem 1.2rem; background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; border-radius: 12px;">
                                <?php echo htmlspecialchars($job['job_type']); ?>
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 2rem; font-size: 0.95rem; color: var(--text-muted); font-weight: 600; margin-bottom: 2rem;">
                            <span style="color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-building-columns" style="opacity: 0.5;"></i> <?php echo htmlspecialchars($job['company_name']); ?></span>
                            <span style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-location-dot" style="opacity: 0.5;"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                            <span style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-layer-group" style="opacity: 0.5;"></i> <?php echo htmlspecialchars($job['category']); ?></span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                           <div style="display: flex; align-items: center; gap: 0.75rem;">
                               <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1.5px;">Remuneration</span>
                               <span style="font-weight: 900; color: #10b981; font-size: 1.15rem;"><?php echo htmlspecialchars($job['salary_range']); ?></span>
                           </div>
                           <a href="apply.php?id=<?php echo $job['id']; ?>" class="btn-premium btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem; border-radius: 16px; font-weight: 800; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.1);">Initiate Application</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
