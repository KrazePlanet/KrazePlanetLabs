<?php
// Lab 4: Admin Panel Stored XSS
// Vulnerability: Storing admin content without sanitization

session_start();

// Initialize admin content if not exists
if (!isset($_SESSION['admin_content'])) {
    $_SESSION['admin_content'] = array(
        'site_title' => 'Welcome to Our Site',
        'site_description' => 'This is a sample website description',
        'announcement' => 'No announcements at this time',
        'footer_text' => '© 2024 KrazePlanetLabs. All rights reserved.',
        'last_updated' => date('Y-m-d H:i:s')
    );
}

// Handle admin content update
if ($_POST && isset($_POST['update_content'])) {
    $site_title = $_POST['site_title'] ?? $_SESSION['admin_content']['site_title'];
    $site_description = $_POST['site_description'] ?? $_SESSION['admin_content']['site_description'];
    $announcement = $_POST['announcement'] ?? $_SESSION['admin_content']['announcement'];
    $footer_text = $_POST['footer_text'] ?? $_SESSION['admin_content']['footer_text'];
    
    // Vulnerable: No sanitization of admin input
    $_SESSION['admin_content'] = array(
        'site_title' => $site_title,
        'site_description' => $site_description,
        'announcement' => $announcement,
        'footer_text' => $footer_text,
        'last_updated' => date('Y-m-d H:i:s')
    );
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Reset content if requested
if (isset($_GET['reset'])) {
    $_SESSION['admin_content'] = array(
        'site_title' => 'Welcome to Our Site',
        'site_description' => 'This is a sample website description',
        'announcement' => 'No announcements at this time',
        'footer_text' => '© 2024 KrazePlanetLabs. All rights reserved.',
        'last_updated' => date('Y-m-d H:i:s')
    );
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored XSS Lab 4 - Admin Panel XSS</title>
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

        .btn-danger {
            background: linear-gradient(90deg, var(--accent-red), #e53e3e);
            border: none;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
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

        .content-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-green);
        }

        .content-field {
            margin-bottom: 0.5rem;
        }

        .content-label {
            font-weight: 600;
            color: var(--accent-green);
            display: inline-block;
            width: 120px;
        }

        .content-value {
            color: #e2e8f0;
        }

        .content-timestamp {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 1rem;
            border-top: 1px solid #334155;
            padding-top: 0.5rem;
        }

        .admin-warning {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid var(--accent-red);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: var(--accent-red);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Stored XSS Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../../contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Stored XSS Lab 4</h1>
            <p class="hero-subtitle">Admin Panel XSS</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a stored XSS vulnerability in an admin panel where site content is managed and displayed without proper sanitization.</p>
            <p><strong>Objective:</strong> Inject persistent XSS payloads in admin content fields that will affect all site visitors.</p>
        </div>

        <div class="admin-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Warning:</strong> This lab simulates an admin panel vulnerability. In real scenarios, this would affect all website visitors and could lead to complete site compromise.
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle admin content update
if ($_POST && isset($_POST['update_content'])) {
    $site_title = $_POST['site_title'];
    $site_description = $_POST['site_description'];
    $announcement = $_POST['announcement'];
    $footer_text = $_POST['footer_text'];
    
    // Vulnerable: No sanitization of admin input
    $_SESSION['admin_content'] = array(
        'site_title' => $site_title,
        'site_description' => $site_description,
        'announcement' => $announcement,
        'footer_text' => $footer_text
    );
}

// Display content (also vulnerable)
echo "&lt;title&gt;" . $content['site_title'] . "&lt;/title&gt;";
echo "&lt;h1&gt;" . $content['site_title'] . "&lt;/h1&gt;";
// ... other content</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2"></i>Admin Content Management
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="site_title" class="form-label">Site Title</label>
                                <input type="text" class="form-control" id="site_title" name="site_title" 
                                       value="<?php echo htmlspecialchars($_SESSION['admin_content']['site_title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="site_description" class="form-label">Site Description</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="2" 
                                          placeholder="Brief description of the site..."><?php echo htmlspecialchars($_SESSION['admin_content']['site_description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="announcement" class="form-label">Announcement</label>
                                <textarea class="form-control" id="announcement" name="announcement" rows="3" 
                                          placeholder="Site-wide announcement..."><?php echo htmlspecialchars($_SESSION['admin_content']['announcement']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="footer_text" class="form-label">Footer Text</label>
                                <input type="text" class="form-control" id="footer_text" name="footer_text" 
                                       value="<?php echo htmlspecialchars($_SESSION['admin_content']['footer_text']); ?>" 
                                       placeholder="Footer copyright text...">
                            </div>
                            <button type="submit" name="update_content" class="btn btn-primary">Update Content</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-eye me-2"></i>Live Site Preview</span>
                        <a href="?reset=1" class="btn btn-danger btn-sm">Reset Content</a>
                    </div>
                    <div class="card-body">
                        <div class="content-item">
                            <div class="content-field">
                                <span class="content-label">Site Title:</span>
                                <span class="content-value"><?php echo $_SESSION['admin_content']['site_title']; // Vulnerable: Direct output without sanitization ?></span>
                            </div>
                            <div class="content-field">
                                <span class="content-label">Description:</span>
                                <div class="content-value"><?php echo $_SESSION['admin_content']['site_description']; // Vulnerable: Direct output without sanitization ?></div>
                            </div>
                            <div class="content-field">
                                <span class="content-label">Announcement:</span>
                                <div class="content-value"><?php echo $_SESSION['admin_content']['announcement']; // Vulnerable: Direct output without sanitization ?></div>
                            </div>
                            <div class="content-field">
                                <span class="content-label">Footer:</span>
                                <span class="content-value"><?php echo $_SESSION['admin_content']['footer_text']; // Vulnerable: Direct output without sanitization ?></span>
                            </div>
                            <div class="content-timestamp">
                                Last updated: <?php echo htmlspecialchars($_SESSION['admin_content']['last_updated']); ?>
                            </div>
                        </div>
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
                        <li><strong>Parameters:</strong> <code>site_title</code>, <code>site_description</code>, <code>announcement</code>, <code>footer_text</code></li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Admin content fields vulnerable to XSS</li>
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
                    <p><strong>Announcement field:</strong></p>
                    <ul>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                    </ul>
                    <p><strong>Footer field:</strong></p>
                    <ul>
                        <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;script&gt;document.location='http://evil.com'&lt;/script&gt;</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Attack Scenarios</h5>
            <ul>
                <li>Complete website defacement and takeover</li>
                <li>Mass credential theft from all visitors</li>
                <li>Malware distribution to all site users</li>
                <li>Session hijacking and account compromise</li>
                <li>SEO poisoning and reputation damage</li>
                <li>Backdoor installation for persistent access</li>
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
                    <li>Validate and sanitize all admin input before storage</li>
                    <li>Use whitelist-based input validation for each field type</li>
                    <li>Implement proper output encoding based on context</li>
                    <li>Use a WAF (Web Application Firewall) for additional protection</li>
                    <li>Implement admin access controls and authentication</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
