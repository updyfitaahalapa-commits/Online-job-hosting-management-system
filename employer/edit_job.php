<?php
// employer/edit_job.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$job_id = $_GET['id'] ?? null;
$employer_id = $_SESSION['user_id'];
if (!$job_id) redirect('manage_jobs.php');

$error = '';
$success = '';

// Fetch current job
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ? AND employer_id = ?");
$stmt->execute([$job_id, $employer_id]);
$job = $stmt->fetch();

if (!$job) redirect('manage_jobs.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $location = sanitize($_POST['location']);
    $salary_range = sanitize($_POST['salary_range']);
    $job_type = sanitize($_POST['job_type']);
    $status = sanitize($_POST['status']);

    try {
        $stmt = $pdo->prepare("UPDATE jobs SET title = ?, description = ?, category = ?, location = ?, salary_range = ?, job_type = ?, status = ? WHERE id = ? AND employer_id = ?");
        $stmt->execute([$title, $description, $category, $location, $salary_range, $job_type, $status, $job_id, $employer_id]);
        $success = "Job updated successfully!";
        // Refresh job data
        $job['title'] = $title;
        $job['description'] = $description;
        $job['category'] = $category;
        $job['location'] = $location;
        $job['salary_range'] = $salary_range;
        $job['job_type'] = $job_type;
        $job['status'] = $status;
    } catch (Exception $e) {
        $error = "Failed to update job.";
    }
}

require_once '../includes/header.php';
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="manage_jobs.php" class="text-sm font-bold text-gray-500 hover:text-blue-600 transition flex items-center mb-4">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to My Jobs
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Vacancy</h1>
        <p class="text-gray-500 mt-2">Update the details for: <span class="text-blue-600 font-bold"><?php echo $job['title']; ?></span></p>
    </div>

    <?php if ($error): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm font-bold">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 text-sm font-bold">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-full">
                <label class="block text-sm font-bold text-gray-700 mb-2">Job Title</label>
                <input type="text" name="title" value="<?php echo $job['title']; ?>" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-600 outline-none transition">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                <select name="category" required class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none transition">
                    <option value="IT & Software" <?php echo $job['category'] === 'IT & Software' ? 'selected' : ''; ?>>IT & Software</option>
                    <option value="Marketing" <?php echo $job['category'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                    <option value="Finance" <?php echo $job['category'] === 'Finance' ? 'selected' : ''; ?>>Finance</option>
                    <option value="Sales" <?php echo $job['category'] === 'Sales' ? 'selected' : ''; ?>>Sales</option>
                    <option value="Customer Service" <?php echo $job['category'] === 'Customer Service' ? 'selected' : ''; ?>>Customer Service</option>
                    <option value="Design" <?php echo $job['category'] === 'Design' ? 'selected' : ''; ?>>Design</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Job Type</label>
                <select name="job_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none transition">
                    <option value="Full-time" <?php echo $job['job_type'] === 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                    <option value="Part-time" <?php echo $job['job_type'] === 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                    <option value="Contract" <?php echo $job['job_type'] === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                    <option value="Remote" <?php echo $job['job_type'] === 'Remote' ? 'selected' : ''; ?>>Remote</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Location</label>
                <input type="text" name="location" value="<?php echo $job['location']; ?>" required class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Salary Range</label>
                <input type="text" name="salary_range" value="<?php echo $job['salary_range']; ?>" required class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none transition">
                    <option value="active" <?php echo $job['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $job['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="col-span-full">
                <label class="block text-sm font-bold text-gray-700 mb-2">Detailed Description</label>
                <textarea name="description" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none transition"><?php echo $job['description']; ?></textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 transition">
                Save Changes
            </button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
