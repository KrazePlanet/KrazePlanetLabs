<?php
// Lab 5: Advanced Filter Bypass RCE
// Vulnerability: Multiple filtering mechanisms with bypass techniques

$message = '';
$command_output = '';
$command = '';
$filter_type = $_GET['filter'] ?? 'none';
$bypass_technique = $_GET['bypass'] ?? 'none';

// Filter functions
function applyFilter($input, $type) {
    switch ($type) {
        case 'basic':
            // Basic filter: Remove common command injection characters
            return str_replace([';', '&', '|', '`', '$', '(', ')', '<', '>'], '', $input);
            
        case 'command_filter':
            // Filter: Remove common commands
            $commands = ['cat', 'ls', 'whoami', 'id', 'uname', 'ps', 'netstat', 'wget', 'curl'];
            foreach ($commands as $cmd) {
                $input = str_ireplace($cmd, '', $input);
            }
            return $input;
            
        case 'space_filter':
            // Filter: Remove spaces
            return str_replace(' ', '', $input);
            
        case 'quote_filter':
            // Filter: Remove quotes
            return str_replace(['"', "'", '`'], '', $input);
            
        case 'encoding_filter':
            // Filter: Basic URL decoding
            return urldecode($input);
            
        case 'length_filter':
            // Filter: Limit length
            return substr($input, 0, 10);
            
        default:
            return $input;
    }
}

// Bypass functions
function applyBypass($input, $technique) {
    switch ($technique) {
        case 'double_encoding':
            return str_replace(['%2f', '%3a', '%40'], ['%252f', '%253a', '%2540'], $input);
            
        case 'unicode_encoding':
            return str_replace(['/', ':', '@'], ['%c0%af', '%c0%ae', '%c0%40'], $input);
            
        case 'null_byte':
            return $input . '%00';
            
        case 'redirect':
            return 'http://httpbin.org/redirect-to?url=' . urlencode($input);
            
        case 'dns_rebind':
            return 'http://169.254.169.254.nip.io/';
            
        case 'ip_encoding':
            return str_replace(['127.0.0.1', 'localhost'], ['2130706433', '0x7f000001'], $input);
            
        case 'command_substitution':
            return str_replace(['ls', 'cat', 'whoami'], ['`ls`', '`cat`', '`whoami`'], $input);
            
        default:
            return $input;
    }
}

