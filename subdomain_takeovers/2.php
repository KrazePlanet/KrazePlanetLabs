<?php
// Lab 2: CNAME Subdomain Takeover
// Vulnerability: CNAME subdomain takeover vulnerabilities

session_start();

$message = '';
$subdomain = $_GET['subdomain'] ?? '';
$cname_records = [];
$takeover_status = 'unknown';
$vulnerable_services = [
    's3.amazonaws.com' => 'AWS S3 Bucket',
    'herokuapp.com' => 'Heroku App',
    'github.io' => 'GitHub Pages',
    'netlify.app' => 'Netlify Site',
    'vercel.app' => 'Vercel Site',
    'firebaseapp.com' => 'Firebase Hosting',
    'cloudfront.net' => 'AWS CloudFront',
    'fastly.com' => 'Fastly CDN',
    'akamai.net' => 'Akamai CDN'
];

// Simulate CNAME lookup and takeover detection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_cname') {
        $subdomain = $_POST['subdomain'] ?? '';
        
        if (!empty($subdomain)) {
            // Simulate CNAME lookup
            $cname_records = [
                'CNAME' => ['vulnerable-service.s3.amazonaws.com'],
                'A' => ['192.168.1.100'],
                'TXT' => ['v=spf1 include:_spf.google.com ~all']
            ];
            
            // Simulate takeover detection
            $cname_target = $cname_records['CNAME'][0];
            $is_vulnerable = false;
            $vulnerable_service = '';
            
            foreach ($vulnerable_services as $service => $name) {
                if (strpos($cname_target, $service) !== false) {
                    $is_vulnerable = true;
                    $vulnerable_service = $name;
                    break;
                }
            }
            
            if ($is_vulnerable) {
                $takeover_status = 'vulnerable';
                $message = '<div class="alert alert-danger">⚠️ VULNERABLE: CNAME points to ' . $vulnerable_service . ' - Can be taken over!</div>';
            } else {
                $takeover_status = 'secure';
                $message = '<div class="alert alert-success">✅ SECURE: CNAME points to secure service.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please enter a subdomain to check.</div>';
        }
    } elseif ($action === 'simulate_cname_takeover') {
        $subdomain = $_POST['subdomain'] ?? '';
        $service_type = $_POST['service_type'] ?? '';
        
        if (!empty($subdomain) && !empty($service_type)) {
            $message = '<div class="alert alert-warning">⚠️ CNAME takeover simulation initiated for: ' . htmlspecialchars($subdomain) . ' (' . htmlspecialchars($service_type) . ')</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: CNAME Subdomain Takeover - Subdomain Takeover Labs</title>
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

        .cname-warning {
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

        .cname-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .cname-record {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .cname-takeover-techniques {
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

        .service-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .service-item {
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
            <h1 class="hero-title">Lab 2: CNAME Subdomain Takeover</h1>
            <p class="hero-subtitle">CNAME subdomain takeover vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CNAME subdomain takeover vulnerabilities where attackers can take control of subdomains that point to external services via CNAME records.</p>
            <p><strong>Objective:</strong> Understand how CNAME subdomain takeover attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-link-45deg me-2"></i>CNAME Takeover Scanner
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check CNAME Records</h5>
                            <p>This tool checks if a subdomain's CNAME record is vulnerable to takeover:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_cname">
                                <div class="mb-3">
                                    <label for="subdomain" class="form-label">Subdomain to Check</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain" placeholder="vulnerable.example.com" value="<?php echo htmlspecialchars($subdomain); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Check CNAME</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>CNAME Takeover Tester
                    </div>
                    <div class="card-body">
                        <div class="cname-warning">
                            <h5>⚠️ CNAME Takeover Warning</h5>
                            <p>This lab demonstrates CNAME subdomain takeover vulnerabilities:</p>
                            <ul>
                                <li><code>CNAME Misconfiguration</code> - Pointing to non-existent services</li>
                                <li><code>Service Deletion</code> - Services deleted but CNAME still points</li>
                                <li><code>Weak Authentication</code> - Weak service authentication</li>
                                <li><code>No Validation</code> - No CNAME validation</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Vulnerable Services</h5>
                            <p>Common vulnerable services:</p>
                            <ul>
                                <li><code>*.s3.amazonaws.com</code> - AWS S3 Buckets</li>
                                <li><code>*.herokuapp.com</code> - Heroku Apps</li>
                                <li><code>*.github.io</code> - GitHub Pages</li>
                                <li><code>*.netlify.app</code> - Netlify Sites</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testCnameTakeover()" class="btn btn-primary">Test CNAME Takeover</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-cloud me-2"></i>Vulnerable Services
                    </div>
                    <div class="card-body">
                        <div class="service-list">
                            <h5>Common Vulnerable Services:</h5>
                            <?php foreach ($vulnerable_services as $service => $name): ?>
                            <div class="service-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <strong><?php echo $name; ?></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-info">*.<?php echo $service; ?></span>
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

        <?php if (!empty($cname_records)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-2 me-2"></i>CNAME Records for <?php echo htmlspecialchars($subdomain); ?>
                    </div>
                    <div class="card-body">
                        <div class="cname-display">
                            <?php foreach ($cname_records as $type => $records): ?>
                            <div class="cname-record">
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
                        <i class="bi bi-code-square me-2"></i>CNAME Subdomain Takeover Techniques
                    </div>
                    <div class="card-body">
                        <div class="cname-takeover-techniques">
                            <div class="technique-card">
                                <div class="technique-title">CNAME Enumeration</div>
                                <div class="technique-demo"># Enumerate CNAME records
dig subdomain.example.com CNAME
nslookup -type=CNAME subdomain.example.com
host -t CNAME subdomain.example.com</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Service Detection</div>
                                <div class="technique-demo"># Detect vulnerable services
# Look for patterns like:
# - *.s3.amazonaws.com
# - *.herokuapp.com
# - *.github.io
# - *.netlify.app</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Service Verification</div>
                                <div class="technique-demo"># Verify service existence
curl -I https://subdomain.example.com
# Look for 404 errors or
# "NoSuchBucket" errors</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Service Registration</div>
                                <div class="technique-demo"># Register vulnerable service
# 1. Create account on service
# 2. Register subdomain name
# 3. Deploy malicious content
# 4. Verify takeover</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Content Deployment</div>
                                <div class="technique-demo"># Deploy malicious content
# - Phishing pages
# - Malware distribution
# - Credential harvesting
# - XSS payloads</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Impact Assessment</div>
                                <div class="technique-demo"># Assess takeover impact
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
                        <li><strong>Type:</strong> CNAME Subdomain Takeover</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> CNAME misconfiguration</li>
                        <li><strong>Issue:</strong> Service deletion without CNAME cleanup</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>CNAME Enumeration:</strong> Find CNAME records</li>
                        <li><strong>Service Detection:</strong> Detect vulnerable services</li>
                        <li><strong>Service Verification:</strong> Verify service existence</li>
                        <li><strong>Service Registration:</strong> Register vulnerable service</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CNAME Subdomain Takeover Examples</h5>
            <p>Use these techniques to exploit CNAME subdomain takeover vulnerabilities:</p>
            
            <h6>1. CNAME Enumeration:</h6>
            <div class="code-block"># Enumerate CNAME records
dig subdomain.example.com CNAME
nslookup -type=CNAME subdomain.example.com
host -t CNAME subdomain.example.com

# Use tools like:
# - subjack
# - takeover
# - subzy
# - subdomain-takeover</div>

            <h6>2. AWS S3 Bucket CNAME Takeover:</h6>
            <div class="code-block"># Check S3 bucket CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.s3.amazonaws.com

# Check if bucket exists
aws s3 ls s3://subdomain.example.com

# If bucket doesn't exist:
# 1. Create S3 bucket with same name
# 2. Upload malicious content
# 3. Verify takeover

# Example:
aws s3 mb s3://subdomain.example.com
echo "Subdomain Takeover" > index.html
aws s3 cp index.html s3://subdomain.example.com/
aws s3 website s3://subdomain.example.com --index-document index.html</div>

            <h6>3. Heroku App CNAME Takeover:</h6>
            <div class="code-block"># Check Heroku app CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.herokuapp.com

# Check if app exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Heroku app
# 2. Deploy malicious content
# 3. Verify takeover

# Example:
heroku create subdomain-example-com
echo "Subdomain Takeover" > index.html
git init
git add .
git commit -m "Initial commit"
git push heroku main</div>

            <h6>4. GitHub Pages CNAME Takeover:</h6>
            <div class="code-block"># Check GitHub Pages CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.github.io

# Check if site exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create GitHub repository
# 2. Enable GitHub Pages
# 3. Upload malicious content
# 4. Verify takeover

# Example:
git init
echo "Subdomain Takeover" > index.html
git add .
git commit -m "Initial commit"
git push origin main
# Enable GitHub Pages in repository settings</div>

            <h6>5. Netlify Site CNAME Takeover:</h6>
            <div class="code-block"># Check Netlify site CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.netlify.app

# Check if site exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Netlify site
# 2. Upload malicious content
# 3. Verify takeover

# Example:
# Upload files via Netlify dashboard
# Or connect GitHub repository
netlify deploy --dir . --prod</div>

            <h6>6. Vercel Site CNAME Takeover:</h6>
            <div class="code-block"># Check Vercel site CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.vercel.app

# Check if site exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Vercel project
# 2. Deploy malicious content
# 3. Verify takeover

# Example:
vercel --prod
# Or connect GitHub repository</div>

            <h6>7. Firebase Hosting CNAME Takeover:</h6>
            <div class="code-block"># Check Firebase site CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.firebaseapp.com

# Check if site exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Firebase project
# 2. Deploy malicious content
# 3. Verify takeover

# Example:
firebase init hosting
firebase deploy</div>

            <h6>8. AWS CloudFront CNAME Takeover:</h6>
            <div class="code-block"># Check CloudFront CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.cloudfront.net

# Check if distribution exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create CloudFront distribution
# 2. Configure origin
# 3. Deploy malicious content
# 4. Verify takeover

# Example:
aws cloudfront create-distribution --distribution-config file://config.json</div>

            <h6>9. Fastly CDN CNAME Takeover:</h6>
            <div class="code-block"># Check Fastly CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.fastly.com

# Check if service exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Fastly service
# 2. Configure backend
# 3. Deploy malicious content
# 4. Verify takeover</div>

            <h6>10. Akamai CDN CNAME Takeover:</h6>
            <div class="code-block"># Check Akamai CNAME
dig subdomain.example.com CNAME
# If CNAME points to *.akamai.net

# Check if service exists
curl -I https://subdomain.example.com

# If 404 error:
# 1. Create Akamai property
# 2. Configure origin
# 3. Deploy malicious content
# 4. Verify takeover</div>

            <h6>11. Automated CNAME Takeover Detection:</h6>
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

            <h6>12. Manual CNAME Verification:</h6>
            <div class="code-block"># Manual verification steps
# 1. Check CNAME records
# 2. Verify service existence
# 3. Test for 404 errors
# 4. Attempt service registration
# 5. Confirm takeover
# 6. Document findings

# Example verification:
dig subdomain.example.com CNAME
curl -I https://subdomain.example.com
# Check response for 404 or service errors</div>

            <h6>13. CNAME Takeover Prevention:</h6>
            <div class="code-block"># Prevent CNAME takeovers
# 1. Regular DNS auditing
# 2. Service monitoring
# 3. Proper cleanup procedures
# 4. DNS security controls
# 5. Subdomain monitoring

# Example monitoring:
# - Set up DNS monitoring
# - Monitor for CNAME changes
# - Alert on service deletions
# - Regular security scans</div>

            <h6>14. CNAME Takeover Impact:</h6>
            <div class="code-block"># Assess takeover impact
# - Brand reputation damage
# - Credential theft potential
# - Phishing attack vectors
# - SEO manipulation
# - Trust and security implications

# Document findings:
# - Vulnerable CNAME records
# - Affected services
# - Potential impact
# - Remediation steps</div>

            <h6>15. CNAME Takeover Remediation:</h6>
            <div class="code-block"># Remediate CNAME takeovers
# 1. Remove vulnerable CNAME records
# 2. Point to secure services
# 3. Implement proper monitoring
# 4. Regular security audits
# 5. DNS security controls

# Example remediation:
# - Delete vulnerable CNAME
# - Point to secure A record
# - Set up monitoring
# - Regular security scans</div>
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
                    <li>Regular CNAME record auditing and cleanup</li>
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
                    <li>Use CNAME takeover detection tools</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testCnameTakeover() {
            alert('CNAME Takeover test initiated. Try the takeover techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
