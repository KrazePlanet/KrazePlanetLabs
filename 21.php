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

// Initialize Lab 21 database tables
function initializeLab21Database($pdo) {
    // Users table for Lab 21
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lab21_users (
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

    // Support tickets table for Lab 21
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lab21_support_tickets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            subject VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
            priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Insert default admin user if none exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM lab21_users WHERE role = 'admin'");
    $check->execute();
    if ($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO lab21_users (username, email, password, full_name, role) VALUES (?, ?, ?, 'Administrator', 'admin')");
        $stmt->execute([
            'admin',
            'admin@lab21.local',
            password_hash('admin123', PASSWORD_DEFAULT)
        ]);
    }
}
initializeLab21Database($pdo);

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
    labLogout(21);
}

// --- Register ---
$authError = ''; $authSuccess = '';
if (!isset($_SESSION['lab21_user_id']) && isset($_POST['register'])) {
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
            $chk = $pdo->prepare("SELECT COUNT(*) FROM lab21_users WHERE username = ? OR email = ?");
            $chk->execute([$u, $e]);
            if ($chk->fetchColumn()) {
                $authError = 'Username or email already exists';
            } else {
                $ins = $pdo->prepare("INSERT INTO lab21_users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
                $ins->execute([$u, $e, password_hash($p, PASSWORD_DEFAULT), $fn]);
                $authSuccess = 'Account created! You can now login.';
            }
        } catch (PDOException $ex) { $authError = 'Registration failed: ' . $ex->getMessage(); }
    }
}

// --- Login ---
if (!isset($_SESSION['lab21_user_id']) && isset($_POST['login'])) {
    if (labLogin($pdo, 21, $_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: 21.php'); exit();
    } else {
        $authError = 'Invalid username or password';
    }
}

// --- Auth Gate ---
if (!isset($_SESSION['lab21_user_id'])) {
    $authView = $_GET['view'] ?? 'login';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stored XSS - Support Ticket System</title>
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
    .switch-link { text-align:center; margin-top:1rem; font-size:0.9rem; }
    .switch-link a { color:var(--accent-green); text-decoration:none; }
  </style>
</head>
<body>
<div class="auth-box">
  <h1 class="auth-title"><i class="bi bi-shield-shaded me-2"></i><?php echo $authView === 'register' ? 'Create Account' : 'Login'; ?></h1>
  <p class="auth-sub">Lab 21 — Support Ticket System</p>
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
  <div class="switch-link"><a href="21.php"><i class="bi bi-arrow-left me-1"></i>Back to Login</a></div>
  <?php else: ?>
  <form method="POST">
    <div class="mb-3"><label class="form-label">Username or Email</label>
      <input type="text" class="form-control" name="username" required></div>
    <div class="mb-3"><label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" required></div>
    <button type="submit" name="login" class="btn btn-primary"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
  </form>
  <div class="demo-box"><strong><i class="bi bi-info-circle me-1"></i>Demo Accounts</strong>admin / admin123 &nbsp;|&nbsp; <em>or register a new account</em></div>
  <div class="switch-link"><a href="21.php?view=register"><i class="bi bi-person-plus me-1"></i>Create New Account</a></div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body></html>
<?php exit(); }

// --- Lab 21 Logic (logged in) ---
$user = labGetCurrentUser($pdo, 21);
$error = '';
$success = '';

// Handle support ticket creation
if ($_POST && isset($_POST['create_ticket'])) {
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? 'medium';
    
    if (empty($subject) || empty($description)) {
        $error = 'Subject and description are required';
    } else {
        try {
            // Vulnerable: No sanitization of user input before storing in database
            $stmt = $pdo->prepare("
                INSERT INTO lab21_support_tickets (user_id, subject, description, priority) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$user['id'], $subject, $description, $priority]);
            $success = 'Support ticket created successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to create support ticket: ' . $e->getMessage();
        }
    }
}

