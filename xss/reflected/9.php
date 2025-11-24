<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','image','svg','audio','video','body');
    $re = str_replace($arr, '', $_GET['lname']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - XSS Extreme Filter Bypass Lab</title>
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
      --accent-gold: #f6e05e;
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

    .filter-item.new {
      background: var(--accent-pink);
      color: white;
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

    .challenge-update {
      background: rgba(237, 100, 166, 0.1);
      border: 1px solid var(--accent-pink);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .extreme-badge {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple), var(--accent-red));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-extreme 2s infinite alternate;
      text-shadow: 0 0 10px rgba(237, 100, 166, 0.5);
    }

    @keyframes pulse-extreme {
      0% { 
        box-shadow: 0 0 10px var(--accent-pink); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-pink), 0 0 30px var(--accent-purple); 
        transform: scale(1.02);
      }
    }

    .critical-notice {
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
      <p class="hero-subtitle">Extreme Challenge: Beyond Ultimate Filters</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="extreme-badge">Extreme Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This challenge pushes beyond the limits of conventional filtering! The filter now blocks 12 case variations of 'script' plus 'img', 'image', 'svg', 'audio', 'video', and now 'body' tags.</p>
      
      <div class="critical-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Critical Update:</strong> The filter now blocks 'body' tags, closing one of the most reliable XSS vectors!
      </div>
      
      <div class="challenge-update">
        <i class="bi bi-megaphone me-2"></i><strong>Beyond Ultimate:</strong> This filter now blocks the 'body' element, making this one of the most restrictive blacklists possible.
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Extreme Filter:</strong> Blocks 12 case variations of 'script' plus 'img', 'image', 'svg', 'audio', 'video', and 'body'
        <div class="case-variations">
          <strong>Blocked tags and variations:</strong>
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
            <div class="filter-item">svg</div>
            <div class="filter-item">audio</div>
            <div class="filter-item">video</div>
            <div class="filter-item new">body</div>
          </div>
        </div>
        <div class="mt-2">
          <small>Filter method: <code>str_replace(array('script','Script','sCript',...,'body'), '', $_GET['lname'])</code></small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Filter Complexity:</span>
          <span class="text-danger">Extreme-Level Blacklist</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Bypass this extreme filter and execute a JavaScript alert.</p>
      <p class="mb-0"><strong>Critical Note:</strong> Only the <strong>Last Name</strong> field is filtered and displayed. The First Name field is unfiltered but not used in output.</p>
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
                'img','image','svg','audio','video','body');
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
                <label for="lname" class="form-label">Last Name <span class="badge" style="background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));">Extreme Filter</span></label>
                <input class="form-control" type="text" placeholder="Enter last name (filtered)" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                <div class="form-text">This field has 18 different filters applied - including 'body' tag!</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Extreme Filters</button>
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
          $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','image','svg','audio','video','body');
          $re = str_replace($arr, '', $_GET['lname']);
          echo htmlspecialchars($re);
        ?>
      </div>
      <?php if($_GET['lname'] !== $re): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Extreme filter has modified your input</small>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Impossible-Level Bypass Techniques
          </div>
          <div class="card-body">
            <ul>
              <li><strong>Remaining structural elements:</strong> <code>iframe</code>, <code>object</code>, <code>embed</code>, <code>link</code>, <code>meta</code>, <code>base</code>, <code>form</code>, <code>input</code>, <code>button</code>, <code>select</code>, <code>textarea</code>, <code>source</code>, <code>track</code>, <code>canvas</code>, <code>details</code>, <code>summary</code>, <code>marquee</code>, <code>frameset</code>, <code>frame</code> (deprecated), <code>applet</code> (deprecated)</li>
              <li><strong>All remaining event handlers:</strong> Any event handler on allowed elements: <code>onload</code>, <code>onerror</code>, <code>onclick</code>, <code>onmouseover</code>, <code>onfocus</code>, <code>onblur</code>, <code>onchange</code>, <code>onsubmit</code>, <code>onreset</code>, <code>onselect</code>, <code>onabort</code>, <code>oncanplay</code>, <code>oncanplaythrough</code>, <code>ondurationchange</code>, <code>onemptied</code>, <code>onended</code>, <code>onloadeddata</code>, <code>onloadedmetadata</code>, <code>onloadstart</code>, <code>onpause</code>, <code>onplay</code>, <code>onplaying</code>, <code>onprogress</code>, <code>onratechange</code>, <code>onseeked</code>, <code>onseeking</code>, <code>onstalled</code>, <code>onsuspend</code>, <code>ontimeupdate</code>, <code>onvolumechange</code>, <code>onwaiting</code></li>
              <li><strong>Extreme encoding techniques:</strong> Mixed HTML entities, URL encoding, Unicode, and Base64: <code>&lt;&#105;fr&#97;me onload=&#97;lert(1)&gt;</code></li>
              <li><strong>JavaScript pseudo-protocol with extreme encoding:</strong> <code>&lt;a href="&amp;#106;&amp;#97;&amp;#118;&amp;#97;&amp;#115;&amp;#99;&amp;#114;&amp;#105;&amp;#112;&amp;#116;:alert(1)"&gt;click&lt;/a&gt;</code></li>
              <li><strong>Data URI with multiple encoding layers:</strong> Nested encoding techniques</li>
              <li><strong>Unicode normalization and homoglyph attacks:</strong> Characters that normalize to blocked tags</li>
              <li><strong>Advanced Mutation XSS (mXSS):</strong> Complex payloads that mutate during parsing</li>
              <li><strong>CSS injection in style attributes:</strong> Expression functions, other CSS-based attacks</li>
              <li><strong>Template and shadow DOM manipulation:</strong> Modern web component attacks</li>
              <li><strong>MathML and other namespace polyglots:</strong> Cross-namespace payloads</li>
              <li><strong>Browser-specific quirks and features:</strong> Exploiting parser differences</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-award me-2"></i>The Impossible Challenge
          </div>
          <div class="card-body">
            <p>This filter represents the absolute edge of blacklist filtering:</p>
            <ul>
              <li>18 different filter patterns including all major tags</li>
              <li>Blocks 'body' - one of the most versatile XSS vectors</li>
              <li>Demonstrates why blacklists are fundamentally flawed</li>
              <li>Requires thinking completely outside conventional methods</li>
              <li>The ultimate test of XSS creativity and knowledge</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-trophy me-2"></i>Legend Status Achieved
          </div>
          <div class="card-body">
            <p>Successfully bypassing this filter proves:</p>
            <ul>
              <li>Legendary mastery of XSS evasion</li>
              <li>Deep understanding of browser internals</li>
              <li>Expert knowledge of encoding techniques</li>
              <li>Unmatched creative problem-solving</li>
              <li>God-tier web security expertise</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-star-fill me-2"></i>Beyond Ultimate XSS Mastery
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-md-2 mb-3">
                <div class="p-3 rounded" style="background: rgba(72, 187, 120, 0.3);">
                  <small>Level 1</small>
                  <div>Basic</div>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <div class="p-3 rounded" style="background: rgba(72, 187, 120, 0.5);">
                  <small>Level 2</small>
                  <div>Multiple</div>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <div class="p-3 rounded" style="background: rgba(237, 137, 54, 0.5);">
                  <small>Level 3</small>
                  <div>Extended</div>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <div class="p-3 rounded" style="background: rgba(237, 137, 54, 0.7);">
                  <small>Level 4</small>
                  <div>Case Variations</div>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <div class="p-3 rounded" style="background: rgba(245, 101, 101, 0.7);">
                  <small>Level 5</small>
                  <div>Comprehensive</div>
                </div>
              </div>
              <div class="col-md-2 mb-3">
                <div class="p-3 rounded" style="background: rgba(159, 122, 234, 0.7);">
                  <small>Level 6</small>
                  <div>Ultimate</div>
                </div>
              </div>
            </div>
            <div class="text-center mt-4">
              <div class="extreme-badge" style="font-size: 1.2rem; padding: 1rem 2rem;">
                Level 7: Extreme
              </div>
              <p class="mt-3 mb-0">You've reached the extreme challenge - beyond ultimate! This filter blocks the 'body' element, making it one of the most restrictive blacklists possible.</p>
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