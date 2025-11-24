<?php
// Lab 4: Meta Refresh Open Redirect
// Vulnerability: Using meta refresh for redirects without validation

$redirect_url = $_GET['url'] ?? '';
$delay = $_GET['delay'] ?? 3;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Redirect Lab 4 - Meta Refresh</title>
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

        .meta-code {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-green);
        }

        .countdown {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-red);
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

        .alert-warning {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
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
            <h1 class="hero-title">Open Redirect Lab 4</h1>
            <p class="hero-subtitle">Meta Refresh Redirect</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates open redirect vulnerabilities that occur when HTML meta refresh tags are used for redirects without proper validation of the target URL.</p>
            <p><strong>Objective:</strong> Test meta refresh-based redirects and understand how HTML-based redirects can be exploited.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
$redirect_url = $_GET['url'] ?? '';
$delay = $_GET['delay'] ?? 3;

// Vulnerable: No validation of the redirect URL
// HTML will generate:
// &lt;meta http-equiv="refresh" content="$delay;url=$redirect_url"&gt;</pre>
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
                                <label for="url" class="form-label">Redirect URL</label>
                                <input class="form-control" type="text" placeholder="https://example.com" 
                                       aria-label="Redirect URL" name="url" 
                                       value="<?php echo htmlspecialchars($redirect_url); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="delay" class="form-label">Delay (seconds)</label>
                                <input class="form-control" type="number" min="1" max="10" 
                                       aria-label="Delay" name="delay" 
                                       value="<?php echo htmlspecialchars($delay); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Test Redirect</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($redirect_url)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-arrow-return-right me-2"></i>Current Redirect Settings
            </div>
            <div class="card-body">
                <p><strong>URL:</strong> <code><?php echo htmlspecialchars($redirect_url); ?></code></p>
                <p><strong>Delay:</strong> <code><?php echo htmlspecialchars($delay); ?> seconds</code></p>
                <p class="text-muted">The redirect will happen automatically after the specified delay.</p>
            </div>
        </div>

        <div class="meta-code">
            <h5><i class="bi bi-code-slash me-2"></i>Meta Refresh Code Being Generated</h5>
            <pre><code>&lt;meta http-equiv="refresh" content="<?php echo htmlspecialchars($delay); ?>;url=<?php echo htmlspecialchars($redirect_url); ?>"&gt;</code></pre>
        </div>

        <div class="alert alert-warning">
            <h5><i class="bi bi-arrow-clockwise me-2"></i>Redirect in Progress...</h5>
            <p>You will be redirected to: <strong><?php echo htmlspecialchars($redirect_url); ?></strong></p>
            <p>Redirecting in <span class="countdown" id="countdown"><?php echo $delay; ?></span> seconds...</p>
            <button class="btn btn-sm btn-outline-danger" onclick="cancelRedirect()">Cancel Redirect</button>
        </div>

        <!-- Vulnerable meta refresh tag -->
        <meta http-equiv="refresh" content="<?php echo intval($delay); ?>;url=<?php echo htmlspecialchars($redirect_url); ?>">

        <script>
            let countdown = <?php echo intval($delay); ?>;
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                }
            }, 1000);
            
            function cancelRedirect() {
                clearInterval(timer);
                document.querySelector('meta[http-equiv="refresh"]').remove();
                document.querySelector('.alert-warning').innerHTML = '<h5><i class="bi bi-check-circle me-2"></i>Redirect Cancelled</h5><p>You can now safely navigate away from this page.</p>';
            }
        </script>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Meta Refresh Open Redirect</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Parameters:</strong> <code>url</code>, <code>delay</code></li>
                        <li><strong>Method:</strong> HTML meta refresh tag</li>
                        <li><strong>Issue:</strong> Client-side redirect without URL validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these URLs to test the vulnerability:</p>
                    <ul>
                        <li><code>?url=https://evil.com&delay=1</code></li>
                        <li><code>?url=//evil.com&delay=2</code></li>
                        <li><code>?url=javascript:alert('XSS')&delay=1</code></li>
                        <li><code>?url=data:text/html,<script>alert('XSS')</script>&delay=1</code></li>
                        <li><code>?url=ftp://evil.com&delay=3</code></li>
                        <li><code>?url=file:///etc/passwd&delay=2</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Attack Scenarios</h5>
            <ul>
                <li>Phishing attacks with automatic redirects</li>
                <li>XSS attacks through javascript: protocol</li>
                <li>File access through file: protocol</li>
                <li>Bypassing browser security controls</li>
                <li>Social engineering with fake loading pages</li>
                <li>SEO manipulation through redirect chains</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Validate redirect URLs on the server-side before generating meta refresh tags</li>
                    <li>Use a whitelist of allowed domains for redirects</li>
                    <li>Implement Content Security Policy (CSP) to prevent javascript: protocol</li>
                    <li>Sanitize and encode user input before using in HTML</li>
                    <li>Consider using server-side redirects (HTTP 302) instead of meta refresh</li>
                    <li>Add user confirmation for redirects when possible</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
