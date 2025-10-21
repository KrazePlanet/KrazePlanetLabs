<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    echo urlencode($_GET["lname"]);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Context-Aware Encoding XSS Lab</title>
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

    .encoding-badge {
      background: linear-gradient(45deg, var(--accent-orange), var(--accent-red));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-encoding 2s infinite alternate;
      text-shadow: 0 0 10px rgba(237, 137, 54, 0.5);
    }

    @keyframes pulse-encoding {
      0% { 
        box-shadow: 0 0 10px var(--accent-orange); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-orange), 0 0 30px var(--accent-red); 
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

    .security-low {
      background-color: var(--accent-red);
      color: white;
    }

    .security-medium {
      background-color: var(--accent-orange);
      color: #1a202c;
    }

    .encoding-comparison {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
      border: 1px solid #334155;
    }

    .encoding-example {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
      border: 1px solid #334155;
    }

    .context-warning {
      background: rgba(245, 101, 101, 0.1);
      border: 1px solid var(--accent-red);
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
      <h1 class="hero-title">Context-Aware Encoding XSS Lab</h1>
      <p class="hero-subtitle">Test XSS with inconsistent encoding functions in HTML context</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="encoding-badge">Context-Aware Encoding Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates a critical security vulnerability where different encoding functions are used in the wrong context. The 'fname' parameter uses htmlspecialchars() (correct for HTML context), while 'lname' uses urlencode() (incorrect for HTML context).</p>
      
      <div class="context-warning">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Critical Vulnerability:</strong> Using urlencode() in HTML context provides no protection against XSS attacks. URL encoding is designed for URL contexts, not HTML content.
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Parameter Security Levels:</strong>
        <div class="case-variations">
          <div class="row mt-2">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">fname - Uses htmlspecialchars() (correct for HTML)</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-low">Vulnerable</span>
                <span class="ms-2">lname - Uses urlencode() (wrong for HTML context)</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <span class="badge bg-warning me-2">Common Mistake</span>
                <span>Using wrong encoding for output context</span>
              </div>
              <div class="d-flex align-items-center mt-2">
                <span class="badge bg-info me-2">Context Matters</span>
                <span>Different contexts require different encoding</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="encoding-comparison">
        <h6>Encoding Function Comparison:</h6>
        <div class="row">
          <div class="col-md-6">
            <strong>htmlspecialchars() - Correct for HTML:</strong>
            <div class="mt-1">
              <code>Input: &lt;script&gt;alert(1)&lt;/script&gt;</code>
            </div>
            <div class="mt-1">
              <code>Output: &amp;lt;script&amp;gt;alert(1)&amp;lt;/script&amp;gt;</code>
            </div>
            <div class="mt-2">
              <small class="text-success">Safe - Prevents XSS in HTML context</small>
            </div>
          </div>
          <div class="col-md-6">
            <strong>urlencode() - Wrong for HTML:</strong>
            <div class="mt-1">
              <code>Input: &lt;script&gt;alert(1)&lt;/script&gt;</code>
            </div>
            <div class="mt-1">
              <code>Output: %3Cscript%3Ealert%281%29%3C%2Fscript%3E</code>
            </div>
            <div class="mt-2">
              <small class="text-danger">Vulnerable - No protection against HTML XSS</small>
            </div>
          </div>
        </div>
        
        <div class="encoding-example">
          <h6>Why urlencode() Doesn't Protect Against HTML XSS:</h6>
          <p>When the browser receives <code>%3Cscript%3Ealert%281%29%3C%2Fscript%3E</code> in HTML context, it decodes it back to:</p>
          <code>&lt;script&gt;alert(1)&lt;/script&gt;</code>
          <p class="mt-2">This happens because URL encoding is automatically decoded by browsers when rendering HTML content.</p>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Overall Security Level:</span>
          <span class="text-danger">Critical Vulnerability</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 20%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Exploit the incorrect use of urlencode() in HTML context to execute XSS payloads through the 'lname' parameter.</p>
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
    echo urlencode($_GET["lname"]);
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
            <form action="" method="get" class="mb-4">
              <div class="mb-3">
                <label for="fname" class="form-label">First Name <span class="param-security-level security-high">Secure</span></label>
                <input class="form-control" type="text" placeholder="Enter first name" aria-label="First name" name="fname" value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : ''; ?>">
                <div class="form-text">This parameter uses htmlspecialchars() encoding (correct for HTML)</div>
              </div>
              <div class="mb-3">
                <label for="lname" class="form-label">Last Name <span class="param-security-level security-low">Vulnerable</span></label>
                <input class="form-control" type="text" placeholder="Enter last name" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                <div class="form-text">This parameter uses urlencode() (WRONG for HTML context - XSS possible!)</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Encoding Functions</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php if(isset($_GET["fname"]) && isset($_GET["lname"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Parameters Output
      </div>
      <div class="output-content">
        <div class="mb-3">
          <strong>First Name (htmlspecialchars):</strong><br>
          <?php echo htmlspecialchars($_GET["fname"], ENT_QUOTES); ?>
        </div>
        <div>
          <strong>Last Name (urlencode):</strong><br>
          <?php echo urlencode($_GET["lname"]); ?>
        </div>
      </div>
      <div class="mt-3">
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>First name is safely encoded with htmlspecialchars()</small><br>
        <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>WARNING: Last name uses urlencode() which provides NO protection in HTML context!</small>
      </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>XSS Payload Examples for URL-Encoded Parameter
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Basic Script Tags:</h6>
                <ul>
                  <li><code>&lt;script&gt;alert(1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;alert(document.domain)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;alert(document.cookie)&lt;/script&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Event Handlers:</h6>
                <ul>
                  <li><code>&lt;img src=x onerror=alert(1)&gt;</code></li>
                  <li><code>&lt;body onload=alert(1)&gt;</code></li>
                  <li><code>&lt;svg onload=alert(1)&gt;</code></li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                <h6>JavaScript Protocol:</h6>
                <ul>
                  <li><code>&lt;a href="javascript:alert(1)"&gt;click&lt;/a&gt;</code></li>
                  <li><code>&lt;iframe src="javascript:alert(1)"&gt;&lt;/iframe&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Advanced Techniques:</h6>
                <ul>
                  <li><code>&lt;object data="javascript:alert(1)"&gt;&lt;/object&gt;</code></li>
                  <li><code>&lt;embed src="javascript:alert(1)"&gt;&lt;/embed&gt;</code></li>
                </ul>
              </div>
            </div>
            
            <div class="encoding-example mt-3">
              <h6>Why These Work:</h6>
              <p>When you submit <code>&lt;script&gt;alert(1)&lt;/script&gt;</code> as the last name:</p>
              <ol>
                <li>The server applies urlencode(): <code>%3Cscript%3Ealert%281%29%3C%2Fscript%3E</code></li>
                <li>The browser receives this in HTML context and automatically decodes it</li>
                <li>The decoded content becomes: <code>&lt;script&gt;alert(1)&lt;/script&gt;</code></li>
                <li>The browser executes the JavaScript</li>
              </ol>
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
            <p>Using wrong encoding functions demonstrates:</p>
            <ul>
              <li>Context-aware encoding is critical for security</li>
              <li>urlencode() provides no XSS protection in HTML context</li>
              <li>Inconsistent security implementations create vulnerabilities</li>
              <li>Developers must understand output context for proper encoding</li>
              <li>Automated URL decoding in browsers can bypass intended protection</li>
              <li>Security controls must match the attack vector</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-check-circle me-2"></i>Proper Encoding by Context
          </div>
          <div class="card-body">
            <p>Use the right encoding for each context:</p>
            <ul>
              <li><strong>HTML Content:</strong> htmlspecialchars() or htmlentities()</li>
              <li><strong>HTML Attributes:</strong> htmlspecialchars(ENT_QUOTES)</li>
              <li><strong>URL Parameters:</strong> urlencode() or rawurlencode()</li>
              <li><strong>JavaScript Context:</strong> json_encode() or custom escaping</li>
              <li><strong>CSS Context:</strong> Custom CSS escaping</li>
              <li><strong>SQL Queries:</strong> Prepared statements (not encoding)</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-code-square me-2"></i>Real-World Impact & Prevention
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Common Encoding Mistakes:</h6>
                <ul>
                  <li><strong>URL encoding in HTML:</strong> No XSS protection</li>
                  <li><strong>HTML encoding in JavaScript:</strong> Syntax errors</li>
                  <li><strong>No encoding in SQL:</strong> SQL injection risk</li>
                  <li><strong>Inconsistent encoding:</strong> Mixed security levels</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Impact of Wrong Encoding:</h6>
                <ul>
                  <li>XSS vulnerabilities despite "encoding"</li>
                  <li>False sense of security</li>
                  <li>Session hijacking through cookie theft</li>
                  <li>Account takeover attacks</li>
                  <li>Complete application compromise</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Prevention Strategies:</h6>
                <ul>
                  <li><strong>Context-aware encoding:</strong> Use the right function for each context</li>
                  <li><strong>Security frameworks:</strong> Use frameworks that handle encoding automatically</li>
                  <li><strong>Security training:</strong> Educate developers about context-aware encoding</li>
                  <li><strong>Code review:</strong> Check encoding usage in security reviews</li>
                  <li><strong>Automated testing:</strong> Test for XSS with various payloads</li>
                  <li><strong>Security headers:</strong> Implement Content Security Policy (CSP)</li>
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