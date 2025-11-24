<?php
// Lab 3: Admin Panel Bypass IDOR
// Vulnerability: Direct admin panel access without proper authorization

session_start();

// Simulate user authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user
    $_SESSION['username'] = 'user1';
    $_SESSION['role'] = 'user'; // Default role
}

$message = '';
$admin_data = '';
$action = $_GET['action'] ?? 'dashboard';
$user_id = $_GET['user_id'] ?? '';

// Simulate user database
$users = [
    1 => [
        'id' => 1,
        'username' => 'user1',
        'email' => 'user1@example.com',
        'role' => 'user',
        'status' => 'active',
        'last_login' => '2024-01-15 10:30:00',
        'created_date' => '2024-01-01',
        'permissions' => ['read_profile', 'edit_profile']
    ],
    2 => [
        'id' => 2,
        'username' => 'user2',
        'email' => 'user2@example.com',
        'role' => 'user',
        'status' => 'active',
        'last_login' => '2024-01-14 15:45:00',
        'created_date' => '2024-01-02',
        'permissions' => ['read_profile', 'edit_profile']
    ],
    3 => [
        'id' => 3,
        'username' => 'admin',
        'email' => 'admin@example.com',
        'role' => 'admin',
        'status' => 'active',
        'last_login' => '2024-01-15 09:15:00',
        'created_date' => '2023-12-01',
        'permissions' => ['admin_panel', 'user_management', 'system_settings', 'view_logs']
    ]
];

// Simulate system settings
$system_settings = [
    'site_name' => 'KrazePlanetLabs',
    'maintenance_mode' => false,
    'max_users' => 1000,
    'session_timeout' => 30,
    'backup_frequency' => 'daily',
    'security_level' => 'high'
];

// Simulate system logs
$system_logs = [
    ['timestamp' => '2024-01-15 10:30:00', 'level' => 'INFO', 'message' => 'User user1 logged in'],
    ['timestamp' => '2024-01-15 10:25:00', 'level' => 'WARNING', 'message' => 'Failed login attempt for user admin'],
    ['timestamp' => '2024-01-15 10:20:00', 'level' => 'ERROR', 'message' => 'Database connection timeout'],
    ['timestamp' => '2024-01-15 10:15:00', 'level' => 'INFO', 'message' => 'System backup completed successfully']
];

