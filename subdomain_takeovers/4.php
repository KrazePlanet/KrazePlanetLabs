<?php
// Lab 4: Advanced Subdomain Takeover
// Vulnerability: Advanced subdomain takeover vulnerabilities

session_start();

$message = '';
$subdomain = $_GET['subdomain'] ?? '';
$dns_records = [];
$takeover_status = 'unknown';
$advanced_techniques = [
    'wildcard_takeover' => 'Wildcard Subdomain Takeover',
    'dns_rebinding' => 'DNS Rebinding Attack',
    'subdomain_enumeration' => 'Subdomain Enumeration',
    'dns_poisoning' => 'DNS Poisoning',
    'dns_tunneling' => 'DNS Tunneling',
    'dns_exfiltration' => 'DNS Exfiltration'
];

// Simulate advanced takeover detection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_advanced') {
        $subdomain = $_POST['subdomain'] ?? '';
        $technique = $_POST['technique'] ?? '';
        
        if (!empty($subdomain) && !empty($technique)) {
            // Simulate advanced takeover detection
            $dns_records = [
                'A' => ['192.168.1.100'],
                'CNAME' => ['vulnerable-service.example.com'],
                'TXT' => ['v=spf1 include:_spf.google.com ~all'],
                'MX' => ['mail.example.com']
            ];
            
            if ($technique === 'wildcard_takeover') {
                $takeover_status = 'vulnerable';
                $message = '<div class="alert alert-danger">⚠️ VULNERABLE: Wildcard subdomain takeover possible!</div>';
            } elseif ($technique === 'dns_rebinding') {
                $takeover_status = 'vulnerable';
                $message = '<div class="alert alert-danger">⚠️ VULNERABLE: DNS rebinding attack possible!</div>';
            } else {
                $takeover_status = 'secure';
                $message = '<div class="alert alert-success">✅ SECURE: Advanced technique not applicable.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please select a technique and enter a subdomain.</div>';
        }
    } elseif ($action === 'simulate_advanced_takeover') {
        $subdomain = $_POST['subdomain'] ?? '';
        $technique = $_POST['technique'] ?? '';
        
        if (!empty($subdomain) && !empty($technique)) {
            $message = '<div class="alert alert-warning">⚠️ Advanced takeover simulation initiated for: ' . htmlspecialchars($subdomain) . ' (' . htmlspecialchars($technique) . ')</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Advanced Subdomain Takeover - Subdomain Takeover Labs</title>
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

        .result-display {
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

        .advanced-warning {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .vulnerable-form {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .dns-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .dns-record {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .advanced-techniques {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .technique-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
        }

        .technique-title {
            color: var(--accent-green);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .technique-demo {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 4px;
            padding: 0.5rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .status-vulnerable {
            color: var(--accent-red);
            font-weight: bold;
        }

        .status-secure {
            color: var(--accent-green);
            font-weight: bold;
        }

        .status-unknown {
            color: var(--accent-orange);
            font-weight: bold;
        }

        .technique-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .technique-item {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Subdomain Takeover Labs
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
            <h1 class="hero-title">Lab 4: Advanced Subdomain Takeover</h1>
            <p class="hero-subtitle">Advanced subdomain takeover vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced subdomain takeover vulnerabilities including wildcard takeovers, DNS rebinding, subdomain enumeration, and other sophisticated techniques.</p>
            <p><strong>Objective:</strong> Understand how advanced subdomain takeover attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-gear me-2"></i>Advanced Takeover Scanner
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Advanced Techniques</h5>
                            <p>This tool checks for advanced subdomain takeover techniques:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_advanced">
                                <div class="mb-3">
                                    <label for="subdomain" class="form-label">Subdomain to Check</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain" placeholder="vulnerable.example.com" value="<?php echo htmlspecialchars($subdomain); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="technique" class="form-label">Advanced Technique</label>
                                    <select class="form-select" id="technique" name="technique" required>
                                        <option value="">Select technique</option>
                                        <?php foreach ($advanced_techniques as $key => $name): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Advanced</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-gear me-2"></i>Advanced Takeover Tester
                    </div>
                    <div class="card-body">
                        <div class="advanced-warning">
                            <h5>⚠️ Advanced Takeover Warning</h5>
                            <p>This lab demonstrates advanced subdomain takeover vulnerabilities:</p>
                            <ul>
                                <li><code>Wildcard Takeover</code> - Wildcard subdomain takeover</li>
                                <li><code>DNS Rebinding</code> - DNS rebinding attacks</li>
                                <li><code>Subdomain Enumeration</code> - Advanced enumeration</li>
                                <li><code>DNS Poisoning</code> - DNS poisoning attacks</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Advanced Techniques</h5>
                            <p>Available advanced techniques:</p>
                            <ul>
                                <li><code>Wildcard Takeover</code> - Wildcard subdomain takeover</li>
                                <li><code>DNS Rebinding</code> - DNS rebinding attack</li>
                                <li><code>Subdomain Enumeration</code> - Subdomain enumeration</li>
                                <li><code>DNS Poisoning</code> - DNS poisoning</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testAdvancedTakeover()" class="btn btn-primary">Test Advanced Takeover</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-gear me-2"></i>Advanced Techniques
                    </div>
                    <div class="card-body">
                        <div class="technique-list">
                            <h5>Available Advanced Techniques:</h5>
                            <?php foreach ($advanced_techniques as $key => $name): ?>
                            <div class="technique-item">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <strong><?php echo $name; ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-info"><?php echo $key; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-warning">High Risk</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($dns_records)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-2 me-2"></i>DNS Records for <?php echo htmlspecialchars($subdomain); ?>
                    </div>
                    <div class="card-body">
                        <div class="dns-display">
                            <?php foreach ($dns_records as $type => $records): ?>
                            <div class="dns-record">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <strong><?php echo $type; ?> Record</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <?php foreach ($records as $record): ?>
                                        <span class="text-info"><?php echo htmlspecialchars($record); ?></span><br>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">TTL: 300</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-3">
                            <h5>Takeover Status: <span class="status-<?php echo $takeover_status; ?>"><?php echo ucfirst($takeover_status); ?></span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-code-square me-2"></i>Advanced Subdomain Takeover Techniques
                    </div>
                    <div class="card-body">
                        <div class="advanced-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Wildcard Takeover</div>
                                <div class="technique-demo"># Wildcard subdomain takeover
# Check for wildcard DNS
dig *.example.com
# If wildcard exists, any subdomain
# can be taken over</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">DNS Rebinding</div>
                                <div class="technique-demo"># DNS rebinding attack
# 1. Set up malicious DNS server
# 2. Point subdomain to malicious IP
# 3. Serve malicious content
# 4. Bypass same-origin policy</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Subdomain Enumeration</div>
                                <div class="technique-demo"># Advanced subdomain enumeration
# - Dictionary attacks
# - Certificate transparency
# - DNS bruteforcing
# - Search engine dorking</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">DNS Poisoning</div>
                                <div class="technique-demo"># DNS poisoning attack
# 1. Poison DNS cache
# 2. Point subdomain to malicious IP
# 3. Serve malicious content
# 4. Capture credentials</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">DNS Tunneling</div>
                                <div class="technique-demo"># DNS tunneling
# 1. Set up DNS tunnel
# 2. Exfiltrate data via DNS
# 3. Bypass network restrictions
# 4. Maintain persistence</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">DNS Exfiltration</div>
                                <div class="technique-demo"># DNS exfiltration
# 1. Encode data in DNS queries
# 2. Send to malicious DNS server
# 3. Exfiltrate sensitive data
# 4. Bypass network monitoring</div>
                            </div>
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
                        <li><strong>Type:</strong> Advanced Subdomain Takeover</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Multiple advanced techniques</li>
                        <li><strong>Issue:</strong> Complex vulnerabilities</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Wildcard Takeover:</strong> Wildcard subdomain takeover</li>
                        <li><strong>DNS Rebinding:</strong> DNS rebinding attacks</li>
                        <li><strong>Subdomain Enumeration:</strong> Advanced enumeration</li>
                        <li><strong>DNS Poisoning:</strong> DNS poisoning attacks</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Advanced Subdomain Takeover Examples</h5>
            <p>Use these techniques to exploit advanced subdomain takeover vulnerabilities:</p>
            
            <h6>1. Wildcard Subdomain Takeover:</h6>
            <div class="code-block"># Check for wildcard DNS
dig *.example.com
nslookup *.example.com

# If wildcard exists:
# 1. Any subdomain can be taken over
# 2. Use random subdomains
# 3. Deploy malicious content
# 4. Verify takeover

# Example:
# Check if *.example.com resolves
# If yes, any subdomain like:
# - random123.example.com
# - test.example.com
# - admin.example.com
# Can be taken over</div>

            <h6>2. DNS Rebinding Attack:</h6>
            <div class="code-block"># DNS rebinding attack
# 1. Set up malicious DNS server
# 2. Point subdomain to malicious IP
# 3. Serve malicious content
# 4. Bypass same-origin policy

# Example:
# Set up DNS server that returns:
# - First query: legitimate IP
# - Second query: malicious IP
# This bypasses same-origin policy

# Malicious DNS server:
# Query 1: subdomain.example.com -> 1.2.3.4
# Query 2: subdomain.example.com -> 5.6.7.8</div>

            <h6>3. Advanced Subdomain Enumeration:</h6>
            <div class="code-block"># Advanced subdomain enumeration
# Dictionary attacks
subfinder -d example.com -w wordlist.txt

# Certificate transparency
crt.sh -q example.com

# DNS bruteforcing
dnsrecon -d example.com -t brt

# Search engine dorking
site:example.com inurl:subdomain

# Use tools like:
# - subfinder
# - amass
# - assetfinder
# - findomain
# - crt.sh</div>

            <h6>4. DNS Poisoning Attack:</h6>
            <div class="code-block"># DNS poisoning attack
# 1. Poison DNS cache
# 2. Point subdomain to malicious IP
# 3. Serve malicious content
# 4. Capture credentials

# Example:
# Poison DNS cache to point:
# subdomain.example.com -> 1.2.3.4
# Serve phishing page at 1.2.3.4
# Capture user credentials</div>

            <h6>5. DNS Tunneling:</h6>
            <div class="code-block"># DNS tunneling
# 1. Set up DNS tunnel
# 2. Exfiltrate data via DNS
# 3. Bypass network restrictions
# 4. Maintain persistence

# Example:
# Use tools like:
# - dns2tcp
# - iodine
# - dnscat2

# Set up tunnel:
dnscat2 server --dns domain=example.com
dnscat2 client --dns domain=example.com</div>

            <h6>6. DNS Exfiltration:</h6>
            <div class="code-block"># DNS exfiltration
# 1. Encode data in DNS queries
# 2. Send to malicious DNS server
# 3. Exfiltrate sensitive data
# 4. Bypass network monitoring

# Example:
# Encode data in subdomain:
# data.example.com
# sensitive.example.com
# credentials.example.com

# Use base64 encoding:
# echo "sensitive data" | base64
# Send as subdomain query</div>

            <h6>7. Subdomain Takeover via CDN:</h6>
            <div class="code-block"># CDN subdomain takeover
# 1. Check CDN configuration
# 2. Find misconfigured CDN
# 3. Take over CDN subdomain
# 4. Serve malicious content

# Example:
# Check Cloudflare configuration
# Find misconfigured CDN
# Take over CDN subdomain
# Serve malicious content</div>

            <h6>8. Subdomain Takeover via Load Balancer:</h6>
            <div class="code-block"># Load balancer takeover
# 1. Check load balancer config
# 2. Find misconfigured LB
# 3. Take over LB subdomain
# 4. Serve malicious content

# Example:
# Check AWS ALB configuration
# Find misconfigured load balancer
# Take over LB subdomain
# Serve malicious content</div>

            <h6>9. Subdomain Takeover via API Gateway:</h6>
            <div class="code-block"># API Gateway takeover
# 1. Check API Gateway config
# 2. Find misconfigured gateway
# 3. Take over gateway subdomain
# 4. Serve malicious content

# Example:
# Check AWS API Gateway
# Find misconfigured gateway
# Take over gateway subdomain
# Serve malicious content</div>

            <h6>10. Subdomain Takeover via WAF:</h6>
            <div class="code-block"># WAF subdomain takeover
# 1. Check WAF configuration
# 2. Find misconfigured WAF
# 3. Take over WAF subdomain
# 4. Serve malicious content

# Example:
# Check Cloudflare WAF
# Find misconfigured WAF
# Take over WAF subdomain
# Serve malicious content</div>

            <h6>11. Subdomain Takeover via DDoS Protection:</h6>
            <div class="code-block"># DDoS protection takeover
# 1. Check DDoS protection config
# 2. Find misconfigured protection
# 3. Take over protection subdomain
# 4. Serve malicious content

# Example:
# Check AWS Shield
# Find misconfigured protection
# Take over protection subdomain
# Serve malicious content</div>

            <h6>12. Subdomain Takeover via SSL Certificate:</h6>
            <div class="code-block"># SSL certificate takeover
# 1. Check SSL certificate
# 2. Find misconfigured cert
# 3. Take over cert subdomain
# 4. Serve malicious content

# Example:
# Check SSL certificate
# Find misconfigured cert
# Take over cert subdomain
# Serve malicious content</div>

            <h6>13. Subdomain Takeover via Email Service:</h6>
            <div class="code-block"># Email service takeover
# 1. Check email service config
# 2. Find misconfigured service
# 3. Take over service subdomain
# 4. Serve malicious content

# Example:
# Check SendGrid configuration
# Find misconfigured service
# Take over service subdomain
# Serve malicious content</div>

            <h6>14. Subdomain Takeover via Analytics:</h6>
            <div class="code-block"># Analytics takeover
# 1. Check analytics config
# 2. Find misconfigured analytics
# 3. Take over analytics subdomain
# 4. Serve malicious content

# Example:
# Check Google Analytics
# Find misconfigured analytics
# Take over analytics subdomain
# Serve malicious content</div>

            <h6>15. Subdomain Takeover via Monitoring:</h6>
            <div class="code-block"># Monitoring takeover
# 1. Check monitoring config
# 2. Find misconfigured monitoring
# 3. Take over monitoring subdomain
# 4. Serve malicious content

# Example:
# Check New Relic configuration
# Find misconfigured monitoring
# Take over monitoring subdomain
# Serve malicious content</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete subdomain control and traffic redirection</li>
                <li>Advanced phishing attacks and credential theft</li>
                <li>Brand reputation damage and trust issues</li>
                <li>Compliance violations and legal issues</li>
                <li>Data exfiltration and privacy breaches</li>
                <li>Advanced persistent threats and APT attacks</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive subdomain monitoring</li>
                    <li>Use secure DNS configurations and controls</li>
                    <li>Regular DNS security audits and assessments</li>
                    <li>Implement proper DNS security controls</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual subdomain activity</li>
                    <li>Implement proper subdomain validation</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use advanced subdomain takeover detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testAdvancedTakeover() {
            alert('Advanced Takeover test initiated. Try the advanced takeover techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
