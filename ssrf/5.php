<?php
// Lab 5: Advanced Protocol Bypass SSRF
// Vulnerability: Multiple protocol support with bypass techniques

$message = '';
$response_content = '';
$response_headers = '';
$url = '';
$protocol = $_GET['protocol'] ?? 'http';
$bypass_technique = $_GET['bypass'] ?? 'none';

// Protocol bypass functions
function applyBypass($url, $technique) {
    switch ($technique) {
        case 'double_encoding':
            return str_replace(['%2f', '%3a', '%40'], ['%252f', '%253a', '%2540'], $url);
        case 'unicode_encoding':
            return str_replace(['/', ':', '@'], ['%c0%af', '%c0%ae', '%c0%40'], $url);
        case 'null_byte':
            return $url . '%00';
        case 'redirect':
            return 'http://httpbin.org/redirect-to?url=' . urlencode($url);
        case 'dns_rebind':
            return 'http://169.254.169.254.nip.io/';
        case 'ip_encoding':
            return str_replace(['127.0.0.1', 'localhost'], ['2130706433', '0x7f000001'], $url);
        case 'port_scan':
            return 'http://127.0.0.1:' . ($_GET['port'] ?? '8080');
        default:
            return $url;
    }
}

// Handle advanced SSRF request
if (isset($_GET['url']) && !empty($_GET['url'])) {
    $url = $_GET['url'];
    
    // Apply bypass technique
    $url = applyBypass($url, $bypass_technique);
    
    // Vulnerable: No validation of URL or protocol
    try {
        $context_options = [
            'http' => [
                'timeout' => 10,
                'user_agent' => 'AdvancedSSRF/1.0',
                'follow_location' => true,
                'max_redirects' => 10
            ]
        ];
        
        // Support different protocols
        if ($protocol === 'gopher') {
            $context_options['http']['method'] = 'GET';
            $context_options['http']['header'] = "Content-Type: application/x-www-form-urlencoded\r\n";
        }
        
        $context = stream_context_create($context_options);
        
        $response = file_get_contents($url, false, $context);
        
        if ($response !== false) {
            $response_content = $response;
            $response_headers = $http_response_header ?? [];
            $message = '<div class="alert alert-success">Advanced SSRF request completed successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to make request: ' . htmlspecialchars($url) . '</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error making request: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced Protocol Bypass - SSRF</title>
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

        .response-content {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
            max-height: 400px;
            overflow-y: auto;
        }

        .response-headers {
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

        .bypass-info {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .protocol-examples {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to SSRF Labs
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
            <h1 class="hero-title">Lab 5: Advanced Protocol Bypass</h1>
            <p class="hero-subtitle">SSRF with advanced bypass techniques and protocol support</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced SSRF vulnerabilities with multiple protocol support and bypass techniques. The application supports various protocols and encoding methods that can be exploited to bypass security controls.</p>
            <p><strong>Objective:</strong> Use advanced SSRF techniques to bypass filters and access internal services through various protocols and encoding methods.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle advanced SSRF request
if (isset($_GET['url']) && !empty($_GET['url'])) {
    $url = $_GET['url'];
    
    // Apply bypass technique
    $url = applyBypass($url, $bypass_technique);
    
    // Vulnerable: No validation of URL or protocol
    try {
        $context_options = [
            'http' => [
                'timeout' => 10,
                'user_agent' => 'AdvancedSSRF/1.0',
                'follow_location' => true,
                'max_redirects' => 10
            ]
        ];
        
        // Support different protocols
        if ($protocol === 'gopher') {
            $context_options['http']['method'] = 'GET';
            $context_options['http']['header'] = "Content-Type: application/x-www-form-urlencoded\r\n";
        }
        
        $context = stream_context_create($context_options);
        $response = file_get_contents($url, false, $context);
        
        if ($response !== false) {
            // Display response content
        }
    } catch (Exception $e) {
        // Error handling
    }
}

// Example vulnerable usage:
// ?url=http://localhost:8080&protocol=http&bypass=none
// ?url=file:///etc/passwd&protocol=file&bypass=null_byte
// ?url=gopher://localhost:3306&protocol=gopher&bypass=double_encoding</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Advanced SSRF Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="url" class="form-label">Target URL</label>
                                <input type="text" class="form-control" id="url" name="url" 
                                       placeholder="Enter target URL..." value="<?php echo htmlspecialchars($url); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="protocol" class="form-label">Protocol</label>
                                <select class="form-select" id="protocol" name="protocol">
                                    <option value="http" <?php echo $protocol === 'http' ? 'selected' : ''; ?>>HTTP</option>
                                    <option value="https" <?php echo $protocol === 'https' ? 'selected' : ''; ?>>HTTPS</option>
                                    <option value="file" <?php echo $protocol === 'file' ? 'selected' : ''; ?>>File</option>
                                    <option value="gopher" <?php echo $protocol === 'gopher' ? 'selected' : ''; ?>>Gopher</option>
                                    <option value="dict" <?php echo $protocol === 'dict' ? 'selected' : ''; ?>>Dict</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="bypass" class="form-label">Bypass Technique</label>
                                <select class="form-select" id="bypass" name="bypass">
                                    <option value="none" <?php echo $bypass_technique === 'none' ? 'selected' : ''; ?>>None</option>
                                    <option value="double_encoding" <?php echo $bypass_technique === 'double_encoding' ? 'selected' : ''; ?>>Double Encoding</option>
                                    <option value="unicode_encoding" <?php echo $bypass_technique === 'unicode_encoding' ? 'selected' : ''; ?>>Unicode Encoding</option>
                                    <option value="null_byte" <?php echo $bypass_technique === 'null_byte' ? 'selected' : ''; ?>>Null Byte</option>
                                    <option value="redirect" <?php echo $bypass_technique === 'redirect' ? 'selected' : ''; ?>>Redirect</option>
                                    <option value="dns_rebind" <?php echo $bypass_technique === 'dns_rebind' ? 'selected' : ''; ?>>DNS Rebind</option>
                                    <option value="ip_encoding" <?php echo $bypass_technique === 'ip_encoding' ? 'selected' : ''; ?>>IP Encoding</option>
                                    <option value="port_scan" <?php echo $bypass_technique === 'port_scan' ? 'selected' : ''; ?>>Port Scan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Execute Advanced SSRF</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?url=http://localhost:8080&protocol=http&bypass=none" style="color: var(--accent-green);">Test Basic SSRF</a></li>
                                <li><a href="?url=file:///etc/passwd&protocol=file&bypass=null_byte" style="color: var(--accent-green);">Test File Protocol</a></li>
                                <li><a href="?url=gopher://localhost:3306&protocol=gopher&bypass=double_encoding" style="color: var(--accent-green);">Test Gopher Protocol</a></li>
                                <li><a href="?url=http://169.254.169.254/&protocol=http&bypass=dns_rebind" style="color: var(--accent-green);">Test Cloud Metadata</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($response_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-text me-2"></i>Response Content: <?php echo htmlspecialchars($url); ?>
                    </div>
                    <div class="card-body">
                        <div class="response-content">
                            <pre><?php echo htmlspecialchars($response_content); ?></pre>
                        </div>
                        
                        <?php if (!empty($response_headers)): ?>
                        <div class="response-headers">
                            <h6><i class="bi bi-list me-2"></i>Response Headers:</h6>
                            <pre><?php echo htmlspecialchars(implode("\n", $response_headers)); ?></pre>
                        </div>
                        <?php endif; ?>
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
                        <li><strong>Type:</strong> Advanced Server-Side Request Forgery (SSRF)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>url</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Multiple protocol support with bypass techniques</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Advanced Payloads</h5>
                    <p>Try these advanced payloads:</p>
                    <ul>
                        <li><code>http://localhost:8080</code> - Basic local service</li>
                        <li><code>file:///etc/passwd</code> - Local file access</li>
                        <li><code>gopher://localhost:3306</code> - Database via Gopher</li>
                        <li><code>dict://localhost:11211</code> - Memcached via Dict</li>
                        <li><code>http://169.254.169.254/</code> - Cloud metadata</li>
                    </ul>
                    <p><strong>Bypass Techniques:</strong></p>
                    <ul>
                        <li><code>double_encoding</code> - Double URL encoding</li>
                        <li><code>unicode_encoding</code> - Unicode encoding</li>
                        <li><code>null_byte</code> - Null byte injection</li>
                        <li><code>redirect</code> - HTTP redirect bypass</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bypass-info">
            <h5><i class="bi bi-shield-exclamation me-2"></i>Bypass Techniques</h5>
            <p>This lab supports various bypass techniques:</p>
            <ul>
                <li><strong>Double Encoding:</strong> <code>%252f</code> instead of <code>%2f</code></li>
                <li><strong>Unicode Encoding:</strong> <code>%c0%af</code> instead of <code>/</code></li>
                <li><strong>Null Byte:</strong> <code>%00</code> to terminate strings</li>
                <li><strong>Redirect:</strong> Use HTTP redirects to bypass filters</li>
                <li><strong>DNS Rebind:</strong> Use DNS rebinding services</li>
                <li><strong>IP Encoding:</strong> Use decimal/hex IP representations</li>
                <li><strong>Port Scan:</strong> Scan internal ports</li>
            </ul>
        </div>

        <div class="protocol-examples">
            <h5><i class="bi bi-diagram-3 me-2"></i>Supported Protocols</h5>
            <p>This lab supports various protocols:</p>
            <ul>
                <li><strong>HTTP/HTTPS:</strong> Standard web protocols</li>
                <li><strong>File:</strong> Local file system access</li>
                <li><strong>Gopher:</strong> Protocol for data retrieval</li>
                <li><strong>Dict:</strong> Dictionary protocol for databases</li>
                <li><strong>LDAP:</strong> Lightweight Directory Access Protocol</li>
            </ul>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test advanced techniques:</p>
            <ul>
                <li><a href="?url=http://localhost:8080&protocol=http&bypass=none" style="color: var(--accent-green);">Test Basic SSRF</a></li>
                <li><a href="?url=file:///etc/passwd&protocol=file&bypass=null_byte" style="color: var(--accent-green);">Test File Protocol</a></li>
                <li><a href="?url=gopher://localhost:3306&protocol=gopher&bypass=double_encoding" style="color: var(--accent-green);">Test Gopher Protocol</a></li>
                <li><a href="?url=http://169.254.169.254/&protocol=http&bypass=dns_rebind" style="color: var(--accent-green);">Test Cloud Metadata</a></li>
                <li><a href="?url=dict://localhost:11211&protocol=dict&bypass=unicode_encoding" style="color: var(--accent-green);">Test Dict Protocol</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Advanced Attack Scenarios</h5>
            <ul>
                <li>Protocol confusion and bypass attacks</li>
                <li>Advanced encoding and obfuscation techniques</li>
                <li>DNS rebinding and subdomain takeover</li>
                <li>Port scanning and service enumeration</li>
                <li>Cloud metadata and credentials exposure</li>
                <li>Database access through alternative protocols</li>
                <li>Local file system access</li>
                <li>Bypassing WAF and security controls</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Advanced Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive URL validation and sanitization</li>
                    <li>Block all dangerous protocols (file://, gopher://, dict://)</li>
                    <li>Use whitelist-based URL validation</li>
                    <li>Implement proper error handling and logging</li>
                    <li>Use outbound proxies with strict restrictions</li>
                    <li>Implement request timeouts and size limits</li>
                    <li>Regular security testing and updates</li>
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
