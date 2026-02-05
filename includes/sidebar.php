<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {
    $role_menu = [
        ['label' => 'Dashboard', 'path' => BASE_URL . 'admin/dashboard.php', 'icon' => 'fas fa-tachometer-alt'],
        ['label' => 'User Management', 'path' => BASE_URL . 'admin/users.php', 'icon' => 'fas fa-users'],
        ['label' => 'Reports', 'path' => BASE_URL . 'admin/reports.php', 'icon' => 'fas fa-file-invoice']
    ];
} elseif ($role === 'employer') {
    $role_menu = [
        ['label' => 'Dashboard', 'path' => BASE_URL . 'employer/dashboard.php', 'icon' => 'fas fa-tachometer-alt'],
        ['label' => 'Job Post', 'path' => BASE_URL . 'employer/post_job.php', 'icon' => 'fas fa-plus-square'],
        ['label' => 'Shortlist', 'path' => BASE_URL . 'employer/applications.php', 'icon' => 'fas fa-list-check']
    ];
} else { // Seeker
    $role_menu = [
        ['label' => 'Profile', 'path' => BASE_URL . 'seeker/profile.php', 'icon' => 'fas fa-user-circle'],
        ['label' => 'Search', 'path' => BASE_URL . 'seeker/jobs.php', 'icon' => 'fas fa-search'],
        ['label' => 'Tracking', 'path' => BASE_URL . 'seeker/my_applications.php', 'icon' => 'fas fa-clock-rotate-left']
    ];
}
?>

<aside id="sidebar">
    <!-- Sidebar Header / Profile -->
    <div style="padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; color: var(--white); border: 1px solid rgba(255,255,255,0.1);">
                <?php echo strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)); ?>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.95rem; color: var(--white);"><?php echo $_SESSION['name'] ?? 'User'; ?></div>
                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;"><?php echo $role; ?></div>
            </div>
        </div>
    </div>

    <div style="padding: 0 1rem;">
        <div style="color: rgba(255,255,255,0.3); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin-bottom: 1.5rem; padding-left: 0.5rem;">Principal Menu</div>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
            <?php foreach ($role_menu as $item): 
                $isActive = $current_page == basename($item['path']);
            ?>
            <li>
                <a href="<?php echo $item['path']; ?>" 
                   style="display: flex; align-items: center; padding: 0.85rem 1rem; color: <?php echo $isActive ? 'var(--white)' : 'rgba(255,255,255,0.6)'; ?>; text-decoration: none; border-radius: 12px; background: <?php echo $isActive ? 'rgba(255,255,255,0.1)' : 'transparent'; ?>; transition: var(--transition); font-weight: <?php echo $isActive ? '700' : '500'; ?>; font-size: 0.9rem;"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='var(--white)'"
                   onmouseout="this.style.background='<?php echo $isActive ? 'rgba(255,255,255,0.1)' : 'transparent'; ?>'; this.style.color='<?php echo $isActive ? 'var(--white)' : 'rgba(255,255,255,0.6)'; ?>'">
                    <i class="<?php echo $item['icon']; ?>" style="width: 24px; font-size: 1.1rem; opacity: <?php echo $isActive ? '1' : '0.7'; ?>;"></i>
                    <span><?php echo $item['label']; ?></span>
                </a>
            </li>
            <?php endforeach; ?>
            
            <li style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <a href="<?php echo BASE_URL; ?>auth/logout.php" 
                   style="display: flex; align-items: center; padding: 0.85rem 1rem; color: #ff7675; text-decoration: none; border-radius: 12px; transition: var(--transition); font-weight: 600; font-size: 0.9rem;"
                   onmouseover="this.style.background='rgba(255,118,117,0.1)'"
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-sign-out-alt" style="width: 24px; font-size: 1.1rem;"></i>
                    <span>Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
