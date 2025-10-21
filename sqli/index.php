<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - SQL Injection Training Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

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

    .stats-card {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      transition: all 0.3s ease;
      overflow: hidden;
      height: 100%;
    }

    .stats-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      border-color: var(--accent-green);
    }

    .category-card {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      transition: all 0.3s ease;
      overflow: hidden;
      height: 100%;
      margin-bottom: 2rem;
    }

    .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      border-color: var(--accent-green);
    }

    .category-header {
      padding: 1.5rem;
      border-bottom: 1px solid #334155;
      background: rgba(15, 23, 42, 0.5);
    }

    .category-icon {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .category-title {
      font-size: 1.4rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .category-desc {
      color: #94a3b8;
      font-size: 0.95rem;
    }

    .lab-list {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    .lab-list li {
      padding: 14px 18px;
      background-color: rgba(30, 41, 59, 0.5);
      border-bottom: 1px solid #334155;
      transition: background-color 0.2s;
      display: flex;
      align-items: center;
    }

    .lab-list li:last-child {
      border-bottom: none;
    }

    .lab-list li:hover {
      background-color: rgba(56, 66, 89, 0.5);
    }

    .lab-number {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      background: var(--primary-light);
      border-radius: 50%;
      font-size: 0.85rem;
      font-weight: 600;
      margin-right: 12px;
      flex-shrink: 0;
    }

    .lab-link {
      text-decoration: none;
      color: #e2e8f0;
      font-weight: 500;
      flex-grow: 1;
      display: flex;
      align-items: center;
    }

    .lab-link:hover {
      color: var(--accent-green);
    }

    .difficulty-badge {
      font-size: 0.7rem;
      padding: 4px 8px;
      border-radius: 4px;
      margin-left: 10px;
    }

    .badge-low {
      background-color: var(--accent-green);
      color: #1a202c;
    }

    .badge-medium {
      background-color: var(--accent-orange);
      color: #1a202c;
    }

    .badge-high {
      background-color: var(--accent-red);
      color: #1a202c;
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
      border-radius: 10px;
      padding: 1.5rem;
      border: 1px solid #334155;
      text-align: center;
      transition: transform 0.3s;
    }

    .stats-card:hover {
      transform: translateY(-3px);
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
      padding: 3rem 1rem;
      text-align: center;
      color: #94a3b8;
      font-style: italic;
    }

    footer {
      margin-top: 50px;
      padding: 30px 0;
      text-align: center;
      color: #94a3b8;
      border-top: 1px solid #334155;
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
            <a class="nav-link active" href="../about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="../contact">Contact Us</a>
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
    <div class="container text-center">
      <h1 class="hero-title">SQL Injection Platform</h1>
      <p class="hero-subtitle">Master database exploitation through hands-on SQL injection labs designed to challenge your database security skills.</p>
      
      <div class="row mt-5">
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">10</div>
            <div class="stats-label">Labs Available</div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">3</div>
            <div class="stats-label">Difficulty Level</div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">100%</div>
            <div class="stats-label">Hands-On</div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stats-card">
            <div class="stats-number">SQLi</div>
            <div class="stats-label">Focus Area</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mb-5">

    <div class="category-card">
      <div class="category-header">
        <div class="category-icon">💉</div>
        <h3 class="category-title">Basic SQL Injection Challenges</h3>
        <p class="category-desc">Start with fundamental SQL injection techniques and learn to extract data from databases.</p>
      </div>
      <ul class="lab-list">
        <li>
          <span class="lab-number">1</span>
          <a href="1" class="lab-link">Lab 1</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">2</span>
          <a href="2" class="lab-link">Lab 2</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">3</span>
          <a href="3" class="lab-link">Lab 3</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">4</span>
          <a href="4" class="lab-link">Lab 4</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">5</span>
          <a href="5" class="lab-link">Lab 5</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">6</span>
          <a href="6" class="lab-link">Lab 6</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">7</span>
          <a href="7" class="lab-link">Lab 7</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">8</span>
          <a href="8" class="lab-link">Lab 8</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">9</span>
          <a href="9" class="lab-link">Lab 9</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
        <li>
          <span class="lab-number">10</span>
          <a href="10" class="lab-link">Lab 10</a>
          <span class="difficulty-badge badge-low">Low</span>
        </li>
      </ul>
    </div>

    <div class="category-card">
      <div class="category-header">
        <div class="category-icon">🛡️</div>
        <h3 class="category-title">Advanced SQL Injection Challenges</h3>
        <p class="category-desc">Coming soon - Advanced filter bypass techniques and database-specific exploitation.</p>
      </div>
      <div class="coming-soon">
        <i class="bi bi-tools display-4 mb-3"></i>
        <h4>Coming Soon</h4>
        <p>Advanced SQL injection labs are currently in development</p>
      </div>
    </div>

    <div class="category-card">
      <div class="category-header">
        <div class="category-icon">🚩</div>
        <h3 class="category-title">Expert SQL Injection Challenges</h3>
        <p class="category-desc">Coming soon - Complex scenarios with multiple layers of protection and advanced techniques.</p>
      </div>
      <div class="coming-soon">
        <i class="bi bi-tools display-4 mb-3"></i>
        <h4>Coming Soon</h4>
        <p>Expert SQL injection labs are currently in development</p>
      </div>
    </div>

  </div>

  <footer>
    <div class="container">
      <p>© 2025 KrazePlanetLabs - SQL Injection Training Platform. All rights reserved.</p>
      <p class="mb-0">Designed for educational purposes to enhance web application security skills.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>

</html>