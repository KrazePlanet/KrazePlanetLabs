<?php
// Enterprise Document Management System v3.5.1
// Copyright © 2024 SecureCorp International

session_start();

// Initialize security system
if (!isset($_SESSION['enterprise_security'])) {
    $_SESSION['enterprise_security'] = [
        'user_id' => 'EMP_' . rand(10000, 99999),
        'department' => 'IT Security',
        'access_level' => 7,
        'login_time' => time(),
        'failed_attempts' => 0,
        'last_activity' => time(),
        'security_token' => bin2hex(random_bytes(16))
    ];
}

// Enterprise Security Class
class EnterpriseSecurity {
    private $security_log = [];
    private $threat_level = 0;
    
    public function __construct() {
        $this->initializeSecurityProtocols();
    }
    
    private function initializeSecurityProtocols() {
        // Initialize security monitoring
        register_shutdown_function([$this, 'securityAudit']);
    }
    
    public function validateRequest($input) {
        $this->logSecurityEvent("Request validation started for: " . substr($input, 0, 50));
        
        // Multi-stage validation pipeline
        $stages = [
            'input_sanitization' => $this->sanitizeInput($input),
            'malicious_pattern_detection' => $this->detectMaliciousPatterns($input),
            'path_validation' => $this->validatePath($input),
            'encoding_analysis' => $this->analyzeEncoding($input),
            'final_approval' => $this->finalApproval($input)
        ];
        
        foreach ($stages as $stage => $result) {
            if (!$result['approved']) {
                $this->threat_level += $result['threat_score'];
                $this->logSecurityEvent("SECURITY BLOCKED at $stage: " . $result['reason']);
                return false;
            }
        }
        
        $this->logSecurityEvent("Request approved with threat level: " . $this->threat_level);
        return true;
    }
    
    private function sanitizeInput($input) {
        // Remove null bytes and control characters
        $sanitized = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Normalize path separators
        $sanitized = str_replace('\\', '/', $sanitized);
        
        return [
            'approved' => true,
            'threat_score' => 0,
            'sanitized_output' => $sanitized
        ];
    }
    
    private function detectMaliciousPatterns($input) {
        $patterns = [
            '/\.\.\//' => 10,
            '/\/etc\//' => 15,
            '/\/proc\//' => 20,
            '/\/root\//' => 15,
            '/\/var\//' => 10,
            '/flag/' => 5,
            '/\.env/' => 10,
            '/config/' => 8,
            '/passwd/' => 15,
            '/shadow/' => 20,
            '/\.\.\\\\/' => 10
        ];
        
        foreach ($patterns as $pattern => $score) {
            if (preg_match($pattern, $input)) {
                return [
                    'approved' => false,
                    'threat_score' => $score,
                    'reason' => "Malicious pattern detected: $pattern"
                ];
            }
        }
        
        return ['approved' => true, 'threat_score' => 0];
    }
    
    private function validatePath($input) {
        // Check for path traversal attempts
        $normalized = $this->normalizePath($input);
        
        if (strpos($normalized, '..') !== false) {
            return [
                'approved' => false,
                'threat_score' => 25,
                'reason' => 'Path traversal detected after normalization'
            ];
        }
        
        // Ensure path starts with allowed base
        $allowed_bases = ['documents', 'templates', 'reports', 'archives'];
        $path_valid = false;
        
        foreach ($allowed_bases as $base) {
            if (strpos($normalized, $base) === 0) {
                $path_valid = true;
                break;
            }
        }
        
        if (!$path_valid) {
            return [
                'approved' => false,
                'threat_score' => 15,
                'reason' => 'Path outside allowed directories'
            ];
        }
        
        return ['approved' => true, 'threat_score' => 0];
    }
    
    private function normalizePath($path) {
        $parts = explode('/', $path);
        $result = [];
        
        foreach ($parts as $part) {
            if ($part === '..') {
                if (!empty($result)) array_pop($result);
            } elseif ($part !== '' && $part !== '.') {
                $result[] = $part;
            }
        }
        
        return implode('/', $result);
    }
    
