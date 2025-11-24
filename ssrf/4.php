<?php
// Lab 4: API Gateway SSRF
// Vulnerability: API endpoint proxying without validation

$message = '';
$response_content = '';
$response_headers = '';
$api_endpoint = '';
$api_method = $_GET['api_method'] ?? 'GET';
$api_headers = $_GET['api_headers'] ?? '';

// Handle API gateway request
if (isset($_GET['endpoint']) && !empty($_GET['endpoint'])) {
    $api_endpoint = $_GET['endpoint'];
    
    // Vulnerable: No validation of API endpoint
    try {
        $context_options = [
            'http' => [
                'timeout' => 10,
                'user_agent' => 'APIGateway/1.0',
                'follow_location' => true,
                'max_redirects' => 5
            ]
        ];
        
        // Set method
        $context_options['http']['method'] = $api_method;
        
        // Add custom headers if provided
        if (!empty($api_headers)) {
            $headers = explode("\n", $api_headers);
            $header_string = '';
            foreach ($headers as $header) {
                $header = trim($header);
                if (!empty($header)) {
                    $header_string .= $header . "\r\n";
                }
            }
            $context_options['http']['header'] = $header_string;
        }
        
        $context = stream_context_create($context_options);
        
        $response = file_get_contents($api_endpoint, false, $context);
        
        if ($response !== false) {
            $response_content = $response;
            $response_headers = $http_response_header ?? [];
            $message = '<div class="alert alert-success">API request completed successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to make API request: ' . htmlspecialchars($api_endpoint) . '</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error making API request: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: API Gateway - SSRF</title>
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

        .form-control, .form-select, .form-textarea {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
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

        .form-textarea {
            resize: vertical;
            min-height: 100px;
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
            <h1 class="hero-title">Lab 4: API Gateway</h1>
            <p class="hero-subtitle">SSRF in API gateway functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates an SSRF vulnerability in an API gateway service. The application proxies requests to user-supplied API endpoints without proper validation, allowing access to internal services and systems.</p>
            <p><strong>Objective:</strong> Use SSRF to access internal services, cloud metadata, or local files through the API gateway.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle API gateway request
if (isset($_GET['endpoint']) && !empty($_GET['endpoint'])) {
    $api_endpoint = $_GET['endpoint'];
    
    // Vulnerable: No validation of API endpoint
    try {
        $context_options = [
            'http' => [
                'timeout' => 10,
                'user_agent' => 'APIGateway/1.0',
                'follow_location' => true,
                'max_redirects' => 5
            ]
        ];
        
        // Set method and headers
        $context_options['http']['method'] = $api_method;
        if (!empty($api_headers)) {
            $context_options['http']['header'] = $api_headers;
        }
        
        $context = stream_context_create($context_options);
        $response = file_get_contents($api_endpoint, false, $context);
        
        if ($response !== false) {
            // Display response content
        }
    } catch (Exception $e) {
        // Error handling
    }
}

// Example vulnerable usage:
// ?endpoint=https://api.example.com/data
// ?endpoint=http://localhost:8080
// ?endpoint=file:///etc/passwd</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-3 me-2"></i>API Gateway Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="endpoint" class="form-label">API Endpoint</label>
                                <input type="text" class="form-control" id="endpoint" name="endpoint" 
                                       placeholder="Enter API endpoint..." value="<?php echo htmlspecialchars($api_endpoint); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="api_method" class="form-label">HTTP Method</label>
                                <select class="form-select" id="api_method" name="api_method">
                                    <option value="GET" <?php echo $api_method === 'GET' ? 'selected' : ''; ?>>GET</option>
                                    <option value="POST" <?php echo $api_method === 'POST' ? 'selected' : ''; ?>>POST</option>
                                    <option value="PUT" <?php echo $api_method === 'PUT' ? 'selected' : ''; ?>>PUT</option>
                                    <option value="DELETE" <?php echo $api_method === 'DELETE' ? 'selected' : ''; ?>>DELETE</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="api_headers" class="form-label">Custom Headers (one per line)</label>
                                <textarea class="form-control form-textarea" id="api_headers" name="api_headers" 
                                          placeholder="Authorization: Bearer token&#10;Content-Type: application/json"><?php echo htmlspecialchars($api_headers); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Make API Request</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?endpoint=https://httpbin.org/get" style="color: var(--accent-green);">Test External API</a></li>
                                <li><a href="?endpoint=http://localhost:8080" style="color: var(--accent-green);">Test Local Service</a></li>
                                <li><a href="?endpoint=file:///etc/passwd" style="color: var(--accent-green);">Test File Protocol</a></li>
                                <li><a href="?endpoint=http://169.254.169.254/" style="color: var(--accent-green);">Test Cloud Metadata</a></li>
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
                        <i class="bi bi-file-text me-2"></i>API Response: <?php echo htmlspecialchars($api_endpoint); ?>
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
                        <li><strong>Type:</strong> Server-Side Request Forgery (SSRF)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>endpoint</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> API endpoint proxying without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these payloads in the endpoint parameter:</p>
                    <ul>
                        <li><code>http://localhost:8080</code> - Local service</li>
                        <li><code>http://127.0.0.1:3306</code> - Database port</li>
                        <li><code>file:///etc/passwd</code> - Local file</li>
                        <li><code>http://169.254.169.254/</code> - Cloud metadata</li>
                        <li><code>http://localhost:22</code> - SSH port</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>4.php?endpoint=http://localhost:8080</code></li>
                        <li><code>4.php?endpoint=file:///etc/passwd</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?endpoint=http://localhost:8080" style="color: var(--accent-green);">Test Local Service (Port 8080)</a></li>
                <li><a href="?endpoint=http://127.0.0.1:3306" style="color: var(--accent-green);">Test Database Port (3306)</a></li>
                <li><a href="?endpoint=file:///etc/passwd" style="color: var(--accent-green);">Test File Protocol</a></li>
                <li><a href="?endpoint=http://169.254.169.254/" style="color: var(--accent-green);">Test Cloud Metadata</a></li>
                <li><a href="?endpoint=http://localhost:22" style="color: var(--accent-green);">Test SSH Port (22)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Access to internal services through API endpoints</li>
                <li>Cloud metadata and credentials exposure</li>
                <li>Database access and data exfiltration</li>
                <li>Local file system access</li>
                <li>Port scanning and service enumeration</li>
                <li>Bypassing firewalls and network restrictions</li>
                <li>API gateway abuse and resource exhaustion</li>
                <li>Header injection and request manipulation</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Validate and whitelist allowed API endpoints</li>
                    <li>Block private IP ranges and localhost</li>
                    <li>Use URL parsing libraries to validate URLs</li>
                    <li>Implement proper error handling</li>
                    <li>Use outbound proxies with restrictions</li>
                    <li>Disable dangerous protocols (file://, gopher://)</li>
                    <li>Implement request timeouts and size limits</li>
                    <li>Validate and sanitize custom headers</li>
                    <li>Implement rate limiting and authentication</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
