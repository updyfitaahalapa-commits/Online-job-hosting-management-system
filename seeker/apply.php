<?php
// seeker/apply.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

$job_id = $_GET['id'] ?? null;
if (!$job_id) redirect('jobs.php');

$seeker_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Check if already applied
$stmt = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?");
$stmt->execute([$job_id, $seeker_id]);
$already_applied = $stmt->fetch();

// Fetch job details
$stmt = $pdo->prepare("SELECT j.*, u.name as company_name FROM jobs j JOIN users u ON j.employer_id = u.id WHERE j.id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job) redirect('jobs.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already_applied) {
    $cover_letter = sanitize($_POST['cover_letter']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO applications (job_id, seeker_id, cover_letter) VALUES (?, ?, ?)");
        $stmt->execute([$job_id, $seeker_id, $cover_letter]);
        $success = "Application submitted successfully!";
        $already_applied = true; // Update state after submission
    } catch (Exception $e) {
        $error = "Failed to submit application. Please try again.";
    }
}

require_once '../includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <a href="jobs.php" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center mb-4">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to Jobs
        </a>
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center text-white font-black text-3xl shadow-xl">
                <?php echo strtoupper(substr($job['company_name'], 0, 1)); ?>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900"><?php echo $job['title']; ?></h1>
                <p class="text-lg text-gray-500 font-medium"><?php echo $job['company_name']; ?> &bull; <?php echo $job['location']; ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Application Form -->
        <div class="lg:col-span-2">
            <?php if ($success): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-8 rounded-3xl shadow-sm mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-green-800">Application Sent!</h3>
                            <p class="text-green-700 mt-1">The employer has been notified and will review your profile shortly.</p>
                            <div class="mt-4">
                                <a href="my_applications.php" class="text-green-800 font-bold underline">Track Application Status</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($already_applied): ?>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-8 rounded-3xl shadow-sm mb-8 text-center py-16">
                    <i class="fas fa-info-circle text-blue-500 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-blue-800 mb-2">You already applied</h3>
                    <p class="text-blue-700">Your application for this position is currently being processed.</p>
                    <a href="my_applications.php" class="mt-6 inline-block bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 transition">View My Applications</a>
                </div>
            <?php else: ?>
                <form action="apply.php?id=<?php echo $job_id; ?>" method="POST" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Submit Application</h3>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Cover Letter (Optional)</label>
                        <textarea name="cover_letter" rows="8" class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:ring-2 focus:ring-blue-600 outline-none transition text-gray-700" placeholder="Introduce yourself and explain why you're a great fit for this role..."></textarea>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-2xl border border-dashed border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-red-500 shadow-sm mr-4">
                                <i class="fas fa-file-pdf text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Your Current Resume</p>
                                <p class="text-xs text-gray-400">Successfully uploaded in profile</p>
                            </div>
                            <a href="profile.php" class="ml-auto text-xs font-bold text-blue-600 hover:underline">Update</a>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-100 transform active:scale-[0.98]">
                        Confirm Submission
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="mt-8 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Job Description</h3>
                <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed">
                    <?php echo nl2br($job['description']); ?>
                </div>
            </div>
        </div>

        <!-- Job Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6">Position Details</h3>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mr-4">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Salary Range</p>
                            <p class="text-sm font-bold text-gray-900"><?php echo $job['salary_range']; ?></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mr-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Employment</p>
                            <p class="text-sm font-bold text-gray-900"><?php echo $job['job_type']; ?></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mr-4">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Category</p>
                            <p class="text-sm font-bold text-gray-900"><?php echo $job['category']; ?></p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mr-4">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Date Posted</p>
                            <p class="text-sm font-bold text-gray-900"><?php echo date('M d, Y', strtotime($job['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-indigo-900 p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
                <i class="fas fa-shield-alt absolute -right-4 -bottom-4 text-8xl opacity-10 rotate-12"></i>
                <h3 class="text-lg font-bold mb-2">Safety Tip</h3>
                <p class="text-indigo-200 text-xs leading-relaxed">Never share bank details or pay any fees for job applications. Legitimate employers will never ask for money.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