    private function analyzeEncoding($input) {
        // Check for double encoding
        $double_encoded = preg_match('/%25[0-9a-f]{2}/i', $input);
        if ($double_encoded) {
            return [
                'approved' => false,
                'threat_score' => 12,
                'reason' => 'Double URL encoding detected'
            ];
        }
        
        // Check for mixed encoding
        $mixed_encoding = preg_match('/%[0-9a-f]{2}.*%25[0-9a-f]{2}/i', $input);
        if ($mixed_encoding) {
            return [
                'approved' => false,
                'threat_score' => 8,
                'reason' => 'Mixed encoding patterns detected'
            ];
        }
        
        return ['approved' => true, 'threat_score' => 0];
    }
    
    private function finalApproval($input) {
        // Final comprehensive check
        $threat_indicators = 0;
        
        // Check for PHP wrappers (case variations)
        if (preg_match('/php:\/\//i', $input)) {
            $threat_indicators += 20;
        }
        
        // Check for data protocol
        if (preg_match('/data:\/\//i', $input)) {
            $threat_indicators += 25;
        }
        
        // Check for expect wrapper
        if (preg_match('/expect:\/\//i', $input)) {
            $threat_indicators += 30;
        }
        
        if ($threat_indicators > 15) {
            return [
                'approved' => false,
                'threat_score' => $threat_indicators,
                'reason' => 'Dangerous protocol wrapper detected'
            ];
        }
        
        return ['approved' => true, 'threat_score' => 0];
    }
    
    private function logSecurityEvent($event) {
        $this->security_log[] = [
            'timestamp' => microtime(true),
            'event' => $event,
            'user' => $_SESSION['enterprise_security']['user_id'],
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
    }
    
    public function getSecurityLog() {
        return $this->security_log;
    }
    
    public function securityAudit() {
        // Log security events at shutdown
        if (!empty($this->security_log)) {
            $audit_entry = "=== SECURITY AUDIT ===\n";
            $audit_entry .= "User: " . $_SESSION['enterprise_security']['user_id'] . "\n";
            $audit_entry .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
            $audit_entry .= "Final Threat Level: " . $this->threat_level . "\n";
            $audit_entry .= "Events:\n" . implode("\n", array_column($this->security_log, 'event')) . "\n\n";
            
            @file_put_contents('security_audit.log', $audit_entry, FILE_APPEND | LOCK_EX);
        }
    }
}

// Document Viewer Class with Vulnerable Methods
class DocumentViewer {
    private $base_path;
    private $security;
    
    public function __construct($base_path) {
        $this->base_path = $base_path;
        $this->security = new EnterpriseSecurity();
    }
    
    public function viewDocument($document_path) {
        // Method 1: Direct file inclusion (vulnerable)
        if (isset($_GET['file'])) {
            return $this->handleFileParameter($_GET['file']);
        }
        
        // Method 2: Template system (vulnerable)
        if (isset($_POST['template'])) {
            return $this->handleTemplateSystem($_POST['template']);
        }
        
        // Method 3: Archive viewer (vulnerable)
        if (isset($_GET['archive'])) {
            return $this->handleArchiveViewer($_GET['archive']);
        }
        
        // Method 4: Language files (vulnerable)
        if (isset($_GET['lang'])) {
            return $this->handleLanguageFiles($_GET['lang']);
        }
        
        return null;
    }
    
    private function handleFileParameter($file_path) {
        // VULNERABILITY: Inadequate filtering with complex bypass possibilities
        $filtered_path = $this->basicFilter($file_path);
        
        // Security validation
        if (!$this->security->validateRequest($filtered_path)) {
            return "SECURITY VIOLATION: Request blocked by enterprise security system";
        }
        
        $full_path = $this->base_path . $filtered_path;
        
        // Check if file exists and is readable
        if (file_exists($full_path) && is_readable($full_path)) {
            ob_start();
            include($full_path);
            return ob_get_clean();
        } else {
            // Fallback: Try with different base (VULNERABILITY)
            $fallback_path = $filtered_path;
            if (file_exists($fallback_path)) {
                ob_start();
                include($fallback_path);
                return ob_get_clean();
            }
        }
        
        return "Document not found or access denied";
    }
    
