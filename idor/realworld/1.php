<?php
require_once 'config.php';
require_login();

$message = '';
$profile_data = '';
$user_id = $_GET['user_id'] ?? get_user_id();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $bio = $_POST['bio'] ?? '';
    $website = $_POST['website'] ?? '';
    $location = $_POST['location'] ?? '';
    $social_media = json_encode([
        'twitter' => $_POST['twitter'] ?? '',
        'linkedin' => $_POST['linkedin'] ?? '',
        'github' => $_POST['github'] ?? ''
    ]);
    $preferences = json_encode([
        'theme' => $_POST['theme'] ?? 'light',
        'notifications' => isset($_POST['notifications'])
    ]);
    
    try {
        // Check if profile exists
        $stmt = $pdo->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->fetch()) {
            // Update existing profile
            $stmt = $pdo->prepare("UPDATE user_profiles SET bio = ?, website = ?, location = ?, social_media = ?, preferences = ?, updated_at = NOW() WHERE user_id = ?");
            $stmt->execute([$bio, $website, $location, $social_media, $preferences, $user_id]);
        } else {
            // Create new profile
            $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, bio, website, location, social_media, preferences) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $bio, $website, $location, $social_media, $preferences]);
        }
        
        $message = '<div class="alert alert-success">Profile updated successfully!</div>';
        log_security_event('profile_update', 'Profile updated for user ID: ' . $user_id);
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Failed to update profile: ' . $e->getMessage() . '</div>';
    }
}

// Vulnerable: No authorization check - direct access to any user's profile
try {
    $stmt = $pdo->prepare("SELECT u.*, p.* FROM users u LEFT JOIN user_profiles p ON u.id = p.user_id WHERE u.id = ?");
    $stmt->execute([$user_id]);
    $profile_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$profile_data) {
        $message = '<div class="alert alert-danger">User not found!</div>';
    }
} catch (Exception $e) {
    $message = '<div class="alert alert-danger">Error loading profile: ' . $e->getMessage() . '</div>';
}

