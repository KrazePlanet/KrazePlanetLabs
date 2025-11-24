<?php
// Lab 5: Clickjacking with Social Engineering
// Vulnerability: Clickjacking combined with social engineering techniques

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
    <title>Lab 5: Clickjacking with Social Engineering - Clickjacking Labs</title>
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

        .social-engineering-warning {
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

        .social-engineering-demo {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .social-engineering-container {
            position: relative;
            width: 100%;
            height: 400px;
            border: 1px solid #334155;
            border-radius: 8px;
            overflow: hidden;
        }

        .social-engineering-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .hidden-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.01;
            z-index: 5;
        }

        .social-engineering-techniques {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .technique-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
        }

        .technique-title {
            color: var(--accent-green);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .technique-demo {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .social-engineering-scenarios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .scenario-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
            border-left: 4px solid var(--accent-orange);
        }

        .scenario-title {
            color: var(--accent-orange);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .scenario-demo {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
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
            <h1 class="hero-title">Lab 5: Clickjacking with Social Engineering</h1>
            <p class="hero-subtitle">Clickjacking combined with social engineering techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates clickjacking vulnerabilities combined with social engineering techniques. Attackers can use psychological manipulation and deceptive content to trick users into performing unintended actions through clickjacking attacks.</p>
            <p><strong>Objective:</strong> Understand how social engineering can be combined with clickjacking to create more effective attacks.</p>
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
                                    <label for="amount" class="form-label">Donation Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="1000">
                                </div>
                                <div class="mb-3">
                                    <label for="to_account" class="form-label">Charity</label>
                                    <input type="text" class="form-control" id="to_account" name="to_account" value="attacker-charity">
                                </div>
                                <button type="submit" class="action-button danger-button" onclick="setAction('transfer_money')">
                                    <i class="bi bi-arrow-right me-2"></i>Make Donation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-check me-2"></i>Social Engineering Clickjacking Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="social-engineering-warning">
                            <h5>⚠️ Social Engineering Clickjacking Warning</h5>
                            <p>This lab demonstrates social engineering clickjacking vulnerabilities:</p>
                            <ul>
                                <li><code>Psychological Manipulation</code> - Emotional triggers</li>
                                <li><code>Deceptive Content</code> - Fake notifications</li>
                                <li><code>Urgency Tactics</code> - Time pressure</li>
                                <li><code>Authority Exploitation</code> - Fake authority</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Social Engineering Attack Vectors</h5>
                            <p>These actions can be exploited via social engineering clickjacking:</p>
                            <ul>
                                <li><code>Like Post</code> - Social media manipulation</li>
                                <li><code>Follow User</code> - Unwanted following</li>
                                <li><code>Share Content</code> - Unwanted sharing</li>
                                <li><code>Delete Account</code> - Account deletion</li>
                                <li><code>Change Password</code> - Password changes</li>
                                <li><code>Make Donation</code> - Financial fraud</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testSocialEngineeringClickjacking()" class="btn btn-primary">Test Social Engineering Clickjacking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Social Engineering Clickjacking Demo
                    </div>
                    <div class="card-body">
                        <div class="social-engineering-demo">
                            <h5>Social Engineering Clickjacking Demonstration:</h5>
                            <p>This demonstrates how social engineering can be combined with clickjacking to create more effective attacks:</p>
                            
                            <div class="social-engineering-container">
                                <div class="social-engineering-overlay">
                                    🎁 You've won $1000! Click here to claim!
                                </div>
                                <div class="hidden-content">
                                    <button onclick="performAction('like_post')" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #007bff; color: white; padding: 20px 40px; border: none; border-radius: 8px; font-size: 18px; cursor: pointer;">Hidden Button</button>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">The overlay above uses social engineering techniques to trick users into clicking on hidden content.</small>
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
                        <i class="bi bi-code-square me-2"></i>Social Engineering Techniques
                    </div>
                    <div class="card-body">
                        <div class="social-engineering-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Urgency Tactics</div>
                                <div class="technique-demo">"Limited time offer!"
"Act now or lose this opportunity!"
"Only 5 minutes left!"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Authority Exploitation</div>
                                <div class="technique-demo">"Security Alert from Admin"
"Official System Notification"
"IT Department Warning"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Emotional Triggers</div>
                                <div class="technique-demo">"Help save a child's life"
"Your account is at risk"
"Don't miss out on this deal"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Fake Notifications</div>
                                <div class="technique-demo">"You have 3 new messages"
"System update required"
"Security breach detected"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Social Proof</div>
                                <div class="technique-demo">"1,000+ people liked this"
"Join 50,000+ users"
"Most popular choice"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Fear Tactics</div>
                                <div class="technique-demo">"Your account will be deleted"
"Security breach detected"
"Immediate action required"</div>
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
                        <i class="bi bi-code-square me-2"></i>Social Engineering Scenarios
                    </div>
                    <div class="card-body">
                        <div class="social-engineering-scenarios">
                            <div class="scenario-card">
                                <div class="scenario-title">Phishing Attack</div>
                                <div class="scenario-demo">"Your account has been compromised. Click here to secure it immediately."</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Fake Prize</div>
                                <div class="scenario-demo">"Congratulations! You've won $1000. Click here to claim your prize."</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Fake Update</div>
                                <div class="scenario-demo">"System update required. Click here to install the latest version."</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Fake Charity</div>
                                <div class="scenario-demo">"Help save children's lives. Click here to make a donation."</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Fake Survey</div>
                                <div class="scenario-demo">"Take our quick survey and win a prize. Click here to participate."</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Fake News</div>
                                <div class="scenario-demo">"Breaking news: Click here to read the full story."</div>
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
                        <li><strong>Type:</strong> Social Engineering Clickjacking</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Psychological manipulation</li>
                        <li><strong>Issue:</strong> Social engineering + clickjacking</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Psychological Manipulation:</strong> Emotional triggers</li>
                        <li><strong>Deceptive Content:</strong> Fake notifications</li>
                        <li><strong>Urgency Tactics:</strong> Time pressure</li>
                        <li><strong>Authority Exploitation:</strong> Fake authority</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Social Engineering Clickjacking Examples</h5>
            <p>Use these techniques to exploit social engineering clickjacking vulnerabilities:</p>
            
            <h6>1. Urgency-based Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Urgency-based Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
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
            z-index: 10;
        }
        .fake-button {
            background: #dc3545;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .hidden-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 5;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;⚠️ URGENT SECURITY ALERT&lt;/h1&gt;
            &lt;p&gt;Your account will be deleted in 5 minutes!&lt;/p&gt;
            &lt;p&gt;Click below to secure it immediately!&lt;/p&gt;
            &lt;button class="fake-button"&gt;SECURE NOW&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('delete_account')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. Authority-based Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Authority-based Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #f8f9fa;
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
            z-index: 10;
        }
        .fake-button {
            background: #007bff;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
        }
        .hidden-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 5;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🔒 Official Security Notification&lt;/h1&gt;
            &lt;p&gt;From: IT Security Department&lt;/p&gt;
            &lt;p&gt;Your account requires immediate verification.&lt;/p&gt;
            &lt;button class="fake-button"&gt;VERIFY ACCOUNT&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('change_password')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. Emotional Trigger Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Emotional Trigger Clickjacking&lt;/title&gt;
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
            z-index: 10;
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
        .hidden-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 5;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;💝 Help Save a Child's Life&lt;/h1&gt;
            &lt;p&gt;Your small donation can make a big difference.&lt;/p&gt;
            &lt;p&gt;Click below to help now!&lt;/p&gt;
            &lt;button class="fake-button"&gt;HELP NOW&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('transfer_money')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>4. Fake Prize Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;head&gt;
    &lt;title&gt;Fake Prize Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
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
            z-index: 10;
        }
        .fake-button {
            background: #ffc107;
            color: #212529;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .hidden-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 5;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🎉 CONGRATULATIONS!&lt;/h1&gt;
            &lt;p&gt;You've won $1000!&lt;/p&gt;
            &lt;p&gt;Click below to claim your prize!&lt;/p&gt;
            &lt;button class="fake-button"&gt;CLAIM PRIZE&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('like_post')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>5. Social Proof Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Social Proof Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #f0f0f0;
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
            z-index: 10;
        }
        .fake-button {
            background: #17a2b8;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
        }
        .hidden-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 5;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;👥 Join 50,000+ Users&lt;/h1&gt;
            &lt;p&gt;This is the most popular choice!&lt;/p&gt;
            &lt;p&gt;1,000+ people liked this today!&lt;/p&gt;
            &lt;button class="fake-button"&gt;JOIN NOW&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('follow_user')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>6. Fear-based Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Fear-based Clickjacking&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(45deg, #dc3545, #fd7e14);
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
            z-index: 10;
        }
        .fake-button {
            background: #dc3545;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
            animation: shake 0.5s infinite;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .hidden-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 250px;
            opacity: 0.01;
            z-index: 5;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🚨 SECURITY BREACH DETECTED&lt;/h1&gt;
            &lt;p&gt;Your account is at risk!&lt;/p&gt;
            &lt;p&gt;Immediate action required!&lt;/p&gt;
            &lt;button class="fake-button"&gt;SECURE NOW&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('admin_action')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
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
                <li>Charity fraud and donation manipulation</li>
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
                    <li>Implement proper input validation</li>
                    <li>Use secure coding practices</li>
                    <li>Educate users about social engineering</li>
                    <li>Implement user awareness training</li>
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
        
        function testSocialEngineeringClickjacking() {
            document.getElementById('action-results').innerHTML = 
                '<div class="alert alert-info">Social Engineering Clickjacking test initiated. Check the vulnerable actions above.</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
