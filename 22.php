<?php
// ============================================================
// Database Configuration (inlined from config.php)
// ============================================================
$host = 'localhost';
$dbname = 'xss_labs';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize Lab 22 database tables
function initializeLab22Database($pdo) {
    // Users table for Lab 22
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lab22_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100),
            bio TEXT,
            website VARCHAR(255),
            location VARCHAR(100),
            avatar VARCHAR(255),
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Site settings table for Lab 22
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lab22_site_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            updated_by INT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Insert default site settings
    $pdo->exec("
        INSERT IGNORE INTO lab22_site_settings (setting_key, setting_value) VALUES
        ('site_title', 'KrazePlanetLabs - XSS Testing Platform'),
        ('site_description', 'A platform for learning about XSS vulnerabilities'),
        ('welcome_message', 'Welcome to our XSS testing platform!'),
        ('footer_text', '© 2024 KrazePlanetLabs. All rights reserved.')
    ");

    // Insert default admin user if none exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM lab22_users WHERE role = 'admin'");
    $check->execute();
    if ($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO lab22_users (username, email, password, full_name, role) VALUES (?, ?, ?, 'Administrator', 'admin')");
        $stmt->execute([
            'admin',
            'admin@lab22.local',
            password_hash('admin123', PASSWORD_DEFAULT)
        ]);
    }
}
initializeLab22Database($pdo);

// Session management
session_start();

// ============================================================
// Per-lab Authentication Helpers
// ============================================================
function labRequireLogin($labNum) {
    if (!isset($_SESSION["lab{$labNum}_user_id"])) {
        header("Location: {$labNum}.php");
        exit();
    }
}

function labRequireAdmin($labNum) {
    labRequireLogin($labNum);
    if ($_SESSION["lab{$labNum}_role"] !== 'admin') {
        die('ACCESS denied. Admin privileges required.');
    }
}

function labGetCurrentUser($pdo, $labNum) {
    if (!isset($_SESSION["lab{$labNum}_user_id"])) {
        return null;
    }
    $t = "lab{$labNum}_users";
    $stmt = $pdo->prepare("SELECT * FROM `$t` WHERE id = ?");
    $stmt->execute([$_SESSION["lab{$labNum}_user_id"]]);
    return $stmt->fetch();
}

function labLogin($pdo, $labNum, $username, $password) {
    $t = "lab{$labNum}_users";
    $stmt = $pdo->prepare("SELECT * FROM `$t` WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["lab{$labNum}_user_id"] = $user['id'];
        $_SESSION["lab{$labNum}_username"] = $user['username'];
        $_SESSION["lab{$labNum}_role"] = $user['role'];
        return true;
    }
    return false;
}

function labLogout($labNum) {
    unset($_SESSION["lab{$labNum}_user_id"]);
    unset($_SESSION["lab{$labNum}_username"]);
    unset($_SESSION["lab{$labNum}_role"]);
    header("Location: {$labNum}.php");
    exit();
}

// --- Logout ---
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    labLogout(22);
}

