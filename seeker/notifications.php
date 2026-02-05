<?php
// seeker/notifications.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

$user_id = $_SESSION['user_id'];

// Mock notifications (or fetch from DB if table used)
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="margin-bottom: 30px;">
    <h1 style="font-size: 28px; color: #2c3e50; margin: 0;">Notifications</h1>
    <p style="color: #666; font-size: 14px; margin-top: 5px;">Stay updated on your application status.</p>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto; padding: 0;">
    <?php if (empty($notifications)): ?>
        <div style="padding: 40px; text-align: center; color: #888;">
            <i class="fas fa-bell-slash" style="font-size: 30px; margin-bottom: 10px; display: block;"></i>
            No new notifications at this time.
        </div>
    <?php else: ?>
        <ul style="list-style: none; padding: 0; margin: 0; border-top: 1px solid #eee;">
            <?php foreach ($notifications as $n): ?>
                <li style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1; color: #333;">
                        <i class="fas fa-info-circle" style="color: #3498db; margin-right: 10px;"></i>
                        <?php echo htmlspecialchars($n['message']); ?>
                    </div>
                    <span style="font-size: 11px; color: #aaa; margin-left: 20px;"><?php echo date('M d, Y', strtotime($n['created_at'])); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