    private function handleTemplateSystem($template) {
        // VULNERABILITY: Template inclusion with weak validation
        $template_path = "templates/" . $template;
        
        if (!$this->security->validateRequest($template_path)) {
            return "SECURITY VIOLATION: Template request blocked";
        }
        
        $full_path = $this->base_path . $template_path;
        
        if (file_exists($full_path)) {
            ob_start();
            include($full_path);
            return ob_get_clean();
        }
        
        return "Template not found";
    }
    
    private function handleArchiveViewer($archive_path) {
        // VULNERABILITY: Archive path handling with traversal possibilities
        $normalized_path = $this->normalizeArchivePath($archive_path);
        
        if (!$this->security->validateRequest($normalized_path)) {
            return "SECURITY VIOLATION: Archive access blocked";
        }
        
        $full_path = $this->base_path . "archives/" . $normalized_path;
        
        if (file_exists($full_path)) {
            return file_get_contents($full_path);
        }
        
        return "Archive file not found";
    }
    
    private function handleLanguageFiles($lang_file) {
        // VULNERABILITY: Language file inclusion with extension issues
        $lang_path = "lang/" . $lang_file . ".php";
        
        if (!$this->security->validateRequest($lang_path)) {
            return "SECURITY VIOLATION: Language file access blocked";
        }
        
        $full_path = $this->base_path . $lang_path;
        
        if (file_exists($full_path)) {
            ob_start();
            include($full_path);
            return ob_get_clean();
        }
        
        return "Language file not found";
    }
    
    private function basicFilter($input) {
        // Basic filtering that can be bypassed
        $filtered = $input;
        
        // Remove some dangerous patterns (but not all)
        $filtered = str_replace('../', '', $filtered);
        $filtered = str_replace('..\\', '', $filtered);
        
        // Single URL decode
        $filtered = urldecode($filtered);
        
        return $filtered;
    }
    
