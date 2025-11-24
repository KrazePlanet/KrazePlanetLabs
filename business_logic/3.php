<?php
// Lab 3: Workflow Flaws
// Vulnerability: Workflow flaws vulnerabilities

session_start();

$message = '';
$orders = $_SESSION['orders'] ?? [];
$order_id = 1;

// Simulate workflow flaws processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_order') {
        $customer_name = $_POST['customer_name'] ?? '';
        $amount = floatval($_POST['amount'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        
        if (!empty($customer_name) && $amount > 0) {
            $order = [
                'id' => $order_id++,
                'customer_name' => $customer_name,
                'amount' => $amount,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $orders[] = $order;
            $_SESSION['orders'] = $orders;
            $message = '<div class="alert alert-success">✅ Order created successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid order data.</div>';
        }
    } elseif ($action === 'update_status') {
        $order_id = intval($_POST['order_id'] ?? 0);
        $new_status = $_POST['new_status'] ?? '';
        
        if ($order_id > 0 && !empty($new_status)) {
            foreach ($orders as &$order) {
                if ($order['id'] == $order_id) {
                    $order['status'] = $new_status;
                    $order['updated_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            $_SESSION['orders'] = $orders;
            $message = '<div class="alert alert-success">✅ Order status updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Invalid status update.</div>';
        }
    } elseif ($action === 'bypass_workflow') {
        $bypass_type = $_POST['bypass_type'] ?? '';
        $bypass_data = $_POST['bypass_data'] ?? '';
        
        if (!empty($bypass_type) && !empty($bypass_data)) {
            $message = '<div class="alert alert-warning">⚠️ Workflow bypass attempt detected: ' . htmlspecialchars($bypass_type) . ' - ' . htmlspecialchars($bypass_data) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: Workflow Flaws - Business Logic Labs</title>
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

        .workflow-warning {
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

        .orders-display {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid #334155;
        }

        .order-item {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid #334155;
        }

        .workflow-flaws-techniques {
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

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background: var(--accent-orange);
            color: #1a202c;
        }

        .status-approved {
            background: var(--accent-green);
            color: #1a202c;
        }

        .status-rejected {
            background: var(--accent-red);
            color: #e2e8f0;
        }

        .status-completed {
            background: var(--accent-blue);
            color: #e2e8f0;
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
            <h1 class="hero-title">Lab 3: Workflow Flaws</h1>
            <p class="hero-subtitle">Workflow flaws vulnerabilities</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates workflow flaws vulnerabilities where attackers can bypass business process controls, skip approval steps, and manipulate workflow states to gain unauthorized access or benefits.</p>
            <p><strong>Objective:</strong> Understand how workflow flaws attacks work and how to exploit them.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-diagram-3 me-2"></i>Vulnerable Order System
                    </div>
                    <div class="card-body">
                        <div class="vulnerable-form">
                            <h5>Create Order</h5>
                            <p>This system allows creating orders. Try to bypass workflow controls:</p>
                            
                            <form method="POST">
                                <input type="hidden" name="action" value="create_order">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="John Doe" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="99.99" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Order</button>
                            </form>
                            
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-diagram-3 me-2"></i>Workflow Bypass Tester
                    </div>
                    <div class="card-body">
                        <div class="workflow-warning">
                            <h5>⚠️ Workflow Flaws Warning</h5>
                            <p>This lab demonstrates workflow flaws vulnerabilities:</p>
                            <ul>
                                <li><code>No Authorization</code> - No proper workflow authorization</li>
                                <li><code>Status Manipulation</code> - Status can be modified directly</li>
                                <li><code>Skip Approval</code> - Can skip approval steps</li>
                                <li><code>No Validation</code> - No workflow validation</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Bypass Techniques</h5>
                            <p>These techniques can be used for workflow bypass:</p>
                            <ul>
                                <li><code>Status Manipulation</code> - Modify workflow status</li>
                                <li><code>Skip Steps</code> - Bypass approval steps</li>
                                <li><code>Parameter Tampering</code> - Modify workflow parameters</li>
                                <li><code>Direct Access</code> - Access workflow endpoints directly</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3">
                            <button onclick="testWorkflowBypass()" class="btn btn-primary">Test Workflow Bypass</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($orders)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-list-ul me-2"></i>Orders List
                    </div>
                    <div class="card-body">
                        <div class="orders-display">
                            <?php foreach ($orders as $order): ?>
                            <div class="order-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <strong>Order #<?php echo $order['id']; ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="text-success">$<?php echo number_format($order['amount'], 2); ?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <div class="input-group">
                                                <select class="form-select form-select-sm" name="new_status">
                                                    <option value="pending">Pending</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="rejected">Rejected</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
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
                        <i class="bi bi-code-square me-2"></i>Workflow Flaws Techniques
                    </div>
                    <div class="card-body">
                        <div class="workflow-flaws-techniques">
                            <div class="technique-card">
                                <div class="technique-title">Status Manipulation</div>
                                <div class="technique-demo">// Modify workflow status directly
{
  "order_id": 1,
  "status": "completed"  // Skip approval steps
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Skip Approval Steps</div>
                                <div class="technique-demo">// Skip approval workflow
{
  "order_id": 1,
  "skip_approval": true,
  "status": "approved"
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Parameter Tampering</div>
                                <div class="technique-demo">// Modify workflow parameters
{
  "order_id": 1,
  "approver_id": "admin",
  "bypass_workflow": true
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Direct Access</div>
                                <div class="technique-demo">// Access workflow endpoints directly
POST /admin/approve_order
{
  "order_id": 1,
  "admin_override": true
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Role Escalation</div>
                                <div class="technique-demo">// Escalate user role
{
  "order_id": 1,
  "user_role": "admin",
  "approval_level": "high"
}</div>
                            </div>
                            
                            <div class="technique-card">
                                <div class="technique-title">Time Manipulation</div>
                                <div class="technique-demo">// Manipulate workflow timing
{
  "order_id": 1,
  "created_at": "2024-01-01",
  "expires_at": "2025-12-31"
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
                        <li><strong>Type:</strong> Workflow Flaws</li>
                        <li><strong>Severity:</strong> Medium</li>
                        <li><strong>Method:</strong> Status manipulation</li>
                        <li><strong>Issue:</strong> No proper workflow validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Attack Vectors</h5>
                    <ul>
                        <li><strong>Status Manipulation:</strong> Modify workflow status</li>
                        <li><strong>Skip Steps:</strong> Bypass approval steps</li>
                        <li><strong>Parameter Tampering:</strong> Modify workflow parameters</li>
                        <li><strong>Direct Access:</strong> Access workflow endpoints directly</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Workflow Flaws Examples</h5>
            <p>Use these techniques to exploit workflow flaws vulnerabilities:</p>
            
            <h6>1. Basic Status Manipulation:</h6>
            <div class="code-block">// Original request
POST /create_order
{
  "customer_name": "John Doe",
  "amount": 99.99,
  "status": "pending"
}

// Bypassed request
POST /create_order
{
  "customer_name": "John Doe",
  "amount": 99.99,
  "status": "completed"  // Skip approval
}</div>

            <h6>2. Skip Approval Steps:</h6>
            <div class="code-block">// Skip approval workflow
{
  "order_id": 1,
  "skip_approval": true,
  "status": "approved",
  "approver_id": "admin",
  "approval_date": "2024-01-01"
}</div>

            <h6>3. Parameter Tampering:</h6>
            <div class="code-block">// Modify workflow parameters
{
  "order_id": 1,
  "approver_id": "admin",
  "approval_level": "high",
  "bypass_workflow": true,
  "admin_override": true
}</div>

            <h6>4. Direct Access:</h6>
            <div class="code-block">// Access workflow endpoints directly
POST /admin/approve_order
{
  "order_id": 1,
  "admin_override": true,
  "skip_validation": true
}

// Or use different endpoints
POST /workflow/bypass
POST /admin/workflow/override
POST /internal/approve</div>

            <h6>5. Role Escalation:</h6>
            <div class="code-block">// Escalate user role
{
  "order_id": 1,
  "user_role": "admin",
  "approval_level": "high",
  "permissions": ["approve", "override", "bypass"]
}</div>

            <h6>6. Time Manipulation:</h6>
            <div class="code-block">// Manipulate workflow timing
{
  "order_id": 1,
  "created_at": "2024-01-01",
  "expires_at": "2025-12-31",
  "approval_deadline": "2024-12-31",
  "time_override": true
}</div>

            <h6>7. Workflow State Bypass:</h6>
            <div class="code-block">// Bypass workflow states
{
  "order_id": 1,
  "current_state": "completed",
  "target_state": "completed",
  "skip_states": ["pending", "approved"],
  "workflow_override": true
}</div>

            <h6>8. Approval Chain Bypass:</h6>
            <div class="code-block">// Bypass approval chain
{
  "order_id": 1,
  "approval_chain": ["admin", "manager"],
  "current_approver": "admin",
  "skip_chain": true,
  "direct_approval": true
}</div>

            <h6>9. Workflow Rules Bypass:</h6>
            <div class="code-block">// Bypass workflow rules
{
  "order_id": 1,
  "workflow_rules": {
    "require_approval": false,
    "require_documentation": false,
    "require_verification": false
  },
  "rule_override": true
}</div>

            <h6>10. Workflow Validation Bypass:</h6>
            <div class="code-block">// Bypass workflow validation
{
  "order_id": 1,
  "validation_checks": {
    "amount_check": false,
    "approver_check": false,
    "status_check": false
  },
  "skip_validation": true
}</div>

            <h6>11. Workflow Audit Bypass:</h6>
            <div class="code-block">// Bypass workflow audit
{
  "order_id": 1,
  "audit_trail": {
    "enabled": false,
    "log_actions": false,
    "track_changes": false
  },
  "audit_override": true
}</div>

            <h6>12. Workflow Permissions Bypass:</h6>
            <div class="code-block">// Bypass workflow permissions
{
  "order_id": 1,
  "permissions": {
    "can_approve": true,
    "can_override": true,
    "can_bypass": true,
    "admin_access": true
  },
  "permission_override": true
}</div>

            <h6>13. Workflow Context Bypass:</h6>
            <div class="code-block">// Bypass workflow context
{
  "order_id": 1,
  "workflow_context": {
    "environment": "production",
    "user_type": "admin",
    "access_level": "high"
  },
  "context_override": true
}</div>

            <h6>14. Workflow History Bypass:</h6>
            <div class="code-block">// Bypass workflow history
{
  "order_id": 1,
  "workflow_history": {
    "track_changes": false,
    "log_approvals": false,
    "record_actions": false
  },
  "history_override": true
}</div>

            <h6>15. Workflow Integration Bypass:</h6>
            <div class="code-block">// Bypass workflow integration
{
  "order_id": 1,
  "workflow_integration": {
    "external_systems": false,
    "api_validation": false,
    "service_checks": false
  },
  "integration_override": true
}</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Business process disruption and operational impact</li>
                <li>Unauthorized access and privilege escalation</li>
                <li>Compliance violations and legal issues</li>
                <li>Data manipulation and integrity issues</li>
                <li>Financial losses and revenue impact</li>
                <li>Inventory manipulation and stock issues</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper workflow validation</li>
                    <li>Use secure workflow state management</li>
                    <li>Implement proper authorization checks</li>
                    <li>Use secure coding practices</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual workflow patterns</li>
                    <li>Implement proper input validation</li>
                    <li>Use secure session management</li>
                    <li>Implement proper error handling</li>
                    <li>Educate users about security threats</li>
                    <li>Use multi-factor authentication</li>
                    <li>Implement proper logging and monitoring</li>
                    <li>Use workflow verification systems</li>
                    <li>Implement proper audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function testWorkflowBypass() {
            alert('Workflow Bypass test initiated. Try the bypass techniques above.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
