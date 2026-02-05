<?php
// employer/application_detail.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$app_id = $_GET['id'] ?? null;
if (!$app_id) redirect('applications.php');

$error = '';
$success = '';

// Update status logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    try {
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $app_id]);
        $success = "Application status updated to " . ucfirst($new_status);
        
        // Add notification for seeker
        $stmt_app = $pdo->prepare("SELECT seeker_id, job_id FROM applications WHERE id = ?");
        $stmt_app->execute([$app_id]);
        $app_data = $stmt_app->fetch();
        
        $msg = "Your application status has been updated to: " . strtoupper($new_status);
        $stmt_notif = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt_notif->execute([$app_data['seeker_id'], $msg]);

    } catch (Exception $e) {
        $error = "Failed to update status.";
    }
}

// Fetch application, seeker profile and job info
$stmt = $pdo->prepare("
    SELECT a.*, j.title as job_title, u.name as seeker_name, u.email as seeker_email, p.* 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    JOIN users u ON a.seeker_id = u.id 
    JOIN profiles p ON u.id = p.user_id
    WHERE a.id = ?
");
$stmt->execute([$app_id]);
$application = $stmt->fetch();

if (!$application) redirect('applications.php');

require_once '../includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <a href="applications.php" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to Applications
            </a>
            <h1 class="text-3xl font-bold text-gray-900"><?php echo $application['seeker_name']; ?></h1>
            <p class="text-gray-500">Application for: <span class="text-blue-600 font-bold"><?php echo $application['job_title']; ?></span></p>
        </div>
        
        <form action="" method="POST" class="bg-white p-2 rounded-2xl shadow-xl flex gap-2 border border-gray-100">
            <select name="status" class="bg-gray-50 border-none rounded-xl text-xs font-bold px-4 py-2 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="pending" <?php echo $application['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="shortlisted" <?php echo $application['status'] === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                <option value="rejected" <?php echo $application['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
            <button type="submit" name="update_status" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition">
                Update
            </button>
        </form>
    </div>

    <?php if ($success): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 text-sm font-bold">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Candidate Overview -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm text-center">
                <div class="w-24 h-24 bg-blue-50 rounded-full mx-auto mb-4 border-4 border-white shadow-xl flex items-center justify-center">
                    <i class="fas fa-user text-4xl text-blue-200"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-xl mb-1"><?php echo $application['seeker_name']; ?></h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-6">Candidate</p>
                
                <div class="space-y-4 text-left">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Contact Email</span>
                        <p class="text-sm font-medium text-gray-900"><?php echo $application['seeker_email']; ?></p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Phone Number</span>
                        <p class="text-sm font-medium text-gray-900"><?php echo $application['contact'] ?: 'Not provided'; ?></p>
                    </div>
                </div>
            </div>
            
            <button class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold flex items-center justify-center hover:bg-black transition shadow-lg">
                <i class="fas fa-file-pdf mr-3"></i> Download Resume
            </button>
        </div>

        <!-- Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                    <i class="fas fa-paper-plane mr-3 text-blue-600"></i> Cover Letter
                </h3>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 italic text-gray-600 leading-relaxed">
                    <?php echo nl2br($application['cover_letter']) ?: 'No cover letter provided by the candidate.'; ?>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center">
                    <i class="fas fa-id-card mr-3 text-blue-600"></i> Professional Bio
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    <?php echo nl2br($application['bio']) ?: 'Candidate has not filled out their professional bio yet.'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
