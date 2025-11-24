<?php
// Lab 3: Webhook Tester SSRF
// Vulnerability: Webhook URL testing without validation

$message = '';
$response_content = '';
$response_headers = '';
$webhook_url = '';
$method = $_GET['method'] ?? 'GET';
$payload = $_GET['payload'] ?? '';

// Handle webhook test request
if (isset($_GET['webhook']) && !empty($_GET['webhook'])) {
    $webhook_url = $_GET['webhook'];
    
    // Vulnerable: No validation of webhook URL
    try {
        $context_options = [
            'http' => [
                'timeout' => 10,
                'user_agent' => 'WebhookTester/1.0',
                'follow_location' => true,
                'max_redirects' => 5
            ]
        ];
        
        if ($method === 'POST') {
            $context_options['http']['method'] = 'POST';
            $context_options['http']['header'] = 'Content-Type: application/json';
            $context_options['http']['content'] = $payload;
        }
        
        $context = stream_context_create($context_options);
        
        $response = file_get_contents($webhook_url, false, $context);
        
        if ($response !== false) {
            $response_content = $response;
            $response_headers = $http_response_header ?? [];
            $message = '<div class="alert alert-success">Webhook test completed successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to test webhook: ' . htmlspecialchars($webhook_url) . '</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error testing webhook: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Webhook Tester - SSRF</title>
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
            <h1 class="hero-title">Lab 3: Webhook Tester</h1>
            <p class="hero-subtitle">SSRF in webhook testing functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates an SSRF vulnerability in a webhook testing service. The application makes HTTP requests to user-supplied webhook URLs without proper validation, allowing access to internal services.</p>
            <p><strong>Objective:</strong> Use SSRF to access internal services, cloud metadata, or local files through webhook testing.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle webhook test request
if (isset($_GET['webhook']) && !empty($_GET['webhook'])) {
    $webhook_url = $_GET['webhook'];
    
    // Vulnerable: No validation of webhook URL
    try {
        $context_options = [
            'http' => [
                'timeout' => 10,
                'user_agent' => 'WebhookTester/1.0',
                'follow_location' => true,
                'max_redirects' => 5
            ]
        ];
        
        if ($method === 'POST') {
            $context_options['http']['method'] = 'POST';
            $context_options['http']['header'] = 'Content-Type: application/json';
            $context_options['http']['content'] = $payload;
        }
        
        $context = stream_context_create($context_options);
        $response = file_get_contents($webhook_url, false, $context);
        
        if ($response !== false) {
            // Display response content
        }
    } catch (Exception $e) {
        // Error handling
    }
}

// Example vulnerable usage:
// ?webhook=https://example.com/webhook
// ?webhook=http://localhost:8080
// ?webhook=file:///etc/passwd</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-webhook me-2"></i>Webhook Tester Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="webhook" class="form-label">Webhook URL</label>
                                <input type="text" class="form-control" id="webhook" name="webhook" 
                                       placeholder="Enter webhook URL..." value="<?php echo htmlspecialchars($webhook_url); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="method" class="form-label">HTTP Method</label>
                                <select class="form-select" id="method" name="method">
                                    <option value="GET" <?php echo $method === 'GET' ? 'selected' : ''; ?>>GET</option>
                                    <option value="POST" <?php echo $method === 'POST' ? 'selected' : ''; ?>>POST</option>
                                </select>
                            </div>
                            <div class="mb-3" id="payload-section" style="<?php echo $method === 'POST' ? '' : 'display: none;'; ?>">
                                <label for="payload" class="form-label">Payload (JSON)</label>
                                <textarea class="form-control form-textarea" id="payload" name="payload" 
                                          placeholder='{"test": "data"}'><?php echo htmlspecialchars($payload); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Test Webhook</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?webhook=https://httpbin.org/post&method=POST" style="color: var(--accent-green);">Test External Webhook</a></li>
                                <li><a href="?webhook=http://localhost:8080" style="color: var(--accent-green);">Test Local Service</a></li>
                                <li><a href="?webhook=file:///etc/passwd" style="color: var(--accent-green);">Test File Protocol</a></li>
                                <li><a href="?webhook=http://169.254.169.254/" style="color: var(--accent-green);">Test Cloud Metadata</a></li>
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
                        <i class="bi bi-file-text me-2"></i>Webhook Response: <?php echo htmlspecialchars($webhook_url); ?>
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
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameter:</strong> <code>webhook</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Webhook URL testing without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these payloads in the webhook parameter:</p>
                    <ul>
                        <li><code>http://localhost:8080</code> - Local service</li>
                        <li><code>http://127.0.0.1:3306</code> - Database port</li>
                        <li><code>file:///etc/passwd</code> - Local file</li>
                        <li><code>http://169.254.169.254/</code> - Cloud metadata</li>
                        <li><code>http://localhost:22</code> - SSH port</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>3.php?webhook=http://localhost:8080</code></li>
                        <li><code>3.php?webhook=file:///etc/passwd</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?webhook=http://localhost:8080" style="color: var(--accent-green);">Test Local Service (Port 8080)</a></li>
                <li><a href="?webhook=http://127.0.0.1:3306" style="color: var(--accent-green);">Test Database Port (3306)</a></li>
                <li><a href="?webhook=file:///etc/passwd" style="color: var(--accent-green);">Test File Protocol</a></li>
                <li><a href="?webhook=http://169.254.169.254/" style="color: var(--accent-green);">Test Cloud Metadata</a></li>
                <li><a href="?webhook=http://localhost:22" style="color: var(--accent-green);">Test SSH Port (22)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Access to internal services through webhook parameters</li>
                <li>Cloud metadata and credentials exposure</li>
                <li>Database access and data exfiltration</li>
                <li>Local file system access</li>
                <li>Port scanning and service enumeration</li>
                <li>Bypassing firewalls and network restrictions</li>
                <li>Webhook payload injection attacks</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Validate and whitelist allowed webhook URLs</li>
                    <li>Block private IP ranges and localhost</li>
                    <li>Use URL parsing libraries to validate URLs</li>
                    <li>Implement proper error handling</li>
                    <li>Use outbound proxies with restrictions</li>
                    <li>Disable dangerous protocols (file://, gopher://)</li>
                    <li>Implement request timeouts and size limits</li>
                    <li>Validate webhook payloads</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('method').addEventListener('change', function() {
            const payloadSection = document.getElementById('payload-section');
            if (this.value === 'POST') {
                payloadSection.style.display = 'block';
            } else {
                payloadSection.style.display = 'none';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
