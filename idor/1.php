<?php
// Lab 1: Basic User Profile Access IDOR
// Vulnerability: Direct user profile access without authorization checks

session_start();

// Simulate user authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user
    $_SESSION['username'] = 'user1';
}

$message = '';
$profile_data = '';
$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

// Simulate user database
$users = [
    1 => [
        'id' => 1,
        'username' => 'user1',
        'email' => 'user1@example.com',
        'full_name' => 'John Doe',
        'phone' => '+1-555-0123',
        'address' => '123 Main St, City, State',
        'ssn' => '123-45-6789',
        'salary' => '$75,000',
        'department' => 'Engineering'
    ],
    2 => [
        'id' => 2,
        'username' => 'user2',
        'email' => 'user2@example.com',
        'full_name' => 'Jane Smith',
        'phone' => '+1-555-0124',
        'address' => '456 Oak Ave, City, State',
        'ssn' => '987-65-4321',
        'salary' => '$85,000',
        'department' => 'Marketing'
    ],
    3 => [
        'id' => 3,
        'username' => 'admin',
        'email' => 'admin@example.com',
        'full_name' => 'Admin User',
        'phone' => '+1-555-0125',
        'address' => '789 Pine St, City, State',
        'ssn' => '555-12-3456',
        'salary' => '$120,000',
        'department' => 'Administration'
    ]
];

// Vulnerable: No authorization check - direct access to any user profile
if (isset($users[$user_id])) {
    $profile_data = $users[$user_id];
    $message = '<div class="alert alert-success">Profile loaded successfully!</div>';
} else {
    $message = '<div class="alert alert-danger">User not found!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1: Basic User Profile Access - IDOR</title>
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
            <h1 class="hero-title">Lab 1: Basic User Profile Access</h1>
            <p class="hero-subtitle">IDOR in user profile viewing functionality</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: Low</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a basic IDOR vulnerability in a user profile system. The application allows users to view any user's profile by simply changing the user_id parameter without proper authorization checks.</p>
            <p><strong>Objective:</strong> Access other users' profiles by manipulating the user_id parameter to view sensitive information.</p>
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
$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

// Simulate user database
$users = [
    1 => ['id' => 1, 'username' => 'user1', ...],
    2 => ['id' => 2, 'username' => 'user2', ...],
    3 => ['id' => 3, 'username' => 'admin', ...]
];

// Direct access without checking if user is authorized
if (isset($users[$user_id])) {
    $profile_data = $users[$user_id];
    // Display profile data
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
                        <i class="bi bi-person me-2"></i>User Profile Viewer
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="GET">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="number" class="form-control" id="user_id" name="user_id" 
                                       placeholder="Enter user ID..." value="<?php echo htmlspecialchars($user_id); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">View Profile</button>
                        </form>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-link me-2"></i>Quick Test URLs:</h6>
                            <ul>
                                <li><a href="?user_id=1" style="color: var(--accent-green);">View User 1 Profile</a></li>
                                <li><a href="?user_id=2" style="color: var(--accent-green);">View User 2 Profile</a></li>
                                <li><a href="?user_id=3" style="color: var(--accent-green);">View Admin Profile</a></li>
                                <li><a href="?user_id=999" style="color: var(--accent-green);">Test Non-existent User</a></li>
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
                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($profile_data['full_name']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($profile_data['email']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile_data['phone']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($profile_data['address']); ?></p>
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($profile_data['department']); ?></p>
                            </div>
                            
                            <h5 class="mt-3">Sensitive Information</h5>
                            <div class="sensitive-data">
                                <p><strong>SSN:</strong> <?php echo htmlspecialchars($profile_data['ssn']); ?></p>
                                <p><strong>Salary:</strong> <?php echo htmlspecialchars($profile_data['salary']); ?></p>
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
                        <li><strong>Parameter:</strong> <code>user_id</code></li>
                        <li><strong>Method:</strong> GET</li>
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
                        <li><code>3</code> - Admin profile</li>
                        <li><code>999</code> - Non-existent user</li>
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
                <li><a href="?user_id=3" style="color: var(--accent-green);">View Admin Profile (Unauthorized Access)</a></li>
                <li><a href="?user_id=999" style="color: var(--accent-green);">Test Non-existent User</a></li>
            </ul>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>Unauthorized access to user data and personal information</li>
                <li>Access to confidential documents and sensitive files</li>
                <li>Privilege escalation and admin function access</li>
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
                    <li>Implement proper authorization checks before accessing resources</li>
                    <li>Use indirect object references instead of direct database IDs</li>
                    <li>Implement proper access control lists (ACLs)</li>
                    <li>Use role-based access control (RBAC)</li>
                    <li>Implement proper session management</li>
                    <li>Use whitelist-based validation for allowed resources</li>
                    <li>Implement proper logging and monitoring</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
