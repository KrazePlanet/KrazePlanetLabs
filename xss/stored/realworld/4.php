<?php
require_once 'config.php';
requireLogin();

$user = getCurrentUser($pdo);
$error = '';
$success = '';

// Handle support ticket creation
if ($_POST && isset($_POST['create_ticket'])) {
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? 'medium';
    
    if (empty($subject) || empty($description)) {
        $error = 'Subject and description are required';
    } else {
        try {
            // Vulnerable: No sanitization of user input before storing in database
            $stmt = $pdo->prepare("
                INSERT INTO support_tickets (user_id, subject, description, priority) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$user['id'], $subject, $description, $priority]);
            $success = 'Support ticket created successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to create support ticket: ' . $e->getMessage();
        }
    }
}

// Get all support tickets
$stmt = $pdo->prepare("
    SELECT st.*, u.username, u.full_name 
    FROM support_tickets st 
    JOIN users u ON st.user_id = u.id 
    ORDER BY st.created_at DESC 
    LIMIT 20
");
$stmt->execute();
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: Support Ticket System - Real-World Stored XSS</title>
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

        .ticket-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .ticket-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent-green);
            margin-bottom: 0.5rem;
        }

        .ticket-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .ticket-priority {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .priority-low { background: rgba(72, 187, 120, 0.2); color: var(--accent-green); }
        .priority-medium { background: rgba(237, 137, 54, 0.2); color: var(--accent-orange); }
        .priority-high { background: rgba(245, 101, 101, 0.2); color: var(--accent-red); }
        .priority-critical { background: rgba(245, 101, 101, 0.4); color: #fff; }

        .ticket-status {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-open { background: rgba(72, 187, 120, 0.2); color: var(--accent-green); }
        .status-in_progress { background: rgba(237, 137, 54, 0.2); color: var(--accent-orange); }
        .status-resolved { background: rgba(66, 153, 225, 0.2); color: var(--accent-blue); }
        .status-closed { background: rgba(107, 114, 128, 0.2); color: #9ca3af; }

        .ticket-description {
            color: #e2e8f0;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .ticket-author {
            font-size: 0.9rem;
            color: #94a3b8;
            border-top: 1px solid #334155;
            padding-top: 0.5rem;
        }

        .alert-danger {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid var(--accent-red);
            color: var(--accent-red);
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            border: 1px solid var(--accent-green);
            color: var(--accent-green);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Labs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                </span>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 4: Support Ticket System</h1>
            <p class="hero-subtitle">Real-World Stored XSS in Support Tickets</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a real-world stored XSS vulnerability in a support ticket system. Support tickets are stored in a MySQL database and displayed to all users without proper sanitization.</p>
            <p><strong>Objective:</strong> Inject persistent XSS payloads in support ticket content that will execute for all users viewing the support tickets.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Backend Source Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle support ticket creation
if ($_POST && isset($_POST['create_ticket'])) {
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    
    // Vulnerable: No sanitization before database storage
    $stmt = $pdo->prepare("
        INSERT INTO support_tickets (user_id, subject, description, priority) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $subject, $description, $priority]);
}

// Display support tickets (also vulnerable)
foreach ($tickets as $ticket) {
    echo "&lt;h3&gt;" . $ticket['subject'] . "&lt;/h3&gt;";
    echo "&lt;div class='description'&gt;" . $ticket['description'] . "&lt;/div&gt;";
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-ticket-detailed me-2"></i>Create Support Ticket
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       placeholder="Brief description of your issue" required>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="6" 
                                          placeholder="Please provide detailed information about your issue..." required></textarea>
                            </div>
                            <button type="submit" name="create_ticket" class="btn btn-primary">Create Ticket</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-ticket-detailed me-2"></i>Support Tickets (<?php echo count($tickets); ?>)
                    </div>
                    <div class="card-body">
                        <?php if (empty($tickets)): ?>
                            <p class="text-muted">No support tickets yet. Create the first one!</p>
                        <?php else: ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <div class="ticket-item">
                                    <div class="ticket-header">
                                        <div>
                                            <div class="ticket-title">
                                                <?php echo $ticket['subject']; // Vulnerable: Direct output without sanitization ?>
                                            </div>
                                            <div class="ticket-meta">
                                                <span class="ticket-priority priority-<?php echo $ticket['priority']; ?>">
                                                    <?php echo ucfirst($ticket['priority']); ?>
                                                </span>
                                                <span class="ticket-status status-<?php echo $ticket['status']; ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?>
                                                </span>
                                                <span>
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?php echo date('M j, Y g:i A', strtotime($ticket['created_at'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ticket-description">
                                        <?php echo $ticket['description']; // Vulnerable: Direct output without sanitization ?>
                                    </div>
                                    
                                    <div class="ticket-author">
                                        <i class="bi bi-person-circle me-1"></i>
                                        Submitted by <?php echo htmlspecialchars($ticket['full_name'] ?: $ticket['username']); ?>
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
                        <li><strong>Type:</strong> Stored XSS in Support Tickets</li>
                        <li><strong>Severity:</strong> Critical</li>
                        <li><strong>Parameters:</strong> <code>subject</code>, <code>description</code></li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Storage:</strong> MySQL Database</li>
                        <li><strong>Issue:</strong> Support ticket fields vulnerable to XSS</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p><strong>Subject field:</strong></p>
                    <ul>
                        <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>Description field:</strong></p>
                    <ul>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                        <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;script&gt;document.location='http://evil.com'&lt;/script&gt;</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Support system defacement and hijacking</li>
                <li>Mass credential theft from support staff and users</li>
                <li>Malware distribution through malicious support tickets</li>
                <li>Session hijacking and account compromise</li>
                <li>Social engineering attacks through fake support responses</li>
                <li>Privilege escalation and admin access</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use <code>htmlspecialchars()</code> for HTML entity encoding</li>
                    <li>Implement Content Security Policy (CSP) headers</li>
                    <li>Validate and sanitize all user input before database storage</li>
                    <li>Use whitelist-based input validation for each field type</li>
                    <li>Implement proper output encoding based on context</li>
                    <li>Consider using a WAF (Web Application Firewall)</li>
                    <li>Implement ticket moderation and review processes</li>
                    <li>Restrict access to support tickets based on user roles</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
