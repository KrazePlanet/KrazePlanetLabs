<?php
// Lab 4: Template Injection RCE
// Vulnerability: Template engine code execution without validation

$message = '';
$template_output = '';
$template = '';

// Handle template rendering request
if (isset($_POST['template']) && !empty($_POST['template'])) {
    $template = $_POST['template'];
    
    // Vulnerable: Direct template evaluation without validation
    try {
        // Simulate template engine with eval()
        $template_code = '<?php ' . $template . ' ?>';
        
        // Capture output
        ob_start();
        eval($template_code);
        $template_output = ob_get_clean();
        
        if ($template_output !== false) {
            $message = '<div class="alert alert-success">Template rendered successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to render template!</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error rendering template: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Alternative vulnerable template engine simulation
if (isset($_POST['alt_template']) && !empty($_POST['alt_template'])) {
    $template = $_POST['alt_template'];
    
    // Vulnerable: Template with variable substitution
    $variables = [
        'name' => 'User',
        'message' => 'Welcome to our site!',
        'date' => date('Y-m-d H:i:s')
    ];
    
    // Replace template variables
    $rendered_template = $template;
    foreach ($variables as $key => $value) {
        $rendered_template = str_replace('{{' . $key . '}}', $value, $rendered_template);
    }
    
    // Vulnerable: Direct evaluation of template content
    try {
        ob_start();
        eval('?>' . $rendered_template . '<?php');
        $template_output = ob_get_clean();
        $message = '<div class="alert alert-success">Template rendered successfully!</div>';
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error rendering template: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Template Injection RCE - RCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-dark: #1a1f36;
            --primary-light: #2d3748;
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

        .form-control, .form-textarea {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-textarea:focus {
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

        .result-content {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
            max-height: 400px;
            overflow-y: auto;
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .form-textarea {
            resize: vertical;
            min-height: 150px;
        }

        .nav-tabs .nav-link {
            color: #cbd5e0;
            border-color: #334155;
        }

        .nav-tabs .nav-link.active {
            background-color: rgba(30, 41, 59, 0.7);
            border-color: var(--accent-green);
            color: var(--accent-green);
        }

        .tab-content {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to RCE Labs
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
            <h1 class="hero-title">Lab 4: Template Injection RCE</h1>
            <p class="hero-subtitle">RCE through template engine code execution</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a Remote Code Execution vulnerability through template injection. The application uses a template engine that directly evaluates user-supplied template code without proper validation.</p>
            <p><strong>Objective:</strong> Inject malicious template code to execute arbitrary commands on the server.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle template rendering request
if (isset($_POST['template']) && !empty($_POST['template'])) {
    $template = $_POST['template'];
    
    // Vulnerable: Direct template evaluation without validation
    try {
        // Simulate template engine with eval()
        $template_code = '&lt;?php ' . $template . ' ?&gt;';
        
        // Capture output
        ob_start();
        eval($template_code);
        $template_output = ob_get_clean();
        
        // Display output
    } catch (Exception $e) {
        // Error handling
    }
}

// Example malicious template:
// system('whoami');
// echo shell_exec('ls -la');
// file_get_contents('/etc/passwd');</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-code me-2"></i>Template Engine Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <ul class="nav nav-tabs" id="templateTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="direct-tab" data-bs-toggle="tab" data-bs-target="#direct" type="button" role="tab">Direct Template</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variable-tab" data-bs-toggle="tab" data-bs-target="#variable" type="button" role="tab">Variable Template</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="templateTabsContent">
                            <div class="tab-pane fade show active" id="direct" role="tabpanel">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="template" class="form-label">Template Code</label>
                                        <textarea class="form-control form-textarea" id="template" name="template" 
                                                  placeholder="Enter template code..."><?php echo htmlspecialchars($template); ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Render Template</button>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade" id="variable" role="tabpanel">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="alt_template" class="form-label">Template with Variables</label>
                                        <textarea class="form-control form-textarea" id="alt_template" name="alt_template" 
                                                  placeholder="Enter template with variables...">Hello {{name}}, {{message}} Current date: {{date}}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Render Template</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Example Payloads:</h6>
                            <ul>
                                <li><a href="#" onclick="setTemplate('system(\"whoami\");')" style="color: var(--accent-green);">whoami command</a></li>
                                <li><a href="#" onclick="setTemplate('echo shell_exec(\"ls -la\");')" style="color: var(--accent-green);">ls -la command</a></li>
                                <li><a href="#" onclick="setTemplate('echo file_get_contents(\"/etc/passwd\");')" style="color: var(--accent-green);">cat /etc/passwd</a></li>
                                <li><a href="#" onclick="setTemplate('echo \"User: \" . shell_exec(\"id\") . \"System: \" . shell_exec(\"uname -a\");')" style="color: var(--accent-green);">id && uname -a</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($template_output): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-code me-2"></i>Template Output
                    </div>
                    <div class="card-body">
                        <div class="result-content">
                            <pre><?php echo htmlspecialchars($template_output); ?></pre>
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
                        <li><strong>Type:</strong> Remote Code Execution (RCE)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>template</code></li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Template engine code execution without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Malicious Template Payloads</h5>
                    <p>Try these template payloads:</p>
                    <ul>
                        <li><code>system('whoami');</code> - Execute whoami</li>
                        <li><code>echo shell_exec('ls -la');</code> - List files</li>
                        <li><code>echo file_get_contents('/etc/passwd');</code> - Read passwd</li>
                        <li><code>echo "User: " . shell_exec('id') . "System: " . shell_exec('uname -a');</code> - Multiple commands</li>
                    </ul>
                    <p><strong>Template Types:</strong></p>
                    <ul>
                        <li><strong>Direct:</strong> Raw PHP code execution</li>
                        <li><strong>Variable:</strong> Template with variable substitution</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Test Instructions</h5>
            <p>Follow these steps to test the vulnerability:</p>
            <ol>
                <li>Click on one of the example payloads above</li>
                <li>The payload will be automatically filled in the textarea</li>
                <li>Click "Render Template" to execute the payload</li>
                <li>Observe the command execution results</li>
                <li>Try different commands by modifying the template</li>
            </ol>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete server compromise through template injection</li>
                <li>Database access and data exfiltration</li>
                <li>File system access and manipulation</li>
                <li>Network access and lateral movement</li>
                <li>Bypassing all security controls</li>
                <li>Privilege escalation and persistence</li>
                <li>Cryptocurrency mining and botnet participation</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use safe template engines with proper sandboxing</li>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use whitelist-based template validation</li>
                    <li>Avoid direct code evaluation in templates</li>
                    <li>Implement proper error handling</li>
                    <li>Use least privilege principles</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Regular security testing and updates</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function setTemplate(template) {
            document.getElementById('template').value = template;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
