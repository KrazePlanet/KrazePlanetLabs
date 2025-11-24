<?php
// Lab 5: Advanced Open Redirect with Filter Bypasses
// Vulnerability: Various filtering bypass techniques

$redirect_url = $_GET['url'] ?? '';
$bypass_type = $_GET['bypass'] ?? 'basic';

// Simulate different filtering mechanisms
function applyFilter($url, $type) {
    switch ($type) {
        case 'basic':
            // No filtering - basic vulnerability
            return $url;
            
        case 'protocol':
            // Filter: Block common protocols
            $blocked_protocols = ['http://', 'https://', 'ftp://', 'file://'];
            foreach ($blocked_protocols as $protocol) {
                if (stripos($url, $protocol) === 0) {
                    return false; // Blocked
                }
            }
            return $url;
            
        case 'domain':
            // Filter: Block external domains
            $parsed = parse_url($url);
            if (isset($parsed['host']) && $parsed['host'] !== 'localhost' && $parsed['host'] !== '127.0.0.1') {
                return false; // Blocked
            }
            return $url;
            
        case 'javascript':
            // Filter: Block javascript: protocol
            if (stripos($url, 'javascript:') === 0) {
                return false; // Blocked
            }
            return $url;
            
        case 'double_encode':
            // Filter: Basic URL encoding detection
            if (strpos($url, '%') !== false) {
                return false; // Blocked
            }
            return $url;
            
        default:
            return $url;
    }
}

$filtered_url = applyFilter($redirect_url, $bypass_type);