// Vulnerable: No proper authorization check for admin functions
switch ($action) {
    case 'dashboard':
        $admin_data = [
            'type' => 'dashboard',
            'total_users' => count($users),
            'active_users' => count(array_filter($users, fn($u) => $u['status'] === 'active')),
            'system_status' => 'Online',
            'last_backup' => '2024-01-15 02:00:00'
        ];
        $message = '<div class="alert alert-success">Admin dashboard loaded successfully!</div>';
        break;
        
    case 'users':
        $admin_data = [
            'type' => 'users',
            'users' => $users
        ];
        $message = '<div class="alert alert-success">User list loaded successfully!</div>';
        break;
        
    case 'user_details':
        if ($user_id && isset($users[$user_id])) {
            $admin_data = [
                'type' => 'user_details',
                'user' => $users[$user_id]
            ];
            $message = '<div class="alert alert-success">User details loaded successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">User not found!</div>';
        }
        break;
        
    case 'settings':
        $admin_data = [
            'type' => 'settings',
            'settings' => $system_settings
        ];
        $message = '<div class="alert alert-success">System settings loaded successfully!</div>';
        break;
        
    case 'logs':
        $admin_data = [
            'type' => 'logs',
            'logs' => $system_logs
        ];
        $message = '<div class="alert alert-success">System logs loaded successfully!</div>';
        break;
        
    default:
        $message = '<div class="alert alert-warning">Invalid action specified!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Admin Panel Bypass - IDOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-dark: #1a1f36;
            --primary-light: #2d3748;
            --accent-green: #48bb78;
            --accent-blue: #4299e1;
            --accent-orange: #ed8936;
            --accent-red: #f56565;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--accent-green) !important;
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--accent-green) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%231e293b"/><path d="M0 0L100 100M100 0L0 100" stroke="%23374151" stroke-width="1"/></svg>');
            padding: 2rem 0;
            border-bottom: 1px solid #2d3748;
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(90deg, #48bb78, #4299e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #cbd5e0;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            border-radius: 2px;
        }

        .card {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        .card-header {
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid #334155;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        .form-control, .form-select {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.2rem rgba(72, 187, 120, 0.25);
            color: #e2e8f0;
        }

        .form-label {
            font-weight: 500;
            color: #cbd5e0;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }

        .vulnerability-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-orange);
        }

        .payload-examples {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-blue);
        }

        .danger-zone {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-red);
        }

        pre {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            color: #e2e8f0;
            border: 1px solid #334155;
            overflow-x: auto;
        }

        .lab-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .lab-badge {
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            color: #1a202c;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .admin-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .admin-info {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .sensitive-data {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-red);
        }

        .admin-badge {
            background: var(--accent-red);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .nav-pills .nav-link {
            color: #cbd5e0;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
        }

        .nav-pills .nav-link.active {
            background: var(--accent-green);
            color: #1a202c;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to IDOR Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 3: Admin Panel Bypass</h1>
            <p class="hero-subtitle">IDOR in admin panel access control</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates an IDOR vulnerability in an admin panel system. The application allows users to access admin functions by simply changing the action parameter without proper authorization checks, even though they are not admin users.</p>
            <p><strong>Objective:</strong> Access admin panel functions by manipulating the action parameter to view sensitive administrative data.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No proper authorization check
$action = $_GET['action'] ?? 'dashboard';

// Simulate user authentication
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'user'; // Regular user, not admin

// Direct access to admin functions without checking role
switch ($action) {
    case 'dashboard':
        // Load admin dashboard
        break;
    case 'users':
        // Load user management
        break;
    case 'settings':
        // Load system settings
        break;
    case 'logs':
        // Load system logs
        break;
}

// Example vulnerable usage:
// ?action=dashboard (admin dashboard)
// ?action=users (user management)
// ?action=settings (system settings)
// ?action=logs (system logs)</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Admin Panel Access
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <ul class="nav nav-pills mb-3" id="adminTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'dashboard' ? 'active' : ''; ?>" 
                                        onclick="loadAdminPanel('dashboard')">Dashboard</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'users' ? 'active' : ''; ?>" 
                                        onclick="loadAdminPanel('users')">Users</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'settings' ? 'active' : ''; ?>" 
                                        onclick="loadAdminPanel('settings')">Settings</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'logs' ? 'active' : ''; ?>" 
                                        onclick="loadAdminPanel('logs')">Logs</button>
                            </li>
                        </ul>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?action=dashboard" style="color: var(--accent-green);">Admin Dashboard</a></li>
                                <li><a href="?action=users" style="color: var(--accent-green);">User Management</a></li>
                                <li><a href="?action=settings" style="color: var(--accent-green);">System Settings</a></li>
                                <li><a href="?action=logs" style="color: var(--accent-green);">System Logs</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($admin_data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Admin Panel: <?php echo ucfirst($action); ?>
                        <span class="admin-badge ms-2">ADMIN ACCESS</span>
                    </div>
                    <div class="card-body">
                        <div class="admin-display">
                            <?php if ($admin_data['type'] === 'dashboard'): ?>
                                <h5>System Dashboard</h5>
                                <div class="admin-info">
                                    <p><strong>Total Users:</strong> <?php echo $admin_data['total_users']; ?></p>
                                    <p><strong>Active Users:</strong> <?php echo $admin_data['active_users']; ?></p>
                                    <p><strong>System Status:</strong> <?php echo $admin_data['system_status']; ?></p>
                                    <p><strong>Last Backup:</strong> <?php echo $admin_data['last_backup']; ?></p>
                                </div>
                            <?php elseif ($admin_data['type'] === 'users'): ?>
                                <h5>User Management</h5>
                                <?php foreach ($admin_data['users'] as $user): ?>
                                    <div class="admin-info">
                                        <p><strong>ID:</strong> <?php echo $user['id']; ?> | 
                                           <strong>Username:</strong> <?php echo $user['username']; ?> | 
                                           <strong>Role:</strong> <?php echo $user['role']; ?> | 
                                           <strong>Status:</strong> <?php echo $user['status']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif ($admin_data['type'] === 'user_details'): ?>
                                <h5>User Details</h5>
                                <div class="sensitive-data">
                                    <p><strong>ID:</strong> <?php echo $admin_data['user']['id']; ?></p>
                                    <p><strong>Username:</strong> <?php echo $admin_data['user']['username']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $admin_data['user']['email']; ?></p>
                                    <p><strong>Role:</strong> <?php echo $admin_data['user']['role']; ?></p>
                                    <p><strong>Status:</strong> <?php echo $admin_data['user']['status']; ?></p>
                                    <p><strong>Last Login:</strong> <?php echo $admin_data['user']['last_login']; ?></p>
                                    <p><strong>Created Date:</strong> <?php echo $admin_data['user']['created_date']; ?></p>
                                    <p><strong>Permissions:</strong> <?php echo implode(', ', $admin_data['user']['permissions']); ?></p>
                                </div>
                            <?php elseif ($admin_data['type'] === 'settings'): ?>
                                <h5>System Settings</h5>
                                <div class="sensitive-data">
                                    <?php foreach ($admin_data['settings'] as $key => $value): ?>
                                        <p><strong><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</strong> <?php echo $value; ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($admin_data['type'] === 'logs'): ?>
                                <h5>System Logs</h5>
                                <?php foreach ($admin_data['logs'] as $log): ?>
                                    <div class="admin-info">
                                        <p><strong>[<?php echo $log['timestamp']; ?>]</strong> 
                                           <span class="badge bg-<?php echo $log['level'] === 'ERROR' ? 'danger' : ($log['level'] === 'WARNING' ? 'warning' : 'info'); ?>">
                                               <?php echo $log['level']; ?>
                                           </span> 
                                           <?php echo $log['message']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Insecure Direct Object Reference (IDOR)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>action</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Direct access to admin functions without authorization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these action values:</p>
                    <ul>
                        <li><code>dashboard</code> - Admin dashboard</li>
                        <li><code>users</code> - User management</li>
                        <li><code>settings</code> - System settings</li>
                        <li><code>logs</code> - System logs</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>3.php?action=dashboard</code></li>
                        <li><code>3.php?action=users</code></li>
                        <li><code>3.php?action=settings</code></li>
                        <li><code>3.php?action=logs</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?action=dashboard" style="color: var(--accent-green);">Admin Dashboard (Unauthorized Access)</a></li>
                <li><a href="?action=users" style="color: var(--accent-green);">User Management (Unauthorized Access)</a></li>
                <li><a href="?action=settings" style="color: var(--accent-green);">System Settings (Unauthorized Access)</a></li>
                <li><a href="?action=logs" style="color: var(--accent-green);">System Logs (Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to admin panel functions</li>
                <li>Access to user management and system settings</li>
                <li>Viewing system logs and sensitive information</li>
                <li>Privilege escalation and admin function access</li>
                <li>Data exfiltration and unauthorized data modification</li>
                <li>Bypassing access controls and authorization mechanisms</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper role-based access control (RBAC)</li>
                    <li>Check user permissions before allowing access to admin functions</li>
                    <li>Use indirect object references instead of direct action parameters</li>
                    <li>Implement proper session management and authentication</li>
                    <li>Use whitelist-based validation for allowed actions</li>
                    <li>Implement proper logging and monitoring for admin access</li>
                    <li>Regular security testing and access control reviews</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function loadAdminPanel(action) {
            window.location.href = '?action=' + action;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
