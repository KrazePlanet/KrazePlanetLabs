<?php
require_once 'config.php';
require_login();

$message = '';
$order_data = '';
$order_id = $_GET['order_id'] ?? '';
$action = $_GET['action'] ?? 'view';

// Handle order operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_order'])) {
        $product_name = $_POST['product_name'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);
        $price = (float)($_POST['price'] ?? 0);
        $shipping_address = $_POST['shipping_address'] ?? '';
        $payment_method = $_POST['payment_method'] ?? '';
        
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_name, quantity, price, status, shipping_address, payment_method) VALUES (?, ?, ?, ?, 'pending', ?, ?)");
            $stmt->execute([get_user_id(), $product_name, $quantity, $price, $shipping_address, $payment_method]);
            
            $message = '<div class="alert alert-success">Order created successfully!</div>';
            log_security_event('order_create', 'Order created: ' . $product_name);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to create order: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'] ?? 'pending';
        $shipping_address = $_POST['shipping_address'] ?? '';
        
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ?, shipping_address = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$status, $shipping_address, $order_id]);
            
            $message = '<div class="alert alert-success">Order updated successfully!</div>';
            log_security_event('order_update', 'Order updated: ID ' . $order_id);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to update order: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['cancel_order'])) {
        $order_id = $_POST['order_id'];
        
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ?");
            $stmt->execute([$order_id]);
            
            $message = '<div class="alert alert-success">Order cancelled successfully!</div>';
            log_security_event('order_cancel', 'Order cancelled: ID ' . $order_id);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to cancel order: ' . $e->getMessage() . '</div>';
        }
    }
}

// Vulnerable: No authorization check - direct access to any order
if ($order_id) {
    try {
        $stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$order_id]);
        $order_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order_data) {
            $message = '<div class="alert alert-danger">Order not found!</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error loading order: ' . $e->getMessage() . '</div>';
    }
}

// Get user's orders
$user_orders = get_user_orders(get_user_id());

