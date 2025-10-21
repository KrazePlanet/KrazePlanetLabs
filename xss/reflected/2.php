<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    $re = str_replace('script', '', $_GET['lname']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - XSS Filter Bypass Lab</title>
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

    pre {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1.5rem;
      color: #e2e8f0;
      border: 1px solid #334155;
      overflow-x: auto;
    }

    .output-section {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      padding: 1.5rem;
      margin-top: 2rem;
    }

    .output-title {
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--accent-green);
    }

    .output-content {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      min-height: 60px;
      border: 1px solid #334155;
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

    .filter-info {
      background: rgba(30, 41, 59, 0.7);
      border-left: 4px solid var(--accent-orange);
      padding: 1rem 1.5rem;
      margin: 1.5rem 0;
      border-radius: 0 8px 8px 0;
    }

    .code-highlight {
      color: var(--accent-orange);
      font-weight: 600;
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
            <a class="nav-link active" href="../../about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="../../contact">Contact Us</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Categories
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Reflected XSS</a></li>
              <li><a class="dropdown-item" href="#">Stored XSS</a></li>
              <li><a class="dropdown-item" href="#">DOM XSS</a></li>
              <li><a class="dropdown-item" href="#">Blind XSS</a></li>
            </ul>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control search-box me-2" type="search" placeholder="Search labs..." aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="hero-section">
    <div class="container">
      <h1 class="hero-title">Reflected XSS Bootcamp</h1>
      <p class="hero-subtitle">Lab: Basic Filter Bypass</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <span class="lab-badge">Difficulty: Low</span>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates a reflected XSS vulnerability with a basic filter that attempts to block the word "script".</p>
      
      <div class="filter-info">
        <i class="bi bi-filter me-2"></i><strong>Active Filter:</strong> The application removes the string <span class="code-highlight">'script'</span> from the Last Name parameter using <code>str_replace('script', '', $_GET['lname'])</code>
      </div>
      
      <p><strong>Objective:</strong> Bypass the filter and execute a JavaScript alert.</p>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header text-center">
            <i class="bi bi-code-slash me-2"></i>Backend Source Code
          </div>
          <div class="card-body">
            <pre>
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    $re = str_replace('script', '', $_GET['lname']);
    echo $re;
}</pre>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-input-cursor-text me-2"></i>Test Input Form
          </div>
          <div class="card-body">
            <form action="" method="get">
              <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input class="form-control" type="text" placeholder="Enter first name" aria-label="First name" name="fname" value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : ''; ?>">
              </div>
              <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input class="form-control" type="text" placeholder="Enter last name" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Filter</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php if(isset($_GET["fname"]) && isset($_GET["lname"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Filtered Output (Last Name Only)
      </div>
      <div class="output-content">
        <?php 
          // Apply the filter and display the result
          $re = str_replace('script', '', $_GET['lname']);
          echo htmlspecialchars($re);
        ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Hints & Filter Bypass Techniques
          </div>
          <div class="card-body">
            <ul>
              <li>The filter only removes the exact string <code>script</code> - try case variations</li>
              <li>Consider using nested tags or alternative event handlers</li>
              <li>Try using HTML entities or encoding techniques</li>
              <li>Experiment with different tag combinations that don't rely on the word "script"</li>
              <li>Remember that the filter is only applied to the Last Name field</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-exclamation-triangle me-2"></i>Security Note
          </div>
          <div class="card-body">
            <p>This lab demonstrates why simple string replacement is an ineffective XSS prevention technique. Modern applications should use context-aware output encoding and proper sanitization libraries.</p>
            <p class="mb-0"><strong>Real-world recommendation:</strong> Use HTMLPurifier or similar libraries that understand context and can properly neutralize XSS vectors.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>
</html>