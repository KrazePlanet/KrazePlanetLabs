<?php
// Lab 2: Document Access Control IDOR
// Vulnerability: Direct document access without authorization checks

session_start();

// Simulate user authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user
    $_SESSION['username'] = 'user1';
}

$message = '';
$document_data = '';
$doc_id = $_GET['doc_id'] ?? '1';

// Simulate document database
$documents = [
    1 => [
        'id' => 1,
        'title' => 'Project Alpha - Requirements',
        'content' => 'This document contains the requirements for Project Alpha...',
        'owner_id' => 1,
        'owner_name' => 'user1',
        'created_date' => '2024-01-15',
        'file_type' => 'PDF',
        'file_size' => '2.5 MB',
        'confidential' => false
    ],
    2 => [
        'id' => 2,
        'title' => 'Project Beta - Financial Report',
        'content' => 'This document contains sensitive financial information for Project Beta...',
        'owner_id' => 2,
        'owner_name' => 'user2',
        'created_date' => '2024-01-20',
        'file_type' => 'Excel',
        'file_size' => '1.8 MB',
        'confidential' => true
    ],
    3 => [
        'id' => 3,
        'title' => 'Company Strategy 2024',
        'content' => 'This document contains confidential company strategy and future plans...',
        'owner_id' => 3,
        'owner_name' => 'admin',
        'created_date' => '2024-01-25',
        'file_type' => 'Word',
        'file_size' => '3.2 MB',
        'confidential' => true
    ],
    4 => [
        'id' => 4,
        'title' => 'HR Policies and Procedures',
        'content' => 'This document contains HR policies and employee procedures...',
        'owner_id' => 3,
        'owner_name' => 'admin',
        'created_date' => '2024-01-30',
        'file_type' => 'PDF',
        'file_size' => '4.1 MB',
        'confidential' => true
    ]
];

// Vulnerable: No authorization check - direct access to any document
if (isset($documents[$doc_id])) {
    $document_data = $documents[$doc_id];
    $message = '<div class="alert alert-success">Document loaded successfully!</div>';
} else {
    $message = '<div class="alert alert-danger">Document not found!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: Document Access Control - IDOR</title>
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

        .form-control {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
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

        .document-display {
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

        .document-info {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .confidential-data {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-red);
        }

        .confidential-badge {
            background: var(--accent-red);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
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
            <h1 class="hero-title">Lab 2: Document Access Control</h1>
            <p class="hero-subtitle">IDOR in document viewing functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates an IDOR vulnerability in a document management system. The application allows users to view any document by simply changing the doc_id parameter without proper authorization checks, including confidential documents.</p>
            <p><strong>Objective:</strong> Access confidential documents by manipulating the doc_id parameter to view sensitive information.</p>
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
$doc_id = $_GET['doc_id'] ?? '1';

// Simulate document database
$documents = [
    1 => ['id' => 1, 'title' => 'Project Alpha', 'owner_id' => 1, ...],
    2 => ['id' => 2, 'title' => 'Financial Report', 'owner_id' => 2, 'confidential' => true, ...],
    3 => ['id' => 3, 'title' => 'Company Strategy', 'owner_id' => 3, 'confidential' => true, ...]
];

// Direct access without checking if user is authorized
if (isset($documents[$doc_id])) {
    $document_data = $documents[$doc_id];
    // Display document data
}

// Example vulnerable usage:
// ?doc_id=1 (own document - allowed)
// ?doc_id=2 (other user's confidential document - unauthorized access)
// ?doc_id=3 (admin's confidential document - unauthorized access)</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark me-2"></i>Document Viewer
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="doc_id" class="form-label">Document ID</label>
                                <input type="number" class="form-control" id="doc_id" name="doc_id" 
                                       placeholder="Enter document ID..." value="<?php echo htmlspecialchars($doc_id); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">View Document</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?doc_id=1" style="color: var(--accent-green);">View Document 1 (Your Document)</a></li>
                                <li><a href="?doc_id=2" style="color: var(--accent-green);">View Document 2 (Confidential)</a></li>
                                <li><a href="?doc_id=3" style="color: var(--accent-green);">View Document 3 (Admin Confidential)</a></li>
                                <li><a href="?doc_id=4" style="color: var(--accent-green);">View Document 4 (HR Confidential)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($document_data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark me-2"></i>Document: <?php echo htmlspecialchars($document_data['title']); ?>
                        <?php if ($document_data['confidential']): ?>
                            <span class="confidential-badge ms-2">CONFIDENTIAL</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="document-display">
                            <h5>Document Information</h5>
                            <div class="document-info">
                                <p><strong>Title:</strong> <?php echo htmlspecialchars($document_data['title']); ?></p>
                                <p><strong>Owner:</strong> <?php echo htmlspecialchars($document_data['owner_name']); ?></p>
                                <p><strong>Created Date:</strong> <?php echo htmlspecialchars($document_data['created_date']); ?></p>
                                <p><strong>File Type:</strong> <?php echo htmlspecialchars($document_data['file_type']); ?></p>
                                <p><strong>File Size:</strong> <?php echo htmlspecialchars($document_data['file_size']); ?></p>
                                <p><strong>Confidential:</strong> <?php echo $document_data['confidential'] ? 'Yes' : 'No'; ?></p>
                            </div>
                            
                            <h5 class="mt-3">Document Content</h5>
                            <div class="<?php echo $document_data['confidential'] ? 'confidential-data' : 'document-info'; ?>">
                                <p><?php echo htmlspecialchars($document_data['content']); ?></p>
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
                        <li><strong>Type:</strong> Insecure Direct Object Reference (IDOR)</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameter:</strong> <code>doc_id</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Direct access to documents without authorization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these doc_id values:</p>
                    <ul>
                        <li><code>1</code> - Project Alpha (Your Document)</li>
                        <li><code>2</code> - Financial Report (Confidential)</li>
                        <li><code>3</code> - Company Strategy (Admin Confidential)</li>
                        <li><code>4</code> - HR Policies (Admin Confidential)</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>2.php?doc_id=2</code></li>
                        <li><code>2.php?doc_id=3</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?doc_id=1" style="color: var(--accent-green);">View Document 1 (Your Document)</a></li>
                <li><a href="?doc_id=2" style="color: var(--accent-green);">View Document 2 (Confidential - Unauthorized Access)</a></li>
                <li><a href="?doc_id=3" style="color: var(--accent-green);">View Document 3 (Admin Confidential - Unauthorized Access)</a></li>
                <li><a href="?doc_id=4" style="color: var(--accent-green);">View Document 4 (HR Confidential - Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to confidential documents</li>
                <li>Access to financial reports and sensitive business data</li>
                <li>Viewing company strategy and future plans</li>
                <li>Access to HR policies and employee information</li>
                <li>Data exfiltration and unauthorized data modification</li>
                <li>Compliance violations and privacy breaches</li>
                <li>Competitive intelligence gathering</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper authorization checks before accessing documents</li>
                    <li>Use indirect object references instead of direct database IDs</li>
                    <li>Implement proper access control lists (ACLs) for documents</li>
                    <li>Use role-based access control (RBAC) for document access</li>
                    <li>Implement document-level permissions and ownership checks</li>
                    <li>Use whitelist-based validation for allowed documents</li>
                    <li>Implement proper logging and monitoring for document access</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
