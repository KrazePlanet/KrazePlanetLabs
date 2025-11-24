<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["button"])){
    $arr = array('script','img','image');
    $re = str_replace($arr, '">?', $_GET['button']);
    echo $re;
}
elseif(isset($_GET["categoryid"])){
    echo htmlentities($_GET["categoryid"], ENT_QUOTES);
}
elseif(isset($_GET["color"])){
    $arr = array('alert','confirm');
    $color = str_replace($arr, '<br>', $_GET['color']);
    echo $color;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Multi-Filter XSS Lab</title>
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

    .filter-item.tag {
      background: var(--accent-blue);
    }

    .filter-item.function {
      background: var(--accent-purple);
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

    .multi-filter-badge {
      background: linear-gradient(45deg, var(--accent-orange), var(--accent-red), var(--accent-purple), var(--accent-blue));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-multi 2s infinite alternate;
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

    @keyframes pulse-multi {
      0% { 
        box-shadow: 0 0 10px var(--accent-orange); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-orange), 0 0 30px var(--accent-red); 
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

    .transformation-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }

    .transformation-table th,
    .transformation-table td {
      border: 1px solid #334155;
      padding: 0.75rem;
      text-align: left;
    }

    .transformation-table th {
      background: rgba(30, 41, 59, 0.9);
      color: var(--accent-green);
      font-weight: 600;
    }

    .transformation-table td {
      background: rgba(15, 23, 42, 0.7);
    }

    .filter-example {
      font-family: monospace;
      background: rgba(15, 23, 42, 0.9);
      padding: 0.5rem;
      border-radius: 4px;
      border: 1px solid #334155;
      margin: 0.5rem 0;
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
      <h1 class="hero-title">Multi-Filter XSS Lab</h1>
      <p class="hero-subtitle">Test XSS against multiple different filtering techniques</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="multi-filter-badge">Multi-Filter Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates multiple different filtering approaches with various transformation techniques, creating a comprehensive XSS testing environment.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Multiple Filter Types:</strong> This application uses four different filtering strategies across different parameters, including HTML encoding and string transformations.
      </div>
      
      <div class="arjun-tool-info">
        <i class="bi bi-tools me-2"></i><strong>Hidden Parameters:</strong> There are multiple hidden parameters not shown in the main form. Use tools like Arjun to discover them!
        <div class="mt-2">
          <code># use arjun tool to find hidden parameter</code>
        </div>
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Multiple Filtering Methods:</strong>
        <div class="case-variations">
          <div class="row mt-2">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">fname, lname - Uses htmlspecialchars()</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-medium">Quote Injection</span>
                <span class="ms-2">button - Replaces tags with '\">?'</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">categoryid - Uses htmlentities()</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-medium">Break Tag Filter</span>
                <span class="ms-2">color - Replaces functions with '&lt;br&gt;'</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="filter-demo">
        <h6>Filter Transformations:</h6>
        <table class="transformation-table">
          <thead>
            <tr>
              <th>Parameter</th>
              <th>Input Example</th>
              <th>After Filter</th>
              <th>Transformation</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>button</td>
              <td><code>&lt;script&gt;alert(1)&lt;/script&gt;</code></td>
              <td><code>&lt;\"&gt;?&gt;alert(1)&lt;/\"&gt;?&gt;</code></td>
              <td>'script' → '">?'</td>
            </tr>
            <tr>
              <td>color</td>
              <td><code>alert(1);confirm(2)</code></td>
              <td><code>&lt;br&gt;(1);&lt;br&gt;(2)</code></td>
              <td>'alert','confirm' → '&lt;br&gt;'</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Filter Complexity:</span>
          <span class="text-warning">Multiple Filter Types</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 80%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Test XSS payloads against all filtering methods and find ways to bypass each specific filter implementation.</p>
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
    echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["button"])){
    $arr = array('script','img','image');
    $re = str_replace($arr, '">?', $_GET['button']);
    echo $re;
}
elseif(isset($_GET["categoryid"])){
    echo htmlentities($_GET["categoryid"], ENT_QUOTES);
}
elseif(isset($_GET["color"])){
    $arr = array('alert','confirm');
    $color = str_replace($arr, '<br>', $_GET['color']);
    echo $color;
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
                <button class="nav-link" id="hidden-tab" data-bs-toggle="tab" data-bs-target="#hidden" type="button" role="tab">Hidden Parameters</button>
              </li>
            </ul>
            <div class="tab-content" id="paramTabsContent">
              <div class="tab-pane fade show active" id="visible" role="tabpanel">
                <h5 class="mb-3">Visible Parameters (HTML Encoded)</h5>
                <form action="" method="get" class="mb-4">
                  <div class="mb-3">
                    <label for="fname" class="form-label">First Name <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter first name" aria-label="First name" name="fname" value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : ''; ?>">
                    <div class="form-text">This parameter uses htmlspecialchars() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="lname" class="form-label">Last Name <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter last name" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                    <div class="form-text">This parameter uses htmlspecialchars() encoding</div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Test HTML Encoding</button>
                </form>
              </div>
              <div class="tab-pane fade" id="hidden" role="tabpanel">
                <h5 class="mb-3">Hidden Parameters</h5>
                <div class="tool-tip">
                  <i class="bi bi-info-circle me-2"></i><strong>Hint:</strong> The 'button', 'categoryid', and 'color' parameters are not shown in the main form but are implemented in the backend with various filtering methods.
                </div>
                <form action="" method="get">
                  <div class="mb-3">
                    <label for="button" class="form-label">Parameter 'button' <span class="param-security-level security-medium">Quote Injection</span></label>
                    <input class="form-control" type="text" placeholder="Enter button value" aria-label="button" name="button" value="<?php echo isset($_GET['button']) ? htmlspecialchars($_GET['button']) : ''; ?>">
                    <div class="form-text">This parameter replaces 'script','img','image' with '\">?'</div>
                  </div>
                  <div class="mb-3">
                    <label for="categoryid" class="form-label">Parameter 'categoryid' <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter categoryid value" aria-label="categoryid" name="categoryid" value="<?php echo isset($_GET['categoryid']) ? htmlspecialchars($_GET['categoryid']) : ''; ?>">
                    <div class="form-text">This parameter uses htmlentities() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="color" class="form-label">Parameter 'color' <span class="param-security-level security-medium">Break Tag Filter</span></label>
                    <input class="form-control" type="text" placeholder="Enter color value" aria-label="color" name="color" value="<?php echo isset($_GET['color']) ? htmlspecialchars($_GET['color']) : ''; ?>">
                    <div class="form-text">This parameter replaces 'alert','confirm' with '&lt;br&gt;'</div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Test Multiple Filters</button>
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
        <i class="bi bi-arrow-return-right me-2"></i>Visible Parameters Output (HTML Encoded)
      </div>
      <div class="output-content">
        <?php 
          echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
          echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
        ?>
      </div>
      <div class="mt-3">
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Output is safely encoded with htmlspecialchars()</small>
      </div>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET["button"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Parameter 'button' Output (Quote Injection Filtered)
      </div>
      <div class="output-content">
        <?php 
          $arr = array('script','img','image');
          $re = str_replace($arr, '">?', $_GET['button']);
          echo htmlspecialchars($re);
        ?>
      </div>
      <?php if($_GET['button'] !== $re): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Button filter has modified your input (replaced tags with '\">?')</small>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET["categoryid"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Parameter 'categoryid' Output (HTML Entities Encoded)
      </div>
      <div class="output-content">
        <?php 
          echo htmlentities($_GET["categoryid"], ENT_QUOTES);
        ?>
      </div>
      <div class="mt-3">
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Output is safely encoded with htmlentities()</small>
      </div>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET["color"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Parameter 'color' Output (Break Tag Filtered)
      </div>
      <div class="output-content">
        <?php 
          $arr = array('alert','confirm');
          $color = str_replace($arr, '<br>', $_GET['color']);
          echo htmlspecialchars($color);
        ?>
      </div>
      <?php if($_GET['color'] !== $color): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Color filter has modified your input (replaced functions with '&lt;br&gt;')</small>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Multi-Filter Bypass Techniques
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Button Parameter Bypasses:</h6>
                <ul>
                  <li><strong>Case variation:</strong> Use <code>&lt;SCRIPT&gt;</code>, <code>&lt;Script&gt;</code></li>
                  <li><strong>Alternative tags:</strong> Use <code>&lt;svg&gt;</code>, <code>&lt;body&gt;</code>, <code>&lt;iframe&gt;</code></li>
                  <li><strong>Exploit transformation:</strong> The '\">?' replacement might help close attributes
                    <div class="filter-example">
                      Example: <code>script</code> becomes <code>\"&gt;?</code> which might close an attribute
                    </div>
                  </li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Color Parameter Bypasses:</h6>
                <ul>
                  <li><strong>Alternative functions:</strong> Use <code>prompt</code>, <code>console.log</code>, <code>document.write</code></li>
                  <li><strong>Function obfuscation:</strong>
                    <ul>
                      <li><code>window['al'+'ert'](1)</code></li>
                      <li><code>eval('al'+'ert(1)')</code></li>
                      <li><code>Function('al'+'ert(1)')()</code></li>
                    </ul>
                  </li>
                  <li><strong>Exploit replacement:</strong> Use payloads where '&lt;br&gt;' creates valid syntax</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>General Bypass Techniques:</h6>
                <ul>
                  <li><strong>Encoding:</strong> Use HTML entities, Unicode, or Base64 encoding</li>
                  <li><strong>Context switching:</strong> Use different execution contexts (HTML, JavaScript, CSS)</li>
                  <li><strong>Event handlers:</strong> Use onload, onerror, onmouseover without blocked functions</li>
                  <li><strong>JavaScript protocol:</strong> Use <code>javascript:alert(1)</code> in href or src attributes</li>
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
            <p>Multiple filtering approaches demonstrate:</p>
            <ul>
              <li>Different filters require different bypass techniques</li>
              <li>Transformation filters can create new attack vectors</li>
              <li>Quote injection filters might help close attributes</li>
              <li>Break tag replacement can break JavaScript but might be exploitable</li>
              <li>Multiple security controls increase complexity but not necessarily security</li>
              <li>Proper output encoding is still the most reliable defense</li>
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
            <p>For secure web applications:</p>
            <ul>
              <li>Use consistent context-aware output encoding</li>
              <li>Implement strict Content Security Policy (CSP) headers</li>
              <li>Validate input using strict whitelists, not blacklists</li>
              <li>Use modern sanitization libraries (DOMPurify, etc.)</li>
              <li>Avoid transformation filters that can be exploited</li>
              <li>Conduct comprehensive security testing with various payloads</li>
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
            <i class="bi bi-code-square me-2"></i>Payload Examples for Multi-Filter
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Button Parameter Payloads:</h6>
                <ul>
                  <li><code>&lt;SCRIPT&gt;alert(1)&lt;/SCRIPT&gt;</code> - Uppercase bypass</li>
                  <li><code>&lt;svg onload=alert(1)&gt;</code> - Alternative tag</li>
                  <li><code>&lt;body onload=alert(1)&gt;</code> - Body tag with event</li>
                  <li><code>script</code> - Test transformation behavior</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Color Parameter Payloads:</h6>
                <ul>
                  <li><code>prompt(1)</code> - Alternative function</li>
                  <li><code>console.log(1)</code> - Console output</li>
                  <li><code>window['al'+'ert'](1)</code> - String concatenation</li>
                  <li><code>eval('al'+'ert(1)')</code> - Using eval</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Advanced Exploitation Payloads:</h6>
                <ul>
                  <li><strong>DOM-based:</strong> Payloads that leverage DOM manipulation</li>
                  <li><strong>Template injection:</strong> Payloads that use template literals</li>
                  <li><strong>Property injection:</strong> Payloads that set object properties</li>
                  <li><strong>Protocol handlers:</strong> Payloads that use data: or javascript: protocols</li>
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