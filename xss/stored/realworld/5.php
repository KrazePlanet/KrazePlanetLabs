<?php
require_once 'config.php';
requireLogin();
requireAdmin();

$user = getCurrentUser($pdo);
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
            UPDATE site_settings 
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
$stmt = $pdo->prepare("SELECT setting_key, setting_value FROM site_settings");
$stmt->execute();
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get all users for admin view
$stmt = $pdo->prepare("
    SELECT id, username, full_name, email, role, created_at 
    FROM users 
    ORDER BY created_at DESC 
    LIMIT 20
");
$stmt->execute();
$all_users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Admin Content Management - Real-World Stored XSS</title>
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

        .form-control {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
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

        .admin-warning {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid var(--accent-red);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: var(--accent-red);
        }

        .site-preview {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .site-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-green);
            margin-bottom: 0.5rem;
        }

        .site-description {
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        .welcome-message {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 3px solid var(--accent-blue);
        }

        .footer-text {
            color: #94a3b8;
            font-size: 0.9rem;
            border-top: 1px solid #334155;
            padding-top: 1rem;
        }

        .user-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-green);
        }

        .user-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--accent-green);
        }

        .user-role {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .role-admin { background: rgba(245, 101, 101, 0.2); color: var(--accent-red); }
        .role-user { background: rgba(72, 187, 120, 0.2); color: var(--accent-green); }

        .user-details {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .alert-danger {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid var(--accent-red);
            color: var(--accent-red);
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            border: 1px solid var(--accent-green);
            color: var(--accent-green);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                    <span class="badge bg-danger ms-2">Admin</span>
                </span>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 5: Admin Content Management</h1>
            <p class="hero-subtitle">Real-World Stored XSS in Admin Panel</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a real-world stored XSS vulnerability in an admin content management system. Site settings are stored in a MySQL database and displayed to all users without proper sanitization.</p>
            <p><strong>Objective:</strong> Inject persistent XSS payloads in admin site settings that will affect all website visitors.</p>
        </div>

        <div class="admin-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Critical Warning:</strong> This lab simulates an admin panel vulnerability. In real scenarios, this would affect ALL website visitors and could lead to complete site compromise.
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle admin settings update
if ($_POST && isset($_POST['update_settings'])) {
    $site_title = $_POST['site_title'];
    $site_description = $_POST['site_description'];
    $welcome_message = $_POST['welcome_message'];
    $footer_text = $_POST['footer_text'];
    
    // Vulnerable: No sanitization before database storage
    $stmt = $pdo->prepare("
        UPDATE site_settings 
        SET setting_value = ? 
        WHERE setting_key = ?
    ");
    $stmt->execute([$site_title, 'site_title']);
    // ... other settings
}

// Display site content (also vulnerable)
echo "&lt;title&gt;" . $settings['site_title'] . "&lt;/title&gt;";
echo "&lt;h1&gt;" . $settings['site_title'] . "&lt;/h1&gt;";
// ... other content</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Site Settings Management
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="site_title" class="form-label">Site Title</label>
                                <input type="text" class="form-control" id="site_title" name="site_title" 
                                       value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="site_description" class="form-label">Site Description</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="2" 
                                          placeholder="Brief description of the site..."><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="welcome_message" class="form-label">Welcome Message</label>
                                <textarea class="form-control" id="welcome_message" name="welcome_message" rows="3" 
                                          placeholder="Welcome message for visitors..."><?php echo htmlspecialchars($settings['welcome_message'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="footer_text" class="form-label">Footer Text</label>
                                <input type="text" class="form-control" id="footer_text" name="footer_text" 
                                       value="<?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>" 
                                       placeholder="Footer copyright text...">
                            </div>
                            <button type="submit" name="update_settings" class="btn btn-primary">Update Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-eye me-2"></i>Live Site Preview
                    </div>
                    <div class="card-body">
                        <div class="site-preview">
                            <div class="site-title">
                                <?php echo $settings['site_title'] ?? 'Site Title'; // Vulnerable: Direct output without sanitization ?>
                            </div>
                            <div class="site-description">
                                <?php echo $settings['site_description'] ?? 'Site description'; // Vulnerable: Direct output without sanitization ?>
                            </div>
                            <div class="welcome-message">
                                <strong>Welcome Message:</strong><br>
                                <?php echo $settings['welcome_message'] ?? 'Welcome to our site!'; // Vulnerable: Direct output without sanitization ?>
                            </div>
                            <div class="footer-text">
                                <?php echo $settings['footer_text'] ?? '© 2024 KrazePlanetLabs. All rights reserved.'; // Vulnerable: Direct output without sanitization ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-people me-2"></i>User Management (<?php echo count($all_users); ?> users)
                    </div>
                    <div class="card-body">
                        <?php foreach ($all_users as $profile_user): ?>
                            <div class="user-item">
                                <div class="user-header">
                                    <div class="user-name">
                                        <i class="bi bi-person-circle me-1"></i>
                                        <?php echo htmlspecialchars($profile_user['full_name'] ?: $profile_user['username']); ?>
                                    </div>
                                    <div class="user-role role-<?php echo $profile_user['role']; ?>">
                                        <?php echo ucfirst($profile_user['role']); ?>
                                    </div>
                                </div>
                                <div class="user-details">
                                    <i class="bi bi-envelope me-1"></i>
                                    <?php echo htmlspecialchars($profile_user['email']); ?>
                                    <span class="ms-3">
                                        <i class="bi bi-calendar me-1"></i>
                                        Joined <?php echo date('M Y', strtotime($profile_user['created_at'])); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Stored XSS in Admin Panel</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameters:</strong> <code>site_title</code>, <code>site_description</code>, <code>welcome_message</code>, <code>footer_text</code></li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Storage:</strong> MySQL Database</li>
                        <li><strong>Issue:</strong> Admin site settings vulnerable to XSS</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p><strong>Site Title field:</strong></p>
                    <ul>
                        <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>Welcome Message field:</strong></p>
                    <ul>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                    </ul>
                    <p><strong>Footer Text field:</strong></p>
                    <ul>
                        <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;script&gt;document.location='http://evil.com'&lt;/script&gt;</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete website defacement and takeover</li>
                <li>Mass credential theft from ALL visitors</li>
                <li>Malware distribution to all site users</li>
                <li>Session hijacking and account compromise</li>
                <li>SEO poisoning and reputation damage</li>
                <li>Backdoor installation for persistent access</li>
                <li>Complete administrative control compromise</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use <code>htmlspecialchars()</code> for HTML entity encoding</li>
                    <li>Implement Content Security Policy (CSP) headers</li>
                    <li>Validate and sanitize all admin input before database storage</li>
                    <li>Use whitelist-based input validation for each field type</li>
                    <li>Implement proper output encoding based on context</li>
                    <li>Use a WAF (Web Application Firewall) for additional protection</li>
                    <li>Implement admin access controls and authentication</li>
                    <li>Regular security audits and penetration testing</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