// Get all users for the dropdown
$all_users = get_all_users();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: User Profile Management IDOR - IDOR Labs</title>
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

        .profile-display {
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

        .user-info {
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
            <h1 class="hero-title">Lab 1: User Profile Management IDOR</h1>
            <p class="hero-subtitle">Real-world IDOR in user profile viewing and editing functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a real-world IDOR vulnerability in a user profile management system. The application allows users to view and edit any user's profile by simply changing the user_id parameter without proper authorization checks.</p>
            <p><strong>Objective:</strong> Access and modify other users' profiles by manipulating the user_id parameter to view sensitive information and make unauthorized changes.</p>
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
$user_id = $_GET['user_id'] ?? get_user_id();

// Direct access to any user's profile
$stmt = $pdo->prepare("SELECT u.*, p.* FROM users u 
                      LEFT JOIN user_profiles p ON u.id = p.user_id 
                      WHERE u.id = ?");
$stmt->execute([$user_id]);
$profile_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update profile without checking ownership
    $stmt = $pdo->prepare("UPDATE user_profiles SET 
                          bio = ?, website = ?, location = ? 
                          WHERE user_id = ?");
    $stmt->execute([$bio, $website, $location, $user_id]);
}

// Example vulnerable usage:
// ?user_id=1 (own profile - allowed)
// ?user_id=2 (other user's profile - unauthorized access)
// ?user_id=3 (admin profile - unauthorized access)</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person me-2"></i>Profile Management
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET" class="mb-3">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select User Profile</label>
                                <select class="form-select" id="user_id" name="user_id" onchange="this.form.submit()">
                                    <?php foreach ($all_users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>" 
                                                <?php echo $user_id == $user['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['username']); ?> 
                                            (<?php echo htmlspecialchars($user['email']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?user_id=1" style="color: var(--accent-green);">View User 1 Profile</a></li>
                                <li><a href="?user_id=2" style="color: var(--accent-green);">View User 2 Profile</a></li>
                                <li><a href="?user_id=3" style="color: var(--accent-green);">View User 3 Profile</a></li>
                                <li><a href="?user_id=4" style="color: var(--accent-green);">View User 4 Profile</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($profile_data): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person me-2"></i>User Profile: <?php echo htmlspecialchars($profile_data['username']); ?>
                    </div>
                    <div class="card-body">
                        <div class="profile-display">
                            <h5>Basic Information</h5>
                            <div class="user-info">
                                <p><strong>Username:</strong> <?php echo htmlspecialchars($profile_data['username']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($profile_data['email']); ?></p>
                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($profile_data['full_name'] ?? 'Not set'); ?></p>
                                <p><strong>Role:</strong> <?php echo ucfirst($profile_data['role']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile_data['phone'] ?? 'Not set'); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($profile_data['address'] ?? 'Not set'); ?></p>
                                <p><strong>Created:</strong> <?php echo date('Y-m-d H:i:s', strtotime($profile_data['created_at'])); ?></p>
                            </div>
                            
                            <h5 class="mt-3">Profile Information</h5>
                            <div class="sensitive-data">
                                <p><strong>Bio:</strong> <?php echo htmlspecialchars($profile_data['bio'] ?? 'Not set'); ?></p>
                                <p><strong>Website:</strong> <?php echo htmlspecialchars($profile_data['website'] ?? 'Not set'); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($profile_data['location'] ?? 'Not set'); ?></p>
                                <?php if ($profile_data['social_media']): ?>
                                    <p><strong>Social Media:</strong> <?php echo htmlspecialchars($profile_data['social_media']); ?></p>
                                <?php endif; ?>
                                <?php if ($profile_data['preferences']): ?>
                                    <p><strong>Preferences:</strong> <?php echo htmlspecialchars($profile_data['preferences']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Profile Update Form -->
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="update_profile" value="1">
                            <h5>Update Profile</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo htmlspecialchars($profile_data['bio'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="website" name="website" 
                                           value="<?php echo htmlspecialchars($profile_data['website'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="<?php echo htmlspecialchars($profile_data['location'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="theme" class="form-label">Theme</label>
                                    <select class="form-select" id="theme" name="theme">
                                        <option value="light" <?php echo ($profile_data['preferences'] && strpos($profile_data['preferences'], 'light') !== false) ? 'selected' : ''; ?>>Light</option>
                                        <option value="dark" <?php echo ($profile_data['preferences'] && strpos($profile_data['preferences'], 'dark') !== false) ? 'selected' : ''; ?>>Dark</option>
                                        <option value="auto" <?php echo ($profile_data['preferences'] && strpos($profile_data['preferences'], 'auto') !== false) ? 'selected' : ''; ?>>Auto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="text" class="form-control" id="twitter" name="twitter" 
                                           value="<?php echo htmlspecialchars($profile_data['social_media'] ? json_decode($profile_data['social_media'], true)['twitter'] ?? '' : ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="text" class="form-control" id="linkedin" name="linkedin" 
                                           value="<?php echo htmlspecialchars($profile_data['social_media'] ? json_decode($profile_data['social_media'], true)['linkedin'] ?? '' : ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="github" class="form-label">GitHub</label>
                                    <input type="text" class="form-control" id="github" name="github" 
                                           value="<?php echo htmlspecialchars($profile_data['social_media'] ? json_decode($profile_data['social_media'], true)['github'] ?? '' : ''); ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifications" name="notifications" 
                                           <?php echo ($profile_data['preferences'] && strpos($profile_data['preferences'], 'true') !== false) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="notifications">
                                        Enable notifications
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Update Profile
                            </button>
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
                        <li><strong>Parameter:</strong> <code>user_id</code></li>
                        <li><strong>Method:</strong> GET/POST</li>
                        <li><strong>Issue:</strong> Direct access to user profiles without authorization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Try these user_id values:</p>
                    <ul>
                        <li><code>1</code> - User 1 profile</li>
                        <li><code>2</code> - User 2 profile</li>
                        <li><code>3</code> - User 3 profile</li>
                        <li><code>4</code> - User 4 profile</li>
                    </ul>
                    <p><strong>Example URLs:</strong></p>
                    <ul>
                        <li><code>1.php?user_id=2</code></li>
                        <li><code>1.php?user_id=3</code></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="test-urls">
            <h5><i class="bi bi-link me-2"></i>Quick Test URLs</h5>
            <p>Click these links to test the vulnerability:</p>
            <ul>
                <li><a href="?user_id=1" style="color: var(--accent-green);">View User 1 Profile (Your Profile)</a></li>
                <li><a href="?user_id=2" style="color: var(--accent-green);">View User 2 Profile (Unauthorized Access)</a></li>
                <li><a href="?user_id=3" style="color: var(--accent-green);">View User 3 Profile (Unauthorized Access)</a></li>
                <li><a href="?user_id=4" style="color: var(--accent-green);">View User 4 Profile (Unauthorized Access)</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to user data and personal information</li>
                <li>Modification of other users' profiles and preferences</li>
                <li>Access to sensitive social media and contact information</li>
                <li>Data exfiltration and unauthorized data modification</li>
                <li>Bypassing access controls and authorization mechanisms</li>
                <li>Compliance violations and privacy breaches</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Implement proper authorization checks before accessing profiles</li>
                    <li>Use indirect object references instead of direct database IDs</li>
                    <li>Implement proper access control lists (ACLs) for profile access</li>
                    <li>Use role-based access control (RBAC) for profile management</li>
                    <li>Implement proper session management and user context</li>
                    <li>Use whitelist-based validation for allowed profile access</li>
                    <li>Implement proper logging and monitoring for profile access</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
