<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: URL Fragment DOM XSS</title>
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

        .demo-section {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .url-display {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            word-break: break-all;
        }

        .fragment-display {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 3px solid var(--accent-orange);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to DOM XSS Labs
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
            <h1 class="hero-title">Lab 1: URL Fragment DOM XSS</h1>
            <p class="hero-subtitle">Client-side XSS using URL fragments (#)</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a DOM XSS vulnerability where user input from the URL fragment (the part after #) is directly inserted into the DOM without proper sanitization.</p>
            <p><strong>Objective:</strong> Inject a DOM XSS payload using the URL fragment that will execute when the page loads.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable JavaScript Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable code - reads from URL fragment
window.onload = function() {
    // Get the fragment from the URL
    var fragment = window.location.hash.substring(1);
    
    // Vulnerable: Direct insertion into DOM
    if (fragment) {
        document.getElementById('output').innerHTML = 
            "Welcome, " + fragment + "!";
    }
};

// Alternative vulnerable code
document.addEventListener('DOMContentLoaded', function() {
    var hash = location.hash.slice(1);
    if (hash) {
        document.querySelector('#message').innerHTML = 
            "Hello " + hash + "!";
    }
});</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Live Demo
                    </div>
                    <div class="card-body">
                        <div class="demo-section">
                            <h6><i class="bi bi-link me-2"></i>Current URL:</h6>
                            <div class="url-display" id="currentUrl">
                                <script>
                                    document.getElementById('currentUrl').textContent = window.location.href;
                                </script>
                            </div>
                            
                            <h6><i class="bi bi-hash me-2"></i>URL Fragment:</h6>
                            <div class="fragment-display" id="fragmentDisplay">
                                <script>
                                    var fragment = window.location.hash.substring(1);
                                    document.getElementById('fragmentDisplay').textContent = fragment || 'No fragment present';
                                </script>
                            </div>
                            
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Vulnerable Output:</h6>
                            <div class="fragment-display" id="output">
                                <script>
                                    // Vulnerable code - this is the actual vulnerability
                                    window.onload = function() {
                                        var fragment = window.location.hash.substring(1);
                                        if (fragment) {
                                            document.getElementById('output').innerHTML = 
                                                "Welcome, " + fragment + "!";
                                        } else {
                                            document.getElementById('output').innerHTML = 
                                                "No fragment provided. Try adding #YourName to the URL!";
                                        }
                                    };
                                </script>
                            </div>
                        </div>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-target me-2"></i>Test URLs:</h6>
                            <p>Try these URLs in your browser:</p>
                            <ul>
                                <li><code>#John</code> - Basic test</li>
                                <li><code>#&lt;script&gt;alert('XSS')&lt;/script&gt;</code> - XSS payload</li>
                                <li><code>#&lt;img src=x onerror=alert('XSS')&gt;</code> - Image XSS</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> DOM XSS via URL Fragment</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Source:</strong> <code>window.location.hash</code></li>
                        <li><strong>Sink:</strong> <code>innerHTML</code></li>
                        <li><strong>Trigger:</strong> Page load with fragment in URL</li>
                        <li><strong>Issue:</strong> Direct insertion of user input into DOM</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Add these to the end of the URL after #:</p>
                    <ul>
                        <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                        <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>Example URL:</strong></p>
                    <code>1.php#&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Session hijacking by stealing cookies from URL fragments</li>
                <li>Credential theft through fake login forms</li>
                <li>Malware distribution via malicious URLs</li>
                <li>Phishing attacks with legitimate-looking URLs</li>
                <li>Bypassing client-side security controls</li>
                <li>Social engineering through URL manipulation</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use <code>textContent</code> instead of <code>innerHTML</code></li>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use Content Security Policy (CSP) headers</li>
                    <li>Avoid direct DOM manipulation with user input</li>
                    <li>Use safe DOM manipulation methods</li>
                    <li>Implement proper output encoding</li>
                    <li>Use a JavaScript security library like DOMPurify</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
