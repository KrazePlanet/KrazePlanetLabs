<?php
require_once 'config.php';
require_login();

$message = '';
$api_data = '';
$endpoint = $_GET['endpoint'] ?? 'users';
$resource_id = $_GET['resource_id'] ?? '';
$api_key = $_GET['api_key'] ?? '';

// Handle API operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_api_key'])) {
        $permissions = $_POST['permissions'] ?? ['read'];
        $permissions_json = json_encode($permissions);
        
        try {
            $api_key_value = 'api_key_' . bin2hex(random_bytes(16));
            $stmt = $pdo->prepare("INSERT INTO api_keys (user_id, api_key, permissions, is_active) VALUES (?, ?, ?, TRUE)");
            $stmt->execute([get_user_id(), $api_key_value, $permissions_json]);
            
            $message = '<div class="alert alert-success">API key created successfully! Key: ' . $api_key_value . '</div>';
            log_security_event('api_key_create', 'API key created');
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to create API key: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['revoke_api_key'])) {
        $key_id = $_POST['key_id'];
        
        try {
            $stmt = $pdo->prepare("UPDATE api_keys SET is_active = FALSE WHERE id = ? AND user_id = ?");
            $stmt->execute([$key_id, get_user_id()]);
            
            $message = '<div class="alert alert-success">API key revoked successfully!</div>';
            log_security_event('api_key_revoke', 'API key revoked: ID ' . $key_id);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to revoke API key: ' . $e->getMessage() . '</div>';
        }
    }
}

