<?php
// Lab 2: Image Gallery Directory Traversal
// Vulnerability: Image path construction without validation

$message = '';
$image_path = '';
$image_found = false;

// Handle image display request
if (isset($_GET['image'])) {
    $image = $_GET['image'];
    
    // Vulnerable: No validation of image path
    $image_path = 'images/' . $image;
    
    if (file_exists($image_path) && is_file($image_path)) {
        $image_found = true;
        $message = '<div class="alert alert-success">Image loaded successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Image not found: ' . htmlspecialchars($image_path) . '</div>';
    }
}

// Get list of available images
$images_dir = 'images/';
$available_images = [];
if (is_dir($images_dir)) {
    $files = scandir($images_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_file($images_dir . $file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                $available_images[] = $file;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 2: Image Gallery Traversal - Directory Traversal</title>
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

        .image-gallery {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
        }

        .image-display {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            text-align: center;
        }

        .image-display img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            border: 2px solid var(--accent-green);
        }

        .image-list {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-orange);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-item {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 0.5rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .image-item:hover {
            background: rgba(15, 23, 42, 0.8);
            transform: translateY(-2px);
        }

        .image-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to Directory Traversal Labs
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
            <h1 class="hero-title">Lab 2: Image Gallery Traversal</h1>
            <p class="hero-subtitle">Directory Traversal in image gallery functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Medium</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a directory traversal vulnerability in an image gallery system. The application constructs image paths by concatenating user input without proper validation, allowing access to files outside the intended directory.</p>
            <p><strong>Objective:</strong> Access files outside the images directory using directory traversal sequences to view system files or sensitive data.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable PHP Code
                    </div>
                    <div class="card-body">
                        <pre>
// Handle image display request
if (isset($_GET['image'])) {
    $image = $_GET['image'];
    
    // Vulnerable: No validation of image path
    $image_path = 'images/' . $image;
    
    if (file_exists($image_path) && is_file($image_path)) {
        // Display image
        echo '&lt;img src="' . $image_path . '"&gt;';
    } else {
        // Error: Image not found
    }
}

// Example vulnerable usage:
// ?image=photo1.jpg
// ?image=../../../etc/passwd
// ?image=..\..\..\windows\system32\drivers\etc\hosts</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-images me-2"></i>Image Gallery Demo
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image Name</label>
                                <input type="text" class="form-control" id="image" name="image" 
                                       placeholder="Enter image name..." value="<?php echo htmlspecialchars($_GET['image'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Load Image</button>
                        </form>
                        
                        <div class="image-list">
                            <h6><i class="bi bi-folder me-2"></i>Available Images:</h6>
                            <?php if (empty($available_images)): ?>
                                <p class="text-muted">No images available in the images directory.</p>
                            <?php else: ?>
                                <div class="image-grid">
                                    <?php foreach ($available_images as $image): ?>
                                        <div class="image-item" onclick="loadImage('<?php echo htmlspecialchars($image); ?>')">
                                            <img src="images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($image); ?>">
                                            <small><?php echo htmlspecialchars($image); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($image_found): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-image me-2"></i>Image Display: <?php echo htmlspecialchars($image_path); ?>
                    </div>
                    <div class="card-body">
                        <div class="image-display">
                            <?php if (in_array(strtolower(pathinfo($image_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])): ?>
                                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Loaded Image">
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    This file is not a valid image format. It might be a text file or other file type.
                                </div>
                                <pre><?php echo htmlspecialchars(file_get_contents($image_path)); ?></pre>
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
                        <li><strong>Type:</strong> Directory Traversal in Image Gallery</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Parameter:</strong> <code>image</code></li>
                        <li><strong>Method:</strong> GET</li>
                        <li><strong>Issue:</strong> Image path construction without validation</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these payloads in the image parameter:</p>
                    <ul>
                        <li><code>../../../etc/passwd</code> - Linux system file</li>
                        <li><code>..\..\..\windows\system32\drivers\etc\hosts</code> - Windows system file</li>
                        <li><code>../../../etc/hosts</code> - Linux hosts file</li>
                        <li><code>../../../proc/version</code> - Linux system info</li>
                        <li><code>../../../etc/shadow</code> - Linux password file</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>2.php?image=../../../etc/passwd</code></li>
                        <li><code>2.php?image=..\..\..\windows\system32\drivers\etc\hosts</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?image=../../../etc/passwd" style="color: var(--accent-green);">Test Linux /etc/passwd</a></li>
                <li><a href="?image=../../../etc/hosts" style="color: var(--accent-green);">Test Linux /etc/hosts</a></li>
                <li><a href="?image=..\..\..\windows\system32\drivers\etc\hosts" style="color: var(--accent-green);">Test Windows hosts file</a></li>
                <li><a href="?image=../../../proc/version" style="color: var(--accent-green);">Test Linux system version</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Access to system configuration files through image parameters</li>
                <li>Exposure of application source code and configuration</li>
                <li>Access to database files and credentials</li>
                <li>Bypassing access controls in media galleries</li>
                <li>Information disclosure through file access</li>
                <li>Privilege escalation through configuration access</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Validate and sanitize all image path inputs</li>
                    <li>Use whitelist-based file access controls</li>
                    <li>Implement proper path normalization</li>
                    <li>Use <code>basename()</code> to extract filename only</li>
                    <li>Implement file type validation</li>
                    <li>Use absolute paths with proper validation</li>
                    <li>Implement proper error handling</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function loadImage(imageName) {
            document.getElementById('image').value = imageName;
            document.querySelector('form').submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
