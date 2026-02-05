<?php
// admin/users.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['admin']);

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($id != $_SESSION['user_id']) { // Don't delete yourself
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "User deleted successfully.";
    }
    redirect('users.php');
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin: 0 0 0.5rem 0;">User Management</h1>
        <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Manage all registered accounts and roles in the system.</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div style="background: #e8f5e9; color: #2e7d32; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(46, 204, 113, 0.2);">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
    </div>
<?php endif; ?>

<div class="premium-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f8f9fa; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--primary);">System Accounts</h3>
        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">
            Total: <?php echo count($users); ?> Users
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--white);">
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Full Name</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Email Address</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">User Role</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0;">Date Joined</th>
                    <th style="padding: 1.25rem 2.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php foreach ($users as $user): ?>
                    <tr style="border-bottom: 1px solid #f8f9fa; transition: var(--transition);" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.25rem 2.5rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; background: rgba(26, 42, 108, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 800; font-size: 0.9rem;">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                                <span style="font-weight: 700; color: var(--text-dark);"><?php echo htmlspecialchars($user['name']); ?></span>
                            </div>
                        </td>
                        <td style="padding: 1.25rem 2.5rem; color: var(--text-muted);"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td style="padding: 1.25rem 2.5rem;">
                            <span style="display: inline-block; padding: 0.4rem 0.75rem; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
                                <?php echo $user['role'] === 'admin' ? 'background: #ffebee; color: #c62828;' : ($user['role'] === 'employer' ? 'background: #e8f5e9; color: #2e7d32;' : 'background: #e3f2fd; color: #1565c0;'); ?>">
                                <?php echo $user['role']; ?>
                            </span>
                        </td>
                        <td style="padding: 1.25rem 2.5rem; color: var(--text-muted);"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td style="padding: 1.25rem 2.5rem; text-align: right;">
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Confirm deletion of this user?')" style="color: #e74c3c; text-decoration: none; font-weight: 700; font-size: 0.85rem;" class="hover:underline">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            <?php else: ?>
                                <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--primary); background: rgba(26, 42, 108, 0.05); padding: 0.3rem 0.75rem; border-radius: 100px;">
                                    <i class="fas fa-user-shield"></i> You (Admin)
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
