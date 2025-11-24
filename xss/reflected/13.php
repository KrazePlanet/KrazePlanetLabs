<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','image','alert','confirm','prompt','audio','video','body');
    $re = str_replace($arr, '', $_GET['lname']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - XSS Complete Function Filter Bypass Lab</title>
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

    .filter-item.new {
      background: var(--accent-pink);
      position: relative;
    }

    .filter-item.new::after {
      content: 'NEW';
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--accent-gold);
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
      <p class="hero-subtitle">Complete Challenge: Total Function Filter Bypass</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="complete-badge">Complete Function Filter Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This is the ultimate function filtering challenge! The filter now blocks ALL three common dialog functions: 'alert', 'confirm', and 'prompt', along with multiple HTML tags.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Complete Function Blocking:</strong> The filter now blocks 'prompt' in addition to 'alert' and 'confirm', closing the last major dialog function vector!
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Complete Function Filter:</strong> Blocks 12 case variations of 'script' plus 'img', 'image', 'audio', 'video', 'body', 'alert', 'confirm', and 'prompt'
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
            <div class="filter-item">image</div>
            <div class="filter-item">audio</div>
            <div class="filter-item">video</div>
            <div class="filter-item">body</div>
            <div class="filter-item function">alert</div>
            <div class="filter-item function">confirm</div>
            <div class="filter-item function new">prompt</div>
          </div>
        </div>
        <div class="mt-2">
          <small>Filter method: <code>str_replace(array('script','Script',...,'alert','confirm','prompt'), '', $_GET['lname'])</code></small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Filter Complexity:</span>
          <span class="text-danger">Complete Function-Level Filtering</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 95%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Bypass the complete function filter and execute JavaScript code, even though all dialog functions are blocked.</p>
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
                'img','image','alert','confirm','prompt',
                'audio','video','body');
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
                <label for="lname" class="form-label">Last Name <span class="badge" style="background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));">Complete Filter</span></label>
                <input class="form-control" type="text" placeholder="Enter last name (filtered)" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                <div class="form-text">This field has 20 filters including ALL dialog functions blocked</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Complete Function Filters</button>
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
          $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','image','alert','confirm','prompt','audio','video','body');
          $re = str_replace($arr, '', $_GET['lname']);
          echo htmlspecialchars($re);
        ?>
      </div>
      <?php if($_GET['lname'] !== $re): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Complete function filter has modified your input</small>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Ultimate Function Bypass Techniques
          </div>
          <div class="card-body">
            <ul>
              <li><strong>Alternative output methods:</strong> Use <code>console.log()</code>, <code>document.write()</code>, <code>document.title</code>, <code>location.href</code>, or manipulate DOM elements</li>
              <li><strong>Create custom functions:</strong> Define your own alert function: <code>function a(){window.alert(1)};a()</code></li>
              <li><strong>Global object reflection:</strong> Access functions through reflection: <code>window['al'+'ert']</code>, <code>this['al'+'ert']</code>, <code>self['al'+'ert']</code></li>
              <li><strong>Advanced encoding:</strong> Use multiple encoding layers: HTML entities, URL encoding, Unicode, Base64</li>
              <li><strong>Dynamic evaluation:</strong> Use <code>eval()</code>, <code>Function()</code>, <code>setTimeout()</code>, <code>setInterval()</code> with encoded payloads</li>
              <li><strong>String manipulation:</strong> Build function names with concatenation, template literals, or array methods</li>
              <li><strong>Character code conversion:</strong> Use <code>String.fromCharCode()</code> to build any function name</li>
              <li><strong>Alternative calling patterns:</strong> Use <code>.call()</code>, <code>.apply()</code>, or indirect evaluation</li>
              <li><strong>DOM-based alternatives:</strong> Create elements, modify styles, trigger events for visible effects</li>
              <li><strong>Error-based execution:</strong> Cause JavaScript errors or use error handlers</li>
              <li><strong>Property descriptor access:</strong> Use <code>Object.getOwnPropertyDescriptor(window,'alert').value</code></li>
              <li><strong>Proxy and reflection APIs:</strong> Use advanced JavaScript features for indirect execution</li>
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
            <p>Complete function blocking demonstrates:</p>
            <ul>
              <li>JavaScript execution cannot be prevented by blocking function names</li>
              <li>The language provides infinite ways to execute code</li>
              <li>Function name filtering provides a false sense of security</li>
              <li>Attackers can always find alternative execution paths</li>
              <li>Proper output encoding is the only reliable defense</li>
              <li>Content Security Policy (CSP) is necessary for real protection</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-check-circle me-2"></i>Enterprise Defense Strategies
          </div>
          <div class="card-body">
            <p>For production-grade XSS prevention:</p>
            <ul>
              <li>Implement strict Content Security Policy (CSP) headers</li>
              <li>Use context-aware output encoding for all dynamic content</li>
              <li>Validate input using strict whitelists, not blacklists</li>
              <li>Use modern sanitization libraries (DOMPurify, etc.)</li>
              <li>Implement Trusted Types for DOM XSS protection</li>
              <li>Use security headers: X-XSS-Protection, X-Content-Type-Options</li>
              <li>Conduct regular security testing and code reviews</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-code-square me-2"></i>Complete Bypass Examples
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Custom Function Creation:</h6>
                <ul>
                  <li><code>function x(){window.alert(1)};x()</code></li>
                  <li><code>window.a=window.alert;a(1)</code></li>
                  <li><code>eval('window["al"+"ert"](1)')</code></li>
                  <li><code>setTimeout('window.alert(1)')</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Alternative Output Methods:</h6>
                <ul>
                  <li><code>console.log(1)</code> - Browser console</li>
                  <li><code>document.title=1</code> - Page title</li>
                  <li><code>document.body.innerHTML=1</code> - Page content</li>
                  <li><code>location.href='javascript:alert(1)'</code> - Navigation</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Advanced Encoding Examples:</h6>
                <ul>
                  <li><code>eval('\\u0061lert(1)')</code> - Unicode escape</li>
                  <li><code>eval('al'+String.fromCharCode(101,114,116)+'(1)')</code> - Character codes</li>
                  <li><code>Function('al'+'ert(1)')()</code> - Function constructor</li>
                  <li><code>top['al'+'ert'](1)</code> - Bracket notation</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-award me-2"></i>Mastering Function Filter Bypass
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-md-4 mb-3">
                <div class="p-3 rounded" style="background: rgba(72, 187, 120, 0.3);">
                  <h6>Level 1</h6>
                  <small>Single Function</small>
                  <div class="mt-2"><code>alert</code></div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="p-3 rounded" style="background: rgba(237, 137, 54, 0.5);">
                  <h6>Level 2</h6>
                  <small>Multiple Functions</small>
                  <div class="mt-2"><code>alert, confirm</code></div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="p-3 rounded" style="background: rgba(159, 122, 234, 0.7);">
                  <h6>Level 3</h6>
                  <small>Complete Blocking</small>
                  <div class="mt-2"><code>alert, confirm, prompt</code></div>
                </div>
              </div>
            </div>
            <div class="text-center mt-4">
              <p>You've reached the ultimate function filtering challenge! This demonstrates that even when all common dialog functions are blocked, JavaScript execution remains possible through creative techniques.</p>
              <p class="mb-0"><strong>Remember:</strong> Function name filtering is not a security control - proper output encoding and CSP are the real defenses.</p>
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