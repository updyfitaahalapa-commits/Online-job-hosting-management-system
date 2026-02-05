<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure BASE_URL is defined
if (!defined('BASE_URL')) {
    require_once dirname(__DIR__) . '/config/db.php';
}

$current_url = $_SERVER['PHP_SELF'];
$script_name = basename($current_url);

// Strictly identify the Home Page (index.php in the root)
// This ensures that an index.php inside a subfolder (like seeker/index.php) is NOT treated as the Home Hero page.
$is_home = ($script_name == 'index.php' && strpos($current_url, '/admin/') === false && strpos($current_url, '/employer/') === false && strpos($current_url, '/seeker/') === false && strpos($current_url, '/auth/') === false);

// Identify Dashboard pages (Admin, Employer, Seeker)
$is_dashboard = (strpos($current_url, '/admin/') !== false || strpos($current_url, '/employer/') !== false || strpos($current_url, '/seeker/') !== false);

// Identify Authentication pages
$is_auth = (strpos($current_url, '/auth/') !== false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JHMS - Online Job Hunting Management System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e293b;
            --primary-light: #334155;
            --secondary: #f1f5f9;
            --accent: #3b82f6;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --radius-2xl: 1.5rem;
        }

        body { 
            font-family: 'Poppins', 'Inter', sans-serif; 
            background-color: var(--secondary); 
            color: var(--text-dark);
            margin: 0;
            line-height: 1.5;
        }

        /* Premium Navbar */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 1100;
            box-shadow: var(--shadow-sm);
            box-sizing: border-box;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: var(--white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(26, 42, 108, 0.2);
        }

        /* Sidebar & Layout */
        #sidebar { 
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--primary);
            color: var(--white);
            transition: var(--transition);
            z-index: 1200;
            transform: translateX(-100%);
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            padding: 2rem 0;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        body.sidebar-open #sidebar { transform: translateX(0); }
        
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1150;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition);
        }
        body.sidebar-open .sidebar-overlay { opacity: 1; pointer-events: auto; }

        .dashboard-content { 
            margin-top: 70px;
            padding: <?php echo ($is_home || $is_auth) ? '0' : '3rem'; ?>;
            flex-grow: 1;
            transition: var(--transition);
            width: 100%;
            box-sizing: border-box;
            min-height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
            max-width: 1600px;
            margin-left: auto;
            margin-right: auto;
        }
        }

        .content-container {
            max-width: <?php echo ($is_dashboard || $is_auth) ? '100%' : '1400px'; ?>;
            margin: 0 auto;
            width: 100%;
            flex-grow: 1;
            padding: <?php echo $is_dashboard ? '0 1rem' : '0 2rem'; ?>;
        }

        /* UI Components */
        .premium-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--transition);
        }
        .premium-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }

        .btn-premium {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: var(--white); }
        .btn-primary:hover { background: var(--primary-light); box-shadow: 0 4px 12px rgba(26, 42, 108, 0.3); }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.75rem;
        }
        .modern-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }
        .modern-table td {
            padding: 1.25rem 1rem;
            background: var(--white);
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
        }
        .modern-table tr td:first-child { border-left: 1px solid #f1f1f1; border-radius: 12px 0 0 12px; }
        .modern-table tr td:last-child { border-right: 1px solid #f1f1f1; border-radius: 0 12px 12px 0; }
    </style>
</head>
<body class="<?php echo $is_dashboard ? 'is-dashboard' : ''; ?>">
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Top Navbar -->
    <nav style="backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.8); border-bottom: 1px solid rgba(226, 232, 240, 0.8); sticky; top: 0; z-index: 1000;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <?php if ($is_dashboard): ?>
                <button id="sidebar-toggle" style="background: var(--white); border: 1px solid #e2e8f0; font-size: 1.1rem; cursor: pointer; color: var(--text-dark); display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 12px; transition: var(--transition); box-shadow: var(--shadow-sm);" class="hover:border-primary">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>index.php" class="logo-container" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
                <div class="logo-icon" style="background: linear-gradient(135deg, var(--primary), var(--accent)); width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--white); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);">
                    <i class="fas fa-bolt" style="font-size: 1.2rem;"></i>
                </div>
                <span style="font-weight: 900; font-size: 1.4rem; color: var(--primary); letter-spacing: -1px; font-family: 'Poppins', sans-serif;">JHMS<span style="color: var(--accent);">.</span></span>
            </a>
        </div>
        
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div style="display: flex; align-items: center; gap: 1rem; border-left: 1px solid #eee; padding-left: 1.5rem;">
                    <div style="text-align: right; display: none; md:block;">
                        <span style="font-size: 0.7rem; color: var(--text-muted); display: block; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Welcome</span>
                        <span style="font-weight: 700; color: var(--primary); font-size: 0.9rem;"><?php echo $_SESSION['name'] ?? 'User'; ?></span>
                    </div>
                    <div style="width: 40px; height: 40px; background: #e3f2fd; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 800;">
                        <?php echo strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)); ?>
                    </div>
                </div>
            <?php else: ?>
                <?php if (!$is_home): ?>
                    <a href="<?php echo BASE_URL; ?>index.php" style="text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 0.9rem;">Home</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>auth/login.php" style="text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 0.9rem;">Log In</a>
                <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn-premium btn-primary">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php if ($is_dashboard) require_once 'sidebar.php'; ?>

    <main class="dashboard-content">
        <?php if ($is_home) require_once 'hero.php'; ?>
        <div class="content-container">