// Get all orders for the dropdown (admin can see all)
$all_orders = [];
if (is_admin()) {
    $all_orders = get_all_orders();
} else {
    $all_orders = $user_orders;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Order Management IDOR - IDOR Labs</title>
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

        .order-display {
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

        .order-info {
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

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending { background: var(--accent-orange); color: white; }
        .status-processing { background: var(--accent-blue); color: white; }
        .status-shipped { background: var(--accent-green); color: white; }
        .status-delivered { background: var(--accent-green); color: white; }
        .status-cancelled { background: var(--accent-red); color: white; }

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
            <h1 class="hero-title">Lab 3: Order Management IDOR</h1>
            <p class="hero-subtitle">Real-world IDOR in order viewing and management functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a real-world IDOR vulnerability in an order management system. The application allows users to view, update, and cancel any order by simply changing the order_id parameter without proper authorization checks.</p>
            <p><strong>Objective:</strong> Access and manipulate other users' orders by manipulating the order_id parameter to view sensitive order information and make unauthorized changes.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No authorization check
$order_id = $_GET['order_id'] ?? '';

// Direct access to any order
$stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      WHERE o.id = ?");
$stmt->execute([$order_id]);
$order_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle order update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    // Update order without checking ownership
    $stmt = $pdo->prepare("UPDATE orders SET 
                          status = ?, shipping_address = ? 
                          WHERE id = ?");
    $stmt->execute([$status, $shipping_address, $order_id]);
}

// Example vulnerable usage:
// ?order_id=1 (own order - allowed)
// ?order_id=2 (other user's order - unauthorized access)
// ?order_id=3 (admin order - unauthorized access)</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-cart me-2"></i>Order Management
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <ul class="nav nav-pills mb-3" id="orderTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'view' ? 'active' : ''; ?>" 
                                        onclick="loadAction('view')">View Order</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'create' ? 'active' : ''; ?>" 
                                        onclick="loadAction('create')">Create Order</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="orderTabsContent">
                            <div class="tab-pane fade <?php echo $action === 'view' ? 'show active' : ''; ?>" id="view" role="tabpanel">
                                <form method="GET">
                                    <input type="hidden" name="action" value="view">
                                    <div class="mb-3">
                                        <label for="order_id" class="form-label">Select Order</label>
                                        <select class="form-select" id="order_id" name="order_id" onchange="this.form.submit()">
                                            <option value="">Select an order...</option>
                                            <?php foreach ($all_orders as $order): ?>
                                                <option value="<?php echo $order['id']; ?>" 
                                                        <?php echo $order_id == $order['id'] ? 'selected' : ''; ?>>
                                                    Order #<?php echo $order['id']; ?> - <?php echo htmlspecialchars($order['product_name']); ?> 
                                                    (<?php echo htmlspecialchars($order['username']); ?>)
                                                    - <?php echo ucfirst($order['status']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade <?php echo $action === 'create' ? 'show active' : ''; ?>" id="create" role="tabpanel">
                                <form method="POST">
                                    <input type="hidden" name="create_order" value="1">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="shipping_address" class="form-label">Shipping Address</label>
                                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="2" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method" required>
                                            <option value="">Select payment method...</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="paypal">PayPal</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Create Order
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?action=view&order_id=1" style="color: var(--accent-green);">View Order 1</a></li>
                                <li><a href="?action=view&order_id=2" style="color: var(--accent-green);">View Order 2</a></li>
                                <li><a href="?action=view&order_id=3" style="color: var(--accent-green);">View Order 3</a></li>
                                <li><a href="?action=view&order_id=4" style="color: var(--accent-green);">View Order 4</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($order_data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-cart me-2"></i>Order #<?php echo $order_data['id']; ?>: <?php echo htmlspecialchars($order_data['product_name']); ?>
                        <span class="status-badge status-<?php echo $order_data['status']; ?> ms-2">
                            <?php echo ucfirst($order_data['status']); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="order-display">
                            <h5>Order Information</h5>
                            <div class="order-info">
                                <p><strong>Order ID:</strong> #<?php echo $order_data['id']; ?></p>
                                <p><strong>Customer:</strong> <?php echo htmlspecialchars($order_data['username']); ?></p>
                                <p><strong>Product:</strong> <?php echo htmlspecialchars($order_data['product_name']); ?></p>
                                <p><strong>Quantity:</strong> <?php echo $order_data['quantity']; ?></p>
                                <p><strong>Price:</strong> $<?php echo number_format($order_data['price'], 2); ?></p>
                                <p><strong>Total:</strong> $<?php echo number_format($order_data['price'] * $order_data['quantity'], 2); ?></p>
                                <p><strong>Status:</strong> <?php echo ucfirst($order_data['status']); ?></p>
                                <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $order_data['payment_method'])); ?></p>
                                <p><strong>Created:</strong> <?php echo date('Y-m-d H:i:s', strtotime($order_data['created_at'])); ?></p>
                                <p><strong>Updated:</strong> <?php echo date('Y-m-d H:i:s', strtotime($order_data['updated_at'])); ?></p>
                            </div>
                            
                            <h5 class="mt-3">Shipping Information</h5>
                            <div class="sensitive-data">
                                <p><strong>Shipping Address:</strong></p>
                                <pre><?php echo htmlspecialchars($order_data['shipping_address']); ?></pre>
                            </div>
                        </div>
                        
                        <!-- Order Update Form -->
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="update_order" value="1">
                            <input type="hidden" name="order_id" value="<?php echo $order_data['id']; ?>">
                            <h5>Update Order</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Order Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending" <?php echo $order_data['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order_data['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order_data['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order_data['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order_data['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_address" class="form-label">Shipping Address</label>
                                    <textarea class="form-control" id="shipping_address" name="shipping_address" rows="2"><?php echo htmlspecialchars($order_data['shipping_address']); ?></textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Update Order
                                </button>
                                <button type="submit" class="btn btn-danger" name="cancel_order">
                                    <i class="bi bi-x-circle me-2"></i>Cancel Order
                                </button>
                            </div>
                        </form>
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
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameter:</strong> <code>order_id</code></li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Direct access to orders without authorization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these order_id values:</p>
                    <ul>
                        <li><code>1</code> - Order 1</li>
                        <li><code>2</code> - Order 2</li>
                        <li><code>3</code> - Order 3</li>
                        <li><code>4</code> - Order 4</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>3.php?action=view&order_id=1</code></li>
                        <li><code>3.php?action=view&order_id=3</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?action=view&order_id=1" style="color: var(--accent-green);">View Order 1</a></li>
                <li><a href="?action=view&order_id=2" style="color: var(--accent-green);">View Order 2 (Unauthorized Access)</a></li>
                <li><a href="?action=view&order_id=3" style="color: var(--accent-green);">View Order 3 (Unauthorized Access)</a></li>
                <li><a href="?action=view&order_id=4" style="color: var(--accent-green);">View Order 4 (Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to order information and customer data</li>
                <li>Modification of order status and shipping information</li>
                <li>Cancellation of other users' orders</li>
                <li>Access to sensitive shipping addresses and payment information</li>
                <li>Data exfiltration and unauthorized data modification</li>
                <li>Compliance violations and privacy breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper authorization checks before accessing orders</li>
                    <li>Use indirect object references instead of direct database IDs</li>
                    <li>Implement proper access control lists (ACLs) for order access</li>
                    <li>Use role-based access control (RBAC) for order management</li>
                    <li>Implement order-level permissions and ownership checks</li>
                    <li>Use whitelist-based validation for allowed orders</li>
                    <li>Implement proper logging and monitoring for order access</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function loadAction(action) {
            window.location.href = '?action=' + action;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
