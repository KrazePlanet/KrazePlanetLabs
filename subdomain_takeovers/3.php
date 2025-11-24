<?php
// Lab 3: NS Subdomain Takeover
// Vulnerability: NS subdomain takeover vulnerabilities

session_start();

$message = '';
$subdomain = $_GET['subdomain'] ?? '';
$ns_records = [];
$takeover_status = 'unknown';
$vulnerable_nameservers = [
    'ns1.digitalocean.com' => 'DigitalOcean DNS',
    'ns2.digitalocean.com' => 'DigitalOcean DNS',
    'ns1.awsdns.com' => 'AWS Route 53',
    'ns2.awsdns.com' => 'AWS Route 53',
    'ns1.cloudflare.com' => 'Cloudflare DNS',
    'ns2.cloudflare.com' => 'Cloudflare DNS',
    'ns1.google.com' => 'Google Cloud DNS',
    'ns2.google.com' => 'Google Cloud DNS'
];

// Simulate NS lookup and takeover detection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_ns') {
        $subdomain = $_POST['subdomain'] ?? '';
        
        if (!empty($subdomain)) {
            // Simulate NS lookup
            $ns_records = [
                'NS' => ['ns1.vulnerable-dns.com', 'ns2.vulnerable-dns.com'],
                'A' => ['192.168.1.100'],
                'TXT' => ['v=spf1 include:_spf.google.com ~all']
            ];
            
            // Simulate takeover detection
            $ns_targets = $ns_records['NS'];
            $is_vulnerable = false;
            $vulnerable_nameserver = '';
            
            foreach ($ns_targets as $ns) {
                foreach ($vulnerable_nameservers as $vuln_ns => $name) {
                    if (strpos($ns, $vuln_ns) !== false) {
                        $is_vulnerable = true;
                        $vulnerable_nameserver = $name;
                        break 2;
                    }
                }
            }
            
            if ($is_vulnerable) {
                $takeover_status = 'vulnerable';
                $message = '<div class="alert alert-danger">⚠️ VULNERABLE: NS points to ' . $vulnerable_nameserver . ' - Can be taken over!</div>';
            } else {
                $takeover_status = 'secure';
                $message = '<div class="alert alert-success">✅ SECURE: NS points to secure nameservers.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please enter a subdomain to check.</div>';
        }
    } elseif ($action === 'simulate_ns_takeover') {
        $subdomain = $_POST['subdomain'] ?? '';
        $nameserver_type = $_POST['nameserver_type'] ?? '';
        
        if (!empty($subdomain) && !empty($nameserver_type)) {
            $message = '<div class="alert alert-warning">⚠️ NS takeover simulation initiated for: ' . htmlspecialchars($subdomain) . ' (' . htmlspecialchars($nameserver_type) . ')</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: NS Subdomain Takeover - Subdomain Takeover Labs</title>
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

        .ns-warning {
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

        .ns-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .ns-record {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .ns-takeover-techniques {
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

        .nameserver-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .nameserver-item {
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
            <h1 class="hero-title">Lab 3: NS Subdomain Takeover</h1>
            <p class="hero-subtitle">NS subdomain takeover vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates NS subdomain takeover vulnerabilities where attackers can take control of subdomains by hijacking their nameservers.</p>
            <p><strong>Objective:</strong> Understand how NS subdomain takeover attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-diagram-2 me-2"></i>NS Takeover Scanner
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check NS Records</h5>
                            <p>This tool checks if a subdomain's NS records are vulnerable to takeover:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_ns">
                                <div class="mb-3">
                                    <label for="subdomain" class="form-label">Subdomain to Check</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain" placeholder="vulnerable.example.com" value="<?php echo htmlspecialchars($subdomain); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check NS</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>NS Takeover Tester
                    </div>
                    <div class="card-body">
                        <div class="ns-warning">
                            <h5>⚠️ NS Takeover Warning</h5>
                            <p>This lab demonstrates NS subdomain takeover vulnerabilities:</p>
                            <ul>
                                <li><code>NS Misconfiguration</code> - Pointing to vulnerable nameservers</li>
                                <li><code>Nameserver Hijacking</code> - Hijacking nameserver control</li>
                                <li><code>Weak Authentication</code> - Weak nameserver authentication</li>
                                <li><code>No Validation</code> - No NS validation</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Vulnerable Nameservers</h5>
                            <p>Common vulnerable nameservers:</p>
                            <ul>
                                <li><code>ns1.digitalocean.com</code> - DigitalOcean DNS</li>
                                <li><code>ns1.awsdns.com</code> - AWS Route 53</li>
                                <li><code>ns1.cloudflare.com</code> - Cloudflare DNS</li>
                                <li><code>ns1.google.com</code> - Google Cloud DNS</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testNsTakeover()" class="btn btn-primary">Test NS Takeover</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-server me-2"></i>Vulnerable Nameservers
                    </div>
                    <div class="card-body">
                        <div class="nameserver-list">
                            <h5>Common Vulnerable Nameservers:</h5>
                            <?php foreach ($vulnerable_nameservers as $ns => $name): ?>
                            <div class="nameserver-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <strong><?php echo $name; ?></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-info"><?php echo $ns; ?></span>
                                    </div>
                                    <div class="col-md-4">
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

        <?php if (!empty($ns_records)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-2 me-2"></i>NS Records for <?php echo htmlspecialchars($subdomain); ?>
                    </div>
                    <div class="card-body">
                        <div class="ns-display">
                            <?php foreach ($ns_records as $type => $records): ?>
                            <div class="ns-record">
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
                        <i class="bi bi-code-square me-2"></i>NS Subdomain Takeover Techniques
                    </div>
                    <div class="card-body">
                        <div class="ns-takeover-techniques">
                            <div class="technique-card">
                                <div class="technique-title">NS Enumeration</div>
                                <div class="technique-demo"># Enumerate NS records
dig subdomain.example.com NS
nslookup -type=NS subdomain.example.com
host -t NS subdomain.example.com</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Nameserver Detection</div>
                                <div class="technique-demo"># Detect vulnerable nameservers
# Look for patterns like:
# - ns1.digitalocean.com
# - ns1.awsdns.com
# - ns1.cloudflare.com
# - ns1.google.com</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Nameserver Verification</div>
                                <div class="technique-demo"># Verify nameserver control
dig @ns1.vulnerable.com subdomain.example.com
# Check if nameserver responds
# and what records it serves</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Nameserver Hijacking</div>
                                <div class="technique-demo"># Hijack nameserver control
# 1. Register vulnerable nameserver
# 2. Point subdomain NS to it
# 3. Serve malicious records
# 4. Verify takeover</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">DNS Record Manipulation</div>
                                <div class="technique-demo"># Manipulate DNS records
# - Point A records to malicious IPs
# - Create malicious CNAME records
# - Serve phishing content
# - Redirect traffic</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Impact Assessment</div>
                                <div class="technique-demo"># Assess takeover impact
# - Complete DNS control
# - Traffic redirection
# - Phishing attacks
# - Credential theft</div>
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
                        <li><strong>Type:</strong> NS Subdomain Takeover</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Nameserver hijacking</li>
                        <li><strong>Issue:</strong> NS points to vulnerable nameservers</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>NS Enumeration:</strong> Find NS records</li>
                        <li><strong>Nameserver Detection:</strong> Detect vulnerable nameservers</li>
                        <li><strong>Nameserver Verification:</strong> Verify nameserver control</li>
                        <li><strong>Nameserver Hijacking:</strong> Hijack nameserver control</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>NS Subdomain Takeover Examples</h5>
            <p>Use these techniques to exploit NS subdomain takeover vulnerabilities:</p>
            
            <h6>1. NS Enumeration:</h6>
            <div class="code-block"># Enumerate NS records
dig subdomain.example.com NS
nslookup -type=NS subdomain.example.com
host -t NS subdomain.example.com

# Use tools like:
# - dig
# - nslookup
# - host
# - dnsrecon</div>

            <h6>2. DigitalOcean DNS Takeover:</h6>
            <div class="code-block"># Check DigitalOcean NS
dig subdomain.example.com NS
# If NS points to ns1.digitalocean.com

# Check if nameserver is vulnerable
dig @ns1.digitalocean.com subdomain.example.com

# If vulnerable:
# 1. Register DigitalOcean account
# 2. Create DNS zone for subdomain
# 3. Point NS to your nameservers
# 4. Serve malicious records

# Example:
# Create DNS zone in DigitalOcean
# Point subdomain NS to your nameservers
# Serve malicious A records</div>

            <h6>3. AWS Route 53 Takeover:</h6>
            <div class="code-block"># Check AWS Route 53 NS
dig subdomain.example.com NS
# If NS points to ns1.awsdns.com

# Check if nameserver is vulnerable
dig @ns1.awsdns.com subdomain.example.com

# If vulnerable:
# 1. Register AWS account
# 2. Create hosted zone
# 3. Point NS to your nameservers
# 4. Serve malicious records

# Example:
aws route53 create-hosted-zone --name subdomain.example.com
# Update NS records to point to your nameservers</div>

            <h6>4. Cloudflare DNS Takeover:</h6>
            <div class="code-block"># Check Cloudflare NS
dig subdomain.example.com NS
# If NS points to ns1.cloudflare.com

# Check if nameserver is vulnerable
dig @ns1.cloudflare.com subdomain.example.com

# If vulnerable:
# 1. Register Cloudflare account
# 2. Add domain to Cloudflare
# 3. Point NS to Cloudflare nameservers
# 4. Serve malicious records

# Example:
# Add domain to Cloudflare
# Update NS records to Cloudflare nameservers
# Configure DNS records</div>

            <h6>5. Google Cloud DNS Takeover:</h6>
            <div class="code-block"># Check Google Cloud NS
dig subdomain.example.com NS
# If NS points to ns1.google.com

# Check if nameserver is vulnerable
dig @ns1.google.com subdomain.example.com

# If vulnerable:
# 1. Register Google Cloud account
# 2. Create DNS zone
# 3. Point NS to your nameservers
# 4. Serve malicious records

# Example:
gcloud dns managed-zones create subdomain-zone --dns-name=subdomain.example.com
# Update NS records to point to your nameservers</div>

            <h6>6. Nameserver Verification:</h6>
            <div class="code-block"># Verify nameserver control
dig @ns1.vulnerable.com subdomain.example.com
dig @ns2.vulnerable.com subdomain.example.com

# Check what records nameserver serves
dig @ns1.vulnerable.com subdomain.example.com ANY

# Look for:
# - A records
# - CNAME records
# - MX records
# - TXT records</div>

            <h6>7. DNS Record Manipulation:</h6>
            <div class="code-block"># Manipulate DNS records
# Point A records to malicious IPs
# Create malicious CNAME records
# Serve phishing content
# Redirect traffic

# Example malicious records:
# A record: 1.2.3.4 (malicious IP)
# CNAME: phishing-site.com
# MX record: malicious-mail-server.com</div>

            <h6>8. Traffic Redirection:</h6>
            <div class="code-block"># Redirect traffic to malicious sites
# 1. Point A records to malicious IPs
# 2. Create CNAME records to phishing sites
# 3. Serve malicious content
# 4. Capture credentials

# Example:
# A record: subdomain.example.com -> 1.2.3.4
# CNAME: www.subdomain.example.com -> phishing-site.com</div>

            <h6>9. Phishing Attack Setup:</h6>
            <div class="code-block"># Set up phishing attack
# 1. Create phishing site
# 2. Point subdomain to phishing site
# 3. Serve convincing content
# 4. Capture credentials

# Example:
# Create phishing page that looks like legitimate site
# Point subdomain.example.com to phishing site
# Serve convincing login form
# Capture user credentials</div>

            <h6>10. Credential Harvesting:</h6>
            <div class="code-block"># Harvest credentials
# 1. Create fake login page
# 2. Point subdomain to fake page
# 3. Serve convincing content
# 4. Capture and store credentials

# Example:
# Create fake login page
# Point subdomain.example.com to fake page
# Serve convincing login form
# Capture and store credentials</div>

            <h6>11. Automated NS Takeover Detection:</h6>
            <div class="code-block"># Use automated tools
# - subjack
# - takeover
# - subzy
# - subdomain-takeover

# Example with subjack:
subjack -w subdomains.txt -t 100 -o results.txt

# Example with takeover:
takeover -l subdomains.txt -t 10

# Example with subzy:
subzy run --targets subdomains.txt</div>

            <h6>12. Manual NS Verification:</h6>
            <div class="code-block"># Manual verification steps
# 1. Check NS records
# 2. Verify nameserver control
# 3. Test for vulnerable nameservers
# 4. Attempt nameserver hijacking
# 5. Confirm takeover
# 6. Document findings

# Example verification:
dig subdomain.example.com NS
dig @ns1.vulnerable.com subdomain.example.com
# Check if nameserver responds and what records it serves</div>

            <h6>13. NS Takeover Prevention:</h6>
            <div class="code-block"># Prevent NS takeovers
# 1. Use secure nameservers
# 2. Implement DNS monitoring
# 3. Regular security audits
# 4. DNS security controls
# 5. Subdomain monitoring

# Example monitoring:
# - Set up DNS monitoring
# - Monitor for NS changes
# - Alert on suspicious activity
# - Regular security scans</div>

            <h6>14. NS Takeover Impact:</h6>
            <div class="code-block"># Assess takeover impact
# - Complete DNS control
# - Traffic redirection
# - Phishing attacks
# - Credential theft
# - Brand reputation damage

# Document findings:
# - Vulnerable NS records
# - Affected nameservers
# - Potential impact
# - Remediation steps</div>

            <h6>15. NS Takeover Remediation:</h6>
            <div class="code-block"># Remediate NS takeovers
# 1. Change NS records to secure nameservers
# 2. Implement proper monitoring
# 3. Regular security audits
# 4. DNS security controls
# 5. Subdomain monitoring

# Example remediation:
# - Change NS to secure nameservers
# - Set up monitoring
# - Regular security scans
# - DNS security controls</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete DNS control and traffic redirection</li>
                <li>Phishing attacks and credential theft</li>
                <li>Brand reputation damage and trust issues</li>
                <li>Compliance violations and legal issues</li>
                <li>Data exfiltration and privacy breaches</li>
                <li>SEO manipulation and search engine abuse</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use secure nameservers and DNS providers</li>
                    <li>Implement proper DNS monitoring and alerting</li>
                    <li>Regular DNS security audits and assessments</li>
                    <li>Implement proper DNS security controls</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual DNS activity</li>
                    <li>Implement proper subdomain monitoring</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use NS takeover detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testNsTakeover() {
            alert('NS Takeover test initiated. Try the takeover techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
