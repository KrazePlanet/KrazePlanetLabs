<?php
// Lab 5: Advanced Parameter Manipulation IDOR
// Vulnerability: Multiple parameter manipulation techniques with advanced bypasses

session_start();

// Simulate user authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user
    $_SESSION['username'] = 'user1';
    $_SESSION['role'] = 'user';
}

$message = '';
$data = '';
$technique = $_GET['technique'] ?? 'basic';
$param1 = $_GET['param1'] ?? '';
$param2 = $_GET['param2'] ?? '';
$param3 = $_GET['param3'] ?? '';

// Simulate complex data structure
$data_sources = [
    'users' => [
        1 => ['id' => 1, 'name' => 'John Doe', 'role' => 'user', 'department' => 'IT'],
        2 => ['id' => 2, 'name' => 'Jane Smith', 'role' => 'user', 'department' => 'HR'],
        3 => ['id' => 3, 'name' => 'Admin User', 'role' => 'admin', 'department' => 'Management']
    ],
    'projects' => [
        1 => ['id' => 1, 'name' => 'Project Alpha', 'owner_id' => 1, 'status' => 'active'],
        2 => ['id' => 2, 'name' => 'Project Beta', 'owner_id' => 2, 'status' => 'completed'],
        3 => ['id' => 3, 'name' => 'Project Gamma', 'owner_id' => 3, 'status' => 'planning']
    ],
    'reports' => [
        1 => ['id' => 1, 'title' => 'Q1 Financial Report', 'author_id' => 3, 'confidential' => true],
        2 => ['id' => 2, 'title' => 'User Activity Report', 'author_id' => 1, 'confidential' => false],
        3 => ['id' => 3, 'title' => 'Security Audit Report', 'author_id' => 3, 'confidential' => true]
    ]
];