if (!empty($redirect_url) && $filtered_url !== false) {
    header("Location: " . $filtered_url);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Redirect Lab 5 - Advanced Filter Bypasses</title>
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

        .bypass-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-green);
        }

        .filter-status {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .blocked-status {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Redirect Labs
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
            <h1 class="hero-title">Open Redirect Lab 5</h1>
            <p class="hero-subtitle">Advanced Filter Bypasses</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates various filtering mechanisms and their bypass techniques for open redirect vulnerabilities. Test different bypass methods against different filters.</p>
            <p><strong>Objective:</strong> Master advanced filter bypass techniques and understand how inadequate filtering can be exploited.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
// Simulate different filtering mechanisms
function applyFilter($url, $type) {
    switch ($type) {
        case 'protocol':
            // Block common protocols
            $blocked = ['http://', 'https://', 'ftp://', 'file://'];
            foreach ($blocked as $protocol) {
                if (stripos($url, $protocol) === 0) {
                    return false; // Blocked
                }
            }
            return $url;
        // ... other filters
    }
}

$filtered_url = applyFilter($redirect_url, $bypass_type);
if (!empty($redirect_url) && $filtered_url !== false) {
    header("Location: " . $filtered_url);
    exit();
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-input-cursor-text me-2"></i>Test Input Form
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <div class="mb-3">
                                <label for="bypass" class="form-label">Filter Type</label>
                                <select class="form-select" id="bypass" name="bypass">
                                    <option value="basic" <?php echo $bypass_type === 'basic' ? 'selected' : ''; ?>>Basic (No Filter)</option>
                                    <option value="protocol" <?php echo $bypass_type === 'protocol' ? 'selected' : ''; ?>>Protocol Filter</option>
                                    <option value="domain" <?php echo $bypass_type === 'domain' ? 'selected' : ''; ?>>Domain Filter</option>
                                    <option value="javascript" <?php echo $bypass_type === 'javascript' ? 'selected' : ''; ?>>JavaScript Filter</option>
                                    <option value="double_encode" <?php echo $bypass_type === 'double_encode' ? 'selected' : ''; ?>>Encoding Filter</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">Redirect URL</label>
                                <input class="form-control" type="text" placeholder="https://evil.com" 
                                       aria-label="Redirect URL" name="url" 
                                       value="<?php echo htmlspecialchars($redirect_url); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Test Bypass</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($redirect_url)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-arrow-return-right me-2"></i>Filter Test Results
            </div>
            <div class="card-body">
                <p><strong>Original URL:</strong> <code><?php echo htmlspecialchars($redirect_url); ?></code></p>
                <p><strong>Filter Type:</strong> <code><?php echo htmlspecialchars($bypass_type); ?></code></p>
                
                <?php if ($filtered_url === false): ?>
                <div class="blocked-status">
                    <strong><i class="bi bi-x-circle me-2"></i>BLOCKED:</strong> The URL was blocked by the filter.
                </div>
                <?php else: ?>
                <div class="filter-status">
                    <strong><i class="bi bi-check-circle me-2"></i>ALLOWED:</strong> The URL passed the filter and will redirect to: <code><?php echo htmlspecialchars($filtered_url); ?></code>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="bypass-info">
            <h5><i class="bi bi-shield-exclamation me-2"></i>Available Filters</h5>
            <div class="row">
                <div class="col-md-6">
                    <h6>Filter Types:</h6>
                    <ul>
                        <li><code>basic</code> - No filtering</li>
                        <li><code>protocol</code> - Blocks common protocols</li>
                        <li><code>domain</code> - Blocks external domains</li>
                        <li><code>javascript</code> - Blocks javascript: protocol</li>
                        <li><code>double_encode</code> - Blocks URL encoding</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Current Filter:</h6>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($bypass_type); ?></span>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Advanced Open Redirect with Filter Bypasses</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameters:</strong> <code>url</code>, <code>bypass</code></li>
                        <li><strong>Method:</strong> Various filtering bypass techniques</li>
                        <li><strong>Issue:</strong> Inadequate filtering mechanisms</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Payloads by Filter Type</h5>
                    
                    <h6>Protocol Filter Bypasses:</h6>
                    <ul>
                        <li><code>//evil.com</code> - Protocol relative URL</li>
                        <li><code>\/\/evil.com</code> - Escaped slashes</li>
                        <li><code>ht%74p://evil.com</code> - URL encoding</li>
                        <li><code>ht%74%70://evil.com</code> - Double encoding</li>
                        <li><code>0x2f2fevil.com</code> - Hex encoding</li>
                    </ul>
                    
                    <h6>Domain Filter Bypasses:</h6>
                    <ul>
                        <li><code>//evil.com</code> - Protocol relative</li>
                        <li><code>http://localhost@evil.com</code> - User info bypass</li>
                        <li><code>http://127.0.0.1@evil.com</code> - IP bypass</li>
                        <li><code>http://evil.com#localhost</code> - Fragment bypass</li>
                        <li><code>http://evil.com?localhost</code> - Query bypass</li>
                    </ul>
                    
                    <h6>JavaScript Filter Bypasses:</h6>
                    <ul>
                        <li><code>javascript:alert(1)</code> - Basic javascript</li>
                        <li><code>JAVASCRIPT:alert(1)</code> - Case variation</li>
                        <li><code>javascript&#58;alert(1)</code> - HTML entity</li>
                        <li><code>javascript%3Aalert(1)</code> - URL encoding</li>
                        <li><code>data:text/html,<script>alert(1)</script></code> - Data URI</li>
                    </ul>
                    
                    <h6>Encoding Filter Bypasses:</h6>
                    <ul>
                        <li><code>https://evil.com</code> - No encoding</li>
                        <li><code>https%3A//evil.com</code> - Single encoding</li>
                        <li><code>https%253A//evil.com</code> - Double encoding</li>
                        <li><code>https://evil.com</code> - Mixed encoding</li>
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
                <li>Domain validation bypasses</li>
                <li>Client-side security control bypasses</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Advanced Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement multiple layers of validation</li>
                    <li>Use whitelist-based validation instead of blacklists</li>
                    <li>Normalize URLs before validation</li>
                    <li>Implement proper URL parsing and validation</li>
                    <li>Use Content Security Policy (CSP) headers</li>
                    <li>Regular security testing and filter updates</li>
                    <li>Consider using redirect tokens instead of direct URLs</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
