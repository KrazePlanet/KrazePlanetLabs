<?php
// Lab 2: Open Redirect via HTTP Headers
// Vulnerability: Using HTTP_REFERER or other headers for redirect

$redirect_url = '';

// Check various headers that might contain redirect URLs
if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $redirect_url = $_SERVER['HTTP_REFERER'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $redirect_url = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_GET['return_url'])) {
    $redirect_url = $_GET['return_url'];
}

if (!empty($redirect_url)) {
    // Vulnerable: Using header values without validation
    header("Location: " . $redirect_url);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Redirect Lab 2 - HTTP Headers</title>
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

        .header-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-green);
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Redirect Labs
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
            <h1 class="hero-title">Open Redirect Lab 2</h1>
            <p class="hero-subtitle">HTTP Headers Redirect</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates open redirect vulnerabilities that occur when applications trust HTTP headers like Referer, X-Forwarded-For, or custom headers for redirect functionality.</p>
            <p><strong>Objective:</strong> Test header-based redirects and understand how HTTP headers can be exploited for open redirect attacks.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
// Check various headers that might contain redirect URLs
if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $redirect_url = $_SERVER['HTTP_REFERER'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $redirect_url = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_GET['return_url'])) {
    $redirect_url = $_GET['return_url'];
}

if (!empty($redirect_url)) {
    // Vulnerable: Using header values without validation
    header("Location: " . $redirect_url);
    exit();
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-input-cursor-text me-2"></i>Test Input Form
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <div class="mb-3">
                                <label for="return_url" class="form-label">Return URL (fallback parameter)</label>
                                <input class="form-control" type="text" placeholder="https://example.com" 
                                       aria-label="Return URL" name="return_url">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Test Redirect</button>
                        </form>
                        <div class="mt-3">
                            <p class="text-muted small">This form uses the return_url parameter. To test header-based redirects, use tools like curl or browser developer tools to modify headers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-info">
            <h5><i class="bi bi-server me-2"></i>Current Headers</h5>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>HTTP_REFERER:</strong></p>
                    <code><?php echo htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Not set'); ?></code>
                </div>
                <div class="col-md-4">
                    <p><strong>X-Forwarded-For:</strong></p>
                    <code><?php echo htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'Not set'); ?></code>
                </div>
                <div class="col-md-4">
                    <p><strong>User-Agent:</strong></p>
                    <code class="small"><?php echo htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'] ?? 'Not set', 0, 50)) . '...'; ?></code>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Open Redirect via HTTP Headers</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Sources:</strong> HTTP_REFERER, X-Forwarded-For, return_url parameter</li>
                        <li><strong>Method:</strong> Header manipulation</li>
                        <li><strong>Issue:</strong> Trusting unvalidated header values for redirects</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Use these methods to test the vulnerability:</p>
                    
                    <h6>Method 1: Referer Header</h6>
                    <pre><code>curl -H "Referer: https://evil.com" "http://localhost/redirect/2.php"</code></pre>
                    
                    <h6>Method 2: X-Forwarded-For Header</h6>
                    <pre><code>curl -H "X-Forwarded-For: https://evil.com" "http://localhost/redirect/2.php"</code></pre>
                    
                    <h6>Method 3: return_url Parameter</h6>
                    <pre><code>?return_url=https://evil.com</code></pre>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Attack Scenarios</h5>
            <ul>
                <li>CSRF attacks by manipulating Referer headers</li>
                <li>Bypassing security controls through header injection</li>
                <li>Social engineering attacks using trusted domains</li>
                <li>Session fixation attacks</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Never trust HTTP headers for redirect URLs</li>
                    <li>Implement strict whitelist validation for all redirect sources</li>
                    <li>Use server-side session storage for redirect URLs</li>
                    <li>Validate and sanitize all user inputs, including headers</li>
                    <li>Consider using CSRF tokens for redirect functionality</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
