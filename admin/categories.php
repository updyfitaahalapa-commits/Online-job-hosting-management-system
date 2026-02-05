<?php
// admin/categories.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['admin']);

// In a real app, you'd have a categories table. For now we use the unique categories from jobs.
$stmt = $pdo->query("SELECT DISTINCT category FROM jobs");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">Industry Taxonomy</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Dynamic classification of the Somalia job market.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <button class="btn-premium btn-primary" style="padding: 0.85rem 1.5rem; border-radius: 12px; font-weight: 700; font-size: 0.9rem;">
            <i class="fas fa-plus"></i> Initialize Sector
        </button>
    </div>
</div>

<div class="premium-card" style="padding: 2.5rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <?php foreach ($categories as $cat): ?>
            <div style="padding: 1.5rem; background: #fafbfc; border: 1px solid rgba(0,0,0,0.02); border-radius: 16px; text-align: center; transition: var(--transition);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-md)'; this.style.background='var(--white)'" onmouseout="this.style.transform='none'; this.style.boxShadow='none'; this.style.background='#fafbfc'">
                <div style="width: 48px; height: 48px; background: var(--white); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; color: var(--accent); box-shadow: var(--shadow-sm);">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div style="font-weight: 800; color: var(--text-dark); font-size: 1rem;"><?php echo htmlspecialchars($cat); ?></div>
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-top: 0.5rem;">Active Sector</div>
            </div>
        <?php endforeach; ?>
    </div>
    <div style="padding-top: 1.5rem; border-top: 1px solid #f8f9fa; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fas fa-info-circle" style="color: var(--primary);"></i>
        <span>Taxonomy is dynamically synchronized with live recruitment cycles.</span>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
