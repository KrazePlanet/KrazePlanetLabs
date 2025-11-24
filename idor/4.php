<?php
// Lab 4: API Endpoint Access IDOR
// Vulnerability: Direct API endpoint access without proper authorization

session_start();

// Simulate user authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user
    $_SESSION['username'] = 'user1';
    $_SESSION['api_key'] = 'user1_api_key_123';
}

$message = '';
$api_data = '';
$endpoint = $_GET['endpoint'] ?? 'users';
$resource_id = $_GET['resource_id'] ?? '';

// Simulate API resources
$api_resources = [
    'users' => [
        1 => [
            'id' => 1,
            'username' => 'user1',
            'email' => 'user1@example.com',
            'profile' => [
                'name' => 'John Doe',
                'phone' => '+1-555-0123',
                'address' => '123 Main St, City, State'
            ],
            'api_usage' => [
                'requests_today' => 150,
                'requests_month' => 4500,
                'last_request' => '2024-01-15 10:30:00'
            ]
        ],
        2 => [
            'id' => 2,
            'username' => 'user2',
            'email' => 'user2@example.com',
            'profile' => [
                'name' => 'Jane Smith',
                'phone' => '+1-555-0124',
                'address' => '456 Oak Ave, City, State'
            ],
            'api_usage' => [
                'requests_today' => 200,
                'requests_month' => 6000,
                'last_request' => '2024-01-15 09:45:00'
            ]
        ],
        3 => [
            'id' => 3,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'profile' => [
                'name' => 'Admin User',
                'phone' => '+1-555-0125',
                'address' => '789 Pine St, City, State'
            ],
            'api_usage' => [
                'requests_today' => 500,
                'requests_month' => 15000,
                'last_request' => '2024-01-15 11:15:00'
            ]
        ]
    ],
    'orders' => [
        1 => [
            'id' => 1,
            'user_id' => 1,
            'product' => 'Premium Plan',
            'amount' => 99.99,
            'status' => 'completed',
            'created_date' => '2024-01-10'
        ],
        2 => [
            'id' => 2,
            'user_id' => 2,
            'product' => 'Basic Plan',
            'amount' => 29.99,
            'status' => 'pending',
            'created_date' => '2024-01-12'
        ],
        3 => [
            'id' => 3,
            'user_id' => 3,
            'product' => 'Enterprise Plan',
            'amount' => 299.99,
            'status' => 'completed',
            'created_date' => '2024-01-14'
        ]
    ],
    'payments' => [
        1 => [
            'id' => 1,
            'user_id' => 1,
            'order_id' => 1,
            'amount' => 99.99,
            'payment_method' => 'credit_card',
            'card_last4' => '1234',
            'status' => 'success',
            'created_date' => '2024-01-10'
        ],
        2 => [
            'id' => 2,
            'user_id' => 2,
            'order_id' => 2,
            'amount' => 29.99,
            'payment_method' => 'paypal',
            'card_last4' => null,
            'status' => 'pending',
            'created_date' => '2024-01-12'
        ],
        3 => [
            'id' => 3,
            'user_id' => 3,
            'order_id' => 3,
            'amount' => 299.99,
            'payment_method' => 'bank_transfer',
            'card_last4' => null,
            'status' => 'success',
            'created_date' => '2024-01-14'
        ]
    ],
    'analytics' => [
        'total_users' => 1500,
        'active_users' => 1200,
        'revenue_today' => 2500.00,
        'revenue_month' => 75000.00,
        'conversion_rate' => 12.5,
        'top_products' => [
            'Premium Plan' => 450,
            'Basic Plan' => 800,
            'Enterprise Plan' => 150
        ]
    ]
];

