<?php
require_once 'config.php';
require_login();

$message = '';
$document_data = '';
$doc_id = $_GET['doc_id'] ?? '';
$action = $_GET['action'] ?? 'view';

// Handle document operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_document'])) {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $file_type = $_POST['file_type'] ?? 'TXT';
        $confidential = isset($_POST['confidential']);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO documents (user_id, title, content, file_type, file_size, confidential) VALUES (?, ?, ?, ?, ?, ?)");
            $file_size = strlen($content);
            $stmt->execute([get_user_id(), $title, $content, $file_type, $file_size, $confidential]);
            
            $message = '<div class="alert alert-success">Document created successfully!</div>';
            log_security_event('document_create', 'Document created: ' . $title);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to create document: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['update_document'])) {
        $doc_id = $_POST['doc_id'];
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $file_type = $_POST['file_type'] ?? 'TXT';
        $confidential = isset($_POST['confidential']);
        
        try {
            $stmt = $pdo->prepare("UPDATE documents SET title = ?, content = ?, file_type = ?, file_size = ?, confidential = ?, updated_at = NOW() WHERE id = ?");
            $file_size = strlen($content);
            $stmt->execute([$title, $content, $file_type, $file_size, $confidential, $doc_id]);
            
            $message = '<div class="alert alert-success">Document updated successfully!</div>';
            log_security_event('document_update', 'Document updated: ' . $title);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to update document: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['delete_document'])) {
        $doc_id = $_POST['doc_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
            $stmt->execute([$doc_id]);
            
            $message = '<div class="alert alert-success">Document deleted successfully!</div>';
            log_security_event('document_delete', 'Document deleted: ID ' . $doc_id);
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Failed to delete document: ' . $e->getMessage() . '</div>';
        }
    }
}

// Vulnerable: No authorization check - direct access to any document
if ($doc_id) {
    try {
        $stmt = $pdo->prepare("SELECT d.*, u.username FROM documents d JOIN users u ON d.user_id = u.id WHERE d.id = ?");
        $stmt->execute([$doc_id]);
        $document_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$document_data) {
            $message = '<div class="alert alert-danger">Document not found!</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error loading document: ' . $e->getMessage() . '</div>';
    }
}

// Get user's documents
$user_documents = get_user_documents(get_user_id());

