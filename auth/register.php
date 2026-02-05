<?php
// auth/register.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();

if (isset($_SESSION['user_id'])) {
    redirect('../index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $role = sanitize($_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $pdo->beginTransaction();
                
                // Insert user
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password, $role]);
                $user_id = $pdo->lastInsertId();

                // Create profile
                $stmt = $pdo->prepare("INSERT INTO profiles (user_id) VALUES (?)");
                $stmt->execute([$user_id]);

                $pdo->commit();
                $success = "Registration successful! You can now login.";
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

require_once '../includes/header.php';
?>

<div style="min-height: 90vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="width: 100%; max-width: 600px;">
        <div class="premium-card" style="padding: 3rem 2.5rem; text-align: center;">
            <div class="logo-icon" style="margin: 0 auto 1.5rem auto; width: 60px; height: 60px; font-size: 1.5rem;">
                <i class="fas fa-briefcase"></i>
<div style="min-height: 95vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem; background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.05), transparent), radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.05), transparent);">
    <div style="width: 100%; max-width: 650px;">
        <div class="premium-card" style="padding: 4.5rem 3.5rem; position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.8);">
            <!-- Decorative Glow -->
            <div style="position: absolute; bottom: -50px; left: -50px; width: 120px; height: 120px; background: var(--accent); filter: blur(70px); opacity: 0.1;"></div>

            <div style="text-align: center; margin-bottom: 4rem;">
                <div style="background: linear-gradient(135deg, var(--primary), var(--accent)); width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; color: var(--white); margin: 0 auto 1.5rem auto; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2); font-size: 1.5rem;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h1 style="font-size: 2.25rem; font-weight: 900; color: var(--primary); margin-bottom: 0.75rem; letter-spacing: -1.5px; font-family: 'Poppins', sans-serif;">Join the Ecosystem</h1>
                <p style="color: var(--text-muted); font-size: 1rem; font-weight: 500;">Create your institutional or candidate profile</p>
            </div>

            <?php if ($error): ?>
                <div style="background: #fef2f2; border: 1px solid #fee2e2; padding: 1.25rem; border-radius: 16px; margin-bottom: 3rem; display: flex; align-items: center; gap: 0.75rem; color: #991b1b; font-size: 0.9rem; font-weight: 600;">
                    <i class="fas fa-circle-exclamation"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: #f0fdf4; border: 1px solid #dcfce7; padding: 1.25rem; border-radius: 16px; margin-bottom: 3rem; display: flex; align-items: center; gap: 0.75rem; color: #166534; font-size: 0.9rem; font-weight: 600;">
                    <i class="fas fa-circle-check"></i>
                    <span><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" style="display: flex; flex-direction: column; gap: 2.5rem;">
                <!-- Role Selector -->
                <div>
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.25rem; text-align: center;">Identify Your Presence</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <label style="position: relative; cursor: pointer;">
                            <input type="radio" name="role" value="seeker" checked style="position: absolute; opacity: 0;">
                            <div class="role-card" style="padding: 1.5rem; border: 2px solid #e2e8f0; border-radius: 20px; text-align: center; transition: var(--transition); background: #f8fafc;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                    <div class="icon-box" style="width: 44px; height: 44px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); transition: var(--transition);">
                                        <i class="fas fa-user-graduate" style="font-size: 1.25rem; color: var(--text-muted);"></i>
                                    </div>
                                    <span style="font-weight: 800; font-size: 0.95rem; color: var(--text-dark);">Job Seeker</span>
                                </div>
                            </div>
                        </label>
                        <label style="position: relative; cursor: pointer;">
                            <input type="radio" name="role" value="employer" style="position: absolute; opacity: 0;">
                            <div class="role-card" style="padding: 1.5rem; border: 2px solid #e2e8f0; border-radius: 20px; text-align: center; transition: var(--transition); background: #f8fafc;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                    <div class="icon-box" style="width: 44px; height: 44px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); transition: var(--transition);">
                                        <i class="fas fa-building-columns" style="font-size: 1.25rem; color: var(--text-muted);"></i>
                                    </div>
                                    <span style="font-weight: 800; font-size: 0.95rem; color: var(--text-dark);">Employer</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <style>
                    input[name="role"]:checked + .role-card {
                        border-color: var(--accent);
                        background: var(--white);
                        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.08);
                        transform: translateY(-2px);
                    }
                    input[name="role"]:checked + .role-card .icon-box { background: var(--accent); color: var(--white); }
                    input[name="role"]:checked + .role-card .icon-box i { color: var(--white); }
                    input[name="role"]:checked + .role-card span { color: var(--primary); }
                </style>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1rem;">Legal Name</label>
                        <input type="text" name="name" required placeholder="John Doe" 
                            style="width: 100%; padding: 1.1rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1rem;">Primary Email</label>
                        <input type="email" name="email" required placeholder="name@example.com" 
                            style="width: 100%; padding: 1.1rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500;"
                            onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1rem;">Access Key</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="reg_password" required placeholder="••••••••" 
                                style="width: 100%; padding: 1.1rem 3.5rem 1.1rem 1.25rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500;"
                                onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                            <button type="button" onclick="togglePassword('reg_password')" style="position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); opacity: 0.5;">
                                <i class="fas fa-eye" id="reg_eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1rem;">Verify Key</label>
                        <div style="position: relative;">
                            <input type="password" name="confirm_password" id="reg_confirm" required placeholder="••••••••" 
                                style="width: 100%; padding: 1.1rem 3.5rem 1.1rem 1.25rem; border-radius: 18px; border: 1px solid #e2e8f0; outline: none; transition: var(--transition); font-family: inherit; font-size: 1rem; background: #f8fafc; font-weight: 500;"
                                onfocus="this.style.borderColor='var(--accent)'; this.style.background='var(--white)'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                            <button type="button" onclick="togglePassword('reg_confirm')" style="position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); opacity: 0.5;">
                                <i class="fas fa-eye" id="reg_confirm_eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <input type="checkbox" required id="terms" style="width: 20px; height: 20px; accent-color: var(--accent); border-radius: 6px; cursor: pointer; margin-top: 0.2rem;">
                    <label for="terms" style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; font-weight: 500;">Authorized compliance with <a href="#" style="color: var(--accent); font-weight: 800; text-decoration: none;" class="hover:underline">Global Terms</a> and <a href="#" style="color: var(--accent); font-weight: 800; text-decoration: none;" class="hover:underline">Privacy Protocols</a>.</label>
                </div>

                <button type="submit" class="btn-premium btn-primary" style="width: 100%; justify-content: center; padding: 1.25rem; font-size: 1.15rem; border-radius: 20px; font-weight: 800; box-shadow: 0 15px 30px rgba(59, 130, 246, 0.25); transform: translateY(0); transition: var(--transition);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    Initialize Professional Profile
                </button>
            </form>

            <div style="margin-top: 4rem; pt: 2rem; border-top: 1px solid #f1f5f9; text-align: center;">
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 1.5rem; font-weight: 600;">
                    Institutional member? <a href="login.php" style="color: var(--accent); font-weight: 800; text-decoration: none;" class="hover:underline">Sign In Terminal</a>
                </p>
                <a href="../index.php" style="margin-top: 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.85rem; text-decoration: none; font-weight: 700; opacity: 0.6;" class="hover:opacity-100">
                    <i class="fas fa-arrow-left"></i> Back to Core
                </a>
            </div>
        </div>
    </div>
</div>


<?php require_once '../includes/footer.php'; ?>