// --- Register ---
$authError = ''; $authSuccess = '';
if (!isset($_SESSION['lab22_user_id']) && isset($_POST['register'])) {
    $u  = trim($_POST['reg_username']  ?? '');
    $e  = trim($_POST['reg_email']     ?? '');
    $p  =      $_POST['reg_password']  ?? '';
    $c  =      $_POST['reg_confirm']   ?? '';
    $fn = trim($_POST['reg_full_name'] ?? '');
    if (empty($u) || empty($e) || empty($p)) {
        $authError = 'Username, email and password are required';
    } elseif ($p !== $c) {
        $authError = 'Passwords do not match';
    } elseif (strlen($p) < 6) {
        $authError = 'Password must be at least 6 characters';
    } else {
        try {
            $chk = $pdo->prepare("SELECT COUNT(*) FROM lab22_users WHERE username = ? OR email = ?");
            $chk->execute([$u, $e]);
            if ($chk->fetchColumn()) {
                $authError = 'Username or email already exists';
            } else {
                $ins = $pdo->prepare("INSERT INTO lab22_users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
                $ins->execute([$u, $e, password_hash($p, PASSWORD_DEFAULT), $fn]);
                $authSuccess = 'Account created! You can now login.';
            }
        } catch (PDOException $ex) { $authError = 'Registration failed: ' . $ex->getMessage(); }
    }
}

// --- Login ---
if (!isset($_SESSION['lab22_user_id']) && isset($_POST['login'])) {
    if (labLogin($pdo, 22, $_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: 22.php'); exit();
    } else {
        $authError = 'Invalid username or password';
    }
}

// --- Auth Gate ---
if (!isset($_SESSION['lab22_user_id'])) {
    $authView = $_GET['view'] ?? 'login';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stored XSS - Admin Panel Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="icon" href="favicon.ico" />
  <style>
    :root { --accent-green:#48bb78; --accent-blue:#4299e1; --accent-orange:#ed8936; --accent-red:#f56565; }
    body { background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%); color:#e2e8f0; min-height:100vh; font-family:'Segoe UI',sans-serif; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; }
    .auth-box { background:rgba(30,41,59,0.85); border-radius:14px; border:1px solid #334155; padding:2rem; width:100%; max-width:440px; box-shadow:0 8px 32px rgba(0,0,0,0.35); }
    .auth-title { font-size:1.9rem; font-weight:700; background:linear-gradient(90deg,#48bb78,#4299e1); -webkit-background-clip:text; -webkit-text-fill-color:transparent; text-align:center; margin-bottom:0.2rem; }
    .auth-sub { text-align:center; font-size:0.85rem; color:#94a3b8; margin-bottom:1.5rem; }
    .form-control { background:rgba(30,41,59,0.7); border:1px solid #334155; color:#e2e8f0; padding:0.7rem 1rem; }
    .form-control:focus { background:rgba(30,41,59,0.9); border-color:var(--accent-green); box-shadow:0 0 0 0.2rem rgba(72,187,120,0.25); color:#e2e8f0; }
    .form-label { font-weight:500; color:#cbd5e0; font-size:0.9rem; }
    .btn-primary { background:linear-gradient(90deg,var(--accent-green),var(--accent-blue)); border:none; padding:0.7rem 1.5rem; font-weight:600; transition:all 0.3s; width:100%; }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(72,187,120,0.3); }
    .alert-danger  { background:rgba(245,101,101,0.1); border:1px solid var(--accent-red);   color:var(--accent-red);   }
    .alert-success { background:rgba(72,187,120,0.1);  border:1px solid var(--accent-green); color:var(--accent-green); }
    .demo-box { background:rgba(15,23,42,0.7); border-radius:8px; padding:0.9rem 1rem; margin-top:1rem; border-left:4px solid var(--accent-orange); font-size:0.85rem; color:#94a3b8; }
    .demo-box strong { color:var(--accent-orange); display:block; margin-bottom:0.25rem; }
    .admin-note { background:rgba(245,101,101,0.08); border:1px solid rgba(245,101,101,0.3); border-radius:8px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.85rem; color:#fca5a5; }
    .switch-link { text-align:center; margin-top:1rem; font-size:0.9rem; }
    .switch-link a { color:var(--accent-green); text-decoration:none; }
  </style>
</head>
<body>
<div class="auth-box">
  <h1 class="auth-title"><i class="bi bi-shield-lock me-2"></i><?php echo $authView === 'register' ? 'Create Account' : 'Login'; ?></h1>
  <p class="auth-sub">Lab 22 — Admin Content Management</p>
  <div class="admin-note"><i class="bi bi-exclamation-triangle me-1"></i>This lab requires an <strong>admin</strong> account. Use: <code>admin / admin123</code></div>
  <?php if ($authError): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($authError); ?></div>
  <?php endif; ?>
  <?php if ($authSuccess): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($authSuccess); ?></div>
  <?php endif; ?>
  <?php if ($authView === 'register'): ?>
  <form method="POST">
    <div class="row g-2 mb-2">
      <div class="col-6"><label class="form-label">Username *</label>
        <input type="text" class="form-control" name="reg_username" value="<?php echo htmlspecialchars($_POST['reg_username'] ?? ''); ?>" required></div>
      <div class="col-6"><label class="form-label">Email *</label>
        <input type="email" class="form-control" name="reg_email" value="<?php echo htmlspecialchars($_POST['reg_email'] ?? ''); ?>" required></div>
    </div>
    <div class="mb-2"><label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="reg_full_name" value="<?php echo htmlspecialchars($_POST['reg_full_name'] ?? ''); ?>"></div>
    <div class="row g-2 mb-3">
      <div class="col-6"><label class="form-label">Password *</label>
        <input type="password" class="form-control" name="reg_password" required></div>
      <div class="col-6"><label class="form-label">Confirm *</label>
        <input type="password" class="form-control" name="reg_confirm" required></div>
    </div>
    <button type="submit" name="register" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Create Account</button>
  </form>
  <div class="switch-link"><a href="22.php"><i class="bi bi-arrow-left me-1"></i>Back to Login</a></div>
  <?php else: ?>
  <form method="POST">
    <div class="mb-3"><label class="form-label">Username or Email</label>
      <input type="text" class="form-control" name="username" required></div>
    <div class="mb-3"><label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" required></div>
    <button type="submit" name="login" class="btn btn-primary"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
  </form>
  <div class="switch-link"><a href="22.php?view=register"><i class="bi bi-person-plus me-1"></i>Create New Account</a></div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body></html>
<?php exit(); }

// --- Admin Check ---
if ($_SESSION['lab22_role'] !== 'admin') {
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stored XSS - Admin Panel Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <style>
    body { background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%); color:#e2e8f0; min-height:100vh; font-family:'Segoe UI',sans-serif; display:flex; align-items:center; justify-content:center; }
    .box { background:rgba(30,41,59,0.85); border-radius:14px; border:1px solid #f56565; padding:2.5rem; text-align:center; max-width:420px; }
    .box h1 { font-size:1.8rem; color:#f56565; margin-bottom:0.5rem; }
    .box p { color:#94a3b8; margin-bottom:1.5rem; }
    a.btn { background:linear-gradient(90deg,#48bb78,#4299e1); border:none; color:#fff; font-weight:600; padding:0.65rem 1.5rem; border-radius:8px; text-decoration:none; }
  </style>
</head>
<body>
<div class="box">
  <h1><i class="bi bi-shield-x me-2"></i>ACCESS Denied</h1>
  <p>Lab 22 requires an <strong>admin</strong> account.<br>Please login with admin credentials.</p>
  <a href="22.php?action=logout" class="btn"><i class="bi bi-arrow-left me-1"></i>Back to Login</a>
</div>
</body></html>
<?php exit(); }

// --- Lab 22 Logic (admin logged in) ---
$user = labGetCurrentUser($pdo, 22);
$error = '';
$success = '';

// Handle site settings update
if ($_POST && isset($_POST['update_settings'])) {
    $site_title = trim($_POST['site_title'] ?? '');
    $site_description = trim($_POST['site_description'] ?? '');
    $welcome_message = trim($_POST['welcome_message'] ?? '');
    $footer_text = trim($_POST['footer_text'] ?? '');
    
    try {
        // Vulnerable: No sanitization of admin input before storing in database
        $stmt = $pdo->prepare("
            UPDATE lab22_site_settings 
            SET setting_value = ?, updated_by = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE setting_key = ?
        ");
        
        $stmt->execute([$site_title, $user['id'], 'site_title']);
        $stmt->execute([$site_description, $user['id'], 'site_description']);
        $stmt->execute([$welcome_message, $user['id'], 'welcome_message']);
        $stmt->execute([$footer_text, $user['id'], 'footer_text']);
        
        $success = 'Site settings updated successfully!';
    } catch (PDOException $e) {
        $error = 'Failed to update site settings: ' . $e->getMessage();
    }
}

// Get current site settings
$stmt = $pdo->prepare("SELECT setting_key, setting_value FROM lab22_site_settings");
$stmt->execute();
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get all users for admin view
$stmt = $pdo->prepare("
    SELECT id, username, full_name, email, role, created_at 
    FROM lab22_users 
    ORDER BY created_at DESC 
    LIMIT 20
");
$stmt->execute();
$all_users = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stored XSS - Admin Panel Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="icon" href="favicon.ico" />

  <style>
    :root { --accent-green:#48bb78; --accent-blue:#4299e1; --accent-orange:#ed8936; --accent-red:#f56565; }
    body { background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%); color:#e2e8f0; min-height:100vh; font-family:'Segoe UI',sans-serif; display:flex; flex-direction:column; }
    a { text-decoration:none; color:inherit; } a:hover { color:inherit; }
    .navbar { background:linear-gradient(90deg,#0f172a 0%,#1e1b4b 100%) !important; border-bottom:1px solid #334155; padding:0.75rem 0; }
    .navbar-brand { font-weight:700; font-size:1.4rem; background:linear-gradient(90deg,#ef4444,#f97316); -webkit-background-clip:text; -webkit-text-fill-color:transparent; display:flex; align-items:center; gap:0.5rem; }
    .navbar-brand i { background:linear-gradient(90deg,#ef4444,#f97316); -webkit-background-clip:text; -webkit-text-fill-color:transparent; font-size:1.5rem; }
    .navbar-nav { margin:0 auto; gap:1.5rem; }
    .navbar-nav .nav-link { color:#94a3b8 !important; font-weight:500; padding:0.5rem 1rem !important; border-radius:0.5rem; transition:all 0.3s; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color:#e2e8f0 !important; background:rgba(255,255,255,0.05); }
    .user-pill { background:rgba(255,255,255,0.06); border:1px solid #334155; border-radius:2rem; padding:0.35rem 1rem; font-size:0.875rem; color:#cbd5e0; }
    .hero-section { background:linear-gradient(rgba(15,23,42,0.9),rgba(15,23,42,0.9)), url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%231e293b"/><path d="M0 0L100 100M100 0L0 100" stroke="%23374151" stroke-width="1"/></svg>'); padding:3rem 0; text-align:center; border-bottom:1px solid #2d3748; margin-bottom:2rem; }
    .hero-title { font-size:2.5rem; font-weight:700; background:linear-gradient(90deg,#48bb78,#4299e1); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.75rem; }
    .hero-subtitle { font-size:1.1rem; color:#cbd5e0; }
    .card { background:rgba(30,41,59,0.7); border-radius:12px; border:1px solid #334155; color:#e2e8f0; transition:all 0.3s ease; }
    .card:hover { box-shadow:0 8px 24px rgba(0,0,0,0.25); border-color:var(--accent-green); }
    .card-header { background:rgba(15,23,42,0.5); border-bottom:1px solid #334155; font-weight:600; padding:1rem 1.5rem; border-radius:12px 12px 0 0 !important; }
    .form-control { background:rgba(30,41,59,0.7); border:1px solid #334155; color:#e2e8f0; padding:0.75rem 1rem; }
    .form-control:focus { background:rgba(30,41,59,0.9); border-color:var(--accent-green); box-shadow:0 0 0 0.2rem rgba(72,187,120,0.25); color:#e2e8f0; }
    .form-label { font-weight:500; color:#cbd5e0; }
    .btn-primary { background:linear-gradient(90deg,var(--accent-green),var(--accent-blue)); border:none; padding:0.75rem 1.5rem; font-weight:600; transition:all 0.3s; }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(72,187,120,0.3); }
    .admin-warning { background:rgba(245,101,101,0.1); border:1px solid var(--accent-red); border-radius:8px; padding:1rem; margin-bottom:1.5rem; color:var(--accent-red); }
    .site-preview { background:rgba(15,23,42,0.7); border-radius:8px; padding:1.5rem; border-left:4px solid var(--accent-green); }
    .site-title-preview { font-size:1.8rem; font-weight:700; color:var(--accent-green); margin-bottom:0.5rem; }
    .site-description-preview { color:#cbd5e0; margin-bottom:1rem; }
    .welcome-message { background:rgba(15,23,42,0.5); border-radius:6px; padding:1rem; margin-bottom:1rem; border-left:3px solid var(--accent-blue); }
    .footer-text { color:#94a3b8; font-size:0.9rem; border-top:1px solid #334155; padding-top:1rem; }
    .user-item { background:rgba(15,23,42,0.7); border-radius:8px; padding:1rem; margin-bottom:0.75rem; border-left:4px solid var(--accent-green); }
    .user-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.25rem; }
    .user-name { font-weight:600; color:var(--accent-green); }
    .user-role { padding:0.2rem 0.5rem; border-radius:4px; font-size:0.78rem; font-weight:600; }
    .role-admin { background:rgba(245,101,101,0.2); color:var(--accent-red); }
    .role-user  { background:rgba(72,187,120,0.2);  color:var(--accent-green); }
    .user-details { font-size:0.85rem; color:#94a3b8; }
    .vulnerability-info { background:rgba(30,41,59,0.7); border-radius:12px; border:1px solid #334155; padding:1.5rem; margin-bottom:1.5rem; border-left:4px solid var(--accent-orange); }
    .payload-examples { background:rgba(30,41,59,0.7); border-radius:12px; border:1px solid #334155; padding:1.5rem; margin-bottom:1.5rem; border-left:4px solid var(--accent-blue); }
    .danger-zone { background:rgba(30,41,59,0.7); border-radius:12px; border:1px solid #334155; padding:1.5rem; margin-bottom:1.5rem; border-left:4px solid var(--accent-red); }
    pre { background:rgba(15,23,42,0.7); border-radius:8px; padding:1.5rem; color:#e2e8f0; border:1px solid #334155; overflow-x:auto; font-size:0.82rem; }
    .alert-danger  { background:rgba(245,101,101,0.1); border:1px solid var(--accent-red);   color:var(--accent-red);   }
    .alert-success { background:rgba(72,187,120,0.1);  border:1px solid var(--accent-green); color:var(--accent-green); }
    footer { text-align:center; padding:1rem 0; color:#94a3b8; font-size:0.875rem; border-top:1px solid #334155; margin-top:auto; }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-md navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="/KrazePlanetLabs/"><i class="bi bi-fire"></i>KrazePlanetLabs</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="/KrazePlanetLabs/">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="index.php">Labs</a></li>
          <li class="nav-item"><a class="nav-link" href="22.php?action=logout">Logout</a></li>
        </ul>
        <div class="ms-auto d-flex align-items-center gap-2">
          <span class="user-pill"><i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($user['username']); ?></span>
          <span class="badge bg-danger">Admin</span>
        </div>
      </div>
    </div>
  </nav>

  <div class="hero-section">
    <div class="container">
      <h1 class="hero-title">Lab 22: Admin Content Management</h1>
      <p class="hero-subtitle">Real-World Stored XSS in Admin Panel</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-header"><i class="bi bi-shield-lock me-2"></i>Site Settings Management</div>
          <div class="card-body">
            <?php if ($error): ?>
              <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
              <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST">
              <div class="mb-3">
                <label for="site_title" class="form-label">Site Title</label>
                <input type="text" class="form-control" id="site_title" name="site_title"
                       value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>" required>
              </div>
              <div class="mb-3">
                <label for="site_description" class="form-label">Site Description</label>
                <textarea class="form-control" id="site_description" name="site_description" rows="2"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
              </div>
              <div class="mb-3">
                <label for="welcome_message" class="form-label">Welcome Message</label>
                <textarea class="form-control" id="welcome_message" name="welcome_message" rows="3"><?php echo htmlspecialchars($settings['welcome_message'] ?? ''); ?></textarea>
              </div>
              <div class="mb-3">
                <label for="footer_text" class="form-label">Footer Text</label>
                <input type="text" class="form-control" id="footer_text" name="footer_text"
                       value="<?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>">
              </div>
              <button type="submit" name="update_settings" class="btn btn-primary w-100">
                <i class="bi bi-save me-2"></i>Update Settings
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-1">
      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-header"><i class="bi bi-eye me-2"></i>Live Site Preview</div>
          <div class="card-body">
            <div class="site-preview">
              <div class="site-title-preview"><?php echo $settings['site_title'] ?? 'Site Title'; // Vulnerable ?></div>
              <div class="site-description-preview"><?php echo $settings['site_description'] ?? 'Site description'; // Vulnerable ?></div>
              <div class="welcome-message"><strong>Welcome Message:</strong><br><?php echo $settings['welcome_message'] ?? 'Welcome!'; // Vulnerable ?></div>
              <div class="footer-text"><?php echo $settings['footer_text'] ?? '© 2024 KrazePlanetLabs.'; // Vulnerable ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-header"><i class="bi bi-people me-2"></i>User Management <span class="badge ms-1" style="background:rgba(72,187,120,0.2);color:var(--accent-green);"><?php echo count($all_users); ?></span></div>
          <div class="card-body" style="max-height:320px;overflow-y:auto;">
            <?php foreach ($all_users as $profile_user): ?>
              <div class="user-item">
                <div class="user-header">
                  <div class="user-name"><i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($profile_user['full_name'] ?: $profile_user['username']); ?></div>
                  <div class="user-role role-<?php echo $profile_user['role']; ?>"><?php echo ucfirst($profile_user['role']); ?></div>
                </div>
                <div class="user-details">
                  <i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($profile_user['email']); ?>
                  <span class="ms-3"><i class="bi bi-calendar me-1"></i>Joined <?php echo date('M Y', strtotime($profile_user['created_at'])); ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer><p class="mb-0">© 2026 KrazePlanet. All rights reserved.</p></footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>
</html>
