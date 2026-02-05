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

<div style="min-height: 90vh; display: flex; align-items: center; justify-content: center; padding: 2rem; background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05), transparent), radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.05), transparent);">
    <div style="width: 100%; max-width: 480px;">
        <div class="premium-card" style="padding: 4rem 3rem; position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.8);">
            <!-- Decorative Glow -->
            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: var(--accent); filter: blur(60px); opacity: 0.1;"></div>

            <div style="text-align: center; margin-bottom: 3.5rem;">
                <div style="background: linear-gradient(135deg, var(--primary), var(--accent)); width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; color: var(--white); margin: 0 auto 1.5rem auto; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2); font-size: 1.5rem;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h1 style="font-size: 2rem; font-weight: 900; color: var(--primary); margin-bottom: 0.75rem; letter-spacing: -1px; font-family: 'Poppins', sans-serif;">Welcome Back</h1>
                <p style="color: var(--text-muted); font-size: 0.95rem; font-weight: 500;">Secure access to your professional gateway</p>
            </div>

            <?php if ($error): ?>
                <div style="background: #fef2f2; border: 1px solid #fee2e2; padding: 1.25rem; border-radius: 16px; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.75rem; color: #991b1b; font-size: 0.9rem; font-weight: 600;">
                    <i class="fas fa-circle-exclamation"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" style="display: flex; flex-direction: column; gap: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1rem;">Professional Email</label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1rem; opacity: 0.5;"></i>
                        <input type="email" name="email" required placeholder="name@company.com" 
                            style="width: 100%; padding: 1.1rem 1.1rem 1.1rem 3.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <label style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px;">Security Password</label>
                        <a href="#" style="font-size: 0.75rem; color: var(--accent); font-weight: 700; text-decoration: none;" class="hover:underline">Recovery?</a>
                    </div>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1rem; opacity: 0.5;"></i>
                        <input type="password" name="password" id="password_input" required placeholder="••••••••" 
                            style="width: 100%; padding: 1.1rem 3.5rem 1.1rem 3.5rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                        <button type="button" onclick="togglePassword('password_input')" style="position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 1rem; padding: 0.25rem; opacity: 0.5;">
                            <i class="fas fa-eye" id="eye_icon"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" id="remember" style="width: 18px; height: 18px; accent-color: var(--accent); border-radius: 6px; cursor: pointer;">
                    <label for="remember" style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600; cursor: pointer;">Authorize for 30 days</label>
                </div>

                <button type="submit" class="btn-premium btn-primary" style="width: 100%; justify-content: center; padding: 1.25rem; font-size: 1.1rem; border-radius: 18px; font-weight: 800; box-shadow: 0 15px 30px rgba(59, 130, 246, 0.25); transform: translateY(0); transition: var(--transition);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    Sign In to Portal
                </button>
            </form>

            <div style="margin-top: 3.5rem; pt: 2rem; border-top: 1px solid #f1f5f9; text-align: center;">
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 1.5rem; font-weight: 600;">
                    New candidate? <a href="register.php" style="color: var(--accent); font-weight: 800; text-decoration: none;" class="hover:underline">Initialize Account</a>
                </p>
                <a href="../index.php" style="margin-top: 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.85rem; text-decoration: none; font-weight: 700; opacity: 0.6;" class="hover:opacity-100">
                    <i class="fas fa-arrow-left"></i> Return to Terminal
                </a>
            </div>
        </div>

        <div style="margin-top: 2.5rem; text-align: center; opacity: 0.5;">
            <div style="display: inline-flex; align-items: center; gap: 0.75rem; color: var(--primary); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">
                <i class="fas fa-lock"></i>
                <span>Quantum Encryption Enabled</span>
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
