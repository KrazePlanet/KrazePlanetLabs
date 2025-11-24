<?php
// Lab 5: Advanced Filter Bypass Directory Traversal
// Vulnerability: Multiple filtering mechanisms with bypass techniques

$message = '';
$file_content = '';
$file_path = '';
$filter_type = $_GET['filter'] ?? 'none';

// Filter functions
function applyFilter($input, $type) {
    switch ($type) {
        case 'basic':
            // Basic filter: Remove ../ and ..\
            return str_replace(['../', '..\\'], '', $input);
            
        case 'double_dot':
            // Filter: Remove double dots
            return str_replace('..', '', $input);
            
        case 'slash_filter':
            // Filter: Remove slashes
            return str_replace(['/', '\\'], '', $input);
            
        case 'path_traversal':
            // Filter: Remove common path traversal sequences
            $patterns = ['../', '..\\', '..%2f', '..%5c', '%2e%2e%2f', '%2e%2e%5c'];
            return str_replace($patterns, '', $input);
            
        case 'encoding':
            // Filter: Basic URL decoding
            return urldecode($input);
            
        case 'null_byte':
            // Filter: Remove null bytes
            return str_replace("\0", '', $input);
            
        default:
            return $input;
    }
}

// Handle file request
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    
    // Apply filter based on current filter type
    $filtered_file = applyFilter($file, $filter_type);
    
    // Vulnerable: Still using filtered input in path construction
    $file_path = 'files/' . $filtered_file;
    
    if (file_exists($file_path)) {
        $file_content = file_get_contents($file_path);
        $message = '<div class="alert alert-success">File loaded successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">File not found: ' . htmlspecialchars($file_path) . '</div>';
    }
}