// Get all support tickets
$stmt = $pdo->prepare("
    SELECT st.*, u.username, u.full_name 
    FROM lab21_support_tickets st 
    JOIN lab21_users u ON st.user_id = u.id 
    ORDER BY st.created_at DESC 
    LIMIT 20
");
$stmt->execute();
$tickets = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stored XSS - Support Ticket System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="icon" href="favicon.ico" />
  <style>
    :root { --accent-green: #48bb78; --accent-blue: #4299e1; --accent-orange: #ed8936; --accent-red: #f56565; }
    body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #e2e8f0; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; flex-direction: column; }
    a { text-decoration: none; color: inherit; } a:hover { color: inherit; }
    .navbar { background: linear-gradient(90deg, #0f172a 0%, #1e1b4b 100%) !important; border-bottom: 1px solid #334155; padding: 0.75rem 0; }
    .navbar-brand { font-weight: 700; font-size: 1.4rem; background: linear-gradient(90deg, #ef4444, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: flex; align-items: center; gap: 0.5rem; }
    .navbar-brand i { background: linear-gradient(90deg, #ef4444, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.5rem; }
    .navbar-nav { margin: 0 auto; gap: 1.5rem; }
    .navbar-nav .nav-link { color: #94a3b8 !important; font-weight: 500; padding: 0.5rem 1rem !important; border-radius: 0.5rem; transition: all 0.3s; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #e2e8f0 !important; background: rgba(255,255,255,0.05); }
    .user-pill { background: rgba(255,255,255,0.06); border: 1px solid #334155; border-radius: 2rem; padding: 0.35rem 1rem; font-size: 0.875rem; color: #cbd5e0; }
    .hero-section {
      background: linear-gradient(rgba(15,23,42,0.9), rgba(15,23,42,0.9)),
                  url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%231e293b"/><path d="M0 0L100 100M100 0L0 100" stroke="%23374151" stroke-width="1"/></svg>');
      padding: 3rem 0; text-align: center; border-bottom: 1px solid #2d3748; margin-bottom: 2rem;
    }
    .hero-title { font-size: 2.5rem; font-weight: 700; background: linear-gradient(90deg, #48bb78, #4299e1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 0.75rem; }
    .hero-subtitle { font-size: 1.1rem; color: #cbd5e0; }
    .card { background: rgba(30,41,59,0.7); border-radius: 12px; border: 1px solid #334155; color: #e2e8f0; transition: all 0.3s ease; }
    .card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.25); border-color: var(--accent-green); }
    .card-header { background: rgba(15,23,42,0.5); border-bottom: 1px solid #334155; font-weight: 600; padding: 1rem 1.5rem; border-radius: 12px 12px 0 0 !important; }
    .form-control, .form-select { background: rgba(30,41,59,0.7); border: 1px solid #334155; color: #e2e8f0; padding: 0.75rem 1rem; }
    .form-control:focus, .form-select:focus { background: rgba(30,41,59,0.9); border-color: var(--accent-green); box-shadow: 0 0 0 0.2rem rgba(72,187,120,0.25); color: #e2e8f0; }
    .form-select option { background: #1e293b; }
    .form-label { font-weight: 500; color: #cbd5e0; }
    .btn-primary { background: linear-gradient(90deg, var(--accent-green), var(--accent-blue)); border: none; padding: 0.75rem 1.5rem; font-weight: 600; transition: all 0.3s; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(72,187,120,0.3); }
    .ticket-item { background: rgba(15,23,42,0.7); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; border-left: 4px solid var(--accent-green); }
    .ticket-title { font-size: 1.2rem; font-weight: 700; color: var(--accent-green); margin-bottom: 0.5rem; }
    .ticket-meta { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.75rem; }
    .ticket-priority, .ticket-status { padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.78rem; font-weight: 600; }
    .priority-low { background: rgba(72,187,120,0.2); color: var(--accent-green); }
    .priority-medium { background: rgba(237,137,54,0.2); color: var(--accent-orange); }
    .priority-high { background: rgba(245,101,101,0.2); color: var(--accent-red); }
    .priority-critical { background: rgba(245,101,101,0.4); color: #fff; }
    .status-open { background: rgba(72,187,120,0.2); color: var(--accent-green); }
    .status-in_progress { background: rgba(237,137,54,0.2); color: var(--accent-orange); }
    .status-resolved { background: rgba(66,153,225,0.2); color: var(--accent-blue); }
    .status-closed { background: rgba(107,114,128,0.2); color: #9ca3af; }
    .ticket-description { color: #e2e8f0; line-height: 1.6; margin-bottom: 0.75rem; }
    .ticket-author { font-size: 0.85rem; color: #94a3b8; border-top: 1px solid #334155; padding-top: 0.5rem; }
    .alert-danger  { background: rgba(245,101,101,0.1); border: 1px solid var(--accent-red);   color: var(--accent-red);   }
    .alert-success { background: rgba(72,187,120,0.1);  border: 1px solid var(--accent-green); color: var(--accent-green); }
    footer { text-align: center; padding: 1rem 0; color: #94a3b8; font-size: 0.875rem; border-top: 1px solid #334155; margin-top: auto; }
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
          <li class="nav-item"><a class="nav-link" href="21.php?action=logout">Logout</a></li>
        </ul>
        <div class="ms-auto d-flex align-items-center gap-3">
          <span class="user-pill"><i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($user['username']); ?></span>
        </div>
      </div>
    </div>
  </nav>

  <div class="hero-section">
    <div class="container">
      <h1 class="hero-title">Lab 21: Support Ticket System</h1>
      <p class="hero-subtitle">Real-World Stored XSS in Support Tickets</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="card h-100">
          <div class="card-header"><i class="bi bi-ticket-detailed me-2"></i>Create Support Ticket</div>
          <div class="card-body">
            <?php if ($error): ?>
              <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
              <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST">
              <div class="mb-3">
                <label for="subject" class="form-label">Subject *</label>
                <input type="text" class="form-control" id="subject" name="subject"
                       placeholder="Brief description of your issue" required>
              </div>
              <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority">
                  <option value="low">Low</option>
                  <option value="medium" selected>Medium</option>
                  <option value="high">High</option>
                  <option value="critical">Critical</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="6"
                          placeholder="Please provide detailed information about your issue..." required></textarea>
              </div>
              <button type="submit" name="create_ticket" class="btn btn-primary w-100">
                <i class="bi bi-send me-2"></i>Create Ticket
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-ticket-detailed me-2"></i>Support Tickets
            <span class="badge ms-2" style="background:rgba(72,187,120,0.2);color:var(--accent-green);"><?php echo count($tickets); ?></span>
          </div>
          <div class="card-body" style="max-height:600px;overflow-y:auto;">
            <?php if (empty($tickets)): ?>
              <p class="text-muted">No support tickets yet. Create the first one!</p>
            <?php else: ?>
              <?php foreach ($tickets as $ticket): ?>
                <div class="ticket-item">
                  <div class="ticket-title"><?php echo $ticket['subject']; // Vulnerable: Direct output without sanitization ?></div>
                  <div class="ticket-meta">
                    <span class="ticket-priority priority-<?php echo $ticket['priority']; ?>"><?php echo ucfirst($ticket['priority']); ?></span>
                    <span class="ticket-status status-<?php echo $ticket['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?></span>
                    <span style="font-size:0.8rem;color:#94a3b8;"><i class="bi bi-calendar me-1"></i><?php echo date('M j, Y g:i A', strtotime($ticket['created_at'])); ?></span>
                  </div>
                  <div class="ticket-description"><?php echo $ticket['description']; // Vulnerable: Direct output without sanitization ?></div>
                  <div class="ticket-author"><i class="bi bi-person-circle me-1"></i>Submitted by <?php echo htmlspecialchars($ticket['full_name'] ?: $ticket['username']); ?></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
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
