<?php
// admin/companies.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['admin']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

// Fetch all employers with profiles
$stmt = $pdo->query("
    SELECT u.id, u.name, u.email, p.company_name, p.logo_path 
    FROM users u 
    LEFT JOIN profiles p ON u.id = p.user_id 
    WHERE u.role = 'employer'
");
$employers = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">Enterprise Partners</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Audit and manage employer accounts within the ecosystem.</p>
    </div>
</div>

<?php if ($success): ?>
    <div style="background: #e8f5e9; color: #2e7d32; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(46, 204, 113, 0.2);">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $success; ?></span>
    </div>
<?php endif; ?>

<div class="premium-card" style="padding: 2.5rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
        <?php if (empty($employers)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 5rem; color: var(--text-muted); background: #fafbfc; border-radius: 20px; border: 1px dashed #ddd;">
                <i class="fas fa-building" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.1;"></i>
                <p style="font-weight: 700;">No registered enterprises found.</p>
            </div>
        <?php else: ?>
            <?php foreach ($employers as $emp): ?>
                <div style="border: 1px solid rgba(0,0,0,0.03); border-radius: 20px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; background: #fafbfc; transition: var(--transition);" onmouseover="this.style.background='var(--white)'; this.style.boxShadow='var(--shadow-md)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='#fafbfc'; this.style.boxShadow='none'; this.style.transform='none'">
                    <div>
                        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
                            <div style="width: 60px; height: 60px; background: var(--white); border-radius: 14px; display: flex; align-items: center; justify-content: center; overflow: hidden; color: var(--primary); font-weight: 800; font-size: 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02);">
                                <?php if ($emp['logo_path']): ?>
                                    <img src="<?php echo BASE_URL . $emp['logo_path']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <?php echo strtoupper(substr($emp['company_name'] ?: $emp['name'], 0, 1)); ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--text-dark);"><?php echo htmlspecialchars($emp['company_name'] ?: $emp['name']); ?></h4>
                                <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem; font-weight: 600; color: var(--text-muted);"><?php echo htmlspecialchars($emp['email']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #f0f0f0; padding-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.7rem; font-weight: 800; color: #2ecc71; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fas fa-certificate"></i> Verified
                        </span>
                        <button class="btn-premium" style="font-size: 0.75rem; padding: 0.5rem 1rem; background: #fff1f0; color: #cf1322; border: 1px solid #ffa39e; border-radius: 8px; font-weight: 700; cursor: pointer;">
                            Revoke
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