// Advanced parameter manipulation techniques
switch ($technique) {
    case 'basic':
        // Basic IDOR - direct parameter manipulation
        if ($param1 && isset($data_sources['users'][$param1])) {
            $data = $data_sources['users'][$param1];
            $message = '<div class="alert alert-success">Basic IDOR - User data loaded!</div>';
        }
        break;
        
    case 'encoded':
        // Encoded parameter manipulation
        $decoded_param = base64_decode($param1);
        if ($decoded_param && isset($data_sources['users'][$decoded_param])) {
            $data = $data_sources['users'][$decoded_param];
            $message = '<div class="alert alert-success">Encoded IDOR - User data loaded!</div>';
        }
        break;
        
    case 'array':
        // Array parameter manipulation
        if ($param1 && $param2) {
            $array_key = $param1 . '_' . $param2;
            if (isset($data_sources['projects'][$array_key])) {
                $data = $data_sources['projects'][$array_key];
                $message = '<div class="alert alert-success">Array IDOR - Project data loaded!</div>';
            }
        }
        break;
        
    case 'hash':
        // Hash-based parameter manipulation
        if ($param1) {
            $hash = md5($param1);
            // Simulate hash-based lookup
            foreach ($data_sources['reports'] as $report) {
                if (md5($report['id']) === $hash) {
                    $data = $report;
                    $message = '<div class="alert alert-success">Hash IDOR - Report data loaded!</div>';
                    break;
                }
            }
        }
        break;
        
    case 'json':
        // JSON parameter manipulation
        if ($param1) {
            $json_data = json_decode($param1, true);
            if ($json_data && isset($json_data['id']) && isset($data_sources['users'][$json_data['id']])) {
                $data = $data_sources['users'][$json_data['id']];
                $message = '<div class="alert alert-success">JSON IDOR - User data loaded!</div>';
            }
        }
        break;
        
    case 'chained':
        // Chained parameter manipulation
        if ($param1 && $param2 && $param3) {
            $chained_key = $param1 . '_' . $param2 . '_' . $param3;
            // Simulate chained lookup
            if (isset($data_sources['projects'][$param1])) {
                $project = $data_sources['projects'][$param1];
                if ($project['owner_id'] == $param2) {
                    $data = $project;
                    $message = '<div class="alert alert-success">Chained IDOR - Project data loaded!</div>';
                }
            }
        }
        break;
        
    case 'bypass':
        // Advanced bypass techniques
        if ($param1) {
            // Try multiple bypass techniques
            $bypass_attempts = [
                $param1,
                base64_decode($param1),
                urldecode($param1),
                str_replace(['%00', '%0a', '%0d'], '', $param1),
                preg_replace('/[^0-9]/', '', $param1)
            ];
            
            foreach ($bypass_attempts as $attempt) {
                if (is_numeric($attempt) && isset($data_sources['users'][$attempt])) {
                    $data = $data_sources['users'][$attempt];
                    $message = '<div class="alert alert-success">Bypass IDOR - User data loaded!</div>';
                    break;
                }
            }
        }
        break;
        
    default:
        $message = '<div class="alert alert-warning">Invalid technique specified!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced Parameter Manipulation - IDOR</title>
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

        .data-display {
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

        .data-info {
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

        .advanced-badge {
            background: var(--accent-red);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .nav-pills .nav-link {
            color: #cbd5e0;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
        }

        .nav-pills .nav-link.active {
            background: var(--accent-green);
            color: #1a202c;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to IDOR Labs
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
            <h1 class="hero-title">Lab 5: Advanced Parameter Manipulation</h1>
            <p class="hero-subtitle">IDOR with advanced parameter manipulation techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced IDOR vulnerabilities using various parameter manipulation techniques. The application implements different methods of parameter handling that can be bypassed using creative manipulation techniques.</p>
            <p><strong>Objective:</strong> Use advanced parameter manipulation techniques to bypass IDOR protections and access unauthorized data.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Advanced parameter manipulation techniques
switch ($technique) {
    case 'basic':
        // Basic IDOR - direct parameter manipulation
        if ($param1 && isset($data_sources['users'][$param1])) {
            $data = $data_sources['users'][$param1];
        }
        break;
        
    case 'encoded':
        // Encoded parameter manipulation
        $decoded_param = base64_decode($param1);
        if ($decoded_param && isset($data_sources['users'][$decoded_param])) {
            $data = $data_sources['users'][$decoded_param];
        }
        break;
        
    case 'hash':
        // Hash-based parameter manipulation
        if ($param1) {
            $hash = md5($param1);
            // Simulate hash-based lookup
        }
        break;
        
    case 'json':
        // JSON parameter manipulation
        if ($param1) {
            $json_data = json_decode($param1, true);
            if ($json_data && isset($json_data['id'])) {
                $data = $data_sources['users'][$json_data['id']];
            }
        }
        break;
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Advanced Parameter Manipulation
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <ul class="nav nav-pills mb-3" id="techniqueTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'basic' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('basic')">Basic</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'encoded' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('encoded')">Encoded</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'array' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('array')">Array</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'hash' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('hash')">Hash</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'json' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('json')">JSON</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'chained' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('chained')">Chained</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $technique === 'bypass' ? 'active' : ''; ?>" 
                                        onclick="loadTechnique('bypass')">Bypass</button>
                            </li>
                        </ul>
                        
                        <form method="GET">
                            <input type="hidden" name="technique" value="<?php echo $technique; ?>">
                            <div class="mb-3">
                                <label for="param1" class="form-label">Parameter 1</label>
                                <input type="text" class="form-control" id="param1" name="param1" 
                                       placeholder="Enter parameter 1..." value="<?php echo htmlspecialchars($param1); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="param2" class="form-label">Parameter 2</label>
                                <input type="text" class="form-control" id="param2" name="param2" 
                                       placeholder="Enter parameter 2..." value="<?php echo htmlspecialchars($param2); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="param3" class="form-label">Parameter 3</label>
                                <input type="text" class="form-control" id="param3" name="param3" 
                                       placeholder="Enter parameter 3..." value="<?php echo htmlspecialchars($param3); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Test Manipulation</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?technique=basic&param1=1" style="color: var(--accent-green);">Basic IDOR</a></li>
                                <li><a href="?technique=encoded&param1=MQ==" style="color: var(--accent-green);">Encoded IDOR</a></li>
                                <li><a href="?technique=hash&param1=1" style="color: var(--accent-green);">Hash IDOR</a></li>
                                <li><a href="?technique=json&param1={\"id\":1}" style="color: var(--accent-green);">JSON IDOR</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Manipulated Data: <?php echo ucfirst($technique); ?> Technique
                        <span class="advanced-badge ms-2">ADVANCED BYPASS</span>
                    </div>
                    <div class="card-body">
                        <div class="data-display">
                            <h5>Manipulated Data</h5>
                            <div class="sensitive-data">
                                <pre><?php echo json_encode($data, JSON_PRETTY_PRINT); ?></pre>
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
                        <li><strong>Type:</strong> Advanced Insecure Direct Object Reference (IDOR)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameters:</strong> <code>technique</code>, <code>param1</code>, <code>param2</code>, <code>param3</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Advanced parameter manipulation without proper validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads by Technique</h5>
                    <p><strong>Basic:</strong> <code>param1=1</code></p>
                    <p><strong>Encoded:</strong> <code>param1=MQ==</code> (base64 encoded "1")</p>
                    <p><strong>Array:</strong> <code>param1=1&param2=2</code></p>
                    <p><strong>Hash:</strong> <code>param1=1</code> (uses MD5 hash)</p>
                    <p><strong>JSON:</strong> <code>param1={"id":1}</code></p>
                    <p><strong>Chained:</strong> <code>param1=1&param2=1&param3=test</code></p>
                    <p><strong>Bypass:</strong> <code>param1=1%00</code> (null byte injection)</p>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test different manipulation techniques:</p>
            <ul>
                <li><a href="?technique=basic&param1=1" style="color: var(--accent-green);">Basic IDOR</a></li>
                <li><a href="?technique=encoded&param1=MQ==" style="color: var(--accent-green);">Encoded IDOR</a></li>
                <li><a href="?technique=hash&param1=1" style="color: var(--accent-green);">Hash IDOR</a></li>
                <li><a href="?technique=json&param1={\"id\":1}" style="color: var(--accent-green);">JSON IDOR</a></li>
                <li><a href="?technique=chained&param1=1&param2=1&param3=test" style="color: var(--accent-green);">Chained IDOR</a></li>
                <li><a href="?technique=bypass&param1=1%00" style="color: var(--accent-green);">Bypass IDOR</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Advanced Attack Scenarios</h5>
            <ul>
                <li>Bypassing WAF and security filters</li>
                <li>Advanced encoding and obfuscation techniques</li>
                <li>Parameter pollution and injection attacks</li>
                <li>Multi-stage payload delivery</li>
                <li>Context-aware injection attacks</li>
                <li>Client-side security control bypasses</li>
                <li>Privilege escalation and persistence</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Advanced Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement multiple layers of validation and sanitization</li>
                    <li>Use whitelist-based validation instead of blacklists</li>
                    <li>Normalize and canonicalize input before validation</li>
                    <li>Implement proper parameter validation and restriction</li>
                    <li>Use least privilege principles</li>
                    <li>Implement proper error handling</li>
                    <li>Regular security testing and filter updates</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                    <li>Implement network segmentation and access controls</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function loadTechnique(technique) {
            window.location.href = '?technique=' + technique;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
