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
            </div>
            
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem; letter-spacing: -0.5px;">Join the Ecosystem</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2.5rem;">Create your account to start your professional journey</p>

            <?php if ($error): ?>
                <div style="background: #fff5f5; border-left: 4px solid #ff7675; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
                    <p style="color: #c0392b; font-size: 0.85rem; margin: 0; font-weight: 600;"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: #f0fff4; border-left: 4px solid #38a169; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
                    <p style="color: #2f855a; font-size: 0.85rem; margin: 0; font-weight: 600;"><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" style="text-align: left; display: flex; flex-direction: column; gap: 2rem;">
                <!-- Role Selector -->
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem;">I am joining as a...</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <label style="position: relative; cursor: pointer;">
                            <input type="radio" name="role" value="seeker" checked style="position: absolute; opacity: 0;">
                            <div class="role-card" style="padding: 1.25rem; border: 2px solid #e1e8ed; border-radius: 16px; text-align: center; transition: var(--transition);">
                                <i class="fas fa-user-tie" style="display: block; font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-muted);"></i>
                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-dark);">Job Seeker</span>
                            </div>
                        </label>
                        <label style="position: relative; cursor: pointer;">
                            <input type="radio" name="role" value="employer" style="position: absolute; opacity: 0;">
                            <div class="role-card" style="padding: 1.25rem; border: 2px solid #e1e8ed; border-radius: 16px; text-align: center; transition: var(--transition);">
                                <i class="fas fa-building" style="display: block; font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-muted);"></i>
                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-dark);">Employer</span>
                            </div>
                        </label>
                    </div>
                </div>

                <style>
                    input[name="role"]:checked + .role-card {
                        border-color: var(--primary);
                        background: rgba(26, 42, 108, 0.03);
                    }
                    input[name="role"]:checked + .role-card i { color: var(--primary); }
                    input[name="role"]:checked + .role-card span { color: var(--primary); }
                </style>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">Full Name</label>
                        <input type="text" name="name" required placeholder="John Doe" 
                            style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid #e1e8ed; outline: none; transition: var(--transition); font-family: inherit; font-size: 0.95rem; box-sizing: border-box;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(26, 42, 108, 0.05)'"
                            onblur="this.style.borderColor='#e1e8ed'; this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">Email Address</label>
                        <input type="email" name="email" required placeholder="name@example.com" 
                            style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid #e1e8ed; outline: none; transition: var(--transition); font-family: inherit; font-size: 0.95rem; box-sizing: border-box;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(26, 42, 108, 0.05)'"
                            onblur="this.style.borderColor='#e1e8ed'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="reg_password" required placeholder="••••••••" 
                                style="width: 100%; padding: 1rem 3rem 1rem 1rem; border-radius: 12px; border: 1px solid #e1e8ed; outline: none; transition: var(--transition); font-family: inherit; font-size: 0.95rem; box-sizing: border-box;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(26, 42, 108, 0.05)'"
                                onblur="this.style.borderColor='#e1e8ed'; this.style.boxShadow='none'">
                            <button type="button" onclick="togglePassword('reg_password', 'reg_eye')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
                                <i class="fas fa-eye" id="reg_eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">Confirm Password</label>
                        <div style="position: relative;">
                            <input type="password" name="confirm_password" id="reg_confirm" required placeholder="••••••••" 
                                style="width: 100%; padding: 1rem 3rem 1rem 1rem; border-radius: 12px; border: 1px solid #e1e8ed; outline: none; transition: var(--transition); font-family: inherit; font-size: 0.95rem; box-sizing: border-box;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(26, 42, 108, 0.05)'"
                                onblur="this.style.borderColor='#e1e8ed'; this.style.boxShadow='none'">
                            <button type="button" onclick="togglePassword('reg_confirm', 'reg_confirm_eye')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
                                <i class="fas fa-eye" id="reg_confirm_eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 0.75rem; margin-top: -0.5rem;">
                    <input type="checkbox" required id="terms" style="width: 18px; height: 18px; accent-color: var(--primary); margin-top: 0.2rem;">
                    <label for="terms" style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">I agree to the <a href="#" style="color: var(--primary); font-weight: 700; text-decoration: none;">Terms of Service</a> and <a href="#" style="color: var(--primary); font-weight: 700; text-decoration: none;">Privacy Policy</a>.</label>
                </div>

                <button type="submit" class="btn-premium btn-primary" style="width: 100%; justify-content: center; padding: 1.1rem; font-size: 1rem; border-radius: 14px; margin-top: 1rem;">
                    Create Professional Account
                </button>
            </form>

            <div style="margin-top: 2.5rem; pt: 2rem; border-top: 1px solid #f8f9fa;">
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <span>Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 700; text-decoration: none; border-bottom: 2px solid rgba(26, 42, 108, 0.1); transition: var(--transition);">Sign In Instead</a></span>
                    <a href="../index.php" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; opacity: 0.7;" class="hover:opacity-100">
                        <i class="fas fa-arrow-left"></i> Back to Home Page
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>


<?php require_once '../includes/footer.php'; ?>
