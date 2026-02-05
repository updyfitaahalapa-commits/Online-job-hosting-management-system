<?php
// employer/profile.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['employer']);

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch current profile
$stmt = $pdo->prepare("SELECT u.name, u.email, p.* FROM users u JOIN profiles p ON u.id = p.user_id WHERE u.id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = sanitize($_POST['company_name']);
    $company_description = sanitize($_POST['company_description']);
    $contact = sanitize($_POST['contact']);
    
    $logo_path = $profile['logo_path'];

    // Handle Logo Upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['logo']['tmp_name'];
        $file_name = $_FILES['logo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = "logo_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_path = "../uploads/logos/" . $new_file_name;
            
            // Create directory if not exists
            if (!is_dir('../uploads/logos')) mkdir('../uploads/logos', 0777, true);

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $logo_path = "uploads/logos/" . $new_file_name;
            } else {
                $error = "Failed to upload logo.";
            }
        } else {
            $error = "Invalid image type.";
        }
    }

    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE profiles SET company_name = ?, company_description = ?, contact = ?, logo_path = ? WHERE user_id = ?");
            $stmt->execute([$company_name, $company_description, $contact, $logo_path, $user_id]);
            $success = "Company profile updated successfully!";
            // Refresh local state
            $profile['company_name'] = $company_name;
            $profile['company_description'] = $company_description;
            $profile['contact'] = $contact;
            $profile['logo_path'] = $logo_path;
        } catch (Exception $e) {
            $error = "Failed to update profile.";
        }
    }
}

require_once '../includes/header.php';
?>

<div class="mb-10">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Company Profile</h1>
    <p class="text-gray-500 mt-1">Manage your business brand and contact information</p>
</div>

<?php if ($success): ?>
    <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 text-sm font-bold">
        <?php echo $success; ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm font-bold">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<form action="profile.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Branding -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm text-center">
            <div class="w-32 h-32 bg-blue-50 rounded-2xl mx-auto mb-6 border-4 border-white shadow-xl flex items-center justify-center relative group overflow-hidden">
                <?php if ($profile['logo_path']): ?>
                    <img src="../<?php echo $profile['logo_path']; ?>" class="w-full h-full object-contain p-2">
                <?php else: ?>
                    <i class="fas fa-building text-5xl text-blue-200"></i>
                <?php endif; ?>
                <label class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                    <i class="fas fa-camera text-white"></i>
                    <input type="file" name="logo" class="hidden">
                </label>
            </div>
            <h3 class="font-bold text-gray-900 text-xl mb-1"><?php echo $profile['company_name'] ?: 'Company Name'; ?></h3>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Verified Employer</p>
        </div>
    </div>

    <!-- Details -->
    <div class="lg:col-span-2 space-y-8 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-full">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Official Company Name</label>
                <input type="text" name="company_name" value="<?php echo $profile['company_name']; ?>" class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-600 outline-none text-sm font-medium">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Business Email</label>
                <input type="email" value="<?php echo $profile['email']; ?>" readonly class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 text-gray-400 cursor-not-allowed text-sm font-bold">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Contact Phone</label>
                <input type="text" name="contact" value="<?php echo $profile['contact']; ?>" class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-600 outline-none text-sm font-medium">
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">About the Company</label>
                <textarea name="company_description" rows="6" class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-600 outline-none text-sm font-medium leading-relaxed" placeholder="Describe your company culture, mission, and what you look for in candidates..."><?php echo $profile['company_description']; ?></textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white font-black px-10 py-4 rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-100 transform active:scale-[0.98]">
                Update Profile
            </button>
        </div>
    </div>
</form>

<?php require_once '../includes/footer.php'; ?>