    private function normalizeArchivePath($path) {
        // Weak path normalization
        return preg_replace('/\.\.\//', '', $path);
    }
}

// Initialize system
$document_viewer = new DocumentViewer(__DIR__ . '/');
$content = '';
$error = '';
$active_tab = 'documents';

// Process requests
if ($_POST || $_GET) {
    $content = $document_viewer->viewDocument('');
}

// Create enterprise environment
createEnterpriseEnvironment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Document Management System v3.5.1</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f8fafc;
            --sidebar: #0f172a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #334155;
            line-height: 1.6;
        }
        
        .enterprise-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
            background: white;
            max-width: 1800px;
            margin: 20px auto;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            background: var(--sidebar);
            color: white;
            padding: 2rem 1.5rem;
            position: relative;
            overflow-y: auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .logo-text {
            font-size: 1.3rem;
            font-weight: 700;
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-role {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-links li {
            margin-bottom: 0.5rem;
        }
        
        .nav-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
        }
        
        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .nav-links i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem;
            background: var(--light);
            overflow-y: auto;
        }
        
        .header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .header h1 {
            color: var(--dark);
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .security-badge {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--secondary);
            font-size: 0.9rem;
        }
        
        /* Content Area */
        .content-area {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .content-tabs {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .content-tab {
            padding: 1rem 1.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .content-tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
            background: white;
        }
        
        .tab-content {
            padding: 2rem;
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .document-card {
            background: var(--light);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }
        
        .document-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .output-container {
            background: #1f2937;
            color: #d1d5db;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            font-family: 'Fira Code', monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        
        .alert-error {
            background: #fef2f2;
            border-color: var(--danger);
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border-color: var(--success);
            color: #059669;
        }
        
        .advanced-section {
            background: linear-gradient(135deg, #fef3c7, #f59e0b);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .payload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .payload-card {
            background: rgba(255,255,255,0.9);
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        
        .payload-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary);
        }
        
        .security-panel {
            background: #0f172a;
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="enterprise-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="logo-text">SecureCorp</div>
            </div>
            
            <div class="user-info">
                <div class="user-name"><?php echo $_SESSION['enterprise_security']['user_id']; ?></div>
                <div class="user-role"><?php echo $_SESSION['enterprise_security']['department']; ?> • Level <?php echo $_SESSION['enterprise_security']['access_level']; ?></div>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Navigation</div>
                <ul class="nav-links">
                    <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-file-alt"></i> Documents</a></li>
                    <li><a href="#"><i class="fas fa-archive"></i> Archives</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Tools</div>
                <ul class="nav-links">
                    <li><a href="#"><i class="fas fa-search"></i> Document Search</a></li>
                    <li><a href="#"><i class="fas fa-download"></i> Export Tools</a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                    <li><a href="#"><i class="fas fa-history"></i> Audit Logs</a></li>
                </ul>
            </div>
            
            <div class="security-panel">
                <h4><i class="fas fa-shield-alt"></i> Security Status</h4>
                <p>Enterprise-grade protection active</p>
                <div style="background: var(--success); padding: 0.5rem; border-radius: 5px; text-align: center; margin-top: 1rem;">
                    ALL SYSTEMS SECURE
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Enterprise Document Management</h1>
                <div class="security-badge">
                    <i class="fas fa-lock"></i> v3.5.1 • SECURE MODE
                </div>
            </div>
            
            <!-- Dashboard Stats -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-value">1,247</div>
                    <div class="stat-label">Total Documents</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">89%</div>
                    <div class="stat-label">System Uptime</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">24/7</div>
                    <div class="stat-label">Security Monitoring</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Security Incidents</div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="content-area">
                <div class="content-tabs">
                    <div class="content-tab active" onclick="switchTab('documents')">Document Viewer</div>
                    <div class="content-tab" onclick="switchTab('templates')">Template System</div>
                    <div class="content-tab" onclick="switchTab('archives')">Archive Access</div>
                    <div class="content-tab" onclick="switchTab('advanced')">Advanced Tools</div>
                </div>
                
                <!-- Documents Tab -->
                <div id="documents-tab" class="tab-content active">
                    <h2>Document Management</h2>
                    <p>Access and manage enterprise documents securely.</p>
                    
                    <div class="form-group">
                        <label class="form-label">Document Path:</label>
                        <input type="text" id="filePath" class="form-control" 
                               placeholder="Enter document path (e.g., documents/report.pdf)">
                        <button class="btn btn-primary" onclick="loadDocument()" style="margin-top: 1rem;">
                            <i class="fas fa-eye"></i> View Document
                        </button>
                    </div>
                    
                    <div class="document-grid">
                        <div class="document-card" onclick="setDocument('documents/welcome.txt')">
                            <div class="document-icon">
                                <i class="fas fa-file-text"></i>
                            </div>
                            <div>Welcome Guide</div>
                        </div>
                        <div class="document-card" onclick="setDocument('documents/policy.pdf')">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>Security Policy</div>
                        </div>
                        <div class="document-card" onclick="setDocument('config/settings.ini')">
                            <div class="document-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div>System Settings</div>
                        </div>
                        <div class="document-card" onclick="setDocument('logs/access.log')">
                            <div class="document-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>Access Logs</div>
                        </div>
                    </div>
                </div>
                
                <!-- Templates Tab -->
                <div id="templates-tab" class="tab-content">
                    <h2>Template System</h2>
                    <p>Manage document templates and layouts.</p>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Template Name:</label>
                            <input type="text" name="template" class="form-control" 
                                   placeholder="Enter template name">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-palette"></i> Load Template
                        </button>
                    </form>
                </div>
                
                <!-- Archives Tab -->
                <div id="archives-tab" class="tab-content">
                    <h2>Archive Access</h2>
                    <p>Access historical documents and backups.</p>
                    
                    <div class="form-group">
                        <label class="form-label">Archive Path:</label>
                        <input type="text" name="archive" class="form-control" 
                               placeholder="Enter archive file path"
                               onchange="loadArchive(this.value)">
                    </div>
                </div>
                
                <!-- Advanced Tab -->
                <div id="advanced-tab" class="tab-content">
                    <h2>Advanced System Tools</h2>
                    <p>Advanced features for system administrators.</p>
                    
                    <div class="advanced-section">
                        <h3><i class="fas fa-flask"></i> Security Testing Zone</h3>
                        <p>This area contains advanced system testing tools.</p>
                        
                        <div class="payload-grid">
                            <div class="payload-card" onclick="setAdvancedPayload('....//....//....//etc/passwd')">
                                <strong>Path Traversal</strong><br>
                                <small>Basic traversal attempt</small>
                            </div>
                            <div class="payload-card" onclick="setAdvancedPayload('....//....//....//etc/hosts')">
                                <strong>Hosts File</strong><br>
                                <small>System hosts file</small>
                            </div>
                            <div class="payload-card" onclick="setAdvancedPayload('....//....//....//proc/self/environ')">
                                <strong>Process Info</strong><br>
                                <small>Environment variables</small>
                            </div>
                            <div class="payload-card" onclick="setAdvancedPayload('PhP://filter/convert.base64-encode/resource=documents/flag.txt')">
                                <strong>PHP Wrapper</strong><br>
                                <small>Base64 encoding</small>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 1.5rem;">
                            <label class="form-label">Custom Payload:</label>
                            <input type="text" id="advancedPayload" class="form-control" 
                                   placeholder="Enter custom testing payload">
                            <button class="btn btn-primary" onclick="testAdvancedPayload()" style="margin-top: 1rem;">
                                <i class="fas fa-bolt"></i> Test Payload
                            </button>
                        </div>
                    </div>
                </div>
                
                <?php if ($content): ?>
                    <div class="output-container">
                        <?php echo htmlspecialchars($content); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Hidden Debug Section -->
            <div style="background: #1e293b; color: white; padding: 1rem; border-radius: 8px; margin-top: 2rem; font-size: 0.8rem;">
                <strong>System Debug:</strong> 
                User: <?php echo $_SESSION['enterprise_security']['user_id']; ?> | 
                Token: <?php echo substr($_SESSION['enterprise_security']['security_token'], 0, 8); ?>... |
                Time: <?php echo date('H:i:s'); ?>
            </div>
        </main>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.content-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
        
        function setDocument(path) {
            document.getElementById('filePath').value = path;
        }
        
        function loadDocument() {
            const path = document.getElementById('filePath').value;
            if (path) {
                window.location.href = '?file=' + encodeURIComponent(path);
            }
        }
        
        function loadArchive(path) {
            if (path) {
                window.location.href = '?archive=' + encodeURIComponent(path);
            }
        }
        
        function setAdvancedPayload(payload) {
            document.getElementById('advancedPayload').value = payload;
        }
        
        function testAdvancedPayload() {
            const payload = document.getElementById('advancedPayload').value;
            if (payload) {
                window.location.href = '?file=' + encodeURIComponent(payload);
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Enterprise Document System v3.5.1 initialized');
        });
    </script>
</body>
</html>

<?php
function createEnterpriseEnvironment() {
    // Create enterprise directory structure
    $dirs = [
        'documents',
        'templates', 
        'archives',
        'logs',
        'config',
        'lang',
        'backups'
    ];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    // Create enterprise files
    $files = [
        'documents/welcome.txt' => "Welcome to Enterprise Document Management System v3.5.1\n\nThis system provides secure document management with enterprise-grade security features.\n\nAuthorized personnel only.\n\nSystem Flags:\n- CTF{3nt3rpr1s3_LF1_Byp455_M4st3r}\n- CTF{Mul71_L4y3r_S3cur17y_F41l}\n- CTF{4dv4nc3d_F1lt3r_Byp455}",
        
        'documents/policy.pdf' => "SECURITY POLICY DOCUMENT\n\nThis is a simulated PDF document containing enterprise security policies.\n\nConfidential: For internal use only.",
        
        'config/settings.ini' => "; Enterprise System Configuration\n\n[database]\nhost = enterprise-db.securecorp.com\nuser = admin_system\npassword = E#x9m*pL3_P@ss!\nname = enterprise_edms\n\n[security]\nencryption_level = AES-256\nsession_timeout = 3600\nmax_file_size = 100MB\n\n[features]\naudit_logging = enabled\nreal_time_monitoring = true\nthreat_detection = advanced",
        
        'logs/access.log' => "2024-01-15 09:30:15 - User EMP_38421 accessed documents/welcome.txt\n2024-01-15 09:35:22 - User EMP_38421 accessed config/settings.ini\n2024-01-15 09:40:18 - SECURITY: Suspicious activity detected - EMP_29384\n2024-01-15 10:15:05 - System backup completed successfully\n2024-01-15 11:00:00 - Security audit: No critical issues found",
        
        'templates/header.php' => "<!-- Enterprise Document Header Template -->\n<header>\n    <h1>SecureCorp International</h1>\n    <p>Confidential Document</p>\n</header>",
        
        'templates/footer.php' => "<!-- Enterprise Document Footer Template -->\n<footer>\n    <p>© 2024 SecureCorp International. All rights reserved.</p>\n    <p>Document Classification: INTERNAL USE ONLY</p>\n</footer>",
        
        'archives/2023_q4.zip' => "Quarterly Archive 2023 Q4\nThis is a simulated archive file containing Q4 2023 documents.",
        
        'lang/en.php' => "<?php\n// English Language File\n\$lang = [\n    'welcome' => 'Welcome to SecureCorp',\n    'documents' => 'Documents',\n    'security' => 'Security',\n    'admin' => 'Administration'\n];\n?>",
        
        'backups/database.sql' => "-- Enterprise Database Backup\n-- Generated: 2024-01-15 10:00:00\n\nCREATE TABLE users (\n    id INT PRIMARY KEY AUTO_INCREMENT,\n    employee_id VARCHAR(20) UNIQUE,\n    username VARCHAR(50),\n    password_hash VARCHAR(255),\n    department VARCHAR(50),\n    access_level INT,\n    last_login DATETIME\n);\n\nINSERT INTO users VALUES \n(1, 'EMP_38421', 'jsmith', '\$2y\$10\$8S2N5V1pBz7Q2VcE8fKjUeR9YwX3A6cH1jL4mN7bV0qP3rT5yG8i', 'IT Security', 7, '2024-01-15 09:25:00');"
    ];
    
    foreach ($files as $file => $content) {
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
    
    // Create flag file
    if (!file_exists('flag.txt')) {
        file_put_contents('flag.txt', "CTF{3nt3rpr1s3_LF1_Byp455_M4st3r}\n\nCongratulations! You've successfully exploited the Enterprise Document Management System.\n\nThis flag represents your ability to bypass multi-layer enterprise security filters.\n\nAdditional Flags:\n- CTF{Mul71_L4y3r_S3cur17y_F41l}\n- CTF{4dv4nc3d_F1lt3r_Byp455}\n- CTF{S3cur3_C0rp_Uns3cur3}");
    }
    
    // Create a test PHP file for execution
    if (!file_exists('documents/test.php')) {
        file_put_contents('documents/test.php', '<?php echo "PHP EXECUTION: Server: " . $_SERVER[\'SERVER_SOFTWARE\'] . "\\n"; phpinfo(); ?>');
    }
}
?>