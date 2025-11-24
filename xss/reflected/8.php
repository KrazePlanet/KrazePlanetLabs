<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','image','svg','audio','video');
    $re = str_replace($arr, '', $_GET['lname']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - XSS Final Ultimate Filter Bypass Lab</title>
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
      background: linear-gradient(45deg, var(--accent-gold), var(--accent-orange));
      color: #1a202c;
      font-weight: 700;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 1rem;
    }

    .filter-info {
      background: rgba(30, 41, 59, 0.7);
      border-left: 4px solid var(--accent-gold);
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
      background: var(--accent-gold);
      color: #1a202c;
      position: relative;
    }

    .filter-item.new::after {
      content: 'NEW';
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--accent-purple);
      color: white;
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
      background: linear-gradient(90deg, var(--accent-gold), var(--accent-orange));
    }

    .case-variations {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
      border: 1px solid #334155;
    }

    .challenge-update {
      background: rgba(246, 224, 94, 0.1);
      border: 1px solid var(--accent-gold);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .final-badge {
      background: linear-gradient(45deg, var(--accent-gold), var(--accent-orange), var(--accent-red));
      color: #1a202c;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: glow 2s infinite alternate;
      text-shadow: 0 0 10px rgba(246, 224, 94, 0.5);
    }

    @keyframes glow {
      0% { box-shadow: 0 0 10px var(--accent-gold); }
      100% { box-shadow: 0 0 20px var(--accent-gold), 0 0 30px var(--accent-orange); }
    }

    .field-change-notice {
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
      <h1 class="hero-title">Reflected XSS Bootcamp</h1>
      <p class="hero-subtitle">Final Challenge: The Complete Ultimate Filter</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="final-badge">Final Ultimate Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This is the final and most comprehensive XSS filter challenge! The filter now blocks 12 case variations of 'script' plus 'img', 'image', 'svg', 'audio', and 'video' tags.</p>
      
      <div class="field-change-notice">
        <i class="bi bi-arrow-left-right me-2"></i><strong>Important Change:</strong> The filter is now applied to the <strong>Last Name</strong> field instead of First Name! Only Last Name is filtered and displayed.
      </div>
      
      <div class="challenge-update">
        <i class="bi bi-megaphone me-2"></i><strong>Final Enhancement:</strong> The filter has been expanded to block 'video' tags, closing the last major media tag vector.
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Complete Ultimate Filter:</strong> Blocks 12 case variations of 'script' plus 'img', 'image', 'svg', 'audio', and 'video'
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
            <div class="filter-item new">video</div>
          </div>
        </div>
        <div class="mt-2">
          <small>Filter method: <code>str_replace(array('script','Script','sCript',...,'video'), '', $_GET['lname'])</code></small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Filter Complexity:</span>
          <span class="text-warning">Maximum-Level Blacklist</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Bypass the complete ultimate filter and execute a JavaScript alert.</p>
      <p class="mb-0"><strong>Critical Note:</strong> Only the <strong>Last Name</strong> field is now filtered and displayed. The First Name field is unfiltered but not used in output.</p>
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
                'img','image','svg','audio','video');
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
                <label for="lname" class="form-label">Last Name <span class="badge" style="background: linear-gradient(45deg, var(--accent-gold), var(--accent-orange)); color: #1a202c;">Ultimate Filter</span></label>
                <input class="form-control" type="text" placeholder="Enter last name (filtered)" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                <div class="form-text">This field has 17 different filters applied - this is the field that gets displayed!</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Complete Ultimate Filters</button>
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
          $arr = array('script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','img','image','svg','audio','video');
          $re = str_replace($arr, '', $_GET['lname']);
          echo htmlspecialchars($re);
        ?>
      </div>
      <?php if($_GET['lname'] !== $re): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Complete ultimate filter has modified your input</small>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Supreme Bypass Techniques
          </div>
          <div class="card-body">
            <ul>
              <li><strong>Remaining HTML elements:</strong> <code>body</code>, <code>iframe</code>, <code>object</code>, <code>embed</code>, <code>link</code>, <code>meta</code>, <code>base</code>, <code>form</code>, <code>input</code>, <code>button</code>, <code>select</code>, <code>textarea</code>, <code>source</code>, <code>track</code>, <code>canvas</code>, <code>details</code>, <code>summary</code>, <code>marquee</code> (deprecated but works)</li>
              <li><strong>All event handlers:</strong> <code>onload</code>, <code>onerror</code>, <code>onclick</code>, <code>onmouseover</code>, <code>onfocus</code>, <code>onblur</code>, <code>onchange</code>, <code>onsubmit</code>, <code>onreset</code>, <code>onselect</code>, <code>onabort</code>, <code>oncanplay</code>, <code>oncanplaythrough</code>, <code>ondurationchange</code>, <code>onemptied</code>, <code>onended</code>, <code>onloadeddata</code>, <code>onloadedmetadata</code>, <code>onloadstart</code>, <code>onpause</code>, <code>onplay</code>, <code>onplaying</code>, <code>onprogress</code>, <code>onratechange</code>, <code>onseeked</code>, <code>onseeking</code>, <code>onstalled</code>, <code>onsuspend</code>, <code>ontimeupdate</code>, <code>onvolumechange</code>, <code>onwaiting</code></li>
              <li><strong>Advanced encoding:</strong> Mixed HTML entities, URL encoding, and Unicode: <code>&lt;b&amp;#111;dy onload=&amp;#97;&amp;#108;&amp;#101;&amp;#114;&amp;#116;(1)&gt;</code></li>
              <li><strong>JavaScript pseudo-protocol with encoding:</strong> <code>&lt;a href="j&amp;#97;v&amp;#97;script:alert(1)"&gt;click&lt;/a&gt;</code></li>
              <li><strong>Data URI with multiple encoding layers:</strong> Base64, URL encoding, HTML entities</li>
              <li><strong>Unicode homoglyph attacks:</strong> Characters that look like blocked tags but are different</li>
              <li><strong>Mutation XSS (mXSS):</strong> Payloads that change during browser parsing</li>
              <li><strong>CSS injection in style attributes:</strong> Expression functions in older IE, other CSS-based attacks</li>
              <li><strong>Template injection:</strong> <code>&lt;template&gt;</code> with event handlers</li>
              <li><strong>MathML polyglots:</strong> Payloads that work as both HTML and MathML</li>
              <li><strong>Namespace confusion:</strong> Mixing HTML and SVG/other namespaces</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-award me-2"></i>The Final Achievement
          </div>
          <div class="card-body">
            <p>This challenge represents the absolute maximum in blacklist filtering:</p>
            <ul>
              <li>17 different filter patterns including all case variations</li>
              <li>Blocks all common multimedia and script tags</li>
              <li>Demonstrates why blacklists can never be complete</li>
              <li>Requires ultimate creativity and browser knowledge</li>
              <li>The final test in the XSS filter bypass series</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-trophy me-2"></i>Master Status Unlocked
          </div>
          <div class="card-body">
            <p>Successfully bypassing this filter proves:</p>
            <ul>
              <li>Complete mastery of XSS evasion techniques</li>
              <li>Deep understanding of browser behavior</li>
              <li>Expert knowledge of HTML/JS encoding</li>
              <li>Advanced creative problem-solving</li>
              <li>Elite-level web security expertise</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-star-fill me-2"></i>Complete XSS Mastery Journey
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
              <div class="final-badge" style="font-size: 1.2rem; padding: 1rem 2rem;">
                Level 7: Final Ultimate
              </div>
              <p class="mt-3 mb-0">You've reached the absolute final challenge in the XSS filter bypass series! This is the maximum complexity.</p>
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