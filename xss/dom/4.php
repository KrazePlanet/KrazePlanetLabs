<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4: JSON-based DOM XSS</title>
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

        .vulnerability-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-orange);
        }

        .payload-examples {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-blue);
        }

        .danger-zone {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent-red);
        }

        pre {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            color: #e2e8f0;
            border: 1px solid #334155;
            overflow-x: auto;
        }

        .lab-info {
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .lab-badge {
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            color: #1a202c;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .demo-section {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-green);
        }

        .json-display {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            word-break: break-all;
        }

        .user-profile {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 3px solid var(--accent-orange);
        }

        .test-urls {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-blue);
        }

        .api-form {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--accent-green);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-arrow-left me-2"></i>Back to DOM XSS Labs
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Lab 4: JSON-based DOM XSS</h1>
            <p class="hero-subtitle">Client-side XSS using JSON data and AJAX</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="lab-info">
            <span class="lab-badge">Difficulty: High</span>
            <h3 class="section-title">Lab Overview</h3>
            <p>This lab demonstrates a DOM XSS vulnerability where JSON data from an API response is directly inserted into the DOM without proper sanitization. The application fetches user data and displays it dynamically.</p>
            <p><strong>Objective:</strong> Inject a DOM XSS payload through JSON data that will execute when the user profile is displayed.</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <i class="bi bi-code-slash me-2"></i>Vulnerable JavaScript Code
                    </div>
                    <div class="card-body">
                        <pre>
// Vulnerable JSON processing
function loadUserProfile(userId) {
    // Simulate API call with JSON data
    var jsonData = {
        "id": userId,
        "name": "John Doe",
        "email": "john@example.com",
        "bio": "Software developer with 5 years experience",
        "website": "https://johndoe.com",
        "location": "New York, USA"
    };
    
    // Vulnerable: Direct insertion into DOM
    document.getElementById('userName').innerHTML = 
        '&lt;h2&gt;' + jsonData.name + '&lt;/h2&gt;';
    
    document.getElementById('userBio').innerHTML = 
        '&lt;p&gt;' + jsonData.bio + '&lt;/p&gt;';
    
    document.getElementById('userWebsite').innerHTML = 
        '&lt;a href="' + jsonData.website + '"&gt;' + jsonData.website + '&lt;/a&gt;';
    
    document.getElementById('userLocation').innerHTML = 
        '&lt;span&gt;' + jsonData.location + '&lt;/span&gt;';
    
    // Vulnerable: JSON display
    document.getElementById('jsonData').innerHTML = 
        '&lt;pre&gt;' + JSON.stringify(jsonData, null, 2) + '&lt;/pre&gt;';
}

