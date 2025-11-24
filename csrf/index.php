<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - CSRF Labs</title>
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
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(90deg, #48bb78, #4299e1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
    }

    .hero-subtitle {
      font-size: 1.2rem;
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
      transition: all 0.3s;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .card-header {
      background: rgba(15, 23, 42, 0.5);
      border-bottom: 1px solid #334155;
      font-weight: 600;
      padding: 1rem 1.5rem;
    }

    .lab-list {
      list-style-type: none;
      padding-left: 0;
    }

    .lab-list li {
      padding: 1rem 1.5rem;
      background: rgba(30, 41, 59, 0.7);
      border-radius: 8px;
      margin-bottom: 12px;
      transition: all 0.3s;
      border: 1px solid #334155;
    }

    .lab-list li:hover {
      background: rgba(30, 41, 59, 0.9);
      transform: translateX(5px);
      border-color: var(--accent-green);
    }

    .lab-link {
      text-decoration: none;
      color: #e2e8f0;
      font-weight: 500;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .lab-link:hover {
      color: var(--accent-green);
      text-decoration: none;
    }

    .lab-icon {
      color: var(--accent-green);
      font-size: 1.2rem;
    }

    .difficulty-badge {
      font-size: 0.8rem;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .search-box {
      background: rgba(30, 41, 59, 0.7);
      border: 1px solid #334155;
      color: #e2e8f0;
    }

    .search-box:focus {
      background: rgba(30, 41, 59, 0.9);
      border-color: var(--accent-green);
      box-shadow: 0 0 0 0.2rem rgba(72, 187, 120, 0.25);
      color: #e2e8f0;
    }

    .btn-outline-success {
      border-color: var(--accent-green);
      color: var(--accent-green);
    }

    .btn-outline-success:hover {
      background-color: var(--accent-green);
      border-color: var(--accent-green);
      color: #1a202c;
    }

    .info-card {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      padding: 1.5rem;
      margin-top: 2rem;
    }

    .info-title {
      color: var(--accent-green);
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .attack-vector {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 0.5rem;
      border-left: 4px solid var(--accent-orange);
    }

    .impact-item {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 0.5rem;
      border-left: 4px solid var(--accent-red);
    }

    .csrf-types {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 0.5rem;
      border-left: 4px solid var(--accent-blue);
    }

    .vulnerability-sources {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 0.5rem;
      border-left: 4px solid var(--accent-green);
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <i class="bi bi-shield-shaded me-2"></i>KrazePlanetLabs
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
        <form class="d-flex" role="search" method="get">
          <input class="form-control search-box me-2" type="search" placeholder="Search labs..." aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="hero-section">
    <div class="container">
      <h1 class="hero-title">CSRF Bootcamp</h1>
      <p class="hero-subtitle">Master Cross-Site Request Forgery vulnerabilities and their exploitation techniques</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="section-title">
      <i class="bi bi-arrow-right-circle me-2"></i>Low Difficulty
      <span class="difficulty-badge bg-success ms-3">Beginner</span>
    </div>
    <ul class="lab-list">
      <li>
        <a href="1.php" class="lab-link">
          <div>
            <i class="bi bi-arrow-left-right me-2 lab-icon"></i>
            <strong>Lab 1:</strong> Basic CSRF Attack
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
    </ul>

    <div class="section-title">
      <i class="bi bi-arrow-right-circle me-2"></i>Medium Difficulty
      <span class="difficulty-badge bg-warning text-dark ms-3">Intermediate</span>
    </div>
    <ul class="lab-list">
      <li>
        <a href="2.php" class="lab-link">
          <div>
            <i class="bi bi-shield-exclamation me-2 lab-icon"></i>
            <strong>Lab 2:</strong> CSRF with Token Bypass
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="3.php" class="lab-link">
          <div>
            <i class="bi bi-upload me-2 lab-icon"></i>
            <strong>Lab 3:</strong> CSRF via File Upload
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
    </ul>

    <div class="section-title">
      <i class="bi bi-arrow-right-circle me-2"></i>High Difficulty
      <span class="difficulty-badge bg-danger ms-3">Advanced</span>
    </div>
    <ul class="lab-list">
      <li>
        <a href="4.php" class="lab-link">
          <div>
            <i class="bi bi-code-slash me-2 lab-icon"></i>
            <strong>Lab 4:</strong> CSRF with JSON Payloads
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="5.php" class="lab-link">
          <div>
            <i class="bi bi-bug me-2 lab-icon"></i>
            <strong>Lab 5:</strong> Advanced CSRF Techniques
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
    </ul>

    <div class="info-card">
      <h3 class="info-title">
        <i class="bi bi-info-circle me-2"></i>About CSRF (Cross-Site Request Forgery)
      </h3>
      <p>CSRF vulnerabilities occur when an attacker tricks a user into performing unwanted actions on a web application in which they are authenticated. The attack exploits the trust that a site has in the user's browser.</p>
      
      <h5 class="mt-4 mb-3">
        <i class="bi bi-bug me-2"></i>Common CSRF Attack Types
      </h5>
      <div class="csrf-types">
        <strong><i class="bi bi-arrow-left-right me-2"></i>Basic CSRF:</strong> Simple form submission attacks using hidden forms or image tags
      </div>
      <div class="csrf-types">
        <strong><i class="bi bi-shield-exclamation me-2"></i>Token Bypass:</strong> Bypassing CSRF protection mechanisms like tokens
      </div>
      <div class="csrf-types">
        <strong><i class="bi bi-upload me-2"></i>File Upload CSRF:</strong> Using file upload functionality to perform CSRF attacks
      </div>
      <div class="csrf-types">
        <strong><i class="bi bi-code-slash me-2"></i>JSON CSRF:</strong> Exploiting applications that accept JSON payloads
      </div>
      <div class="csrf-types">
        <strong><i class="bi bi-bug me-2"></i>Advanced Techniques:</strong> Complex methods to bypass modern protections
      </div>
      
      <h5 class="mt-4 mb-3">
        <i class="bi bi-exclamation-triangle me-2"></i>Common Vulnerability Sources
      </h5>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-person me-2"></i>User Actions:</strong> Profile updates, password changes, account settings
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-cart me-2"></i>E-commerce:</strong> Purchase actions, cart modifications, payment processing
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-shield-lock me-2"></i>Admin Functions:</strong> User management, system configuration, privilege changes
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-file-earmark me-2"></i>File Operations:</strong> File uploads, document management, content creation
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-diagram-3 me-2"></i>API Endpoints:</strong> RESTful APIs, webhooks, third-party integrations
      </div>
      
      <h5 class="mt-4 mb-3">
        <i class="bi bi-exclamation-triangle me-2"></i>Real-World Impact
      </h5>
      <div class="impact-item">
        <i class="bi bi-person me-2"></i>Unauthorized account modifications and profile changes
      </div>
      <div class="impact-item">
        <i class="bi bi-cart me-2"></i>Unauthorized purchases and financial transactions
      </div>
      <div class="impact-item">
        <i class="bi bi-shield-lock me-2"></i>Privilege escalation and administrative access
      </div>
      <div class="impact-item">
        <i class="bi bi-file-earmark me-2"></i>Malicious file uploads and content injection
      </div>
      <div class="impact-item">
        <i class="bi bi-arrow-left-right me-2"></i>Data exfiltration and unauthorized data access
      </div>
      <div class="impact-item">
        <i class="bi bi-exclamation-triangle me-2"></i>Compliance violations and security breaches
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>

</html>