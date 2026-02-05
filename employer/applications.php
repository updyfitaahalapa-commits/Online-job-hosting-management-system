<?php
// employer/applications.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$employer_id = $_SESSION['user_id'];

// All applications for this employer's jobs
$stmt = $pdo->prepare("
    SELECT a.*, j.title as job_title, u.name as seeker_name, u.email as seeker_email 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    JOIN users u ON a.seeker_id = u.id 
    WHERE j.employer_id = ? 
    ORDER BY a.applied_at DESC
");
$stmt->execute([$employer_id]);
$applications = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="mb-12 flex justify-between items-end gap-6">
    <div>
        <h1 class="text-4xl font-black text-[#1e293b] tracking-tighter">Applications Received</h1>
        <p class="text-sm font-medium text-gray-400 mt-1">Review and manage candidates for all your posted roles</p>
    </div>
    <div class="flex space-x-3">
        <div class="relative group">
            <select class="appearance-none pl-6 pr-12 py-3 bg-white border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-[#1e293b] focus:ring-4 focus:ring-blue-50 outline-none w-48 shadow-sm transition-all">
                <option>Filter by Job</option>
            </select>
            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] border border-gray-50 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                    <th class="px-8 py-5">Candidate</th>
                    <th class="px-8 py-5">Position</th>
                    <th class="px-8 py-5">Date Applied</th>
                    <th class="px-8 py-5">Current Status</th>
                    <th class="px-8 py-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 border-t border-gray-50">
                <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <i class="fas fa-inbox text-5xl text-gray-200 mb-4"></i>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">No applications yet</h3>
                                <p class="text-sm text-gray-500">When candidates apply to your jobs, they will appear here for review.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                        <tr class="hover:bg-blue-50/20 transition duration-150">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100 mr-3">
                                        <?php echo strtoupper(substr($app['seeker_name'], 0, 1)); ?>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900"><?php echo $app['seeker_name']; ?></span>
                                        <span class="text-xs text-gray-400"><?php echo $app['seeker_email']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-medium text-gray-700"><?php echo $app['job_title']; ?></span>
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-500 font-medium">
                                <?php echo date('M d, Y', strtotime($app['applied_at'])); ?>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo getStatusBadgeClass($app['status']); ?>">
                                    <?php echo $app['status']; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <a href="application_detail.php?id=<?php echo $app['id']; ?>" class="text-blue-600 hover:text-blue-700 font-bold text-sm">Review</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
