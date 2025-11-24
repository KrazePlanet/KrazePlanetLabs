<?php
// Lab 5: Advanced Filter Bypass Stored XSS
// Vulnerability: Bypassing various filtering mechanisms

session_start();

// Initialize posts array if not exists
if (!isset($_SESSION['posts'])) {
    $_SESSION['posts'] = array();
}

// Filter types and their implementations
$filter_type = $_GET['filter'] ?? 'basic';

function applyFilter($content, $type) {
    switch ($type) {
        case 'basic':
            // No filtering - basic vulnerability
            return $content;
            
        case 'script_tag':
            // Filter: Block script tags
            return str_ireplace(array('<script', '</script>'), array('&lt;script', '&lt;/script&gt;'), $content);
            
        case 'event_handlers':
            // Filter: Block common event handlers
            $event_handlers = array('onload', 'onclick', 'onmouseover', 'onerror', 'onfocus', 'onblur');
            foreach ($event_handlers as $handler) {
                $content = str_ireplace($handler, 'blocked_' . $handler, $content);
            }
            return $content;
            
        case 'javascript_protocol':
            // Filter: Block javascript: protocol
            return str_ireplace('javascript:', 'blocked_javascript:', $content);
            
        case 'case_sensitive':
            // Filter: Case-sensitive blocking
            return str_replace(array('<SCRIPT', '</SCRIPT>'), array('&lt;SCRIPT', '&lt;/SCRIPT&gt;'), $content);
            
        case 'double_encode':
            // Filter: Basic double encoding detection
            if (strpos($content, '%') !== false) {
                return 'Encoding detected and blocked';
            }
            return $content;
            
        default:
            return $content;
    }
}

// Handle form submission
if ($_POST && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $timestamp = date('Y-m-d H:i:s');
    $id = uniqid();
    
    // Apply filter based on current filter type
    $filtered_title = applyFilter($title, $filter_type);
    $filtered_content = applyFilter($content, $filter_type);
    
    // Store the filtered content
    $_SESSION['posts'][] = array(
        'id' => $id,
        'title' => $filtered_title,
        'content' => $filtered_content,
        'original_title' => $title,
        'original_content' => $content,
        'filter_type' => $filter_type,
        'timestamp' => $timestamp
    );
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF'] . '?filter=' . urlencode($filter_type));
    exit();
}

// Delete post if requested
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $deleteId = $_GET['id'];
    $_SESSION['posts'] = array_filter($_SESSION['posts'], function($post) use ($deleteId) {
        return $post['id'] !== $deleteId;
    });
    header('Location: ' . $_SERVER['PHP_SELF'] . '?filter=' . urlencode($filter_type));
    exit();
}

