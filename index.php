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

    .nav-link {
      font-weight: 500;
      transition: color 0.3s;
    }

    .nav-link:hover {
      color: var(--accent-green) !important;
    }

    .navbar {
      background: linear-gradient(90deg, #0f172a 0%, #1e1b4b 100%) !important;
      border-bottom: 1px solid #334155;
      padding: 0.75rem 0;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
      background: linear-gradient(90deg, #ef4444, #f97316);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .navbar-brand i {
      background: linear-gradient(90deg, #ef4444, #f97316);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 1.5rem;
    }

    .navbar-nav {
      margin: 0 auto;
      gap: 1.5rem;
    }

    .navbar-nav .nav-link {
      color: #94a3b8 !important;
      font-weight: 500;
      padding: 0.5rem 1rem !important;
      border-radius: 0.5rem;
      transition: all 0.3s;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: #e2e8f0 !important;
      background: rgba(255, 255, 255, 0.05);
    }

    .btn-cta {
      background: linear-gradient(90deg, #22c55e, #16a34a);
      border: none;
      color: white;
      font-weight: 600;
      border-radius: 0.75rem;
      transition: all 0.3s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
    }

    .btn-cta:hover {
      background: linear-gradient(90deg, #16a34a, #15803d);
      transform: translateY(-2px);
      color: white;
      box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
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

    a {
      text-decoration: none;
      color: inherit;
    }

    a:hover {
      color: inherit;
    }

    /* Lab Card Styles - Adapted from new-index.php for dark theme */
    .category-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: #e2e8f0;
      margin: 2rem 0 1rem 0;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--accent-green);
      display: inline-block;
    }

    .labs-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .lab-card {
      display: flex;
      background: rgba(30, 41, 59, 0.8);
      border-radius: 50px;
      border: 1px solid #334155;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      align-items: center;
      position: relative;
    }

    .lab-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      border-color: var(--accent-green);
    }

    .lab-badge {
      background: linear-gradient(135deg, var(--primary-dark) 0%, #2d3748 100%);
      color: white;
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      font-size: 0.9rem;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      align-self: stretch;
      min-width: 120px;
      justify-content: center;
      border-right: 1px solid #334155;
    }

    .lab-badge svg {
      width: 20px;
      height: 20px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .lab-content {
      flex: 1;
      padding: 1rem 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 6px;
    }

    .difficulty-tag {
      background: var(--accent-green);
      color: #1a202c;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 4px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      display: inline-flex;
      width: fit-content;
    }

    .difficulty-tag.medium {
      background: #f1c40f;
    }

    .difficulty-tag.hard {
      background: var(--accent-red);
      color: white;
    }

    .lab-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #e2e8f0;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .lab-title svg {
      width: 18px;
      height: 18px;
      transition: transform 0.2s;
    }

    .lab-card:hover .lab-title svg {
      transform: translateX(4px);
    }

    .lab-desc {
      font-size: .72rem;
      color: #7a8fa6;
      margin-top: 3px;
      font-family: monospace;
      letter-spacing: .01em;
    }

    .report-badge {
      color: #fff;
      background: #be185d;
      padding: 1px 6px;
      border-radius: 3px;
      text-decoration: none;
      font-weight: 700;
      font-size: .72rem;
      font-family: monospace;
      letter-spacing: .02em;
      flex-shrink: 0;
    }
    .report-badge:hover { background: #9d174d; }

    .lab-action {
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-ACCESS {
      background: var(--accent-orange);
      color: #1a202c;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.85rem;
      padding: 10px 20px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
      box-shadow: 0 4px 6px rgba(237, 137, 54, 0.3);
      text-transform: uppercase;
      letter-spacing: 0.02em;
      border: none;
      cursor: pointer;
    }

    .btn-ACCESS:hover {
      background: #ff8c5a;
      transform: scale(1.02);
      box-shadow: 0 6px 12px rgba(237, 137, 54, 0.4);
      color: #1a202c;
    }

    .btn-ACCESS svg {
      width: 18px;
      height: 18px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    @media (max-width: 768px) {
      .lab-card {
        flex-direction: column;
        align-items: stretch;
        border-radius: 20px;
      }
      .lab-badge {
        border-radius: 20px 20px 0 0;
        border-right: none;
        border-bottom: 1px solid #334155;
        padding: 1rem;
      }
      .lab-content {
        padding: 1.25rem;
        align-items: flex-start;
      }
      .lab-action {
        padding: 0 1.25rem 1.25rem;
        justify-content: flex-start;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="/">
        <i class="bi bi-fire"></i>KrazePlanetLabs
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact Us</a>
          </li>
        </ul>
        <a href="https://discord.gg/Ujg69RM6qd" target="_blank" rel="noopener noreferrer" class="btn btn-cta ms-auto">
          <i class="bi bi-discord me-2"></i>Join Discord
        </a>
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
    
  <div class="container mb-5">
    <h2 class="section-title">Vulnerability Categories</h2>

    <!-- Cross-Site Scripting (XSS)-->
    <h3 class="category-title">Cross-Site Scripting (XSS)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Basic Input
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Script Tag Filter Evasion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="2.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Script & Img Tag Filter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="3.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Case-Insensitive Filter Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="4.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Less-Than Sign Filter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="5.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS in HTML Title Tag
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="6.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
            <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag easy">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS in Page Heading
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="7.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 8
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag easy">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Function Name Filter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="8.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 9
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Extended Function Filter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="9.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 10
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Event Handler Filter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="10.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 11
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Multi-Parameter Filter Evasion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="11.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>

      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 12
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Encoding Bypass Attempts
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="12.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 13
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - Mixed Security Parameters
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="13.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 14
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - String Concatenation Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="14.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 15
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS - URL Encoding Context
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="15.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div> -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 16
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS in Search Function
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="16.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 17
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS in Category Filter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="17.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 18
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Stored XSS - User Comments System
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="18.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 19
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Stored XSS - User Profile Management
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="19.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 20
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Stored XSS - Blog Post System
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="20.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 21
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Stored XSS - Support Ticket System
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="21.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 22
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Stored XSS - Admin Panel Settings
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="22.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 48
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CSP Bypass - Unsafe Inline Scripts
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="48.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div> -->
      <!-- <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 49
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CSP Protected Page
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="49.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div> -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 50
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Self XSS via POST Parameter
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="50.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 51
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            POST-Based Reflected XSS
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="51.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 52
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            POST XSS in Input Tag Value
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="52.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 53
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            POST XSS in Document Title
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="53.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 54
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            DOM-based XSS with jQuery
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="54.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 55
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Reflected XSS in JS Analytics Context (Equifax — HackerOne <a href="https://hackerone.com/reports/1818163" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1818163</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="55.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 56
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Low</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Reflected XSS in HTML Attribute Context (PUBG — HackerOne <a href="https://hackerone.com/reports/751870" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#751870</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="56.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 57
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Low</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            XSS via javascript: URI in Redirect Parameter (Shopify — HackerOne <a href="https://hackerone.com/reports/1940245" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1940245</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="57.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 58
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Reflected XSS in URL Path Segment (Imgur Mobile — HackerOne <a href="https://hackerone.com/reports/149855" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#149855</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="58.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 59
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Reflected XSS via Unquoted Attribute Injection (Reddit — HackerOne <a href="https://hackerone.com/reports/1549206" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1549206</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="59.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 60
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Stored XSS in Report Name Field (MoPub / Twitter — HackerOne <a href="https://hackerone.com/reports/485748" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#485748</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="60.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 61
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Stored XSS via Rich Text Editor HTML Tab in Article Body — Quill CMS (Shopify — HackerOne <a href="https://hackerone.com/reports/1147433" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1147433</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="61.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 62
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Stored XSS in Profile Signature Field — DevAsk Forum (Acronis — HackerOne <a href="https://hackerone.com/reports/1084183" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1084183</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="62.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 63
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Blind Stored XSS in Company Name (Informatica — HackerOne <a href="https://hackerone.com/reports/1011888" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1011888</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="63.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 64
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Blind XSS via Support Ticket Form — ZAP-Hosting (Name, Subject &amp; Message fields)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="64.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 65
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
            <a href="https://hackerone.com/reports/474656" target="_blank" rel="noopener noreferrer" class="report-badge">HackerOne #474656</a>
          </div>
          <div class="lab-title">
            DOM XSS via URL Tracking Parameter — HackerOne Careers
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
          <div class="lab-desc">?lever- tracking param → jQuery.append() unsanitized sink</div>
        </div>
        <div class="lab-action">
          <a href="65.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 66
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
            <a href="https://hackerone.com/reports/324303" target="_blank" rel="noopener noreferrer" class="report-badge">HackerOne #324303</a>
          </div>
          <div class="lab-title">
            DOM XSS via URL Hash Fragment — MyCrypto Wallet
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
          <div class="lab-desc">#send-transaction hash → innerHTML unsanitized sink</div>
        </div>
        <div class="lab-action">
          <a href="66.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 67
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
            <a href="https://hackerone.com/reports/396493" target="_blank" rel="noopener noreferrer" class="report-badge">HackerOne #396493</a>
          </div>
          <div class="lab-title">
            Reflected DOM XSS via URL + prettyPhoto Hash Chain — Starbucks UK
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
          <div class="lab-desc">?slug= → canonical link attr injection + prettyPhoto jQuery trigger</div>
        </div>
        <div class="lab-action">
          <a href="67.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div> -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 68
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
            <a href="https://hackerone.com/reports/704266" target="_blank" rel="noopener noreferrer" class="report-badge">HackerOne #704266</a>
          </div>
          <div class="lab-title">
            DOM XSS via Hash in jQuery Fancybox Selector — ForeScout Technologies
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
          <div class="lab-desc">window.location.hash → .html() unsanitized sink</div>
        </div>
        <div class="lab-action">
          <a href="68.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 69
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
            <a href="https://hackerone.com/reports/1004833" target="_blank" rel="noopener noreferrer" class="report-badge">HackerOne #1004833</a>
          </div>
          <div class="lab-title">
            DOM XSS via javascript: URI in location.replace — Informatica IQ Card
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
          <div class="lab-desc">document.location.search → location.replace() navigation sink</div>
        </div>
        <div class="lab-action">
          <a href="69.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 1
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/1.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 2
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/2.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 3
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/3.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 4
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/4.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 5
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/5.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 6
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/6.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
            <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 7
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/7.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 8
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 8
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/8.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 9
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 9
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/9.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 10
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 10
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/10.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 11
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 11
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/11.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>

      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 12
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 12
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/12.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 13
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 13
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/13.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 14
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 14
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/14.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 15
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 15
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/15.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 16
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 16
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/16.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 17
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 17
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/17.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 18
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 18
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/18.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 19
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 19
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/19.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 20
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 20
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/20.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 21
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 21
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/21.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 22
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 22
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/22.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 23
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 23
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/23.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 24
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 24
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/24.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 25
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 25
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/25.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 26
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 26
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/26.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 27
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 27
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/27.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 28
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 28
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/28.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 29
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 29
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/29.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 30
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 30
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/30.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 31
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 31
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/31.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 32
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 32
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/32.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 33
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 33
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/33.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
          LAB 34
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PUNISHMENT LAB 34
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="punishment/34.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>


    <!-- HTML Injection (HTMLI) -->
    <h3 class="category-title">HTML Injection (HTMLI)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            HTML Injection in Support Chat (LinkedIn — HackerOne <a href="https://hackerone.com/reports/3079966" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#3079966</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1201.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected HTML Injection via Search Parameter (E-commerce — Common Real-World Pattern)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1202.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Stored HTML Injection via Nickname in Wallet-Share Email (Romit - HackerOne <a href="https://hackerone.com/reports/57914" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#57914</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1203.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Stored HTML Tag Injection via Profile Name in Snippets Page (GitLab — HackerOne <a href="https://hackerone.com/reports/358001" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#358001</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1204.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            HTML Injection via First/Last Name in Confirmation Email (HackerOne — <a href="https://hackerone.com/reports/1374017" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1374017</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1205.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Server-Side Template Injection (SSTI) -->
    <h3 class="category-title">Server-Side Template Injection (SSTI)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Template Engine Code Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="801.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            SSTI via First Name in Registration Welcome Email (Glovo — <a href="https://hackerone.com/reports/1104349" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1104349</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="802.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            SSTI via Profile Name in Account Update Email (Uber — <a href="https://hackerone.com/reports/125980" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#125980</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="803.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            SSTI via Profile Fields in Invitation Email — Smarty RCE (Unikrn — <a href="https://hackerone.com/reports/164224" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#164224</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="804.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Open Redirect -->
    <h3 class="category-title">Open Redirect</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Basic URL Parameter Redirect
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="301.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Open Redirect via URL Path Manipulation (Omise — HackerOne <a href="https://hackerone.com/reports/504751" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#504751</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="302.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Open Redirect via URL Parameter (?url=) — Semrush · HackerOne <a href="https://hackerone.com/reports/311330" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#311330</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="303.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag" style="background:#ea580c;color:#fff;">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Open Redirect via \@ Validator Bypass (Tumblr — HackerOne <a href="https://hackerone.com/reports/2812583" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#2812583</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="304.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Authentication Bypass -->
    <h3 class="category-title">Authentication Bypass</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Admin Auth Bypass via Response Manipulation (UPS — HackerOne <a href="https://hackerone.com/reports/1490470" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1490470</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="201.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            OTP Verification Bypass via Response Manipulation
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="202.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Phone OTP Bypass via Response Manipulation
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="203.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- SQL Injection (SQLI) -->
    <h3 class="category-title">SQL Injection (SQLI)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            SQL Injection - Login Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="101.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            INSERT SQL Injection - Comment System
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="102.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CRUD SQL Injection - Book Management
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="103.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Time-based Blind SQL Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="104.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Integer-based SQL Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="105.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            User-Agent Header Blind SQL Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="106.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Referer Header Blind SQL Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="107.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
          LAB 8
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            X-Forwarded-For Header Blind SQL Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="108.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 9
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Time-based Blind SQLi via item_id + WAF Bypass (Zomato — <a href="https://hackerone.com/reports/403616" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#403616</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="109.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 10
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Time-based Blind SQLi via User-Agent + XOR Arithmetic (labs.data.gov — <a href="https://hackerone.com/reports/297478" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#297478</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="110.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 11
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            UNION-based SQLi via URL siteId — Results Reflected in Page (IntenseDebate — <a href="https://hackerone.com/reports/1046084" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1046084</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="111.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 12
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Blind SQLi via phone_number Login Field + XOR Payload (MTN FutExpert — <a href="https://hackerone.com/reports/1069531" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1069531</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="112.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 13
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            ORDER BY SQLi via WordPress Shortcode Parameter (drivegrab.com / Grab — <a href="https://hackerone.com/reports/273946" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#273946</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="113.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 14
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Boolean-blind SQLi via REST API Path Segment (inDrive — <a href="https://hackerone.com/reports/2051931" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#2051931</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="114.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 15
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Time-Based Blind SQLi via JSONP Analytics Tracker (Rocket.Chat / AgileCRM — <a href="https://hackerone.com/reports/433792" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#433792</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="115.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 16
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Boolean-Blind SQLi in PUT API Path Segment (Hyperpure / Zomato — <a href="https://hackerone.com/reports/1044716" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1044716</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="116.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 17
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            UNION-Based SQLi in Bearer-Auth Admin Search API (Acronis — <a href="https://hackerone.com/reports/923020" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#923020</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="117.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 18
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            UNION SQLi in WooCommerce Coupon Usage Report (Automattic — <a href="https://hackerone.com/reports/3198980" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#3198980</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="118.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 19
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Time-Based Blind SQLi + XOR WAF Bypass in WordPress Login (Acronis — <a href="https://hackerone.com/reports/1224660" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1224660</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="119.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 20
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            UNION SQLi via Integer <code style="background:rgba(255,255,255,.12);padding:1px 4px;border-radius:2px;font-size:.8em;">entryid</code> in DoD Form Confirmation AJAX Endpoint (U.S. DoD — <a href="https://hackerone.com/reports/3127198" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#3127198</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="120.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 21
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Blind SQLi via <code style="background:rgba(255,255,255,.12);padding:1px 4px;border-radius:2px;font-size:.8em;">CASE/**/ WHEN</code> + Comment-Space WAF Bypass in Zomato Banner API (Zomato — <a href="https://hackerone.com/reports/838855" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#838855</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="121.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 22
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Time-Based Blind SQLi via GET Parameter in IntenseDebate Comment Settings (Automattic — <a href="https://hackerone.com/reports/1042746" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1042746</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="122.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 23
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            String SQLi via Nested Subquery WAF Bypass in DoD Publications (U.S. DoD — <a href="https://hackerone.com/reports/491191" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#491191</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="123.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          LAB 24
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Time-Based Blind SQLi via XOR in DoD Publications Search (U.S. DoD — <a href="https://hackerone.com/reports/2312334" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#2312334</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="124.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Cross-Site Request Forgery (CSRF) -->
    <h3 class="category-title">Cross-Site Request Forgery (CSRF)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Login CSRF — Token Never Validated (HackerOne — HackerOne <a href="https://hackerone.com/reports/834366" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#834366</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1301.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Login CSRF — No Token on API Login Endpoint — Unikrn (<a href="https://hackerone.com/reports/339352" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#339352</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1302.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            CSRF via GraphQL GET Mutation — Token Bypass on /api/graphql — GitLab (<a href="https://hackerone.com/reports/1122408" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1122408</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1303.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            CSRF → Reflected XSS via Unsanitized Wishlist Comment — Teavana/Starbucks (<a href="https://hackerone.com/reports/177508" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#177508</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1304.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            CSRF Account Takeover via Profile Edit — U.S. Dept of Defense / NPS (<a href="https://hackerone.com/reports/2712857" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#2712857</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1305.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            CSRF → Attribute-Context XSS via Training Answer Field — DoD/JKO (<a href="https://hackerone.com/reports/1118521" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1118521</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1306.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CSRF Password Change — Unprotected Account Settings
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1307.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 8
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CSRF Email Hijack — Silent Account Takeover
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1308.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 9
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CSRF Account Wipe — Irreversible Data Deletion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1309.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 11
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CSRF 2FA Bypass — Silent Security Downgrade
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1311.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Server-Side Request Forgery (SSRF) -->
    <h3 class="category-title">Server-Side Request Forgery (SSRF)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Source Code Viewer - Basic cURL SSRF
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="601.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Screenshot Tool - URL to Image
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="602.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Port-based Timing Attack
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="603.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Domain Restriction Bypass with Redirects
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="604.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Website Checker with IP Blacklist
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="605.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            AWS Metadata Filter Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="606.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PDF Generator - URL to PDF
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="607.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Local File Inclusion (LFI) -->
    <h3 class="category-title">Local File Inclusion (LFI)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Path Traversal - Basic
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="901.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CMS Local File Inclusion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="902.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            File Upload with LFI Vulnerability
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="903.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Image Gallery File Inclusion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="904.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Unauthenticated LFI via <code style="background:rgba(255,255,255,.12);padding:1px 4px;border-radius:2px;font-size:.8em;">!</code> Path Separator in Jolokia JMX Bridge (U.S. DoD — <a href="https://hackerone.com/reports/2778380" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#2778380</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="125.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Grafana LFI via Path Traversal in Plugin Static Files (MariaDB — <a href="https://hackerone.com/reports/1419213" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1419213</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="126.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            LFI via Prefix-Bypass Path Traversal in <code style="background:rgba(255,255,255,.12);padding:1px 4px;border-radius:2px;font-size:.8em;">download.php</code> (U.S. DoD — <a href="https://hackerone.com/reports/1639364" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1639364</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="127.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 8
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            LFI via Double URL Encoding in GWT CSS Servlet (U.S. DoD — <a href="https://hackerone.com/reports/497771" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#497771</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="128.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 9
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Nginx merge_slashes Path Traversal — Tech Giant OAuth CDN
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1618.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Remote Code Execution (RCE) -->
    <h3 class="category-title">Remote Code Execution (RCE)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            OS Command Injection
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1101.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            RCE via Prototype Pollution in Kibana SIEM ML Signal Processing — Elastic (<a href="https://hackerone.com/reports/861744" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#861744</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1102.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            RCE via ImageMagick + Ghostscript CVE-2017-8291 (Profile Image Upload) — Basecamp (<a href="https://hackerone.com/reports/365271" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#365271</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1103.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            RCE via Arbitrary File Write in RServer Report Export (ASPX Webshell) — U.S. Dept of Defense (<a href="https://hackerone.com/reports/1072832" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1072832</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1104.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Insecure Direct Object Reference (IDOR) -->
    <h3 class="category-title">Insecure Direct Object Reference (IDOR)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            SwiftCart — Insecure Order Invoice Disclosure
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="701.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
            <a href="https://hackerone.com/reports/150095" target="_blank" rel="noopener noreferrer" class="report-badge">HackerOne #150095</a>
          </div>
          <div class="lab-title">
            Uber Driver Portal — Trip &amp; Earnings Disclosure
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="702.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            MediCare+ — Healthcare Records IDOR
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="703.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            FriendZone — Social Media Profile IDOR
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="704.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M20 18l2-1v-2.5"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            SecureBank — Banking Portal Account IDOR
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="705.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Remote File Inclusion (RFI) -->
    <h3 class="category-title">Remote File Inclusion (RFI)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Remote File Inclusion via URL
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1001.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            RFI + XSS + SSRF via Unvalidated URL Proxy in GIS Portal (U.S. DoD — <a href="https://hackerone.com/reports/192940" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#192940</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1002.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            PageForge CMS — Content Manager Remote &amp; Local File Inclusion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1003.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            ShopStream — E-Commerce Bulk Product Import Remote &amp; Local File Inclusion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1004.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            StreamFlux — Video Analytics CDN Origin Asset Proxy Remote &amp; Local File Inclusion
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1005.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- XML External Entity (XXE) -->
    <h3 class="category-title">XML External Entity (XXE)</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            XML External Entity (XXE) via URL
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1401.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            XXE on Twitter SMS SXMP API (File Read via operatorId Error Reflection) — <a href="https://hackerone.com/reports/248668" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#248668</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1402.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            LFI + SSRF via XXE in SVG Emblem Editor (Rockstar Games ImageMagick) — <a href="https://hackerone.com/reports/347139" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#347139</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1403.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            Blind XXE via JPEG XMP Metadata Injection (Informatica OOB Exfiltration) — <a href="https://hackerone.com/reports/836877" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#836877</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1404.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#6366f1;color:#fff;">Real World</span>
          </div>
          <div class="lab-title">
            XXE via XML Resume Upload Starbucks China Career Portal (IIS + ASP.NET) — <a href="https://hackerone.com/reports/500515" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#500515</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1405.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            XXE via XML Registration API &mdash; SecureVault Password Manager
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1406.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag medium">Medium</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            XXE via XML Login API &mdash; SecureVault Password Manager
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1407.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Business Logic Vulnerabilities -->
    <h3 class="category-title">Business Logic Vulnerabilities</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag">Easy</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Business Logic Vulnerability in URL Shortener
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1501.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>

    <!-- Special Vulnerabilities -->
    <h3 class="category-title">Special Vulnerabilities</h3>
    <div class="labs-list">
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 1
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Stored XSS via Profile Bio &amp; Reflected XSS via Search Bar — Cookie Theft &amp; Account Takeover
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1601.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 2
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            LFI via Unrestricted File Upload in Web Application
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1602.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 3
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            RCE via Resume Upload in Job Portal
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1603.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 4
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Reflected XSS via base64-encoded Payload in Search Functionality
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1604.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 5
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            RCE in error parameter on admin panel
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1605.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 6
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Blind SQL Injection via Parameter name &mdash; Executive Dashboard
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1606.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 7
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Blind SQL Injection via PATH_INFO &mdash; Industrial Asset Registry
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1607.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 8
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Blind SQL Injection via Filename &mdash; University Course Catalog
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1608.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 9 -- sitemap.xml -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 9
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Time-based Blind SQLi via sitemap.xml &mdash; ACME Corp Industrial
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1609.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 10 -- RecipeBox -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 10
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            RecipeBox — Base64-Encoded Path LFI Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1610.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 11 -- GovDocs -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 11
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            GovDocs — Double URL Encoding LFI Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1611.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 12 -- Admin Login LFI -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 12
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Admin Portal — Error Parameter Path Traversal
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1612.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 13 -- Jenkins Unauthenticated RCE -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 13
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Jenkins — Unauthenticated Script Console RCE
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1613.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 14 -- ControlHub JSON Auth Bypass -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 14
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            ControlHub — JSON Response Manipulation Auth Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1614.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 15 -- VaultTech JWT Credential Reuse -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 15
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            VaultTech — JWT Token Credential Reuse Across Admin Panels
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1615.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 16 -- CloudSync PII Leaked on Unauthorized File -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 16
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CloudSync — PII Leaked on Unauthorized File
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1616.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 17 -- CBSE Portal Default Credentials -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 17
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CBSE — Default Credentials Authentication Bypass
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1617.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 18 -- Nginx merge_slashes Path Traversal -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 18
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Nginx merge_slashes Off — Path Traversal Bypass (Tech Giant OAuth CDN)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1618.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 19 -- Symfony Profiler Debug Mode -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 19
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Symfony Profiler Debug Mode — DB Credentials, API Keys &amp; Secrets Exposed via app_dev.php
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1619.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 20 -- CGI reset.cgi Remote Code Execution -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 20
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CGI reset.cgi — Command Injection via db_prefix Parameter (Remote Code Execution)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1620.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 21 -- CDN Directory Listing PII Disclosure -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 21
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            CDN Directory Listing — PII Exposed via Open /cdn/ Path (Passport Scans, National IDs, Credit Cards)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="kzlabs/cdn/images/ahmetyuksek-winding-road-10313716_1920.jpg" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 22 -- Aliyun WAF Bypass: cat Blocked, Alternative Commands Bypass WAF -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 22
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Aliyun WAF Bypass — Bypass WAF Rules (Real-World Finding)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1622.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
      <!-- LAB 23 -- Unrestricted File Upload: PHP Profile Picture RCE -->
      <div class="lab-card">
        <div class="lab-badge">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
          LAB 23
        </div>
        <div class="lab-content">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <span class="difficulty-tag hard">Hard</span>
            <span class="difficulty-tag" style="background:#0D9488;color:#fff;">Training</span>
          </div>
          <div class="lab-title">
            Unrestricted File Upload — PHP Profile Picture Leads to Remote Code Execution (MTN Group — <a href="https://hackerone.com/reports/1164452" target="_blank" rel="noopener noreferrer" style="color:#fff;background:#be185d;padding:1px 5px;border-radius:3px;text-decoration:none;font-weight:600;font-size:.85em;">#1164452</a>)
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
        <div class="lab-action">
          <a href="1623.php" class="btn-ACCESS">
            <svg viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            ACCESS THE LAB
          </a>
        </div>
      </div>
    </div>
  </div>
  </div>


  <footer style="text-align: center; padding: 1rem 0; color: #94a3b8; font-size: 0.875rem;">
    <p class="mb-0">© 2026 KrazePlanet. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>
</html>