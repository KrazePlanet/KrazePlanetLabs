<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - SSTI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
    crossorigin="anonymous">

  <style>
    body {
      background-color: #f8f9fa;
    }

    .section-title {
      margin-top: 40px;
      margin-bottom: 15px;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .lab-list {
      list-style-type: none;
      padding-left: 0;
    }

    .lab-list li {
      padding: 12px 16px;
      background-color: white;
      border-radius: 5px;
      margin-bottom: 8px;
      transition: background-color .2s;
    }

    .lab-list li:hover {
      background-color: #e9ecef;
    }

    a {
      text-decoration: none;
      color: #0d6efd;
    }

    a:hover {
      text-decoration: underline;
    }

    footer {
      margin-top: 50px;
      padding: 20px;
      text-align: center;
      color: #6c757d;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark"
    style="background-color: rgb(58, 63, 68);">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" style="color: rgb(107, 189, 69);" href="/">KrazePlanetLabs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="../about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="../contact">Contact Us</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mt-4">

    <div class="section-title">
      <span class="badge bg-success">Low Difficulty</span>
    </div>
    <ul class="lab-list">
      <!-- <li><a href="3">Lab 3</a></li> -->
      <!-- <li><a href="4">Lab 4</a></li> -->
    </ul>

    <div class="section-title">
      <span class="badge bg-warning text-dark">Medium Difficulty</span>
    </div>
    <ul class="lab-list">
      <!-- <li><a href="3">Lab 3</a></li> -->
      <!-- <li><a href="4">Lab 4</a></li> -->
    </ul>

    <div class="section-title">
      <span class="badge bg-danger">High Difficulty</span>
    </div>
    <ul class="lab-list">
      <!-- <li><a href="5">Lab 5</a></li> -->
      <!-- <li><a href="6">Lab 6</a></li> -->
    </ul>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>

</html>
