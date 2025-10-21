<?php
    if (isset($_GET['cmd'])) {
        $cmd = $_GET['cmd'];
        $output = shell_exec($cmd);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KrazePlanetLabs - Command Injection Lab</title>
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
            --accent-purple: #9f7aea;
            --accent-pink: #ed64a6;
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

        .btn-danger {
            background: linear-gradient(90deg, var(--accent-red), var(--accent-orange));
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
        }

        .search-box {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        .search-box:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.2rem rgba(72, 187, 120, 0.25);
            color: #e2e8f0;
        }

        .btn-outline-success {
            border-color: var(--accent-green);
            color: var(--accent-green);
        }

        .btn-outline-success:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
            color: #1a202c;
        }

        pre {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            color: #e2e8f0;
            border: 1px solid #334155;
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .output-section {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .output-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--accent-green);
        }

        .output-content {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            min-height: 60px;
            border: 1px solid #334155;
        }

        .lab-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .lab-badge {
            background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));
            color: white;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .filter-info {
            background: rgba(30, 41, 59, 0.7);
            border-left: 4px solid var(--accent-pink);
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            border-radius: 0 8px 8px 0;
        }

        .code-highlight {
            color: var(--accent-orange);
            font-weight: 600;
        }

        .progress {
            height: 8px;
            background-color: #2d3748;
            margin: 1rem 0;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--accent-pink), var(--accent-purple));
        }

        .function-filter-notice {
            background: rgba(237, 100, 166, 0.1);
            border: 1px solid var(--accent-pink);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .vulnerability-badge {
            background: linear-gradient(45deg, var(--accent-red), var(--accent-orange));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            margin-bottom: 1rem;
            animation: pulse-vulnerability 2s infinite alternate;
            text-shadow: 0 0 10px rgba(245, 101, 101, 0.5);
        }

        @keyframes pulse-vulnerability {
            0% { 
                box-shadow: 0 0 10px var(--accent-red); 
                transform: scale(1);
            }
            100% { 
                box-shadow: 0 0 20px var(--accent-red), 0 0 30px var(--accent-orange); 
                transform: scale(1.02);
            }
        }

        .tool-tip {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid var(--accent-blue);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .command-examples {
            background: rgba(66, 153, 225, 0.1);
            border: 1px solid var(--accent-blue);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .command-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 6px;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-green);
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .danger-zone {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid var(--accent-red);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .warning-badge {
            background: linear-gradient(45deg, var(--accent-orange), var(--accent-red));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shield-shaded me-2"></i>KrazePlanetLabs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../../contact">Contact Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Reflected XSS</a></li>
                            <li><a class="dropdown-item" href="#">Stored XSS</a></li>
                            <li><a class="dropdown-item" href="#">DOM XSS</a></li>
                            <li><a class="dropdown-item" href="#">Command Injection</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control search-box me-2" type="search" placeholder="Search labs..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Command Injection Lab</h1>
            <p class="hero-subtitle">Test Remote Code Execution vulnerabilities with direct command execution</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <div class="vulnerability-badge">Critical RCE Vulnerability</div>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab contains a critical Remote Code Execution (RCE) vulnerability through direct command execution. The application passes user input directly to the <code>shell_exec()</code> function without any filtering or validation.</p>
            
            <div class="danger-zone">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Critical Security Warning:</strong> This lab allows arbitrary command execution on the server. Use with extreme caution in controlled environments only.
            </div>
            
            <div class="function-filter-notice">
                <i class="bi bi-exclamation-triangle me-2"></i><strong>Vulnerable Code:</strong> Direct command execution using <code>shell_exec($_GET['cmd'])</code> with no input validation.
            </div>
            
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Security Level:</span>
                    <span class="text-danger">Critical Vulnerability</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                </div>
            </div>
            
            <p><strong>Objective:</strong> Understand how command injection vulnerabilities work and practice safe exploitation techniques in a controlled environment.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
&lt;?php
    if (isset($_GET['cmd'])) {
        $cmd = $_GET['cmd'];
        $output = shell_exec($cmd);
    }
?&gt;</pre>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-lightbulb me-2"></i>Command Examples
                    </div>
                    <div class="card-body">
                        <div class="command-examples">
                            <h6>Basic System Information:</h6>
                            <div class="command-item">whoami</div>
                            <div class="command-item">pwd</div>
                            <div class="command-item">ls -la</div>
                            <div class="command-item">uname -a</div>
                            
                            <h6 class="mt-3">File Operations:</h6>
                            <div class="command-item">cat /etc/passwd</div>
                            <div class="command-item">ls /home/</div>
                            <div class="command-item">find / -name "*.php" 2>/dev/null | head -10</div>
                            
                            <h6 class="mt-3">Network Information:</h6>
                            <div class="command-item">ifconfig</div>
                            <div class="command-item">netstat -tulpn</div>
                            <div class="command-item">ps aux</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-terminal me-2"></i>Command Execution
                    </div>
                    <div class="card-body">
                        <div class="tool-tip">
                            <i class="bi bi-info-circle me-2"></i><strong>Usage:</strong> Enter any system command to execute it on the server. The output will be displayed below.
                        </div>
                        
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="cmd" class="form-label">System Command <span class="badge bg-danger">DANGEROUS</span></label>
                                <input type="text" class="form-control" id="cmd" name="cmd" placeholder="Enter command to execute" value="<?php echo isset($_GET['cmd']) ? htmlspecialchars($_GET['cmd']) : ''; ?>">
                                <div class="form-text">This input is directly passed to shell_exec() - extreme caution advised</div>
                            </div>
                            <button type="submit" class="btn btn-danger mt-3">
                                <i class="bi bi-play-circle me-2"></i>Execute Command
                            </button>
                        </form>
                        
                        <div class="danger-zone mt-4">
                            <h6><i class="bi bi-exclamation-triangle-fill me-2"></i>Dangerous Commands (Use with Caution):</h6>
                            <div>
                                <span class="warning-badge">rm -rf</span>
                                <span class="warning-badge">dd if=/dev/zero</span>
                                <span class="warning-badge">mkfs</span>
                                <span class="warning-badge">:(){ :|:& };:</span>
                                <span class="warning-badge">shutdown</span>
                            </div>
                            <small class="text-warning">These commands can cause system damage or data loss.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($output)): ?>
        <div class="output-section">
            <div class="output-title">
                <i class="bi bi-arrow-return-right me-2"></i>Command Output
            </div>
            <div class="output-content">
                <pre><?php echo htmlspecialchars($output); ?></pre>
            </div>
            <div class="mt-3">
                <small class="text-success"><i class="bi bi-info-circle me-1"></i>Command executed successfully</small>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Security Implications
                    </div>
                    <div class="card-body">
                        <p>This vulnerability demonstrates:</p>
                        <ul>
                            <li>Arbitrary command execution on the server</li>
                            <li>Complete system compromise potential</li>
                            <li>Data theft and manipulation risks</li>
                            <li>Persistence and backdoor installation</li>
                            <li>Network reconnaissance capabilities</li>
                            <li>Privilege escalation possibilities</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-check-circle me-2"></i>Prevention Measures
                    </div>
                    <div class="card-body">
                        <p>To prevent command injection:</p>
                        <ul>
                            <li>Use allowlists for command parameters</li>
                            <li>Implement proper input validation</li>
                            <li>Use parameterized commands when possible</li>
                            <li>Run services with minimal privileges</li>
                            <li>Use application-level firewalls</li>
                            <li>Regular security testing and code reviews</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Advanced Techniques
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Command Chaining:</h6>
                                <ul>
                                    <li><code>command1 ; command2</code> - Run sequentially</li>
                                    <li><code>command1 && command2</code> - Run if first succeeds</li>
                                    <li><code>command1 || command2</code> - Run if first fails</li>
                                    <li><code>command1 | command2</code> - Pipe output</li>
                                    <li><code>command1 &</code> - Run in background</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Useful Payloads:</h6>
                                <ul>
                                    <li><code>cat /etc/passwd</code> - View user accounts</li>
                                    <li><code>ls -la /home/</code> - List home directories</li>
                                    <li><code>uname -a</code> - System information</li>
                                    <li><code>id</code> - Current user privileges</li>
                                    <li><code>ps aux</code> - Running processes</li>
                                    <li><code>netstat -tulpn</code> - Network connections</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-tools me-2"></i>Testing Methodology
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Reconnaissance:</h6>
                                <ul>
                                    <li>Identify operating system</li>
                                    <li>Discover current user privileges</li>
                                    <li>Map directory structure</li>
                                    <li>Find configuration files</li>
                                    <li>Identify network services</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Exploitation:</h6>
                                <ul>
                                    <li>Test command injection vectors</li>
                                    <li>Attempt privilege escalation</li>
                                    <li>Establish persistence mechanisms</li>
                                    <li>Exfiltrate sensitive data</li>
                                    <li>Maintain access</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>