// Clear all posts if requested
if (isset($_GET['clear'])) {
    $_SESSION['posts'] = array();
    header('Location: ' . $_SERVER['PHP_SELF'] . '?filter=' . urlencode($filter_type));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored XSS Lab 5 - Advanced Filter Bypass XSS</title>
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

        .btn-danger {
            background: linear-gradient(90deg, var(--accent-red), #e53e3e);
            border: none;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
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

        .filter-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-green);
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

        .post-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-green);
        }

        .post-title {
            font-weight: 600;
            color: var(--accent-green);
            margin-bottom: 0.5rem;
        }

        .post-content {
            color: #e2e8f0;
            margin-bottom: 0.5rem;
        }

        .post-meta {
            font-size: 0.8rem;
            color: #94a3b8;
            border-top: 1px solid #334155;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }

        .post-actions {
            margin-top: 0.5rem;
        }

        .post-actions a {
            color: var(--accent-red);
            text-decoration: none;
            font-size: 0.8rem;
        }

        .post-actions a:hover {
            text-decoration: underline;
        }

        .filter-status {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .bypass-payloads {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--accent-orange);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Stored XSS Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../../contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Stored XSS Lab 5</h1>
            <p class="hero-subtitle">Advanced Filter Bypass XSS</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates various filtering mechanisms and their bypass techniques for stored XSS vulnerabilities. Test different bypass methods against different filters.</p>
            <p><strong>Objective:</strong> Master advanced filter bypass techniques and understand how inadequate filtering can be exploited.</p>
        </div>

        <div class="filter-info">
            <h5><i class="bi bi-shield-exclamation me-2"></i>Available Filters</h5>
            <div class="row">
                <div class="col-md-6">
                    <h6>Filter Types:</h6>
                    <ul>
                        <li><code>basic</code> - No filtering</li>
                        <li><code>script_tag</code> - Blocks script tags</li>
                        <li><code>event_handlers</code> - Blocks event handlers</li>
                        <li><code>javascript_protocol</code> - Blocks javascript: protocol</li>
                        <li><code>case_sensitive</code> - Case-sensitive blocking</li>
                        <li><code>double_encode</code> - Blocks URL encoding</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Current Filter:</h6>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($filter_type); ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
function applyFilter($content, $type) {
    switch ($type) {
        case 'script_tag':
            return str_ireplace(array('&lt;script', '&lt;/script&gt;'), 
                               array('&lt;script', '&lt;/script&gt;'), $content);
        case 'event_handlers':
            $handlers = array('onload', 'onclick', 'onmouseover');
            foreach ($handlers as $handler) {
                $content = str_ireplace($handler, 'blocked_' . $handler, $content);
            }
            return $content;
        // ... other filters
    }
}

$filtered_content = applyFilter($content, $filter_type);
// Store and display filtered content</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-input-cursor-text me-2"></i>Create New Post
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="filter" class="form-label">Filter Type</label>
                                <select class="form-select" id="filter" name="filter" onchange="this.form.submit()">
                                    <option value="basic" <?php echo $filter_type === 'basic' ? 'selected' : ''; ?>>Basic (No Filter)</option>
                                    <option value="script_tag" <?php echo $filter_type === 'script_tag' ? 'selected' : ''; ?>>Script Tag Filter</option>
                                    <option value="event_handlers" <?php echo $filter_type === 'event_handlers' ? 'selected' : ''; ?>>Event Handlers Filter</option>
                                    <option value="javascript_protocol" <?php echo $filter_type === 'javascript_protocol' ? 'selected' : ''; ?>>JavaScript Protocol Filter</option>
                                    <option value="case_sensitive" <?php echo $filter_type === 'case_sensitive' ? 'selected' : ''; ?>>Case Sensitive Filter</option>
                                    <option value="double_encode" <?php echo $filter_type === 'double_encode' ? 'selected' : ''; ?>>Double Encode Filter</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Post Title</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Enter post title" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Post Content</label>
                                <textarea class="form-control" id="content" name="content" rows="3" 
                                          placeholder="Write your post content..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-file-text me-2"></i>Posts (<?php echo count($_SESSION['posts']); ?>)</span>
                        <a href="?clear=1&filter=<?php echo urlencode($filter_type); ?>" class="btn btn-danger btn-sm">Clear All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($_SESSION['posts'])): ?>
                            <p class="text-muted">No posts yet. Create one above to test the vulnerability!</p>
                        <?php else: ?>
                            <?php foreach (array_reverse($_SESSION['posts']) as $post): ?>
                                <div class="post-item">
                                    <div class="post-title">
                                        <?php echo $post['title']; // Vulnerable: Direct output without sanitization ?>
                                    </div>
                                    <div class="post-content">
                                        <?php echo $post['content']; // Vulnerable: Direct output without sanitization ?>
                                    </div>
                                    <div class="post-meta">
                                        Filter: <?php echo htmlspecialchars($post['filter_type']); ?> | 
                                        Posted: <?php echo htmlspecialchars($post['timestamp']); ?>
                                    </div>
                                    <div class="post-actions">
                                        <a href="?delete=1&id=<?php echo $post['id']; ?>&filter=<?php echo urlencode($filter_type); ?>">Delete</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Advanced Stored XSS with Filter Bypasses</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameters:</strong> <code>title</code>, <code>content</code></li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Inadequate filtering mechanisms</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Payloads by Filter Type</h5>
                    
                    <div class="bypass-payloads">
                        <strong>Script Tag Filter Bypasses:</strong>
                        <ul>
                            <li><code>&lt;ScRiPt&gt;alert('XSS')&lt;/ScRiPt&gt;</code></li>
                            <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                            <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        </ul>
                    </div>
                    
                    <div class="bypass-payloads">
                        <strong>Event Handlers Filter Bypasses:</strong>
                        <ul>
                            <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                            <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                            <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        </ul>
                    </div>
                    
                    <div class="bypass-payloads">
                        <strong>JavaScript Protocol Filter Bypasses:</strong>
                        <ul>
                            <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                            <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                            <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                        </ul>
                    </div>
                    
                    <div class="bypass-payloads">
                        <strong>Case Sensitive Filter Bypasses:</strong>
                        <ul>
                            <li><code>&lt;ScRiPt&gt;alert('XSS')&lt;/ScRiPt&gt;</code></li>
                            <li><code>&lt;SCRIPT&gt;alert('XSS')&lt;/SCRIPT&gt;</code></li>
                            <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        </ul>
                    </div>
                    
                    <div class="bypass-payloads">
                        <strong>Double Encode Filter Bypasses:</strong>
                        <ul>
                            <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                            <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                            <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        </ul>
                    </div>
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
                <li>Context-aware XSS attacks</li>
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
                    <li>Implement proper context-aware output encoding</li>
                    <li>Use Content Security Policy (CSP) headers</li>
                    <li>Regular security testing and filter updates</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                    <li>Implement proper input validation libraries</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
