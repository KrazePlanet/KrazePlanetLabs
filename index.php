<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <style>
    .card {
      transition: transform .30s;
      border-radius: 20px;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .section-title {
      margin-top: 40px;
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 1.5rem;
      color: #4e9af1;
    }

    .card-img-top {
      border-radius: 20px 20px 0 0;
    }
  </style>
</head>

<body style="background-color: #000">
    <nav class="navbar navbar-expand-md navbar-dark"
    style="background-color: rgb(58, 63, 68); --darkreader-inline-bgcolor:#2f3335;" data-darkreader-inline-bgcolor="">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" style="color: rgb(107, 189, 69);"
              href="/">KrazePlanetLabs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="contact">Contact Us</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>


  <div class="container">
    <div class="row gy-4 my-2">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="xss"><img src="img/1.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">31 Labs</h5>
            <h6 class="card-title">XSS</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="sqli"><img src="img/2.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">10 Labs</h5>
            <h6 class="card-title">SQLI</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="lfi"><img src="img/3.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">1 Labs</h5>
            <h6 class="card-title">LFI</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="rce"><img src="img/4.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">1 Labs</h5>
            <h6 class="card-title">RCE</h6>
          </div>
        </div>
      </div>
      <!-- <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="csrf"><img src="img/3.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">CSRF</h6>
          </div>
        </div>
      </div> -->
      <!-- <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="ssrf"><img src="img/4.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">SSRF</h6>
          </div>
        </div>
      </div> -->
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="ssti"><img src="img/5.png" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">SSTI</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="http_rs"><img src="img/6.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">HTTP Request Smuggling</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="injection"><img src="img/7.png" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">Command Injection</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="idor"><img src="img/8.png" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">IDOR</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="redirect"><img src="img/9.png" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">Open Redirect</h6>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
          <a href="directory_triversal"><img src="img/10.jpg" class="card-img-top" alt="..."></a>
          <div class="card-body">
            <h5 class="card-title">0 Labs</h5>
            <h6 class="card-title">Directory triversal</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
    integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"
    integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK"
    crossorigin="anonymous"></script>
</body>
</html>