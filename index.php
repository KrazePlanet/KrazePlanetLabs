<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Security Training Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="icon" href="favicon.ico" />
  
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
      padding: 3rem 0;
      border-bottom: 1px solid #2d3748;
      margin-bottom: 2rem;
    }

    .hero-title {
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(90deg, #48bb78, #4299e1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 1rem;
    }

    .hero-subtitle {
      font-size: 1.2rem;
      color: #cbd5e0;
      max-width: 600px;
      margin: 0 auto;
    }

    .section-title {
      margin-top: 40px;
      margin-bottom: 25px;
      font-weight: 700;
      font-size: 1.8rem;
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
      transition: all 0.3s ease;
      overflow: hidden;
      height: 100%;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      border-color: var(--accent-green);
    }

    .card-img-top {
      border-radius: 12px 12px 0 0;
      height: 250px;
      object-fit: cover;
      border-bottom: 1px solid #334155;
    }

    .card-body {
      padding: 1.5rem;
    }

    .card-title {
      font-weight: 600;
      color: #e2e8f0;
      margin-bottom: 0.5rem;
    }

    .card-subtitle {
      color: var(--accent-green);
      font-weight: 500;
      font-size: 1.1rem;
    }

    .lab-count {
      display: inline-block;
      background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
      color: #1a202c;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
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

    .stats-card {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      padding: 1.5rem;
      transition: all 0.3s ease;
      overflow: hidden;
      height: 100%;
    }

    .stats-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      border-color: var(--accent-green);
    }

    .stats-number {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      background: linear-gradient(90deg, #48bb78, #4299e1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .stats-label {
      color: #94a3b8;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .coming-soon {
      position: relative;
      overflow: hidden;
    }

    .coming-soon::after {
      content: 'Coming Soon';
      position: absolute;
      top: 10px;
      right: -30px;
      background: var(--accent-orange);
      color: #1a202c;
      padding: 5px 30px;
      font-size: 0.75rem;
      font-weight: 600;
      transform: rotate(45deg);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    footer {
      margin-top: 50px;
      padding: 30px 0;
      text-align: center;
      color: #94a3b8;
      border-top: 1px solid #334155;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    a:hover {
      color: inherit;
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
            <a class="nav-link active" href="about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="contact">Contact Us</a>
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
    <div class="container text-center">
      <h1 class="hero-title">Web Security Training Platform</h1>
      <p class="hero-subtitle">Master cybersecurity vulnerabilities through hands-on labs designed to challenge and enhance your penetration testing skills.</p>
      
      <div class="row mt-5">
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">217+</div>
            <div class="stats-label">Labs Available</div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">22</div>
            <div class="stats-label">Vulnerability Types</div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">3</div>
            <div class="stats-label">Difficulty Levels</div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">100%</div>
            <div class="stats-label">Hands-On</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mb-5">
    <h2 class="section-title">Vulnerability Categories</h2>

    <div class="row gy-4">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="xss">
          <div class="card">
            <img src="img/1.jpg" class="card-img-top" alt="XSS Labs">
            <div class="card-body">
              <span class="lab-count">92 Labs</span>
              <h5 class="card-title">Cross-Site Scripting</h5>
              <h6 class="card-subtitle">XSS</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="sqli">
          <div class="card">
            <img src="img/2.jpg" class="card-img-top" alt="SQL Injection Labs">
            <div class="card-body">
              <span class="lab-count">8 Labs</span>
              <h5 class="card-title">SQL Injection</h5>
              <h6 class="card-subtitle">SQLI</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="lfi">
          <div class="card">
            <img src="img/3.jpg" class="card-img-top" alt="LFI Labs">
            <div class="card-body">
              <span class="lab-count">9 Lab</span>
              <h5 class="card-title">Local File Inclusion</h5>
              <h6 class="card-subtitle">LFI</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="rce">
          <div class="card">
            <img src="img/4.jpg" class="card-img-top" alt="RCE Labs">
            <div class="card-body">
              <span class="lab-count">5 Lab</span>
              <h5 class="card-title">Remote Code Execution</h5>
              <h6 class="card-subtitle">RCE</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="ssti">
          <div class="card">
            <img src="img/5.png" class="card-img-top" alt="SSTI Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Server-Side Template Injection</h5>
              <h6 class="card-subtitle">SSTI</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="http_rs">
          <div class="card">
            <img src="img/6.jpg" class="card-img-top" alt="HTTP Request Smuggling Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">HTTP Request Smuggling</h5>
              <h6 class="card-subtitle">HTTP Request Smuggling</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="injection">
          <div class="card">
            <img src="img/7.png" class="card-img-top" alt="Command Injection Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Command Injection</h5>
              <h6 class="card-subtitle">Command Injection</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="idor">
          <div class="card">
            <img src="img/8.png" class="card-img-top" alt="IDOR Labs">
            <div class="card-body">
              <span class="lab-count">10 Labs</span>
              <h5 class="card-title">Insecure Direct Object Reference</h5>
              <h6 class="card-subtitle">IDOR</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="redirect">
          <div class="card">
            <img src="img/9.png" class="card-img-top" alt="Open Redirect Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Open Redirect</h5>
              <h6 class="card-subtitle">Open Redirect</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="directory_traversal">
          <div class="card">
            <img src="img/10.jpg" class="card-img-top" alt="Directory Traversal Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Directory Traversal</h5>
              <h6 class="card-subtitle">Directory Traversal</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="xxe">
          <div class="card">
            <img src="img/11.jpg" class="card-img-top" alt="XXE Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">XML External Entity</h5>
              <h6 class="card-subtitle">XXE</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="subdomain_takeovers">
          <div class="card">
            <img src="img/12.jpg" class="card-img-top" alt="Subdomain Takeover Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Subdomain Takeovers</h5>
              <h6 class="card-subtitle">Subdomain</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="ssrf">
          <div class="card">
            <img src="img/13.jpg" class="card-img-top" alt="SSRF Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Server-Side Request Forgery</h5>
              <h6 class="card-subtitle">SSRF</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="rfi">
          <div class="card">
            <img src="img/14.jpg" class="card-img-top" alt="RFI Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Remote File Inclusion</h5>
              <h6 class="card-subtitle">RFI</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="response_manipulation">
          <div class="card">
            <img src="img/15.jpg" class="card-img-top" alt="Response Manipulation Labs">
            <div class="card-body">
              <span class="lab-count">17 Labs</span>
              <h5 class="card-title">Response Manipulation</h5>
              <h6 class="card-subtitle">Response Manipulation</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="pollution">
          <div class="card">
            <img src="img/16.jpg" class="card-img-top" alt="Pollution Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Prototype Pollution</h5>
              <h6 class="card-subtitle">Pollution</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="otpbypass">
          <div class="card">
            <img src="img/17.jpg" class="card-img-top" alt="OTP Bypass Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">OTP Bypass</h5>
              <h6 class="card-subtitle">OTP Bypass</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="htmli">
          <div class="card">
            <img src="img/18.jpg" class="card-img-top" alt="HTML Injection Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">HTML Injection</h5>
              <h6 class="card-subtitle">HTMLi</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="fileupload">
          <div class="card">
            <img src="img/19.jpg" class="card-img-top" alt="File Upload Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">File Upload</h5>
              <h6 class="card-subtitle">File Upload</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="csti">
          <div class="card">
            <img src="img/20.jpg" class="card-img-top" alt="CSTI Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Client-Side Template Injection</h5>
              <h6 class="card-subtitle">CSTI</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="csrf">
          <div class="card">
            <img src="img/21.jpg" class="card-img-top" alt="CSRF Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Cross-Site Request Forgery</h5>
              <h6 class="card-subtitle">CSRF</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="cors">
          <div class="card">
            <img src="img/22.jpg" class="card-img-top" alt="CORS Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Cross-Origin Resource Sharing</h5>
              <h6 class="card-subtitle">CORS</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="clickjacking">
          <div class="card">
            <img src="img/23.jpg" class="card-img-top" alt="Clickjacking Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Clickjacking</h5>
              <h6 class="card-subtitle">Clickjacking</h6>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="business_logic">
          <div class="card">
            <img src="img/24.jpg" class="card-img-top" alt="Business Logic Labs">
            <div class="card-body">
              <span class="lab-count">5 Labs</span>
              <h5 class="card-title">Business Logic Flaws</h5>
              <h6 class="card-subtitle">Business Logic</h6>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <p>© 2025 KrazePlanetLabs - Security Training Platform. All rights reserved.</p>
      <p class="mb-0">Designed for educational purposes to enhance cybersecurity skills.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>
</html>