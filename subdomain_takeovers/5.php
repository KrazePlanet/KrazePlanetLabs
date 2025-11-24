<?php
// Lab 5: Real-World Subdomain Takeover
// Vulnerability: Real-world subdomain takeover vulnerabilities

session_start();

$message = '';
$subdomain = $_GET['subdomain'] ?? '';
$dns_records = [];
$takeover_status = 'unknown';
$real_world_scenarios = [
    'ecommerce_takeover' => 'E-commerce Subdomain Takeover',
    'banking_takeover' => 'Banking Subdomain Takeover',
    'government_takeover' => 'Government Subdomain Takeover',
    'healthcare_takeover' => 'Healthcare Subdomain Takeover',
    'education_takeover' => 'Education Subdomain Takeover',
    'social_media_takeover' => 'Social Media Subdomain Takeover'
];

// Simulate real-world takeover detection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'check_real_world') {
        $subdomain = $_POST['subdomain'] ?? '';
        $scenario = $_POST['scenario'] ?? '';
        
        if (!empty($subdomain) && !empty($scenario)) {
            // Simulate real-world takeover detection
            $dns_records = [
                'A' => ['192.168.1.100'],
                'CNAME' => ['vulnerable-service.example.com'],
                'TXT' => ['v=spf1 include:_spf.google.com ~all'],
                'MX' => ['mail.example.com'],
                'NS' => ['ns1.example.com', 'ns2.example.com']
            ];
            
            $takeover_status = 'vulnerable';
            $message = '<div class="alert alert-danger">⚠️ VULNERABLE: Real-world ' . $scenario . ' takeover possible!</div>';
        } else {
            $message = '<div class="alert alert-warning">⚠️ Please select a scenario and enter a subdomain.</div>';
        }
    } elseif ($action === 'simulate_real_world_takeover') {
        $subdomain = $_POST['subdomain'] ?? '';
        $scenario = $_POST['scenario'] ?? '';
        
        if (!empty($subdomain) && !empty($scenario)) {
            $message = '<div class="alert alert-warning">⚠️ Real-world takeover simulation initiated for: ' . htmlspecialchars($subdomain) . ' (' . htmlspecialchars($scenario) . ')</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Real-World Subdomain Takeover - Subdomain Takeover Labs</title>
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

        .real-world-warning {
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

        .real-world-scenarios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .scenario-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #334155;
        }

        .scenario-title {
            color: var(--accent-green);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .scenario-demo {
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

        .scenario-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .scenario-item {
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
            <h1 class="hero-title">Lab 5: Real-World Subdomain Takeover</h1>
            <p class="hero-subtitle">Real-world subdomain takeover vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates real-world subdomain takeover vulnerabilities in various industries including e-commerce, banking, government, healthcare, education, and social media.</p>
            <p><strong>Objective:</strong> Understand how real-world subdomain takeover attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-shield-exclamation me-2"></i>Real-World Takeover Scanner
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Check Real-World Scenarios</h5>
                            <p>This tool checks for real-world subdomain takeover scenarios:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="check_real_world">
                                <div class="mb-3">
                                    <label for="subdomain" class="form-label">Subdomain to Check</label>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain" placeholder="vulnerable.example.com" value="<?php echo htmlspecialchars($subdomain); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="scenario" class="form-label">Real-World Scenario</label>
                                    <select class="form-select" id="scenario" name="scenario" required>
                                        <option value="">Select scenario</option>
                                        <?php foreach ($real_world_scenarios as $key => $name): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Check Real-World</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Real-World Takeover Tester
                    </div>
                    <div class="card-body">
                        <div class="real-world-warning">
                            <h5>⚠️ Real-World Takeover Warning</h5>
                            <p>This lab demonstrates real-world subdomain takeover vulnerabilities:</p>
                            <ul>
                                <li><code>E-commerce Takeover</code> - E-commerce subdomain takeover</li>
                                <li><code>Banking Takeover</code> - Banking subdomain takeover</li>
                                <li><code>Government Takeover</code> - Government subdomain takeover</li>
                                <li><code>Healthcare Takeover</code> - Healthcare subdomain takeover</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Real-World Scenarios</h5>
                            <p>Available real-world scenarios:</p>
                            <ul>
                                <li><code>E-commerce Takeover</code> - E-commerce subdomain takeover</li>
                                <li><code>Banking Takeover</code> - Banking subdomain takeover</li>
                                <li><code>Government Takeover</code> - Government subdomain takeover</li>
                                <li><code>Healthcare Takeover</code> - Healthcare subdomain takeover</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testRealWorldTakeover()" class="btn btn-primary">Test Real-World Takeover</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Real-World Scenarios
                    </div>
                    <div class="card-body">
                        <div class="scenario-list">
                            <h5>Available Real-World Scenarios:</h5>
                            <?php foreach ($real_world_scenarios as $key => $name): ?>
                            <div class="scenario-item">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <strong><?php echo $name; ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-info"><?php echo $key; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-warning">Critical Risk</span>
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
                        <i class="bi bi-code-square me-2"></i>Real-World Subdomain Takeover Scenarios
                    </div>
                    <div class="card-body">
                        <div class="real-world-scenarios">
                            <div class="scenario-card">
                                <div class="scenario-title">E-commerce Takeover</div>
                                <div class="scenario-demo"># E-commerce subdomain takeover
# 1. Find e-commerce subdomains
# 2. Check for vulnerable services
# 3. Take over subdomain
# 4. Serve fake e-commerce site
# 5. Capture payment details</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Banking Takeover</div>
                                <div class="scenario-demo"># Banking subdomain takeover
# 1. Find banking subdomains
# 2. Check for vulnerable services
# 3. Take over subdomain
# 4. Serve fake banking site
# 5. Capture banking credentials</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Government Takeover</div>
                                <div class="scenario-demo"># Government subdomain takeover
# 1. Find government subdomains
# 2. Check for vulnerable services
# 3. Take over subdomain
# 4. Serve fake government site
# 5. Capture sensitive data</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Healthcare Takeover</div>
                                <div class="scenario-demo"># Healthcare subdomain takeover
# 1. Find healthcare subdomains
# 2. Check for vulnerable services
# 3. Take over subdomain
# 4. Serve fake healthcare site
# 5. Capture medical data</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Education Takeover</div>
                                <div class="scenario-demo"># Education subdomain takeover
# 1. Find education subdomains
# 2. Check for vulnerable services
# 3. Take over subdomain
# 4. Serve fake education site
# 5. Capture student data</div>
                            </div>
                            
                            <div class="scenario-card">
                                <div class="scenario-title">Social Media Takeover</div>
                                <div class="scenario-demo"># Social media subdomain takeover
# 1. Find social media subdomains
# 2. Check for vulnerable services
# 3. Take over subdomain
# 4. Serve fake social media site
# 5. Capture social media credentials</div>
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
                        <li><strong>Type:</strong> Real-World Subdomain Takeover</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Method:</strong> Real-world scenarios</li>
                        <li><strong>Issue:</strong> Industry-specific vulnerabilities</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>E-commerce Takeover:</strong> E-commerce subdomain takeover</li>
                        <li><strong>Banking Takeover:</strong> Banking subdomain takeover</li>
                        <li><strong>Government Takeover:</strong> Government subdomain takeover</li>
                        <li><strong>Healthcare Takeover:</strong> Healthcare subdomain takeover</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Real-World Subdomain Takeover Examples</h5>
            <p>Use these techniques to exploit real-world subdomain takeover vulnerabilities:</p>
            
            <h6>1. E-commerce Subdomain Takeover:</h6>
            <div class="code-block"># E-commerce subdomain takeover
# 1. Find e-commerce subdomains
# - shop.example.com
# - store.example.com
# - checkout.example.com
# - payment.example.com

# 2. Check for vulnerable services
dig shop.example.com CNAME
dig store.example.com CNAME

# 3. Take over subdomain
# 4. Serve fake e-commerce site
# 5. Capture payment details

# Example fake e-commerce site:
# - Fake product catalog
# - Fake shopping cart
# - Fake checkout process
# - Capture credit card details</div>

            <h6>2. Banking Subdomain Takeover:</h6>
            <div class="code-block"># Banking subdomain takeover
# 1. Find banking subdomains
# - online.example.com
# - secure.example.com
# - login.example.com
# - banking.example.com

# 2. Check for vulnerable services
dig online.example.com CNAME
dig secure.example.com CNAME

# 3. Take over subdomain
# 4. Serve fake banking site
# 5. Capture banking credentials

# Example fake banking site:
# - Fake login page
# - Fake account dashboard
# - Fake transaction history
# - Capture banking credentials</div>

            <h6>3. Government Subdomain Takeover:</h6>
            <div class="code-block"># Government subdomain takeover
# 1. Find government subdomains
# - portal.example.gov
# - services.example.gov
# - login.example.gov
# - secure.example.gov

# 2. Check for vulnerable services
dig portal.example.gov CNAME
dig services.example.gov CNAME

# 3. Take over subdomain
# 4. Serve fake government site
# 5. Capture sensitive data

# Example fake government site:
# - Fake citizen portal
# - Fake service forms
# - Fake document downloads
# - Capture personal information</div>

            <h6>4. Healthcare Subdomain Takeover:</h6>
            <div class="code-block"># Healthcare subdomain takeover
# 1. Find healthcare subdomains
# - patient.example.com
# - portal.example.com
# - secure.example.com
# - medical.example.com

# 2. Check for vulnerable services
dig patient.example.com CNAME
dig portal.example.com CNAME

# 3. Take over subdomain
# 4. Serve fake healthcare site
# 5. Capture medical data

# Example fake healthcare site:
# - Fake patient portal
# - Fake medical records
# - Fake appointment booking
# - Capture medical information</div>

            <h6>5. Education Subdomain Takeover:</h6>
            <div class="code-block"># Education subdomain takeover
# 1. Find education subdomains
# - student.example.edu
# - portal.example.edu
# - login.example.edu
# - secure.example.edu

# 2. Check for vulnerable services
dig student.example.edu CNAME
dig portal.example.edu CNAME

# 3. Take over subdomain
# 4. Serve fake education site
# 5. Capture student data

# Example fake education site:
# - Fake student portal
# - Fake course materials
# - Fake grade reports
# - Capture student information</div>

            <h6>6. Social Media Subdomain Takeover:</h6>
            <div class="code-block"># Social media subdomain takeover
# 1. Find social media subdomains
# - api.example.com
# - secure.example.com
# - login.example.com
# - mobile.example.com

# 2. Check for vulnerable services
dig api.example.com CNAME
dig secure.example.com CNAME

# 3. Take over subdomain
# 4. Serve fake social media site
# 5. Capture social media credentials

# Example fake social media site:
# - Fake login page
# - Fake profile page
# - Fake news feed
# - Capture social media credentials</div>

            <h6>7. Real-World Impact Assessment:</h6>
            <div class="code-block"># Assess real-world impact
# - Brand reputation damage
# - Customer trust loss
# - Financial losses
# - Legal implications
# - Regulatory compliance issues

# Document findings:
# - Vulnerable subdomains
# - Affected services
# - Potential impact
# - Remediation steps</div>

            <h6>8. Industry-Specific Mitigation:</h6>
            <div class="code-block"># Industry-specific mitigation
# E-commerce:
# - Payment security
# - Customer data protection
# - PCI compliance

# Banking:
# - Financial security
# - Customer data protection
# - Regulatory compliance

# Government:
# - Citizen data protection
# - National security
# - Regulatory compliance</div>

            <h6>9. Compliance and Legal Issues:</h6>
            <div class="code-block"># Compliance and legal issues
# - GDPR compliance
# - CCPA compliance
# - HIPAA compliance
# - PCI DSS compliance
# - SOX compliance

# Legal implications:
# - Data breach notifications
# - Regulatory fines
# - Lawsuits
# - Reputation damage</div>

            <h6>10. Incident Response:</h6>
            <div class="code-block"># Incident response
# 1. Detect takeover
# 2. Assess impact
# 3. Contain threat
# 4. Eradicate threat
# 5. Recover systems
# 6. Learn from incident

# Response steps:
# - Immediate containment
# - Forensic analysis
# - Customer notification
# - Regulatory reporting
# - System recovery</div>

            <h6>11. Prevention Strategies:</h6>
            <div class="code-block"># Prevention strategies
# - Regular subdomain monitoring
# - DNS security controls
# - Service configuration audits
# - Security awareness training
# - Incident response planning

# Monitoring tools:
# - DNS monitoring
# - Subdomain scanning
# - Vulnerability scanning
# - Threat intelligence</div>

            <h6>12. Recovery Procedures:</h6>
            <div class="code-block"># Recovery procedures
# 1. Identify compromised subdomains
# 2. Remove malicious content
# 3. Secure subdomains
# 4. Monitor for re-compromise
# 5. Update security controls

# Recovery steps:
# - DNS record cleanup
# - Service reconfiguration
# - Security hardening
# - Monitoring enhancement</div>

            <h6>13. Long-term Security:</h6>
            <div class="code-block"># Long-term security
# - Continuous monitoring
# - Regular security audits
# - Security awareness training
# - Incident response planning
# - Threat intelligence

# Security controls:
# - DNS security
# - Subdomain monitoring
# - Service configuration
# - Access controls</div>

            <h6>14. Business Continuity:</h6>
            <div class="code-block"># Business continuity
# - Service availability
# - Customer communication
# - Regulatory compliance
# - Reputation management
# - Financial impact

# Continuity planning:
# - Backup systems
# - Communication plans
# - Recovery procedures
# - Stakeholder management</div>

            <h6>15. Lessons Learned:</h6>
            <div class="code-block"># Lessons learned
# - Security gaps identified
# - Process improvements
# - Technology enhancements
# - Training needs
# - Policy updates

# Improvement areas:
# - DNS security
# - Subdomain management
# - Monitoring capabilities
# - Incident response
# - Security awareness</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Complete subdomain control and traffic redirection</li>
                <li>Industry-specific phishing attacks and credential theft</li>
                <li>Brand reputation damage and trust issues</li>
                <li>Compliance violations and legal issues</li>
                <li>Data exfiltration and privacy breaches</li>
                <li>Financial losses and business impact</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive subdomain monitoring and alerting</li>
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
                    <li>Use real-world subdomain takeover detection tools</li>
                    <li>Implement proper audit trails</li>
                    <li>Develop incident response plans</li>
                    <li>Regular security awareness training</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testRealWorldTakeover() {
            alert('Real-World Takeover test initiated. Try the real-world takeover techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
