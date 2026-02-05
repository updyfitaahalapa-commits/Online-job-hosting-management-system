<?php
// seeker/profile.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
checkRole(['seeker']);

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// 1. Fetch current profile first to have current data
$stmt = $pdo->prepare("SELECT u.name, u.email, p.* FROM users u JOIN profiles p ON u.id = p.user_id WHERE u.id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if (!$profile) {
    die("Profile not found. Please run db_init.php");
}

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = sanitize($_POST['bio']);
    $contact = sanitize($_POST['contact']);
    
    $resume_path = $profile['resume_path'] ?? ''; // Keep existing by default
    $profile_pic = $profile['profile_pic'] ?? ''; // Keep existing

    // Handle Resume Upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['resume']['tmp_name'];
        $file_name = $_FILES['resume']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['pdf', 'doc', 'docx'];
        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = "resume_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_path = "../uploads/resumes/" . $new_file_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $resume_path = "uploads/resumes/" . $new_file_name;
            } else {
                $error = "Failed to upload resume.";
            }
        } else {
            $error = "Invalid resume type. Only PDF/DOC/DOCX allowed.";
        }
    }

    // Handle Profile Photo Upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_name = $_FILES['profile_pic']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_img_exts = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($file_ext, $allowed_img_exts)) {
            $new_img_name = "pic_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_path = "../uploads/profile_pics/" . $new_img_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $profile_pic = "uploads/profile_pics/" . $new_img_name;
            } else {
                $error = "Failed to upload profile photo.";
            }
        } else {
            $error = "Invalid image type. Only JPG/PNG/WEBP allowed.";
        }
    }

    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE profiles SET bio = ?, contact = ?, resume_path = ?, profile_pic = ? WHERE user_id = ?");
            $stmt->execute([$bio, $contact, $resume_path, $profile_pic, $user_id]);
            $success = "Profile updated successfully!";
            // Update local state
            $profile['resume_path'] = $resume_path;
            $profile['profile_pic'] = $profile_pic;
            $profile['bio'] = $bio;
            $profile['contact'] = $contact;
        } catch (Exception $e) {
            $error = "Failed to update profile.";
        }
    }
}


require_once '../includes/header.php';
?>

<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--primary); letter-spacing: -1.5px; margin: 0 0 0.5rem 0;">Identity & Credentials</h1>
            <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">Curate your professional presence for the Somalia job market.</p>
        </div>
    </div>

    <?php if ($success): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid rgba(46, 204, 113, 0.2);">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $success; ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background: #fff1f0; color: #cf1322; padding: 1.25rem 2rem; border-radius: 16px; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 1rem; border: 1px solid #ffa39e;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 2rem;">
        <!-- Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <div class="premium-card" style="text-align: center; padding: 2.5rem 1.5rem;">
                <div style="width: 140px; height: 140px; background: #f8f9fa; border-radius: 50%; margin: 0 auto 2rem auto; border: 4px solid var(--white); box-shadow: var(--shadow-lg); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <?php 
                    $pic_path = !empty($profile['profile_pic']) ? "../" . $profile['profile_pic'] : null;
                    if ($pic_path && file_exists($pic_path)): ?>
                        <img src="<?php echo BASE_URL . $profile['profile_pic']; ?>?v=<?php echo time(); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-user-tie" style="font-size: 3.5rem; color: #cbd5e0;"></i>
                    <?php endif; ?>
                    <label style="position: absolute; inset: 0; background: rgba(26, 42, 108, 0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: var(--transition); cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                        <i class="fas fa-camera" style="color: var(--white); font-size: 1.5rem;"></i>
                        <input type="file" name="profile_pic" form="profile-form" style="display: none;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.75rem;">Executive Summary / Bio</label>
                        <textarea name="bio" rows="6" style="width: 100%; padding: 1.25rem; border: 1px solid #e2e8f0; border-radius: 16px; outline: none; font-size: 0.95rem; font-weight: 500; font-family: 'Inter', sans-serif; line-height: 1.6; transition: border-color 0.2s;" placeholder="Define your professional trajectory..." onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e2e8f0'"><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                    </div>

                    <div style="padding-top: 1rem; border-top: 1px solid #f8f9fa; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-premium btn-primary" style="padding: 1rem 3rem; font-size: 1rem; border-radius: 14px; box-shadow: var(--shadow-md);">
                            Persist Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
