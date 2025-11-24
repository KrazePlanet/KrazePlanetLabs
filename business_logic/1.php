<?php
// Lab 1: Price Manipulation
// Vulnerability: Price manipulation vulnerabilities

session_start();

$message = '';
$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Simulate price manipulation processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_item') {
        $item_id = $_POST['item_id'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 1);
        $price = floatval($_POST['price'] ?? 0);
        
        if (!empty($item_id) && $price > 0) {
            $cart[] = [
                'id' => $item_id,
                'name' => $_POST['item_name'] ?? 'Item',
                'quantity' => $quantity,
                'price' => $price,
                'total' => $price * $quantity
            ];
            $_SESSION['cart'] = $cart;
            $message = '<div class="alert alert-success">✅ Item added to cart successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid item data.</div>';
        }
    } elseif ($action === 'update_price') {
        $item_index = intval($_POST['item_index'] ?? -1);
        $new_price = floatval($_POST['new_price'] ?? 0);
        
        if ($item_index >= 0 && $item_index < count($cart) && $new_price >= 0) {
            $cart[$item_index]['price'] = $new_price;
            $cart[$item_index]['total'] = $new_price * $cart[$item_index]['quantity'];
            $_SESSION['cart'] = $cart;
            $message = '<div class="alert alert-success">✅ Price updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid price update.</div>';
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
    <title>Lab 1: Price Manipulation - Business Logic Labs</title>
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

        .price-warning {
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

        .price-manipulation-techniques {
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
            <h1 class="hero-title">Lab 1: Price Manipulation</h1>
            <p class="hero-subtitle">Price manipulation vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates price manipulation vulnerabilities where attackers can modify prices, discounts, and payment amounts to gain financial benefits or bypass pricing controls.</p>
            <p><strong>Objective:</strong> Understand how price manipulation attacks work and how to exploit them.</p>
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
                            <p>This system allows adding items to cart. Try to manipulate prices:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="add_item">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">Item ID</label>
                                    <input type="text" class="form-control" id="item_id" name="item_id" placeholder="ITEM001" required>
                                </div>
                                <div class="mb-3">
                                    <label for="item_name" class="form-label">Item Name</label>
                                    <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Product Name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="99.99" step="0.01" required>
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
                        <i class="bi bi-currency-dollar me-2"></i>Price Manipulation Tester
                    </div>
                    <div class="card-body">
                        <div class="price-warning">
                            <h5>⚠️ Price Manipulation Warning</h5>
                            <p>This lab demonstrates price manipulation vulnerabilities:</p>
                            <ul>
                                <li><code>Client-Side Validation</code> - No server-side price validation</li>
                                <li><code>Parameter Tampering</code> - Price can be modified in requests</li>
                                <li><code>Negative Prices</code> - Negative values allowed</li>
                                <li><code>No Authorization</code> - No price verification</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Manipulation Techniques</h5>
                            <p>These techniques can be used for price manipulation:</p>
                            <ul>
                                <li><code>Parameter Tampering</code> - Modify price parameters</li>
                                <li><code>Negative Prices</code> - Use negative values</li>
                                <li><code>Decimal Manipulation</code> - Use very small decimals</li>
                                <li><code>Client-Side Bypass</code> - Disable client validation</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testPriceManipulation()" class="btn btn-primary">Test Price Manipulation</button>
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
                                            <input type="hidden" name="action" value="update_price">
                                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm" name="new_price" placeholder="New Price" step="0.01">
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
                        <i class="bi bi-code-square me-2"></i>Price Manipulation Techniques
                    </div>
                    <div class="card-body">
                        <div class="price-manipulation-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Parameter Tampering</div>
                                <div class="technique-demo">// Modify price in POST request
{
  "item_id": "ITEM001",
  "item_name": "Expensive Product",
  "quantity": 1,
  "price": 0.01  // Original price was $99.99
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Negative Prices</div>
                                <div class="technique-demo">// Use negative prices
{
  "item_id": "ITEM001",
  "item_name": "Product",
  "quantity": 1,
  "price": -50.00  // Negative price
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Decimal Manipulation</div>
                                <div class="technique-demo">// Use very small decimals
{
  "item_id": "ITEM001",
  "item_name": "Product",
  "quantity": 1000,
  "price": 0.0001  // Very small price
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Client-Side Bypass</div>
                                <div class="technique-demo">// Disable client validation
document.getElementById('price').disabled = false;
document.getElementById('price').value = '0.01';
// Or modify form action
form.action = '/bypass_price_validation';</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Discount Manipulation</div>
                                <div class="technique-demo">// Manipulate discount codes
{
  "item_id": "ITEM001",
  "discount_code": "ADMIN100",
  "price": 99.99,
  "discount_percent": 100  // 100% discount
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Currency Manipulation</div>
                                <div class="technique-demo">// Change currency to get better rates
{
  "item_id": "ITEM001",
  "price": 99.99,
  "currency": "VND",  // Vietnamese Dong
  "exchange_rate": 0.000043  // Very low rate
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
                        <li><strong>Type:</strong> Price Manipulation</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> Parameter tampering</li>
                        <li><strong>Issue:</strong> No server-side validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Parameter Tampering:</strong> Modify price parameters</li>
                        <li><strong>Negative Prices:</strong> Use negative values</li>
                        <li><strong>Decimal Manipulation:</strong> Use very small decimals</li>
                        <li><strong>Client-Side Bypass:</strong> Disable client validation</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Price Manipulation Examples</h5>
            <p>Use these techniques to exploit price manipulation vulnerabilities:</p>
            
            <h6>1. Basic Price Manipulation:</h6>
            <div class="code-block">// Original request
POST /add_to_cart
{
  "item_id": "ITEM001",
  "item_name": "Expensive Product",
  "quantity": 1,
  "price": 99.99
}

// Manipulated request
POST /add_to_cart
{
  "item_id": "ITEM001",
  "item_name": "Expensive Product",
  "quantity": 1,
  "price": 0.01
}</div>

            <h6>2. Negative Price Attack:</h6>
            <div class="code-block">// Use negative prices to get refunds
{
  "item_id": "ITEM001",
  "item_name": "Product",
  "quantity": 1,
  "price": -50.00
}

// This could result in a refund of $50</div>

            <h6>3. Decimal Manipulation:</h6>
            <div class="code-block">// Use very small decimals
{
  "item_id": "ITEM001",
  "item_name": "Product",
  "quantity": 1000,
  "price": 0.0001
}

// Total: $0.10 instead of $99,990</div>

            <h6>4. Discount Code Manipulation:</h6>
            <div class="code-block">// Manipulate discount codes
{
  "item_id": "ITEM001",
  "discount_code": "ADMIN100",
  "price": 99.99,
  "discount_percent": 100,
  "discount_amount": 99.99
}

// Result: Free product</div>

            <h6>5. Currency Exchange Manipulation:</h6>
            <div class="code-block">// Change currency to get better rates
{
  "item_id": "ITEM001",
  "price": 99.99,
  "currency": "VND",
  "exchange_rate": 0.000043,
  "original_currency": "USD"
}

// Convert $99.99 to VND at very low rate</div>

            <h6>6. Quantity and Price Manipulation:</h6>
            <div class="code-block">// Combine quantity and price manipulation
{
  "item_id": "ITEM001",
  "item_name": "Product",
  "quantity": 1000,
  "price": 0.01,
  "bulk_discount": 90
}

// Get 1000 items for $10 instead of $99,990</div>

            <h6>7. Tax Manipulation:</h6>
            <div class="code-block">// Manipulate tax calculations
{
  "item_id": "ITEM001",
  "price": 99.99,
  "tax_rate": -10,  // Negative tax
  "tax_amount": -9.99
}

// Get tax refund instead of paying tax</div>

            <h6>8. Shipping Cost Manipulation:</h6>
            <div class="code-block">// Manipulate shipping costs
{
  "item_id": "ITEM001",
  "price": 99.99,
  "shipping_cost": -50.00,  // Negative shipping
  "free_shipping_threshold": 50
}

// Get free shipping and refund</div>

            <h6>9. Coupon Code Manipulation:</h6>
            <div class="code-block">// Manipulate coupon codes
{
  "item_id": "ITEM001",
  "price": 99.99,
  "coupon_code": "ADMIN100",
  "coupon_discount": 99.99,
  "coupon_type": "percentage",
  "coupon_value": 100
}

// 100% discount coupon</div>

            <h6>10. Membership Discount Manipulation:</h6>
            <div class="code-block">// Manipulate membership discounts
{
  "item_id": "ITEM001",
  "price": 99.99,
  "membership_level": "premium",
  "membership_discount": 50,
  "membership_override": true
}

// 50% discount for premium membership</div>

            <h6>11. Bulk Purchase Manipulation:</h6>
            <div class="code-block">// Manipulate bulk purchase discounts
{
  "item_id": "ITEM001",
  "price": 99.99,
  "quantity": 1000,
  "bulk_discount_percent": 90,
  "bulk_discount_amount": 899.91,
  "bulk_threshold": 100
}

// 90% bulk discount</div>

            <h6>12. Time-Based Price Manipulation:</h6>
            <div class="code-block">// Manipulate time-based pricing
{
  "item_id": "ITEM001",
  "price": 99.99,
  "sale_start": "2024-01-01",
  "sale_end": "2024-12-31",
  "sale_price": 0.01,
  "sale_active": true
}

// Always on sale for $0.01</div>

            <h6>13. Geographic Price Manipulation:</h6>
            <div class="code-block">// Manipulate geographic pricing
{
  "item_id": "ITEM001",
  "price": 99.99,
  "country": "US",
  "region": "CA",
  "local_price": 0.01,
  "currency": "USD",
  "exchange_rate": 1
}

// Use local pricing at very low rate</div>

            <h6>14. User Role Price Manipulation:</h6>
            <div class="code-block">// Manipulate user role pricing
{
  "item_id": "ITEM001",
  "price": 99.99,
  "user_role": "admin",
  "role_discount": 100,
  "role_override": true
}

// 100% discount for admin role</div>

            <h6>15. API Endpoint Manipulation:</h6>
            <div class="code-block">// Use different API endpoints
// Instead of /add_to_cart, use /admin/add_to_cart
POST /admin/add_to_cart
{
  "item_id": "ITEM001",
  "price": 0.01,
  "admin_override": true,
  "skip_validation": true
}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Financial losses and revenue impact</li>
                <li>Inventory manipulation and stock issues</li>
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
                    <li>Implement server-side price validation</li>
                    <li>Use secure price storage and retrieval</li>
                    <li>Implement proper authorization checks</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual pricing patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use secure session management</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use price verification systems</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testPriceManipulation() {
            alert('Price Manipulation test initiated. Try the manipulation techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