// Get list of available files
$files_dir = 'files/';
$available_files = [];
if (is_dir($files_dir)) {
    $files = scandir($files_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_file($files_dir . $file)) {
            $available_files[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: Advanced Filter Bypass - Directory Traversal</title>
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

        .file-content {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
            max-height: 400px;
            overflow-y: auto;
        }

        .file-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .filter-info {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-orange);
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
                <i class="bi bi-arrow-left me-2"></i>Back to Directory Traversal Labs
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
            <h1 class="hero-title">Lab 5: Advanced Filter Bypass</h1>
            <p class="hero-subtitle">Directory Traversal with filter bypass techniques</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates advanced directory traversal vulnerabilities where various filtering mechanisms are implemented but can be bypassed. The application applies different filters to user input but still allows traversal through creative bypass techniques.</p>
            <p><strong>Objective:</strong> Bypass the implemented filters and access files outside the intended directory using advanced traversal techniques.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Multiple filter implementations
function applyFilter($input, $type) {
    switch ($type) {
        case 'basic':
            return str_replace(['../', '..\\'], '', $input);
        case 'double_dot':
            return str_replace('..', '', $input);
        case 'slash_filter':
            return str_replace(['/', '\\'], '', $input);
        case 'path_traversal':
            $patterns = ['../', '..\\', '..%2f', '..%5c'];
            return str_replace($patterns, '', $input);
        case 'encoding':
            return urldecode($input);
        case 'null_byte':
            return str_replace("\0", '', $input);
        default:
            return $input;
    }
}

// Vulnerable processing
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filtered_file = applyFilter($file, $filter_type);
    $file_path = 'files/' . $filtered_file;
    // Still vulnerable to bypasses
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Filter Bypass Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="filter" class="form-label">Filter Type</label>
                                <select class="form-select" id="filter" name="filter" onchange="this.form.submit()">
                                    <option value="none" <?php echo $filter_type === 'none' ? 'selected' : ''; ?>>No Filter</option>
                                    <option value="basic" <?php echo $filter_type === 'basic' ? 'selected' : ''; ?>>Basic Filter</option>
                                    <option value="double_dot" <?php echo $filter_type === 'double_dot' ? 'selected' : ''; ?>>Double Dot Filter</option>
                                    <option value="slash_filter" <?php echo $filter_type === 'slash_filter' ? 'selected' : ''; ?>>Slash Filter</option>
                                    <option value="path_traversal" <?php echo $filter_type === 'path_traversal' ? 'selected' : ''; ?>>Path Traversal Filter</option>
                                    <option value="encoding" <?php echo $filter_type === 'encoding' ? 'selected' : ''; ?>>Encoding Filter</option>
                                    <option value="null_byte" <?php echo $filter_type === 'null_byte' ? 'selected' : ''; ?>>Null Byte Filter</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">File Name</label>
                                <input type="text" class="form-control" id="file" name="file" 
                                       placeholder="Enter file name..." value="<?php echo htmlspecialchars($_GET['file'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Load File</button>
                        </form>
                        
                        <div class="file-list">
                            <h6><i class="bi bi-folder me-2"></i>Available Files:</h6>
                            <?php if (empty($available_files)): ?>
                                <p class="text-muted">No files available in the files directory.</p>
                            <?php else: ?>
                                <ul>
                                    <?php foreach ($available_files as $file): ?>
                                        <li><a href="?file=<?php echo urlencode($file); ?>&filter=<?php echo urlencode($filter_type); ?>" style="color: var(--accent-green);"><?php echo htmlspecialchars($file); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($file_content): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-text me-2"></i>File Content: <?php echo htmlspecialchars($file_path); ?>
                    </div>
                    <div class="card-body">
                        <div class="file-content">
                            <pre><?php echo htmlspecialchars($file_content); ?></pre>
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
                        <li><strong>Type:</strong> Advanced Directory Traversal with Filter Bypasses</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>file</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Inadequate filtering mechanisms</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Payloads by Filter</h5>
                    <p><strong>Basic Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>....//....//....//etc/passwd</code></li>
                        <li><code>..%2f..%2f..%2fetc/passwd</code></li>
                        <li><code>..%252f..%252f..%252fetc/passwd</code></li>
                    </ul>
                    <p><strong>Double Dot Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>.../.../.../etc/passwd</code></li>
                        <li><code>....//....//....//etc/passwd</code></li>
                    </ul>
                    <p><strong>Slash Filter Bypasses:</strong></p>
                    <ul>
                        <li><code>..%2f..%2f..%2fetc%2fpasswd</code></li>
                        <li><code>..%5c..%5c..%5cetc%5cpasswd</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="filter-info">
            <h5><i class="bi bi-funnel me-2"></i>Current Filter: <?php echo ucfirst(str_replace('_', ' ', $filter_type)); ?></h5>
            <p>Try different bypass techniques based on the active filter:</p>
            <ul>
                <li><strong>Basic Filter:</strong> Removes <code>../</code> and <code>..\</code> - Try double encoding or alternative sequences</li>
                <li><strong>Double Dot Filter:</strong> Removes <code>..</code> - Try triple dots or encoding</li>
                <li><strong>Slash Filter:</strong> Removes <code>/</code> and <code>\</code> - Try URL encoding</li>
                <li><strong>Path Traversal Filter:</strong> Removes common sequences - Try alternative encodings</li>
                <li><strong>Encoding Filter:</strong> Applies URL decoding - Try double encoding</li>
                <li><strong>Null Byte Filter:</strong> Removes null bytes - Try other termination methods</li>
            </ul>
        </div>

        <div class="bypass-examples">
            <h5><i class="bi bi-shield-exclamation me-2"></i>Advanced Bypass Techniques</h5>
            <p>Try these advanced bypass techniques:</p>
            <ul>
                <li><code>....//....//....//etc/passwd</code> - Double slash bypass</li>
                <li><code>..%2f..%2f..%2fetc/passwd</code> - URL encoding bypass</li>
                <li><code>..%252f..%252f..%252fetc/passwd</code> - Double encoding bypass</li>
                <li><code>..%c0%af..%c0%af..%c0%afetc/passwd</code> - Unicode encoding bypass</li>
                <li><code>..%2e%2e%2f..%2e%2e%2f..%2e%2e%2fetc/passwd</code> - Character encoding bypass</li>
                <li><code>..%5c..%5c..%5cetc%5cpasswd</code> - Windows path bypass</li>
            </ul>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test different bypass techniques:</p>
            <ul>
                <li><a href="?file=....//....//....//etc/passwd&filter=basic" style="color: var(--accent-green);">Test Double Slash Bypass</a></li>
                <li><a href="?file=..%2f..%2f..%2fetc/passwd&filter=slash_filter" style="color: var(--accent-green);">Test URL Encoding Bypass</a></li>
                <li><a href="?file=..%252f..%252f..%252fetc/passwd&filter=encoding" style="color: var(--accent-green);">Test Double Encoding Bypass</a></li>
                <li><a href="?file=.../.../.../etc/passwd&filter=double_dot" style="color: var(--accent-green);">Test Triple Dot Bypass</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Advanced Attack Scenarios</h5>
            <ul>
                <li>Bypassing WAF and security filters</li>
                <li>Advanced encoding and obfuscation techniques</li>
                <li>Protocol confusion attacks</li>
                <li>Multi-stage payload delivery</li>
                <li>Context-aware traversal attacks</li>
                <li>Client-side security control bypasses</li>
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
                    <li>Implement proper path validation and restriction</li>
                    <li>Use absolute paths with proper validation</li>
                    <li>Implement proper error handling</li>
                    <li>Regular security testing and filter updates</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