// Vulnerable: No proper authorization check for API endpoints
switch ($endpoint) {
    case 'users':
        if ($resource_id) {
            $user = get_user_by_id($resource_id);
            if ($user) {
                $api_data = [
                    'type' => 'user',
                    'data' => $user
                ];
                $message = '<div class="alert alert-success">User data retrieved successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">User not found!</div>';
            }
        } else {
            $api_data = [
                'type' => 'users',
                'data' => get_all_users()
            ];
            $message = '<div class="alert alert-success">Users list retrieved successfully!</div>';
        }
        break;
        
    case 'orders':
        if ($resource_id) {
            $stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
            $stmt->execute([$resource_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                $api_data = [
                    'type' => 'order',
                    'data' => $order
                ];
                $message = '<div class="alert alert-success">Order data retrieved successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Order not found!</div>';
            }
        } else {
            $api_data = [
                'type' => 'orders',
                'data' => get_all_orders()
            ];
            $message = '<div class="alert alert-success">Orders list retrieved successfully!</div>';
        }
        break;
        
    case 'documents':
        if ($resource_id) {
            $stmt = $pdo->prepare("SELECT d.*, u.username FROM documents d JOIN users u ON d.user_id = u.id WHERE d.id = ?");
            $stmt->execute([$resource_id]);
            $document = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($document) {
                $api_data = [
                    'type' => 'document',
                    'data' => $document
                ];
                $message = '<div class="alert alert-success">Document data retrieved successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Document not found!</div>';
            }
        } else {
            $api_data = [
                'type' => 'documents',
                'data' => get_all_documents()
            ];
            $message = '<div class="alert alert-success">Documents list retrieved successfully!</div>';
        }
        break;
        
    case 'analytics':
        $analytics = [
            'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'active_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn(),
            'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'total_documents' => $pdo->query("SELECT COUNT(*) FROM documents")->fetchColumn(),
            'revenue_today' => $pdo->query("SELECT SUM(price * quantity) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn() ?: 0,
            'revenue_month' => $pdo->query("SELECT SUM(price * quantity) FROM orders WHERE MONTH(created_at) = MONTH(CURDATE())")->fetchColumn() ?: 0
        ];
        
        $api_data = [
            'type' => 'analytics',
            'data' => $analytics
        ];
        $message = '<div class="alert alert-success">Analytics data retrieved successfully!</div>';
        break;
        
    case 'logs':
        $logs = $pdo->query("SELECT sl.*, u.username FROM security_logs sl LEFT JOIN users u ON sl.user_id = u.id ORDER BY sl.created_at DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
        
        $api_data = [
            'type' => 'logs',
            'data' => $logs
        ];
        $message = '<div class="alert alert-success">Security logs retrieved successfully!</div>';
        break;
        
    default:
        $message = '<div class="alert alert-warning">Invalid API endpoint!</div>';
}

// Get user's API keys
$user_api_keys = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM api_keys WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([get_user_id()]);
    $user_api_keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Handle error silently
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5: API Access IDOR - IDOR Labs</title>
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

        .api-display {
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

        .api-info {
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

        .api-badge {
            background: var(--accent-blue);
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
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../contact">Contact Us</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars(get_username()); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 5: API Access IDOR</h1>
            <p class="hero-subtitle">Real-world IDOR in API endpoint access control</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a real-world IDOR vulnerability in an API system. The application allows users to access any API endpoint and resource by simply changing the endpoint and resource_id parameters without proper authorization checks.</p>
            <p><strong>Objective:</strong> Access unauthorized API endpoints and resources by manipulating the endpoint and resource_id parameters to view sensitive data and perform unauthorized operations.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No proper authorization check
$endpoint = $_GET['endpoint'] ?? 'users';
$resource_id = $_GET['resource_id'] ?? '';

// Direct access to any API endpoint
switch ($endpoint) {
    case 'users':
        if ($resource_id) {
            $user = get_user_by_id($resource_id);
            $api_data = ['type' => 'user', 'data' => $user];
        } else {
            $api_data = ['type' => 'users', 'data' => get_all_users()];
        }
        break;
    case 'orders':
        // Similar pattern for orders
        break;
    case 'analytics':
        // Access to analytics data
        break;
}

// Example vulnerable usage:
// ?endpoint=users (user data)
// ?endpoint=orders (order data)
// ?endpoint=analytics (analytics data)</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-3 me-2"></i>API Endpoint Access
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <ul class="nav nav-pills mb-3" id="apiTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $endpoint === 'users' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('users')">Users</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $endpoint === 'orders' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('orders')">Orders</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $endpoint === 'documents' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('documents')">Documents</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $endpoint === 'analytics' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('analytics')">Analytics</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $endpoint === 'logs' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('logs')">Logs</button>
                            </li>
                        </ul>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="endpoint" class="form-label">API Endpoint</label>
                                <select class="form-select" id="endpoint" name="endpoint" onchange="this.form.submit()">
                                    <option value="users" <?php echo $endpoint === 'users' ? 'selected' : ''; ?>>Users</option>
                                    <option value="orders" <?php echo $endpoint === 'orders' ? 'selected' : ''; ?>>Orders</option>
                                    <option value="documents" <?php echo $endpoint === 'documents' ? 'selected' : ''; ?>>Documents</option>
                                    <option value="analytics" <?php echo $endpoint === 'analytics' ? 'selected' : ''; ?>>Analytics</option>
                                    <option value="logs" <?php echo $endpoint === 'logs' ? 'selected' : ''; ?>>Logs</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="resource_id" class="form-label">Resource ID (Optional)</label>
                                <input type="number" class="form-control" id="resource_id" name="resource_id" 
                                       placeholder="Enter resource ID..." value="<?php echo htmlspecialchars($resource_id); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Access API</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?endpoint=users" style="color: var(--accent-green);">Users API</a></li>
                                <li><a href="?endpoint=orders" style="color: var(--accent-green);">Orders API</a></li>
                                <li><a href="?endpoint=documents" style="color: var(--accent-green);">Documents API</a></li>
                                <li><a href="?endpoint=analytics" style="color: var(--accent-green);">Analytics API</a></li>
                                <li><a href="?endpoint=logs" style="color: var(--accent-green);">Logs API</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($api_data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-3 me-2"></i>API Response: <?php echo ucfirst($endpoint); ?>
                        <span class="api-badge ms-2">API ACCESS</span>
                    </div>
                    <div class="card-body">
                        <div class="api-display">
                            <h5>API Response Data</h5>
                            <div class="sensitive-data">
                                <pre><?php echo json_encode($api_data, JSON_PRETTY_PRINT); ?></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- API Key Management -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-key me-2"></i>API Key Management
                    </div>
                    <div class="card-body">
                        <h5>Your API Keys</h5>
                        <?php if (empty($user_api_keys)): ?>
                            <p class="text-muted">No API keys found. Create one below.</p>
                        <?php else: ?>
                            <?php foreach ($user_api_keys as $key): ?>
                                <div class="api-info">
                                    <p><strong>Key:</strong> <?php echo htmlspecialchars($key['api_key']); ?></p>
                                    <p><strong>Permissions:</strong> <?php echo htmlspecialchars($key['permissions']); ?></p>
                                    <p><strong>Status:</strong> <?php echo $key['is_active'] ? 'Active' : 'Inactive'; ?></p>
                                    <p><strong>Created:</strong> <?php echo $key['created_at']; ?></p>
                                    <p><strong>Last Used:</strong> <?php echo $key['last_used'] ?? 'Never'; ?></p>
                                    <?php if ($key['is_active']): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="revoke_api_key" value="1">
                                            <input type="hidden" name="key_id" value="<?php echo $key['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Revoke</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <h5 class="mt-4">Create New API Key</h5>
                        <form method="POST">
                            <input type="hidden" name="create_api_key" value="1">
                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="read" name="permissions[]" value="read" checked>
                                    <label class="form-check-label" for="read">Read</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="write" name="permissions[]" value="write">
                                    <label class="form-check-label" for="write">Write</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="admin" name="permissions[]" value="admin">
                                    <label class="form-check-label" for="admin">Admin</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create API Key
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> Insecure Direct Object Reference (IDOR)</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameter:</strong> <code>endpoint</code>, <code>resource_id</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Direct access to API endpoints without authorization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these endpoint values:</p>
                    <ul>
                        <li><code>users</code> - User data</li>
                        <li><code>orders</code> - Order data</li>
                        <li><code>documents</code> - Document data</li>
                        <li><code>analytics</code> - Analytics data</li>
                        <li><code>logs</code> - Security logs</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>5.php?endpoint=users</code></li>
                        <li><code>5.php?endpoint=orders&resource_id=1</code></li>
                        <li><code>5.php?endpoint=analytics</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?endpoint=users" style="color: var(--accent-green);">Users API (Unauthorized Access)</a></li>
                <li><a href="?endpoint=orders" style="color: var(--accent-green);">Orders API (Unauthorized Access)</a></li>
                <li><a href="?endpoint=documents" style="color: var(--accent-green);">Documents API (Unauthorized Access)</a></li>
                <li><a href="?endpoint=analytics" style="color: var(--accent-green);">Analytics API (Unauthorized Access)</a></li>
                <li><a href="?endpoint=logs" style="color: var(--accent-green);">Logs API (Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to API endpoints and resources</li>
                <li>Access to user data, orders, and document information</li>
                <li>Viewing analytics and business intelligence data</li>
                <li>Access to security logs and sensitive information</li>
                <li>Data exfiltration and unauthorized data modification</li>
                <li>Bypassing API access controls and authorization mechanisms</li>
                <li>Compliance violations and privacy breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper API authentication and authorization</li>
                    <li>Use API keys and tokens for access control</li>
                    <li>Implement proper endpoint-level permissions</li>
                    <li>Use indirect object references instead of direct resource IDs</li>
                    <li>Implement proper API rate limiting and throttling</li>
                    <li>Use whitelist-based validation for allowed endpoints</li>
                    <li>Implement proper logging and monitoring for API access</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function loadAPIEndpoint(endpoint) {
            window.location.href = '?endpoint=' + endpoint;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
