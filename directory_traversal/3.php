<?php
// Lab 3: Log File Viewer Directory Traversal
// Vulnerability: Log file path construction without validation

$message = '';
$log_content = '';
$log_path = '';

// Handle log file request
if (isset($_GET['log'])) {
    $log = $_GET['log'];
    
    // Vulnerable: No validation of log file path
    $log_path = 'logs/' . $log;
    
    if (file_exists($log_path) && is_file($log_path)) {
        $log_content = file_get_contents($log_path);
        $message = '<div class="alert alert-success">Log file loaded successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Log file not found: ' . htmlspecialchars($log_path) . '</div>';
    }
}

// Get list of available log files
$logs_dir = 'logs/';
$available_logs = [];
if (is_dir($logs_dir)) {
    $files = scandir($logs_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_file($logs_dir . $file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['log', 'txt', 'err', 'out'])) {
                $available_logs[] = $file;
            }
        }
    }
}

// Create some sample log files if they don't exist
if (!is_dir($logs_dir)) {
    mkdir($logs_dir, 0755, true);
}

$sample_logs = [
    'access.log' => "192.168.1.100 - - [25/Dec/2024:10:30:45 +0000] \"GET /index.php HTTP/1.1\" 200 1234\n192.168.1.101 - - [25/Dec/2024:10:31:12 +0000] \"POST /login.php HTTP/1.1\" 302 0\n192.168.1.102 - - [25/Dec/2024:10:32:33 +0000] \"GET /admin.php HTTP/1.1\" 403 567",
    'error.log' => "[25/Dec/2024:10:30:45] PHP Warning: Undefined variable \$user in /var/www/html/login.php on line 15\n[25/Dec/2024:10:31:12] PHP Fatal error: Call to undefined function mysql_connect() in /var/www/html/db.php on line 8\n[25/Dec/2024:10:32:33] PHP Notice: Use of undefined constant DEBUG - assumed 'DEBUG' in /var/www/html/config.php on line 3",
    'application.log' => "2024-12-25 10:30:45 [INFO] User admin logged in successfully\n2024-12-25 10:31:12 [WARNING] Failed login attempt for user 'hacker'\n2024-12-25 10:32:33 [ERROR] Database connection failed: Access denied for user 'root'@'localhost'",
    'security.log' => "2024-12-25 10:30:45 [SECURITY] SQL injection attempt detected from IP 192.168.1.100\n2024-12-25 10:31:12 [SECURITY] XSS attack blocked from IP 192.168.1.101\n2024-12-25 10:32:33 [SECURITY] Directory traversal attempt from IP 192.168.1.102"
];

foreach ($sample_logs as $filename => $content) {
    if (!file_exists($logs_dir . $filename)) {
        file_put_contents($logs_dir . $filename, $content);
    }
}

// Refresh available logs
$available_logs = [];
if (is_dir($logs_dir)) {
    $files = scandir($logs_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_file($logs_dir . $file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['log', 'txt', 'err', 'out'])) {
                $available_logs[] = $file;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Log File Viewer - Directory Traversal</title>
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

        .form-control {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
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

        .log-content {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
            max-height: 400px;
            overflow-y: auto;
        }

        .log-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .log-item {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .log-item:hover {
            background: rgba(15, 23, 42, 0.8);
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Directory Traversal Labs
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
            <h1 class="hero-title">Lab 3: Log File Viewer</h1>
            <p class="hero-subtitle">Directory Traversal in log file viewing functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a directory traversal vulnerability in a log file viewer system. The application constructs log file paths by concatenating user input without proper validation, allowing access to sensitive system files.</p>
            <p><strong>Objective:</strong> Access system files outside the logs directory using directory traversal sequences to view sensitive configuration files.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle log file request
if (isset($_GET['log'])) {
    $log = $_GET['log'];
    
    // Vulnerable: No validation of log file path
    $log_path = 'logs/' . $log;
    
    if (file_exists($log_path) && is_file($log_path)) {
        $log_content = file_get_contents($log_path);
        // Display log content
    } else {
        // Error: Log file not found
    }
}

// Example vulnerable usage:
// ?log=access.log
// ?log=../../../etc/passwd
// ?log=..\..\..\windows\system32\drivers\etc\hosts</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-text me-2"></i>Log File Viewer Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="log" class="form-label">Log File Name</label>
                                <input type="text" class="form-control" id="log" name="log" 
                                       placeholder="Enter log file name..." value="<?php echo htmlspecialchars($_GET['log'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Load Log File</button>
                        </form>
                        
                        <div class="log-list">
                            <h6><i class="bi bi-folder me-2"></i>Available Log Files:</h6>
                            <?php if (empty($available_logs)): ?>
                                <p class="text-muted">No log files available in the logs directory.</p>
                            <?php else: ?>
                                <?php foreach ($available_logs as $log): ?>
                                    <div class="log-item" onclick="loadLog('<?php echo htmlspecialchars($log); ?>')">
                                        <i class="bi bi-file-text me-2"></i><?php echo htmlspecialchars($log); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($log_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-text me-2"></i>Log File Content: <?php echo htmlspecialchars($log_path); ?>
                    </div>
                    <div class="card-body">
                        <div class="log-content">
                            <pre><?php echo htmlspecialchars($log_content); ?></pre>
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
                        <li><strong>Type:</strong> Directory Traversal in Log Viewer</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameter:</strong> <code>log</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Log file path construction without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these payloads in the log parameter:</p>
                    <ul>
                        <li><code>../../../etc/passwd</code> - Linux system file</li>
                        <li><code>..\..\..\windows\system32\drivers\etc\hosts</code> - Windows system file</li>
                        <li><code>../../../etc/hosts</code> - Linux hosts file</li>
                        <li><code>../../../proc/version</code> - Linux system info</li>
                        <li><code>../../../etc/shadow</code> - Linux password file</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>3.php?log=../../../etc/passwd</code></li>
                        <li><code>3.php?log=..\..\..\windows\system32\drivers\etc\hosts</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?log=../../../etc/passwd" style="color: var(--accent-green);">Test Linux /etc/passwd</a></li>
                <li><a href="?log=../../../etc/hosts" style="color: var(--accent-green);">Test Linux /etc/hosts</a></li>
                <li><a href="?log=..\..\..\windows\system32\drivers\etc\hosts" style="color: var(--accent-green);">Test Windows hosts file</a></li>
                <li><a href="?log=../../../proc/version" style="color: var(--accent-green);">Test Linux system version</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Access to system configuration files through log parameters</li>
                <li>Exposure of application source code and configuration</li>
                <li>Access to database files and credentials</li>
                <li>Bypassing access controls in log management systems</li>
                <li>Information disclosure through file access</li>
                <li>Privilege escalation through configuration access</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Validate and sanitize all log file path inputs</li>
                    <li>Use whitelist-based file access controls</li>
                    <li>Implement proper path normalization</li>
                    <li>Use <code>basename()</code> to extract filename only</li>
                    <li>Implement file type validation for log files</li>
                    <li>Use absolute paths with proper validation</li>
                    <li>Implement proper error handling</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function loadLog(logName) {
            document.getElementById('log').value = logName;
            document.querySelector('form').submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
