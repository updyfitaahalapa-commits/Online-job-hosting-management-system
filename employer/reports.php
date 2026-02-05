<?php
// employer/reports.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$employer_id = $_SESSION['user_id'];

// Get jobs with application counts
$stmt = $pdo->prepare("
    SELECT j.title, COUNT(a.id) as total_apps, 
    SUM(CASE WHEN a.status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted_count
    FROM jobs j 
    LEFT JOIN applications a ON j.id = a.job_id 
    WHERE j.employer_id = ?
    GROUP BY j.id
");
$stmt->execute([$employer_id]);
$job_performance = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="mb-10">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Recruitment Analytics</h1>
    <p class="text-gray-500 mt-1">Detailed performance metrics for your job vacancies</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <h3 class="font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-line mr-2 text-indigo-600"></i> Application Funnel
        </h3>
        <div class="space-y-6">
            <?php foreach ($job_performance as $job): ?>
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <p class="text-sm font-bold text-gray-900"><?php echo $job['title']; ?></p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase"><?php echo $job['total_apps']; ?> Total Candidates</p>
                        </div>
                        <span class="text-xs font-black text-indigo-600"><?php echo $job['total_apps'] > 0 ? round(($job['shortlisted_count'] / $job['total_apps']) * 100) : 0; ?>% Conversion</span>
                    </div>
                    <div class="w-full bg-gray-50 rounded-full h-2 flex overflow-hidden">
                        <?php 
                        $short_p = $job['total_apps'] > 0 ? ($job['shortlisted_count'] / $job['total_apps']) * 100 : 0;
                        ?>
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: <?php echo $short_p; ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bg-indigo-900 p-8 rounded-3xl text-white shadow-xl flex flex-col justify-center text-center">
        <div class="mb-6">
            <i class="fas fa-magic text-4xl text-indigo-200 opacity-50"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Hire Smarter with AI</h3>
        <p class="text-indigo-200 text-sm leading-relaxed mb-8">
            Our upcoming AI module will automatically rank candidates based on their skill match with your job requirements.
        </p>
        <button class="bg-white text-indigo-900 font-black px-6 py-3 rounded-xl text-sm hover:bg-indigo-50 transition">
            Preview Beta Features
        </button>
    </div>
</div>

<div class="mt-8 bg-white p-10 rounded-3xl border border-gray-100 shadow-sm text-center">
    <h3 class="font-bold text-gray-900 mb-2">Need a Printable Report?</h3>
    <p class="text-gray-500 mb-6">Generate a professional PDF summary of your hiring status to share with your team.</p>
    <div class="flex justify-center space-x-4">
        <a href="../includes/export_csv.php?type=applications" class="px-8 py-3 bg-gray-50 text-gray-900 font-bold rounded-xl border border-gray-100 hover:bg-gray-100 transition">Download CSV</a>
        <button class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition">Export PDF</button>
    </div>
</div>

<?php require_once '../includes/header.php'; // Using header as a placeholder for layout wrapper ?>
<?php require_once '../includes/footer.php'; ?>
