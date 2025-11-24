<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Response Manipulation Labs</title>
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

    .manipulation-types {
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
      <h1 class="hero-title">Response Manipulation Bootcamp</h1>
      <p class="hero-subtitle">Master response manipulation vulnerabilities and Burp Suite bypass techniques</p>
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
            <i class="bi bi-arrow-repeat me-2 lab-icon"></i>
            <strong>Lab 1:</strong> Basic Response Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="2.php" class="lab-link">
          <div>
            <i class="bi bi-123 me-2 lab-icon"></i>
            <strong>Lab 2:</strong> Status Code Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="3.php" class="lab-link">
          <div>
            <i class="bi bi-toggle-on me-2 lab-icon"></i>
            <strong>Lab 3:</strong> Boolean Value Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="4.php" class="lab-link">
          <div>
            <i class="bi bi-shield-check me-2 lab-icon"></i>
            <strong>Lab 4:</strong> OTP Bypass via Response
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="5.php" class="lab-link">
          <div>
            <i class="bi bi-person-check me-2 lab-icon"></i>
            <strong>Lab 5:</strong> Authentication Bypass
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
        <a href="6.php" class="lab-link">
          <div>
            <i class="bi bi-shield-lock me-2 lab-icon"></i>
            <strong>Lab 6:</strong> Authorization Bypass
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="7.php" class="lab-link">
          <div>
            <i class="bi bi-envelope-check me-2 lab-icon"></i>
            <strong>Lab 7:</strong> Email Verification Bypass
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="8.php" class="lab-link">
          <div>
            <i class="bi bi-credit-card me-2 lab-icon"></i>
            <strong>Lab 8:</strong> Payment Status Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="9.php" class="lab-link">
          <div>
            <i class="bi bi-person-badge me-2 lab-icon"></i>
            <strong>Lab 9:</strong> User Role Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="10.php" class="lab-link">
          <div>
            <i class="bi bi-flag me-2 lab-icon"></i>
            <strong>Lab 10:</strong> Feature Flag Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="11.php" class="lab-link">
          <div>
            <i class="bi bi-exclamation-triangle me-2 lab-icon"></i>
            <strong>Lab 11:</strong> Error Message Manipulation
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
        <a href="12.php" class="lab-link">
          <div>
            <i class="bi bi-speedometer2 me-2 lab-icon"></i>
            <strong>Lab 12:</strong> Rate Limit Bypass
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="13.php" class="lab-link">
          <div>
            <i class="bi bi-clock me-2 lab-icon"></i>
            <strong>Lab 13:</strong> Session Status Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="14.php" class="lab-link">
          <div>
            <i class="bi bi-code-slash me-2 lab-icon"></i>
            <strong>Lab 14:</strong> API Response Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="15.php" class="lab-link">
          <div>
            <i class="bi bi-check-circle me-2 lab-icon"></i>
            <strong>Lab 15:</strong> Validation Bypass
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="16.php" class="lab-link">
          <div>
            <i class="bi bi-gear me-2 lab-icon"></i>
            <strong>Lab 16:</strong> Business Logic Bypass
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
      <li>
        <a href="17.php" class="lab-link">
          <div>
            <i class="bi bi-shield-exclamation me-2 lab-icon"></i>
            <strong>Lab 17:</strong> Advanced Response Manipulation
          </div>
          <i class="bi bi-chevron-right lab-icon"></i>
        </a>
      </li>
    </ul>

    <div class="info-card">
      <h3 class="info-title">
        <i class="bi bi-info-circle me-2"></i>About Response Manipulation
      </h3>
      <p>Response Manipulation vulnerabilities occur when attackers can use tools like Burp Suite to modify server responses and bypass security controls, authentication, authorization, and business logic.</p>
      
      <h5 class="mt-4 mb-3">
        <i class="bi bi-bug me-2"></i>Common Response Manipulation Types
      </h5>
      <div class="manipulation-types">
        <strong><i class="bi bi-arrow-repeat me-2"></i>Basic Response Manipulation:</strong> Simple response modification techniques
      </div>
      <div class="manipulation-types">
        <strong><i class="bi bi-123 me-2"></i>Status Code Manipulation:</strong> HTTP status code modification
      </div>
      <div class="manipulation-types">
        <strong><i class="bi bi-toggle-on me-2"></i>Boolean Value Manipulation:</strong> Boolean value modification
      </div>
      <div class="manipulation-types">
        <strong><i class="bi bi-shield-check me-2"></i>OTP Bypass:</strong> OTP verification bypass
      </div>
      <div class="manipulation-types">
        <strong><i class="bi bi-person-check me-2"></i>Authentication Bypass:</strong> Authentication bypass techniques
      </div>
      
      <h5 class="mt-4 mb-3">
        <i class="bi bi-exclamation-triangle me-2"></i>Common Vulnerable Areas
      </h5>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-shield me-2"></i>Security Controls:</strong> Authentication, authorization, validation
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-credit-card me-2"></i>Payment Systems:</strong> Payment status, transaction validation
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-person-badge me-2"></i>User Management:</strong> User roles, permissions, verification
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-gear me-2"></i>Business Logic:</strong> Feature flags, business rules, workflows
      </div>
      <div class="vulnerability-sources">
        <strong><i class="bi bi-code-slash me-2"></i>API Endpoints:</strong> API responses, error handling, validation
      </div>
      
      <h5 class="mt-4 mb-3">
        <i class="bi bi-exclamation-triangle me-2"></i>Real-World Impact
      </h5>
      <div class="impact-item">
        <i class="bi bi-shield-exclamation me-2"></i>Complete security control bypass
      </div>
      <div class="impact-item">
        <i class="bi bi-person-badge me-2"></i>Unauthorized access and privilege escalation
      </div>
      <div class="impact-item">
        <i class="bi bi-credit-card me-2"></i>Financial fraud and payment manipulation
      </div>
      <div class="impact-item">
        <i class="bi bi-exclamation-triangle me-2"></i>Compliance violations and legal issues
      </div>
      <div class="impact-item">
        <i class="bi bi-arrow-left-right me-2"></i>Data manipulation and integrity issues
      </div>
      <div class="impact-item">
        <i class="bi bi-graph-down me-2"></i>Business process disruption and operational impact
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>

</html>
