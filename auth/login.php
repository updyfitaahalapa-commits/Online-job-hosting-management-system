<?php
// auth/login.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') redirect('../admin/dashboard.php');
    if ($_SESSION['role'] === 'employer') redirect('../employer/dashboard.php');
    if ($_SESSION['role'] === 'seeker') redirect('../seeker/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') redirect('../admin/dashboard.php');
        if ($user['role'] === 'employer') redirect('../employer/dashboard.php');
        if ($user['role'] === 'seeker') redirect('../seeker/dashboard.php');
    } else {
        $error = "Invalid email or password.";
    }
}

require_once '../includes/header.php';
?>

<div style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="width: 100%; max-width: 450px;">
        <div class="premium-card" style="padding: 3rem 2.5rem; text-align: center;">
            <div class="logo-icon" style="margin: 0 auto 1.5rem auto; width: 60px; height: 60px; font-size: 1.5rem;">
                <i class="fas fa-briefcase"></i>
            </div>
            
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem; letter-spacing: -0.5px;">Welcome Back</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2.5rem;">Access your professional job hunting dashboard</p>

            <?php if ($error): ?>
                <div style="background: #fff5f5; border-left: 4px solid #ff7675; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
                    <p style="color: #c0392b; font-size: 0.85rem; margin: 0; font-weight: 600;"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" style="text-align: left; display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">Email Address</label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem;"></i>
                        <input type="email" name="email" required placeholder="name@company.com" 
                            style="width: 100%; padding: 1rem 1rem 1rem 3rem; border-radius: 12px; border: 1px solid #e1e8ed; outline: none; transition: var(--transition); font-family: inherit; font-size: 0.95rem; box-sizing: border-box;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(26, 42, 108, 0.05)'"
                            onblur="this.style.borderColor='#e1e8ed'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">Password</label>
                        <a href="#" style="font-size: 0.75rem; color: var(--accent); font-weight: 700; text-decoration: none;">Forgot?</a>
                    </div>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem;"></i>
                        <input type="password" name="password" id="password_input" required placeholder="••••••••" 
                            style="width: 100%; padding: 1rem 3rem 1rem 3rem; border-radius: 12px; border: 1px solid #e1e8ed; outline: none; transition: var(--transition); font-family: inherit; font-size: 0.95rem; box-sizing: border-box;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(26, 42, 108, 0.05)'"
                            onblur="this.style.borderColor='#e1e8ed'; this.style.boxShadow='none'">
                        <button type="button" onclick="togglePassword('password_input', 'eye_icon')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 0.9rem; padding: 0.25rem;">
                            <i class="fas fa-eye" id="eye_icon"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: -0.5rem;">
                    <input type="checkbox" id="remember" style="width: 16px; height: 16px; accent-color: var(--primary);">
                    <label for="remember" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">Remember me for 30 days</label>
                </div>

                <button type="submit" class="btn-premium btn-primary" style="width: 100%; justify-content: center; padding: 1.1rem; font-size: 1rem; border-radius: 14px; margin-top: 0.5rem;">
                    Sign In to Account
                </button>
            </form>

            <div style="margin-top: 2.5rem; pt: 2rem; border-top: 1px solid #f8f9fa;">
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <span>New to the platform? <a href="register.php" style="color: var(--primary); font-weight: 700; text-decoration: none; border-bottom: 2px solid rgba(26, 42, 108, 0.1); transition: var(--transition);">Create Account</a></span>
                    <a href="../index.php" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; opacity: 0.7;" class="hover:opacity-100">
                        <i class="fas fa-arrow-left"></i> Back to Home Page
                    </a>
                </p>
            </div>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: rgba(26, 42, 108, 0.03); border-radius: 100px; color: var(--primary); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                <i class="fas fa-shield-alt"></i>
                <span>Enterprise Grade Security Enabled</span>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('eye_icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>