// Vulnerable AJAX function
function fetchUserData(userId) {
    // Simulate AJAX response
    var response = '{"id":"' + userId + '","name":"&lt;script&gt;alert(\'XSS\')&lt;/script&gt;","email":"test@example.com"}';
    var userData = JSON.parse(response);
    
    // Vulnerable: Direct insertion
    document.getElementById('ajaxResult').innerHTML = 
        '&lt;div class="user-info"&gt;' +
        '&lt;h3&gt;' + userData.name + '&lt;/h3&gt;' +
        '&lt;p&gt;Email: ' + userData.email + '&lt;/p&gt;' +
        '&lt;/div&gt;';
}</pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bug me-2"></i>Live Demo
                    </div>
                    <div class="card-body">
                        <div class="api-form">
                            <h6><i class="bi bi-person me-2"></i>Load User Profile:</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="userIdInput" placeholder="Enter user ID..." value="1">
                                <button class="btn btn-primary" onclick="loadUserProfile(document.getElementById('userIdInput').value)">Load Profile</button>
                            </div>
                        </div>
                        
                        <div class="demo-section">
                            <h6><i class="bi bi-person-circle me-2"></i>User Profile:</h6>
                            <div class="user-profile" id="userProfile">
                                <div id="userName"></div>
                                <div id="userBio"></div>
                                <div id="userWebsite"></div>
                                <div id="userLocation"></div>
                            </div>
                            
                            <h6><i class="bi bi-code-square me-2"></i>JSON Data:</h6>
                            <div class="json-display" id="jsonData">
                                <script>
                                    // Initialize with default data
                                    loadUserProfile('1');
                                </script>
                            </div>
                            
                            <h6><i class="bi bi-arrow-repeat me-2"></i>AJAX Test:</h6>
                            <div class="user-profile" id="ajaxResult">
                                <button class="btn btn-sm btn-outline-primary" onclick="fetchUserData('malicious')">Test AJAX XSS</button>
                            </div>
                        </div>
                        
                        <div class="test-urls">
                            <h6><i class="bi bi-target me-2"></i>Test Payloads:</h6>
                            <p>Try these user IDs or modify the JSON data:</p>
                            <ul>
                                <li><code>1</code> - Normal user</li>
                                <li><code>malicious</code> - Triggers AJAX XSS</li>
                                <li>Modify JSON to include XSS payloads</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="vulnerability-info">
                    <h5><i class="bi bi-bug me-2"></i>Vulnerability Details</h5>
                    <ul>
                        <li><strong>Type:</strong> DOM XSS via JSON Data</li>
                        <li><strong>Severity:</strong> High</li>
                        <li><strong>Source:</strong> JSON.parse() and AJAX responses</li>
                        <li><strong>Sink:</strong> <code>innerHTML</code> (multiple locations)</li>
                        <li><strong>Trigger:</strong> JSON data processing and display</li>
                        <li><strong>Issue:</strong> JSON data without sanitization</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="payload-examples">
                    <h5><i class="bi bi-target me-2"></i>Test Payloads</h5>
                    <p>Modify the JSON data to include these payloads:</p>
                    <ul>
                        <li><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                        <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                        <li><code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code></li>
                        <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                    </ul>
                    <p><strong>Example JSON:</strong></p>
                    <pre>{
  "name": "&lt;script&gt;alert('XSS')&lt;/script&gt;",
  "bio": "&lt;img src=x onerror=alert('XSS')&gt;"
}</pre>
                </div>
            </div>
        </div>

        <div class="danger-zone">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Real-World Attack Scenarios</h5>
            <ul>
                <li>API response hijacking and data manipulation</li>
                <li>Session hijacking through malicious JSON data</li>
                <li>Credential theft via fake user profiles</li>
                <li>Malware distribution through JSON responses</li>
                <li>Phishing attacks with legitimate-looking data</li>
                <li>Bypassing API security controls</li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-lightbulb me-2"></i>Mitigation Strategies
            </div>
            <div class="card-body">
                <ul>
                    <li>Use <code>textContent</code> instead of <code>innerHTML</code></li>
                    <li>Implement proper JSON data validation and sanitization</li>
                    <li>Use Content Security Policy (CSP) headers</li>
                    <li>Sanitize all JSON data before DOM insertion</li>
                    <li>Use safe DOM manipulation methods</li>
                    <li>Implement proper output encoding</li>
                    <li>Use a JavaScript security library like DOMPurify</li>
                    <li>Validate JSON schema before processing</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Vulnerable JSON processing
        function loadUserProfile(userId) {
            // Simulate API call with JSON data
            var jsonData = {
                "id": userId,
                "name": "John Doe",
                "email": "john@example.com",
                "bio": "Software developer with 5 years experience",
                "website": "https://johndoe.com",
                "location": "New York, USA"
            };
            
            // Vulnerable: Direct insertion into DOM
            document.getElementById('userName').innerHTML = 
                '<h2>' + jsonData.name + '</h2>';
            
            document.getElementById('userBio').innerHTML = 
                '<p>' + jsonData.bio + '</p>';
            
            document.getElementById('userWebsite').innerHTML = 
                '<a href="' + jsonData.website + '">' + jsonData.website + '</a>';
            
            document.getElementById('userLocation').innerHTML = 
                '<span>' + jsonData.location + '</span>';
            
            // Vulnerable: JSON display
            document.getElementById('jsonData').innerHTML = 
                '<pre>' + JSON.stringify(jsonData, null, 2) + '</pre>';
        }

        // Vulnerable AJAX function
        function fetchUserData(userId) {
            // Simulate AJAX response
            var response = '{"id":"' + userId + '","name":"<script>alert(\'XSS\')</script>","email":"test@example.com"}';
            var userData = JSON.parse(response);
            
            // Vulnerable: Direct insertion
            document.getElementById('ajaxResult').innerHTML = 
                '<div class="user-info">' +
                '<h3>' + userData.name + '</h3>' +
                '<p>Email: ' + userData.email + '</p>' +
                '</div>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
</body>
</html>
