<?php
// Lab 1: Basic SSTI Attack
// Vulnerability: Server-Side Template Injection without proper sanitization

session_start();

$message = '';
$template_output = '';
$user_input = '';

// Simulate template engine (vulnerable to SSTI)
function render_template($template, $data = []) {
    // This is a simplified template engine for demonstration
    // In real applications, this would be more complex
    
    // Basic template variables
    $template = str_replace('{{name}}', $data['name'] ?? '', $template);
    $template = str_replace('{{email}}', $data['email'] ?? '', $template);
    $template = str_replace('{{message}}', $data['message'] ?? '', $template);
    
    // Vulnerable: Direct evaluation of template expressions
    // This is where SSTI occurs
    if (preg_match_all('/\{\{([^}]+)\}\}/', $template, $matches)) {
        foreach ($matches[1] as $expression) {
            $expression = trim($expression);
            
            // Check for dangerous expressions
            if (strpos($expression, 'system') !== false || 
                strpos($expression, 'exec') !== false || 
                strpos($expression, 'shell_exec') !== false ||
                strpos($expression, 'passthru') !== false ||
                strpos($expression, 'eval') !== false) {
                $template_output = "⚠️ DANGEROUS EXPRESSION DETECTED: " . htmlspecialchars($expression);
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
        
        $template_output = render_template($user_input, $data);
        
        if (strpos($template_output, '⚠️ DANGEROUS EXPRESSION DETECTED') !== false) {
            $message = '<div class="alert alert-danger">Dangerous expression detected! This could lead to RCE.</div>';
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
    <title>Lab 1: Basic SSTI Attack - SSTI Labs</title>
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
            <h1 class="hero-title">Lab 1: Basic SSTI Attack</h1>
            <p class="hero-subtitle">Server-Side Template Injection without proper sanitization</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic SSTI vulnerability where user input is directly concatenated into a template without proper sanitization. The template engine evaluates expressions in double curly braces, allowing attackers to inject malicious code.</p>
            <p><strong>Objective:</strong> Inject malicious template expressions to achieve code execution and information disclosure.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct evaluation of template expressions
function render_template($template, $data = []) {
    // Basic template variables
    $template = str_replace('{{name}}', $data['name'] ?? '', $template);
    $template = str_replace('{{email}}', $data['email'] ?? '', $template);
    
    // Vulnerable: Direct evaluation of expressions
    if (preg_match_all('/\{\{([^}]+)\}\}/', $template, $matches)) {
        foreach ($matches[1] as $expression) {
            $expression = trim($expression);
            
            // Evaluate mathematical expressions
            if (preg_match('/^[\d\s\+\-\*\/\(\)\.]+$/', $expression)) {
                $result = eval("return $expression;");
                $template = str_replace('{{' . $expression . '}}', $result, $template);
            }
        }
    }
    
    return $template;
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-slash me-2"></i>Template Engine Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
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
                        <li><strong>Type:</strong> Server-Side Template Injection (SSTI)</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct evaluation of template expressions</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these payloads in the template field:</p>
                    <ul>
                        <li><code>{{7*7}}</code> - Basic math test</li>
                        <li><code>{{7*7*7}}</code> - Complex math</li>
                        <li><code>{{(7*7)+1}}</code> - Parentheses</li>
                        <li><code>{{7.5*2}}</code> - Decimal math</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>SSTI Test Payloads</h5>
            <p>Use these payloads to test the SSTI vulnerability:</p>
            
            <h6>1. Basic Math Tests:</h6>
            <div class="code-block">Hello {{name}}!

Your email is: {{email}}
Your message: {{message}}

Math test: {{7*7}}
Complex math: {{7*7*7}}
With parentheses: {{(7*7)+1}}
Decimal math: {{7.5*2}}</div>

            <h6>2. Information Disclosure:</h6>
            <div class="code-block">Hello {{name}}!

System info: {{phpinfo()}}
Current time: {{date('Y-m-d H:i:s')}}
Server name: {{$_SERVER['SERVER_NAME']}}
PHP version: {{phpversion()}}</div>

            <h6>3. File System Access:</h6>
            <div class="code-block">Hello {{name}}!

File contents: {{file_get_contents('/etc/passwd')}}
Directory listing: {{scandir('/')}}
Current directory: {{getcwd()}}
File exists: {{file_exists('/etc/passwd')}}</div>

            <h6>4. Command Execution (Dangerous):</h6>
            <div class="code-block">Hello {{name}}!

System command: {{system('whoami')}}
Execute command: {{exec('ls -la')}}
Shell command: {{shell_exec('id')}}
Pass through: {{passthru('uname -a')}}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Remote Code Execution (RCE) on the server</li>
                <li>File system access and arbitrary file reading</li>
                <li>Bypass authentication and authorization mechanisms</li>
                <li>Data exfiltration and sensitive information disclosure</li>
                <li>Server compromise and lateral movement</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use safe template engines that don't allow code execution</li>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use sandboxed template environments</li>
                    <li>Avoid direct evaluation of user input in templates</li>
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
