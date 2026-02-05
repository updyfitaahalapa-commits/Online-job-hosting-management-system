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
<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2.5rem; margin-bottom: 4rem;">
    <div class="premium-card" style="grid-column: span 4; display: flex; align-items: center; gap: 2rem; border: 1px solid rgba(255,255,255,0.8);">
        <div style="width: 70px; height: 70px; background: rgba(59, 130, 246, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.75rem; box-shadow: inset 0 2px 4px rgba(59, 130, 246, 0.05);">
            <i class="fas fa-users-viewfinder"></i>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: 900; color: var(--text-dark); line-height: 1; letter-spacing: -1px;"><?php echo $total_users; ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.75rem;">Global Talent</div>
        </div>
    </div>
    
    <div class="premium-card" style="grid-column: span 4; display: flex; align-items: center; gap: 2rem; border: 1px solid rgba(255,255,255,0.8);">
        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.75rem; box-shadow: inset 0 2px 4px rgba(16, 185, 129, 0.05);">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: 900; color: var(--text-dark); line-height: 1; letter-spacing: -1px;"><?php echo $total_jobs; ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.75rem;">Active Mandates</div>
        </div>
    </div>
    
    <div class="premium-card" style="grid-column: span 4; display: flex; align-items: center; gap: 2rem; border: 1px solid rgba(255,255,255,0.8);">
        <div style="width: 70px; height: 70px; background: rgba(139, 92, 246, 0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: #8b5cf6; font-size: 1.75rem; box-shadow: inset 0 2px 4px rgba(139, 92, 246, 0.05);">
            <i class="fas fa-fingerprint"></i>
        </div>
        <div>
            <div style="font-size: 2.25rem; font-weight: 900; color: var(--text-dark); line-height: 1; letter-spacing: -1px;"><?php echo $total_apps; ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.75rem;">Applications</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2.5rem;">
    <!-- Recent Users Table -->
    <div style="grid-column: span 8;">
        <div class="premium-card" style="padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.8);">
            <div style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Recent Talent Acquisitions</h3>
                <a href="users.php" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;" class="hover:gap-3 transition-all">Directory <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Identity</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Access Level</th>
                            <th style="padding: 1.25rem 2.5rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); border-bottom: 1px solid #f1f5f9;">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        <?php foreach ($recent_users as $user): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1.5rem 2.5rem;">
                                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                                        <div style="width: 44px; height: 44px; background: radial-gradient(circle at center, var(--white), #f1f5f9); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 900; font-size: 1rem; border: 1px solid #e2e8f0; box-shadow: var(--shadow-sm);">
                                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; color: var(--text-dark); font-size: 1rem;"><?php echo htmlspecialchars($user['name']); ?></div>
                                            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1.5rem 2.5rem;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 12px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
                                        <?php echo $user['role'] === 'admin' ? 'background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2;' : ($user['role'] === 'employer' ? 'background: #f0fdf4; color: #166534; border: 1px solid #dcfce7;' : 'background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe;'); ?>">
                                        <div style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></div>
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td style="padding: 1.5rem 2.5rem; color: var(--text-muted); font-weight: 600;"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Health & Vital Metrics -->
    <div style="grid-column: span 4; display: flex; flex-direction: column; gap: 2.5rem;">
        <div class="premium-card" style="padding: 2.5rem; border: 1px solid rgba(255,255,255,0.8);">
            <h3 style="margin: 0 0 2rem 0; font-size: 1.25rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Vital Metrics</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; color: #10b981; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 1.5px;">Core Engine</div>
                        <div style="color: #10b981; font-weight: 900; font-size: 0.95rem;">Operational</div>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; color: var(--accent); box-shadow: var(--shadow-sm);">
                        <i class="fas fa-shield"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 1.5px;">Security</div>
                        <div style="color: var(--text-dark); font-weight: 900; font-size: 0.95rem;">Encrypted</div>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; color: #f59e0b; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 1.5px;">Sync State</div>
                        <div style="color: var(--text-dark); font-weight: 900; font-size: 0.95rem;">Real-time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
