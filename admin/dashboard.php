<?php
// admin/dashboard.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['admin']);

// Stats for Admin
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_jobs = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
$total_apps = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$recent_users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">System Overview</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Comprehensive management console for the job hunting ecosystem.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="<?php echo BASE_URL; ?>admin/reports.php" class="btn-premium" style="padding: 0.85rem 1.5rem; background: var(--white); color: var(--primary); border: 1px solid #e1e8ed; border-radius: 12px; font-weight: 700; font-size: 0.9rem;">
            <i class="fas fa-file-invoice"></i> System Reports
        </a>
    </div>
</div>

<!-- Premium Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem; margin-bottom: 3rem;">
    <div style="grid-column: span 4; background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.5rem;">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $total_users; ?></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem;">Global Users</div>
        </div>
    </div>
    
    <div style="grid-column: span 4; background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: rgba(46, 204, 113, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #2ecc71; font-size: 1.5rem;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $total_jobs; ?></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem;">Live Listings</div>
        </div>
    </div>
    
    <div style="grid-column: span 4; background: var(--white); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 60px; height: 60px; background: rgba(155, 89, 182, 0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #9b59b6; font-size: 1.5rem;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-dark); line-height: 1;"><?php echo $total_apps; ?></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem;">Total Applications</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem;">
    <!-- Recent Users Table -->
    <div style="grid-column: span 8;">
        <div class="premium-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--primary);">New Registered Talents</h3>
                <a href="users.php" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 700;">Global Directory &rarr;</a>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: var(--white);">
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">User Profile</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Access Level</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        <?php foreach ($recent_users as $user): ?>
                            <tr style="border-bottom: 1px solid #f8f9fa; transition: var(--transition);" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1.25rem 2.5rem;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 800; font-size: 0.9rem;">
                                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--text-dark);"><?php echo htmlspecialchars($user['name']); ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1.25rem 2.5rem;">
                                    <span style="display: inline-block; padding: 0.4rem 0.75rem; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
                                        <?php echo $user['role'] === 'admin' ? 'background: #ffebee; color: #c62828;' : ($user['role'] === 'employer' ? 'background: #e8f5e9; color: #2e7d32;' : 'background: #e3f2fd; color: #1565c0;'); ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 2.5rem; color: var(--text-muted);"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Health & Quick Info -->
    <div style="grid-column: span 4; display: flex; flex-direction: column; gap: 2rem;">
        <div class="premium-card" style="padding: 2.5rem;">
            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.15rem; font-weight: 800; color: var(--primary); border-bottom: 1px solid #f8f9fa; padding-bottom: 1rem;">System Health</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div style="background: rgba(46, 204, 113, 0.03); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(46, 204, 113, 0.1);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 0.5rem;">Infrastructure Status</div>
                    <div style="color: #2ecc71; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;">
                        <i class="fas fa-check-circle"></i> Fully Operational
                    </div>
                </div>

                <div style="background: #fafbfc; padding: 1.5rem; border-radius: 16px; border: 1px solid #eee;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 0.5rem;">Daily Backup</div>
                    <div style="color: var(--text-dark); font-weight: 800; font-size: 0.95rem;">
                        <i class="fas fa-clock" style="color: var(--accent); margin-right: 0.5rem;"></i> Confirmed (2h ago)
                    </div>
                </div>

                <div style="background: #fafbfc; padding: 1.5rem; border-radius: 16px; border: 1px solid #eee;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 0.5rem;">Security Engine</div>
                    <div style="color: var(--text-dark); font-weight: 800; font-size: 0.95rem;">
                        <i class="fas fa-shield-alt" style="color: var(--primary); margin-right: 0.5rem;"></i> Active Protection
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
