<?php
// Lab 3: User Profile Stored XSS
// Vulnerability: Storing user profile data without sanitization

session_start();

// Initialize user profile if not exists
if (!isset($_SESSION['user_profile'])) {
    $_SESSION['user_profile'] = array(
        'username' => 'Guest',
        'email' => 'guest@example.com',
        'bio' => 'No bio available',
        'website' => '',
        'location' => '',
        'last_updated' => date('Y-m-d H:i:s')
    );
}

// Handle profile update
if ($_POST && isset($_POST['update_profile'])) {
    $username = $_POST['username'] ?? $_SESSION['user_profile']['username'];
    $email = $_POST['email'] ?? $_SESSION['user_profile']['email'];
    $bio = $_POST['bio'] ?? $_SESSION['user_profile']['bio'];
    $website = $_POST['website'] ?? $_SESSION['user_profile']['website'];
    $location = $_POST['location'] ?? $_SESSION['user_profile']['location'];
    
    // Vulnerable: No sanitization of user input
    $_SESSION['user_profile'] = array(
        'username' => $username,
        'email' => $email,
        'bio' => $bio,
        'website' => $website,
        'location' => $location,
        'last_updated' => date('Y-m-d H:i:s')
    );
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Reset profile if requested
if (isset($_GET['reset'])) {
    $_SESSION['user_profile'] = array(
        'username' => 'Guest',
        'email' => 'guest@example.com',
        'bio' => 'No bio available',
        'website' => '',
        'location' => '',
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
    <title>Stored XSS Lab 3 - User Profile XSS</title>
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

        .profile-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-green);
        }

        .profile-field {
            margin-bottom: 0.5rem;
        }

        .profile-label {
            font-weight: 600;
            color: var(--accent-green);
            display: inline-block;
            width: 100px;
        }

        .profile-value {
            color: #e2e8f0;
        }

        .profile-timestamp {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 1rem;
            border-top: 1px solid #334155;
            padding-top: 0.5rem;
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
            <h1 class="hero-title">Stored XSS Lab 3</h1>
            <p class="hero-subtitle">User Profile XSS</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a stored XSS vulnerability in a user profile system where multiple profile fields are stored and displayed without proper sanitization.</p>
            <p><strong>Objective:</strong> Inject persistent XSS payloads in various profile fields to execute malicious scripts.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle profile update
if ($_POST && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $website = $_POST['website'];
    $location = $_POST['location'];
    
    // Vulnerable: No sanitization of user input
    $_SESSION['user_profile'] = array(
        'username' => $username,
        'email' => $email,
        'bio' => $bio,
        'website' => $website,
        'location' => $location
    );
}

// Display profile (also vulnerable)
echo "&lt;strong&gt;Username:&lt;/strong&gt; " . $profile['username'];
echo "&lt;strong&gt;Bio:&lt;/strong&gt; " . $profile['bio'];
// ... other fields</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-gear me-2"></i>Update Profile
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_profile']['username']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_profile']['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" 
                                          placeholder="Tell us about yourself..."><?php echo htmlspecialchars($_SESSION['user_profile']['bio']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" name="website" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_profile']['website']); ?>" 
                                       placeholder="https://yourwebsite.com">
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_profile']['location']); ?>" 
                                       placeholder="City, Country">
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-circle me-2"></i>User Profile</span>
                        <a href="?reset=1" class="btn btn-danger btn-sm">Reset Profile</a>
                    </div>
                    <div class="card-body">
                        <div class="profile-item">
                            <div class="profile-field">
                                <span class="profile-label">Username:</span>
                                <span class="profile-value"><?php echo $_SESSION['user_profile']['username']; // Vulnerable: Direct output without sanitization ?></span>
                            </div>
                            <div class="profile-field">
                                <span class="profile-label">Email:</span>
                                <span class="profile-value"><?php echo $_SESSION['user_profile']['email']; // Vulnerable: Direct output without sanitization ?></span>
                            </div>
                            <div class="profile-field">
                                <span class="profile-label">Bio:</span>
                                <div class="profile-value"><?php echo $_SESSION['user_profile']['bio']; // Vulnerable: Direct output without sanitization ?></div>
                            </div>
                            <div class="profile-field">
                                <span class="profile-label">Website:</span>
                                <span class="profile-value"><?php echo $_SESSION['user_profile']['website']; // Vulnerable: Direct output without sanitization ?></span>
                            </div>
                            <div class="profile-field">
                                <span class="profile-label">Location:</span>
                                <span class="profile-value"><?php echo $_SESSION['user_profile']['location']; // Vulnerable: Direct output without sanitization ?></span>
                            </div>
                            <div class="profile-timestamp">
                                Last updated: <?php echo htmlspecialchars($_SESSION['user_profile']['last_updated']); ?>
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
                        <li><strong>Type:</strong> Stored XSS in User Profile</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameters:</strong> <code>username</code>, <code>email</code>, <code>bio</code>, <code>website</code>, <code>location</code></li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Multiple profile fields vulnerable to XSS</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p><strong>Username field:</strong></p>
                    <ul>
                        <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>Bio field:</strong></p>
                    <ul>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                    </ul>
                    <p><strong>Website field:</strong></p>
                    <ul>
                        <li><code>javascript:alert('XSS')</code></li>
                        <li><code>data:text/html,&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Attack Scenarios</h5>
            <ul>
                <li>Profile hijacking and impersonation</li>
                <li>Persistent defacement of user profiles</li>
                <li>Stealing user credentials through fake forms</li>
                <li>Distributing malware to profile viewers</li>
                <li>Session hijacking and account takeover</li>
                <li>Social engineering through fake profile information</li>
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
                    <li>Validate and sanitize all user input before storage</li>
                    <li>Use whitelist-based input validation for each field type</li>
                    <li>Implement proper output encoding based on context</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
