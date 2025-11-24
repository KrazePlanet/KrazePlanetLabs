<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','alert','svg','audio','video','body');
    $re = str_replace($arr, '', $_GET['lname']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - XSS Alert Function Filter Bypass Lab</title>
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

    .filter-item.function {
      background: var(--accent-purple);
      position: relative;
    }

    .filter-item.function::after {
      content: 'FUNC';
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--accent-green);
      color: #1a202c;
      font-size: 0.6rem;
      padding: 2px 4px;
      border-radius: 4px;
      font-weight: bold;
    }

    .progress {
      height: 8px;
      background-color: #2d3748;
      margin: 1rem 0;
    }

    .progress-bar {
      background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
    }

    .case-variations {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
      border: 1px solid #334155;
    }

    .function-filter-notice {
      background: rgba(159, 122, 234, 0.1);
      border: 1px solid var(--accent-purple);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
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
      <p class="hero-subtitle">Lab: Alert Function Filter Bypass</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <span class="lab-badge">Difficulty: High</span>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates a reflected XSS vulnerability with an advanced filter that blocks multiple HTML tags AND the 'alert' function.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Critical Update:</strong> The filter now blocks the 'alert' function in addition to HTML tags!
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Advanced Filter:</strong> Blocks 12 case variations of 'script' plus 'img', 'svg', 'audio', 'video', 'body', and 'alert'
        <div class="case-variations">
          <strong>Blocked tags and functions:</strong>
          <div class="filter-grid mt-2">
            <div class="filter-item">script</div>
            <div class="filter-item">Script</div>
            <div class="filter-item">sCript</div>
            <div class="filter-item">scRipt</div>
            <div class="filter-item">scrIpt</div>
            <div class="filter-item">scriPt</div>
            <div class="filter-item">scripT</div>
            <div class="filter-item">SCript</div>
            <div class="filter-item">SCRipt</div>
            <div class="filter-item">SCRIpt</div>
            <div class="filter-item">SCRIPt</div>
            <div class="filter-item">SCRIPT</div>
            <div class="filter-item">img</div>
            <div class="filter-item">svg</div>
            <div class="filter-item">audio</div>
            <div class="filter-item">video</div>
            <div class="filter-item">body</div>
            <div class="filter-item function">alert</div>
          </div>
        </div>
        <div class="mt-2">
          <small>Filter method: <code>str_replace(array('script','Script',...,'alert'), '', $_GET['lname'])</code></small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Filter Complexity:</span>
          <span class="text-warning">Advanced Function Filtering</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 75%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Bypass the filter and execute a JavaScript alert, even though 'alert' is blocked.</p>
      <p class="mb-0"><strong>Note:</strong> Only the Last Name field is filtered and displayed. The First Name field is unfiltered but not used in output.</p>
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
    $arr = array('script','Script','sCript','scRipt',
                'scrIpt','scriPt','scripT','SCript',
                'SCRipt','SCRIpt','SCRIPt','SCRIPT',
                'img','alert','svg','audio','video','body');
    $re = str_replace($arr, '', $_GET['lname']);
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
                <label for="fname" class="form-label">First Name <span class="badge bg-success">Unfiltered</span></label>
                <input class="form-control" type="text" placeholder="Enter first name (unfiltered)" aria-label="First name" name="fname" value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : ''; ?>">
                <div class="form-text">This field has no filters but is not displayed in output</div>
              </div>
              <div class="mb-3">
                <label for="lname" class="form-label">Last Name <span class="badge bg-warning text-dark">Advanced Filter</span></label>
                <input class="form-control" type="text" placeholder="Enter last name (filtered)" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                <div class="form-text">This field has 18 filters including 'alert' function blocking</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Advanced Filters</button>
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
          $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','alert','svg','audio','video','body');
          $re = str_replace($arr, '', $_GET['lname']);
          echo htmlspecialchars($re);
        ?>
      </div>
      <?php if($_GET['lname'] !== $re): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Advanced filter has modified your input</small>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Function Filter Bypass Techniques
          </div>
          <div class="card-body">
            <ul>
              <li><strong>Alternative JavaScript functions:</strong> Use <code>prompt()</code>, <code>confirm()</code>, <code>console.log()</code> or create your own alert function</li>
              <li><strong>Window object methods:</strong> Try <code>window.alert</code> or accessing through different object paths</li>
              <li><strong>Encoding and obfuscation:</strong> Use HTML entities, URL encoding, or Unicode: <code>al&amp;#101;rt</code></li>
              <li><strong>String concatenation:</strong> Build the function name dynamically: <code>'al'+'ert'</code></li>
              <li><strong>Alternative syntax:</strong> Use <code>eval()</code> or <code>Function()</code> constructors with encoded strings</li>
              <li><strong>Template literals:</strong> Use backticks and template syntax: <code>`al${''}ert`</code></li>
              <li><strong>GlobalThis access:</strong> Use <code>globalThis['al'+'ert']</code> or similar approaches</li>
              <li><strong>Character code conversion:</strong> Use <code>String.fromCharCode()</code> to build the function name</li>
              <li><strong>Alternative event handlers:</strong> Use handlers that don't require alert: <code>onerror</code> with image loading, etc.</li>
              <li><strong>DOM manipulation:</strong> Create elements and trigger events programmatically</li>
            </ul>
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
            <p>Blocking specific JavaScript functions demonstrates:</p>
            <ul>
              <li>Attackers can easily use alternative functions or obfuscation</li>
              <li>Function name filtering is trivial to bypass with encoding</li>
              <li>JavaScript is extremely flexible with multiple ways to execute code</li>
              <li>Blacklisting function names creates a false sense of security</li>
              <li>Proper output encoding is the only reliable defense</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-check-circle me-2"></i>Proper Defense Strategies
          </div>
          <div class="card-body">
            <p>For effective XSS prevention:</p>
            <ul>
              <li>Use context-aware output encoding (HTML, JavaScript contexts)</li>
              <li>Implement strict Content Security Policy (CSP) headers</li>
              <li>Validate input using whitelists, not blacklists</li>
              <li>Use proven sanitization libraries like DOMPurify</li>
              <li>Never rely on function name filtering for security</li>
              <li>Consider using Trusted Types for DOM XSS protection</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-code-square me-2"></i>JavaScript Function Alternatives
          </div>
          <div class="card-body">
            <p>When 'alert' is blocked, consider these alternatives:</p>
            <div class="row">
              <div class="col-md-6">
                <h6>Built-in alternatives:</h6>
                <ul>
                  <li><code>prompt(1)</code> - Shows a prompt dialog</li>
                  <li><code>confirm(1)</code> - Shows a confirmation dialog</li>
                  <li><code>console.log(1)</code> - Logs to browser console</li>
                  <li><code>print()</code> - Opens print dialog</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Creative alternatives:</h6>
                <ul>
                  <li><code>top['al'+'ert'](1)</code> - String concatenation</li>
                  <li><code>window['al'+'ert'](1)</code> - Bracket notation</li>
                  <li><code>eval('al'+'ert(1)')</code> - Using eval</li>
                  <li><code>Function('al'+'ert(1)')()</code> - Function constructor</li>
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