// Vulnerable: No proper authorization check for API endpoints
if (isset($api_resources[$endpoint])) {
    if ($resource_id) {
        // Access specific resource
        if (isset($api_resources[$endpoint][$resource_id])) {
            $api_data = [
                'type' => 'resource',
                'endpoint' => $endpoint,
                'resource_id' => $resource_id,
                'data' => $api_resources[$endpoint][$resource_id]
            ];
            $message = '<div class="alert alert-success">API resource loaded successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Resource not found!</div>';
        }
    } else {
        // Access all resources in endpoint
        $api_data = [
            'type' => 'endpoint',
            'endpoint' => $endpoint,
            'data' => $api_resources[$endpoint]
        ];
        $message = '<div class="alert alert-success">API endpoint loaded successfully!</div>';
    }
} else {
    $message = '<div class="alert alert-danger">Invalid API endpoint!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: API Endpoint Access - IDOR</title>
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
            <h1 class="hero-title">Lab 4: API Endpoint Access</h1>
            <p class="hero-subtitle">IDOR in API endpoint access control</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates an IDOR vulnerability in an API system. The application allows users to access any API endpoint and resource by simply changing the endpoint and resource_id parameters without proper authorization checks.</p>
            <p><strong>Objective:</strong> Access unauthorized API endpoints and resources by manipulating the endpoint and resource_id parameters to view sensitive data.</p>
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

// Simulate API resources
$api_resources = [
    'users' => [...],
    'orders' => [...],
    'payments' => [...],
    'analytics' => [...]
];

// Direct access without checking if user is authorized
if (isset($api_resources[$endpoint])) {
    if ($resource_id) {
        // Access specific resource
        $api_data = $api_resources[$endpoint][$resource_id];
    } else {
        // Access all resources in endpoint
        $api_data = $api_resources[$endpoint];
    }
}

// Example vulnerable usage:
// ?endpoint=users (user data)
// ?endpoint=orders (order data)
// ?endpoint=payments (payment data)
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
                                <button class="nav-link <?php echo $endpoint === 'payments' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('payments')">Payments</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $endpoint === 'analytics' ? 'active' : ''; ?>" 
                                        onclick="loadAPIEndpoint('analytics')">Analytics</button>
                            </li>
                        </ul>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="endpoint" class="form-label">API Endpoint</label>
                                <select class="form-select" id="endpoint" name="endpoint">
                                    <option value="users" <?php echo $endpoint === 'users' ? 'selected' : ''; ?>>Users</option>
                                    <option value="orders" <?php echo $endpoint === 'orders' ? 'selected' : ''; ?>>Orders</option>
                                    <option value="payments" <?php echo $endpoint === 'payments' ? 'selected' : ''; ?>>Payments</option>
                                    <option value="analytics" <?php echo $endpoint === 'analytics' ? 'selected' : ''; ?>>Analytics</option>
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
                                <li><a href="?endpoint=payments" style="color: var(--accent-green);">Payments API</a></li>
                                <li><a href="?endpoint=analytics" style="color: var(--accent-green);">Analytics API</a></li>
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
                        <i class="bi bi-diagram-3 me-2"></i>API Response: <?php echo ucfirst($api_data['endpoint']); ?>
                        <span class="api-badge ms-2">API ACCESS</span>
                    </div>
                    <div class="card-body">
                        <div class="api-display">
                            <?php if ($api_data['type'] === 'endpoint'): ?>
                                <h5>API Endpoint Data</h5>
                                <?php foreach ($api_data['data'] as $id => $item): ?>
                                    <div class="api-info">
                                        <p><strong>ID:</strong> <?php echo $id; ?> | 
                                           <strong>Data:</strong> <?php echo is_array($item) ? json_encode($item, JSON_PRETTY_PRINT) : $item; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif ($api_data['type'] === 'resource'): ?>
                                <h5>API Resource Data</h5>
                                <div class="sensitive-data">
                                    <pre><?php echo json_encode($api_data['data'], JSON_PRETTY_PRINT); ?></pre>
                                </div>
                            <?php endif; ?>
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
                        <li><code>payments</code> - Payment data</li>
                        <li><code>analytics</code> - Analytics data</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>4.php?endpoint=users</code></li>
                        <li><code>4.php?endpoint=orders&resource_id=1</code></li>
                        <li><code>4.php?endpoint=payments&resource_id=2</code></li>
                        <li><code>4.php?endpoint=analytics</code></li>
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
                <li><a href="?endpoint=payments" style="color: var(--accent-green);">Payments API (Unauthorized Access)</a></li>
                <li><a href="?endpoint=analytics" style="color: var(--accent-green);">Analytics API (Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to API endpoints and resources</li>
                <li>Access to user data, orders, and payment information</li>
                <li>Viewing analytics and business intelligence data</li>
                <li>Data exfiltration and unauthorized data modification</li>
                <li>Bypassing API access controls and authorization mechanisms</li>
                <li>Compliance violations and privacy breaches</li>
                <li>API abuse and rate limiting bypass</li>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
