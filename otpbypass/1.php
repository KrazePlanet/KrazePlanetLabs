<?php
// Lab 1: Basic OTP Bypass
// Vulnerability: Basic OTP bypass techniques

session_start();

$message = '';
$otp_sent = false;
$otp_verified = false;

// Simulate OTP generation and verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'send_otp') {
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if (!empty($phone) || !empty($email)) {
            // Generate a simple 6-digit OTP
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = time();
            $_SESSION['phone'] = $phone;
            $_SESSION['email'] = $email;
            $otp_sent = true;
            $message = '<div class="alert alert-success">✅ OTP sent successfully! Check your phone/email.</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Please provide phone number or email.</div>';
        }
    } elseif ($action === 'verify_otp') {
        $entered_otp = $_POST['otp'] ?? '';
        $stored_otp = $_SESSION['otp'] ?? '';
        
        if (!empty($entered_otp) && !empty($stored_otp)) {
            // Check if OTP is expired (5 minutes)
            if (time() - $_SESSION['otp_time'] > 300) {
                $message = '<div class="alert alert-danger">❌ OTP has expired. Please request a new one.</div>';
            } elseif ($entered_otp === $stored_otp) {
                $otp_verified = true;
                $message = '<div class="alert alert-success">✅ OTP verified successfully! Access granted.</div>';
            } else {
                $message = '<div class="alert alert-danger">❌ Invalid OTP. Please try again.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">❌ Please enter the OTP.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic OTP Bypass - OTP Bypass Labs</title>
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

        .result-display {
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

        .otp-warning {
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

        .otp-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .otp-code {
            font-family: 'Courier New', monospace;
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-green);
            text-align: center;
            letter-spacing: 0.5rem;
        }

        .bypass-techniques {
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to OTP Bypass Labs
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
            <h1 class="hero-title">Lab 1: Basic OTP Bypass</h1>
            <p class="hero-subtitle">Basic OTP bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates basic OTP bypass vulnerabilities where attackers can circumvent OTP authentication through simple techniques like parameter manipulation, session hijacking, and basic social engineering.</p>
            <p><strong>Objective:</strong> Understand how basic OTP bypass attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable OTP System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Two-Factor Authentication</h5>
                            <p>This system uses OTP for two-factor authentication. Try to bypass the OTP verification:</p>
                            
                            <?php if (!$otp_sent): ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="send_otp">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+1234567890">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com">
                                </div>
                                <button type="submit" class="btn btn-primary">Send OTP</button>
                            </form>
                            <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="verify_otp">
                                <div class="mb-3">
                                    <label for="otp" class="form-label">Enter OTP</label>
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="123456" maxlength="6">
                                </div>
                                <button type="submit" class="btn btn-primary">Verify OTP</button>
                            </form>
                            <?php endif; ?>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>OTP Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="otp-warning">
                            <h5>⚠️ OTP Bypass Warning</h5>
                            <p>This lab demonstrates basic OTP bypass vulnerabilities:</p>
                            <ul>
                                <li><code>Parameter Manipulation</code> - URL parameter tampering</li>
                                <li><code>Session Hijacking</code> - Session token theft</li>
                                <li><code>Social Engineering</code> - Human manipulation</li>
                                <li><code>No Rate Limiting</code> - Unlimited attempts</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Bypass Techniques</h5>
                            <p>These techniques can be used to bypass OTP:</p>
                            <ul>
                                <li><code>Parameter Manipulation</code> - URL parameter tampering</li>
                                <li><code>Session Hijacking</code> - Session token theft</li>
                                <li><code>Social Engineering</code> - Human manipulation</li>
                                <li><code>No Rate Limiting</code> - Unlimited attempts</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testOTPBypass()" class="btn btn-primary">Test OTP Bypass</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($otp_sent && !$otp_verified): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>OTP Display (For Testing)
                    </div>
                    <div class="card-body">
                        <div class="otp-display">
                            <h5>Generated OTP (For Testing Only):</h5>
                            <div class="otp-code"><?php echo $_SESSION['otp'] ?? 'N/A'; ?></div>
                            <small class="text-muted">This OTP is displayed for testing purposes only. In real-world scenarios, this would be sent via SMS/email.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Bypass Techniques
                    </div>
                    <div class="card-body">
                        <div class="bypass-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Parameter Manipulation</div>
                                <div class="technique-demo">// URL parameter tampering
?otp_verified=true
?bypass_otp=1
?skip_verification=yes</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Session Hijacking</div>
                                <div class="technique-demo">// Steal session token
document.cookie = "session_id=stolen_token";
// Or use session fixation
?PHPSESSID=fixed_session_id</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Social Engineering</div>
                                <div class="technique-demo">// Call user pretending to be support
"Hi, this is IT support. We need to verify your account. Can you share the OTP you just received?"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">No Rate Limiting</div>
                                <div class="technique-demo">// Unlimited OTP requests
for (let i = 0; i < 1000; i++) {
    fetch('/send_otp', {method: 'POST'});
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Client-Side Bypass</div>
                                <div class="technique-demo">// Disable JavaScript validation
document.getElementById('otp').disabled = false;
// Or modify form action
form.action = '/bypass_otp';</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Header Manipulation</div>
                                <div class="technique-demo">// Add bypass headers
X-Bypass-OTP: true
X-Admin-Override: 1
X-Skip-Verification: yes</div>
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
                        <li><strong>Type:</strong> Basic OTP Bypass</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Parameter manipulation</li>
                        <li><strong>Issue:</strong> Missing validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Parameter Manipulation:</strong> URL parameter tampering</li>
                        <li><strong>Session Hijacking:</strong> Session token theft</li>
                        <li><strong>Social Engineering:</strong> Human manipulation</li>
                        <li><strong>No Rate Limiting:</strong> Unlimited attempts</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Basic OTP Bypass Examples</h5>
            <p>Use these techniques to exploit basic OTP bypass vulnerabilities:</p>
            
            <h6>1. URL Parameter Bypass:</h6>
            <div class="code-block">// Add bypass parameters to URL
?otp_verified=true
?bypass_otp=1
?skip_verification=yes
?admin_override=1
?otp_status=verified</div>

            <h6>2. POST Parameter Bypass:</h6>
            <div class="code-block">// Send bypass parameters in POST data
otp_verified=true
bypass_otp=1
skip_verification=yes
admin_override=1
otp_status=verified</div>

            <h6>3. Header Bypass:</h6>
            <div class="code-block">// Add bypass headers
X-Bypass-OTP: true
X-Admin-Override: 1
X-Skip-Verification: yes
X-OTP-Status: verified
X-Override-Auth: true</div>

            <h6>4. Cookie Bypass:</h6>
            <div class="code-block">// Set bypass cookies
otp_verified=true
bypass_otp=1
skip_verification=yes
admin_override=1
otp_status=verified</div>

            <h6>5. Session Bypass:</h6>
            <div class="code-block">// Manipulate session variables
$_SESSION['otp_verified'] = true;
$_SESSION['bypass_otp'] = 1;
$_SESSION['skip_verification'] = true;
$_SESSION['admin_override'] = 1;</div>

            <h6>6. JavaScript Bypass:</h6>
            <div class="code-block">// Disable client-side validation
document.getElementById('otp').disabled = false;
document.getElementById('otp').value = 'bypass';
// Or modify form action
form.action = '/bypass_otp';
// Or submit form without OTP
form.submit();</div>

            <h6>7. Form Manipulation:</h6>
            <div class="code-block">// Add hidden bypass fields
<input type="hidden" name="otp_verified" value="true">
<input type="hidden" name="bypass_otp" value="1">
<input type="hidden" name="skip_verification" value="yes">
<input type="hidden" name="admin_override" value="1"></div>

            <h6>8. AJAX Bypass:</h6>
            <div class="code-block">// Send bypass request via AJAX
fetch('/verify_otp', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        otp: 'bypass',
        otp_verified: true,
        bypass_otp: 1
    })
});</div>

            <h6>9. cURL Bypass:</h6>
            <div class="code-block">// Send bypass request via cURL
curl -X POST http://vulnerable-site.com/verify_otp \
  -d "otp=bypass&otp_verified=true&bypass_otp=1" \
  -H "X-Bypass-OTP: true"</div>

            <h6>10. Browser DevTools Bypass:</h6>
            <div class="code-block">// Use browser dev tools to modify
// 1. Open DevTools (F12)
// 2. Go to Network tab
// 3. Intercept the OTP verification request
// 4. Modify the request parameters
// 5. Send the modified request</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Account takeover and unauthorized access</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Authentication bypass and privilege escalation</li>
                <li>Compliance violations and security breaches</li>
                <li>Cross-site attacks and data exfiltration</li>
                <li>Social engineering and phishing attacks</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper server-side validation</li>
                    <li>Use secure session management</li>
                    <li>Implement rate limiting and throttling</li>
                    <li>Use secure OTP generation and delivery</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authentication patterns</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about OTP security</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testOTPBypass() {
            alert('OTP Bypass test initiated. Try the bypass techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