// Handle command execution request
if (isset($_GET['cmd']) && !empty($_GET['cmd'])) {
    $command = $_GET['cmd'];
    
    // Apply filter and bypass
    $filtered_command = applyFilter($command, $filter_type);
    $bypassed_command = applyBypass($filtered_command, $bypass_technique);
    
    // Vulnerable: Still using filtered input in command execution
    try {
        $output = shell_exec($bypassed_command . ' 2>&1');
        $command_output = $output ?: 'No output';
        $message = '<div class="alert alert-success">Command executed successfully!</div>';
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error executing command: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced Filter Bypass RCE - RCE</title>
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

        .command-output {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
            max-height: 400px;
            overflow-y: auto;
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .filter-info {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .bypass-examples {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-red);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to RCE Labs
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
            <h1 class="hero-title">Lab 5: Advanced Filter Bypass RCE</h1>
            <p class="hero-subtitle">RCE with advanced filter bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced Remote Code Execution vulnerabilities where various filtering mechanisms are implemented but can be bypassed. The application applies different filters to user input but still allows RCE through creative bypass techniques.</p>
            <p><strong>Objective:</strong> Bypass the implemented filters and execute arbitrary commands using advanced techniques.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Multiple filter implementations
function applyFilter($input, $type) {
    switch ($type) {
        case 'basic':
            return str_replace([';', '&', '|', '`', '$', '(', ')', '<', '>'], '', $input);
        case 'command_filter':
            $commands = ['cat', 'ls', 'whoami', 'id', 'uname'];
            foreach ($commands as $cmd) {
                $input = str_ireplace($cmd, '', $input);
            }
            return $input;
        case 'space_filter':
            return str_replace(' ', '', $input);
        case 'quote_filter':
            return str_replace(['"', "'", '`'], '', $input);
        default:
            return $input;
    }
}

// Bypass functions
function applyBypass($input, $technique) {
    switch ($technique) {
        case 'double_encoding':
            return str_replace(['%2f', '%3a'], ['%252f', '%253a'], $input);
        case 'unicode_encoding':
            return str_replace(['/', ':'], ['%c0%af', '%c0%ae'], $input);
        case 'command_substitution':
            return str_replace(['ls', 'cat'], ['`ls`', '`cat`'], $input);
        default:
            return $input;
    }
}

// Vulnerable processing
if (isset($_GET['cmd'])) {
    $command = $_GET['cmd'];
    $filtered_command = applyFilter($command, $filter_type);
    $bypassed_command = applyBypass($filtered_command, $bypass_technique);
    $output = shell_exec($bypassed_command . ' 2>&1');
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Advanced Filter Bypass Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="filter" class="form-label">Filter Type</label>
                                <select class="form-select" id="filter" name="filter" onchange="this.form.submit()">
                                    <option value="none" <?php echo $filter_type === 'none' ? 'selected' : ''; ?>>No Filter</option>
                                    <option value="basic" <?php echo $filter_type === 'basic' ? 'selected' : ''; ?>>Basic Filter</option>
                                    <option value="command_filter" <?php echo $filter_type === 'command_filter' ? 'selected' : ''; ?>>Command Filter</option>
                                    <option value="space_filter" <?php echo $filter_type === 'space_filter' ? 'selected' : ''; ?>>Space Filter</option>
                                    <option value="quote_filter" <?php echo $filter_type === 'quote_filter' ? 'selected' : ''; ?>>Quote Filter</option>
                                    <option value="encoding_filter" <?php echo $filter_type === 'encoding_filter' ? 'selected' : ''; ?>>Encoding Filter</option>
                                    <option value="length_filter" <?php echo $filter_type === 'length_filter' ? 'selected' : ''; ?>>Length Filter</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="bypass" class="form-label">Bypass Technique</label>
                                <select class="form-select" id="bypass" name="bypass" onchange="this.form.submit()">
                                    <option value="none" <?php echo $bypass_technique === 'none' ? 'selected' : ''; ?>>No Bypass</option>
                                    <option value="double_encoding" <?php echo $bypass_technique === 'double_encoding' ? 'selected' : ''; ?>>Double Encoding</option>
                                    <option value="unicode_encoding" <?php echo $bypass_technique === 'unicode_encoding' ? 'selected' : ''; ?>>Unicode Encoding</option>
                                    <option value="null_byte" <?php echo $bypass_technique === 'null_byte' ? 'selected' : ''; ?>>Null Byte</option>
                                    <option value="redirect" <?php echo $bypass_technique === 'redirect' ? 'selected' : ''; ?>>Redirect</option>
                                    <option value="dns_rebind" <?php echo $bypass_technique === 'dns_rebind' ? 'selected' : ''; ?>>DNS Rebind</option>
                                    <option value="ip_encoding" <?php echo $bypass_technique === 'ip_encoding' ? 'selected' : ''; ?>>IP Encoding</option>
                                    <option value="command_substitution" <?php echo $bypass_technique === 'command_substitution' ? 'selected' : ''; ?>>Command Substitution</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cmd" class="form-label">Command to Execute</label>
                                <input type="text" class="form-control" id="cmd" name="cmd" 
                                       placeholder="Enter command..." value="<?php echo htmlspecialchars($command); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Command</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test Commands:</h6>
                            <ul>
                                <li><a href="?cmd=whoami&filter=basic&bypass=none" style="color: var(--accent-green);">Test Basic Filter</a></li>
                                <li><a href="?cmd=ls -la&filter=space_filter&bypass=command_substitution" style="color: var(--accent-green);">Test Space Filter</a></li>
                                <li><a href="?cmd=cat /etc/passwd&filter=command_filter&bypass=double_encoding" style="color: var(--accent-green);">Test Command Filter</a></li>
                                <li><a href="?cmd=id && uname -a&filter=quote_filter&bypass=unicode_encoding" style="color: var(--accent-green);">Test Quote Filter</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($command_output): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-terminal me-2"></i>Command Output: <?php echo htmlspecialchars($command); ?>
                    </div>
                    <div class="card-body">
                        <div class="command-output">
                            <pre><?php echo htmlspecialchars($command_output); ?></pre>
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
                        <li><strong>Type:</strong> Advanced Remote Code Execution (RCE)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>cmd</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Inadequate filtering mechanisms</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Payloads by Filter</h5>
                    <p><strong>Basic Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>whoami</code> - Direct command</li>
                        <li><code>w%68oami</code> - Character encoding</li>
                        <li><code>w**oami</code> - Wildcard substitution</li>
                    </ul>
                    <p><strong>Space Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>ls${IFS}-la</code> - IFS variable</li>
                        <li><code>ls%20-la</code> - URL encoding</li>
                        <li><code>ls%09-la</code> - Tab character</li>
                    </ul>
                    <p><strong>Command Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>c%61t</code> - Character encoding</li>
                        <li><code>c**t</code> - Wildcard substitution</li>
                        <li><code>`cat`</code> - Command substitution</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="filter-info">
            <h5><i class="bi bi-funnel me-2"></i>Current Filter: <?php echo ucfirst(str_replace('_', ' ', $filter_type)); ?></h5>
            <p>Try different bypass techniques based on the active filter:</p>
            <ul>
                <li><strong>Basic Filter:</strong> Removes common injection characters - Try encoding or alternative characters</li>
                <li><strong>Command Filter:</strong> Removes common commands - Try encoding or wildcards</li>
                <li><strong>Space Filter:</strong> Removes spaces - Try IFS, tabs, or encoding</li>
                <li><strong>Quote Filter:</strong> Removes quotes - Try alternative quote characters</li>
                <li><strong>Encoding Filter:</strong> Applies URL decoding - Try double encoding</li>
                <li><strong>Length Filter:</strong> Limits length - Try short commands or chaining</li>
            </ul>
        </div>

        <div class="bypass-examples">
            <h5><i class="bi bi-shield-exclamation me-2"></i>Advanced Bypass Techniques</h5>
            <p>Try these advanced bypass techniques:</p>
            <ul>
                <li><code>whoami</code> - Direct command execution</li>
                <li><code>w%68oami</code> - Character encoding bypass</li>
                <li><code>ls${IFS}-la</code> - IFS variable for spaces</li>
                <li><code>c%61t%20/etc/passwd</code> - URL encoding bypass</li>
                <li><code>`whoami`</code> - Command substitution</li>
                <li><code>w**oami</code> - Wildcard substitution</li>
                <li><code>w%00hoami</code> - Null byte injection</li>
            </ul>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test different bypass techniques:</p>
            <ul>
                <li><a href="?cmd=whoami&filter=basic&bypass=none" style="color: var(--accent-green);">Test Basic Filter</a></li>
                <li><a href="?cmd=ls${IFS}-la&filter=space_filter&bypass=command_substitution" style="color: var(--accent-green);">Test Space Filter Bypass</a></li>
                <li><a href="?cmd=c%61t%20/etc/passwd&filter=command_filter&bypass=double_encoding" style="color: var(--accent-green);">Test Command Filter Bypass</a></li>
                <li><a href="?cmd=w%68oami&filter=basic&bypass=unicode_encoding" style="color: var(--accent-green);">Test Character Encoding</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Advanced Attack Scenarios</h5>
            <ul>
                <li>Bypassing WAF and security filters</li>
                <li>Advanced encoding and obfuscation techniques</li>
                <li>Protocol confusion attacks</li>
                <li>Multi-stage payload delivery</li>
                <li>Context-aware injection attacks</li>
                <li>Client-side security control bypasses</li>
                <li>Privilege escalation and persistence</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Advanced Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement multiple layers of validation and sanitization</li>
                    <li>Use whitelist-based validation instead of blacklists</li>
                    <li>Normalize and canonicalize input before validation</li>
                    <li>Implement proper command validation and restriction</li>
                    <li>Use least privilege principles</li>
                    <li>Implement proper error handling</li>
                    <li>Regular security testing and filter updates</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                    <li>Implement network segmentation and access controls</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