// Get all documents for the dropdown (admin can see all)
$all_documents = [];
if (is_admin()) {
    $all_documents = get_all_documents();
} else {
    $all_documents = $user_documents;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: Document Management IDOR - IDOR Labs</title>
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
            <h1 class="hero-title">Lab 2: Document Management IDOR</h1>
            <p class="hero-subtitle">Real-world IDOR in document viewing and management functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a real-world IDOR vulnerability in a document management system. The application allows users to view, edit, and delete any document by simply changing the doc_id parameter without proper authorization checks.</p>
            <p><strong>Objective:</strong> Access and manipulate other users' documents by manipulating the doc_id parameter to view confidential information and make unauthorized changes.</p>
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
$doc_id = $_GET['doc_id'] ?? '';

// Direct access to any document
$stmt = $pdo->prepare("SELECT d.*, u.username FROM documents d 
                      JOIN users u ON d.user_id = u.id 
                      WHERE d.id = ?");
$stmt->execute([$doc_id]);
$document_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle document update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc_id = $_POST['doc_id'];
    // Update document without checking ownership
    $stmt = $pdo->prepare("UPDATE documents SET 
                          title = ?, content = ?, confidential = ? 
                          WHERE id = ?");
    $stmt->execute([$title, $content, $confidential, $doc_id]);
}

// Example vulnerable usage:
// ?doc_id=1 (own document - allowed)
// ?doc_id=2 (other user's document - unauthorized access)
// ?doc_id=3 (confidential document - unauthorized access)</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark me-2"></i>Document Management
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <ul class="nav nav-pills mb-3" id="documentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'view' ? 'active' : ''; ?>" 
                                        onclick="loadAction('view')">View Document</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $action === 'create' ? 'active' : ''; ?>" 
                                        onclick="loadAction('create')">Create Document</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="documentTabsContent">
                            <div class="tab-pane fade <?php echo $action === 'view' ? 'show active' : ''; ?>" id="view" role="tabpanel">
                                <form method="GET">
                                    <input type="hidden" name="action" value="view">
                                    <div class="mb-3">
                                        <label for="doc_id" class="form-label">Select Document</label>
                                        <select class="form-select" id="doc_id" name="doc_id" onchange="this.form.submit()">
                                            <option value="">Select a document...</option>
                                            <?php foreach ($all_documents as $doc): ?>
                                                <option value="<?php echo $doc['id']; ?>" 
                                                        <?php echo $doc_id == $doc['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($doc['title']); ?> 
                                                    (<?php echo htmlspecialchars($doc['username']); ?>)
                                                    <?php if ($doc['confidential']): ?>
                                                        - CONFIDENTIAL
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade <?php echo $action === 'create' ? 'show active' : ''; ?>" id="create" role="tabpanel">
                                <form method="POST">
                                    <input type="hidden" name="create_document" value="1">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Document Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Document Content</label>
                                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="file_type" class="form-label">File Type</label>
                                            <select class="form-select" id="file_type" name="file_type">
                                                <option value="TXT">Text</option>
                                                <option value="PDF">PDF</option>
                                                <option value="DOCX">Word</option>
                                                <option value="XLSX">Excel</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="confidential" name="confidential">
                                                <label class="form-check-label" for="confidential">
                                                    Confidential Document
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Create Document
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?action=view&doc_id=1" style="color: var(--accent-green);">View Document 1</a></li>
                                <li><a href="?action=view&doc_id=2" style="color: var(--accent-green);">View Document 2</a></li>
                                <li><a href="?action=view&doc_id=3" style="color: var(--accent-green);">View Document 3</a></li>
                                <li><a href="?action=view&doc_id=4" style="color: var(--accent-green);">View Document 4</a></li>
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
                                <p><strong>Author:</strong> <?php echo htmlspecialchars($document_data['username']); ?></p>
                                <p><strong>File Type:</strong> <?php echo htmlspecialchars($document_data['file_type']); ?></p>
                                <p><strong>File Size:</strong> <?php echo number_format($document_data['file_size']); ?> bytes</p>
                                <p><strong>Confidential:</strong> <?php echo $document_data['confidential'] ? 'Yes' : 'No'; ?></p>
                                <p><strong>Created:</strong> <?php echo date('Y-m-d H:i:s', strtotime($document_data['created_at'])); ?></p>
                                <p><strong>Updated:</strong> <?php echo date('Y-m-d H:i:s', strtotime($document_data['updated_at'])); ?></p>
                            </div>
                            
                            <h5 class="mt-3">Document Content</h5>
                            <div class="<?php echo $document_data['confidential'] ? 'confidential-data' : 'document-info'; ?>">
                                <pre><?php echo htmlspecialchars($document_data['content']); ?></pre>
                            </div>
                        </div>
                        
                        <!-- Document Update Form -->
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="update_document" value="1">
                            <input type="hidden" name="doc_id" value="<?php echo $document_data['id']; ?>">
                            <h5>Update Document</h5>
                            <div class="mb-3">
                                <label for="title" class="form-label">Document Title</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($document_data['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Document Content</label>
                                <textarea class="form-control" id="content" name="content" rows="4" required><?php echo htmlspecialchars($document_data['content']); ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="file_type" class="form-label">File Type</label>
                                    <select class="form-select" id="file_type" name="file_type">
                                        <option value="TXT" <?php echo $document_data['file_type'] === 'TXT' ? 'selected' : ''; ?>>Text</option>
                                        <option value="PDF" <?php echo $document_data['file_type'] === 'PDF' ? 'selected' : ''; ?>>PDF</option>
                                        <option value="DOCX" <?php echo $document_data['file_type'] === 'DOCX' ? 'selected' : ''; ?>>Word</option>
                                        <option value="XLSX" <?php echo $document_data['file_type'] === 'XLSX' ? 'selected' : ''; ?>>Excel</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="confidential" name="confidential" 
                                               <?php echo $document_data['confidential'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="confidential">
                                            Confidential Document
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Update Document
                                </button>
                                <button type="submit" class="btn btn-danger" name="delete_document">
                                    <i class="bi bi-trash me-2"></i>Delete Document
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
                        <li><strong>Parameter:</strong> <code>doc_id</code></li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Direct access to documents without authorization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these doc_id values:</p>
                    <ul>
                        <li><code>1</code> - Document 1</li>
                        <li><code>2</code> - Document 2</li>
                        <li><code>3</code> - Document 3 (Confidential)</li>
                        <li><code>4</code> - Document 4</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>2.php?action=view&doc_id=1</code></li>
                        <li><code>2.php?action=view&doc_id=3</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?action=view&doc_id=1" style="color: var(--accent-green);">View Document 1</a></li>
                <li><a href="?action=view&doc_id=2" style="color: var(--accent-green);">View Document 2 (Unauthorized Access)</a></li>
                <li><a href="?action=view&doc_id=3" style="color: var(--accent-green);">View Document 3 (Confidential - Unauthorized Access)</a></li>
                <li><a href="?action=view&doc_id=4" style="color: var(--accent-green);">View Document 4 (Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to confidential documents</li>
                <li>Modification of other users' documents</li>
                <li>Deletion of important business documents</li>
                <li>Access to sensitive business information</li>
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
                    <li>Implement proper authorization checks before accessing documents</li>
                    <li>Use indirect object references instead of direct database IDs</li>
                    <li>Implement proper access control lists (ACLs) for document access</li>
                    <li>Use role-based access control (RBAC) for document management</li>
                    <li>Implement document-level permissions and ownership checks</li>
                    <li>Use whitelist-based validation for allowed documents</li>
                    <li>Implement proper logging and monitoring for document access</li>
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
