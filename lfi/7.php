<?php
/*
 * Secure Document Repository v2.1
 * Advanced LFI Lab with Multi-Layer Security
 */

session_start();

// Initialize security system
if (!isset($_SESSION['security'])) {
    $_SESSION['security'] = [
        'user_id' => 'user_' . rand(1000, 9999),
        'login_time' => time(),
        'attempts' => 0,
        'last_activity' => time()
    ];
}

// Advanced Security Filter
class SecurityFilter {
    private $threat_level = 0;
    
    public function validateInput($input) {
        $checks = [
            $this->checkTraversal($input),
            $this->checkWrappers($input),
            $this->checkEncoding($input),
            $this->checkPatterns($input)
        ];
        
        foreach ($checks as $check) {
            if (!$check['valid']) {
                $this->threat_level += $check['score'];
                return false;
            }
        }
        return true;
    }
    
    private function checkTraversal($input) {
        if (preg_match('/(\.\.\/|\.\.\\\|\.\.|%2e%2e)/i', $input)) {
            return ['valid' => false, 'score' => 25];
        }
        return ['valid' => true, 'score' => 0];
    }
    
    private function checkWrappers($input) {
        if (preg_match('/^(php|file|http|https|ftp|zlib|data):\/\//i', $input)) {
            return ['valid' => false, 'score' => 30];
        }
        return ['valid' => true, 'score' => 0];
    }
    
    private function checkEncoding($input) {
        if (preg_match('/(%25|%00|%0a|%0d)/i', $input)) {
            return ['valid' => false, 'score' => 20];
        }
        return ['valid' => true, 'score' => 0];
    }
    
    private function checkPatterns($input) {
        $patterns = ['/etc/', '/proc/', '/var/', 'flag', '.env', 'config'];
        foreach ($patterns as $pattern) {
            if (stripos($input, $pattern) !== false) {
                return ['valid' => false, 'score' => 15];
            }
        }
        return ['valid' => true, 'score' => 0];
    }
}

// Document System with Vulnerabilities
class DocumentSystem {
    private $filter;
    
    public function __construct() {
        $this->filter = new SecurityFilter();
    }
    
    public function handleRequest() {
        $output = '';
        
        // Method 1: Document viewer
        if (isset($_GET['doc'])) {
            $output = $this->viewDocument($_GET['doc']);
        }
        
        // Method 2: Template system
        if (isset($_POST['template'])) {
            $output = $this->loadTemplate($_POST['template']);
        }
        
        // Method 3: Archive access
        if (isset($_GET['archive'])) {
            $output = $this->viewArchive($_GET['archive']);
        }
        
        return $output;
    }
    
    private function viewDocument($path) {
        if (!$this->filter->validateInput($path)) {
            return "Security violation detected!";
        }
        
        $filtered = $this->basicFilter($path);
        $file_path = "docs/" . $filtered;
        
        // VULNERABILITY: Weak path check with fallback
        if (file_exists($file_path)) {
            return $this->readFile($file_path);
        } else {
            // Fallback vulnerability
            if (file_exists($filtered)) {
                return $this->readFile($filtered);
            }
        }
        
        return "Document not found.";
    }
    
    private function loadTemplate($template) {
        $template_path = "templates/" . $template;
        
        if (file_exists($template_path)) {
            ob_start();
            include($template_path);
            return ob_get_clean();
        }
        return "Template not found.";
    }
    
    private function viewArchive($archive) {
        $archive_path = "archives/" . $archive;
        
        if (file_exists($archive_path)) {
            return file_get_contents($archive_path);
        }
        return "Archive not found.";
    }
    
    private function basicFilter($input) {
        $filtered = $input;
        $filtered = str_replace('../', '', $filtered);
        $filtered = str_replace('..\\', '', $filtered);
        $filtered = urldecode($filtered);
        return $filtered;
    }
    
    private function readFile($path) {
        if (file_exists($path)) {
            $content = file_get_contents($path);
            return $content !== false ? htmlspecialchars($content) : "Unable to read file";
        }
        return "File not found";
    }
}

// Initialize system
$doc_system = new DocumentSystem();
$content = $doc_system->handleRequest();

// Create environment
createEnvironment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Document Repository</title>
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
            --success: #059669;
            --danger: #dc2626;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #334155;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: var(--dark);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        
        .security-badge {
            display: inline-block;
            background: var(--success);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 600px;
        }
        
        .sidebar {
            background: var(--light);
            padding: 2rem;
            border-right: 1px solid #e2e8f0;
        }
        
        .content {
            padding: 2rem;
        }
        
        .user-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .user-id {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-links li {
            margin-bottom: 0.5rem;
        }
        
        .nav-links a {
            display: block;
            padding: 0.75rem 1rem;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background: var(--primary);
            color: white;
        }
        
        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .card-header {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary);
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
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
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
            background: var(--secondary);
            transform: translateY(-1px);
        }
        
        .output-container {
            background: #1e293b;
            color: #cbd5e1;
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
            color: #991b1b;
        }
        
        .quick-access {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .access-card {
            background: var(--light);
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .access-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .tab-container {
            margin-top: 2rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }
        
        .tab {
            padding: 1rem 2rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            color: #64748b;
        }
        
        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .payload-section {
            background: #fffbeb;
            border: 2px solid #f59e0b;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .payload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .payload-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payload-card:hover {
            border-color: var(--primary);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Secure Document Repository</h1>
            <p>Enterprise-grade document management system</p>
            <div class="security-badge">
                🔒 Multi-Layer Security Active
            </div>
        </div>
        
        <div class="main-content">
            <aside class="sidebar">
                <div class="user-card">
                    <div class="user-id">User: <?php echo $_SESSION['security']['user_id']; ?></div>
                    <div>Security Level: Standard</div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-title">Navigation</div>
                    <ul class="nav-links">
                        <li><a href="#" onclick="showTab('documents')">📄 Document Viewer</a></li>
                        <li><a href="#" onclick="showTab('templates')">🎨 Template System</a></li>
                        <li><a href="#" onclick="showTab('archives')">📦 Archive Access</a></li>
                        <li><a href="#" onclick="showTab('security')">🔍 Security Testing</a></li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <div class="nav-title">Quick Files</div>
                    <ul class="nav-links">
                        <li><a href="#" onclick="loadFile('docs/welcome.txt')">Welcome Guide</a></li>
                        <li><a href="#" onclick="loadFile('docs/config.ini')">Configuration</a></li>
                        <li><a href="#" onclick="loadFile('logs/access.log')">Access Logs</a></li>
                        <li><a href="#" onclick="loadFile('docs/readme.txt')">Readme</a></li>
                    </ul>
                </div>
            </aside>
            
            <main class="content">
                <!-- Documents Tab -->
                <div id="documents-tab" class="tab-content active">
                    <div class="card">
                        <div class="card-header">Document Viewer</div>
                        <p>Access documents from the secure repository</p>
                        
                        <div class="form-group">
                            <label class="form-label">Document Path:</label>
                            <input type="text" id="docPath" class="form-control" 
                                   placeholder="Enter document path (e.g., docs/welcome.txt)">
                        </div>
                        <button class="btn btn-primary" onclick="loadDocument()">
                            View Document
                        </button>
                        
                        <div class="quick-access">
                            <div class="access-card" onclick="setDocument('docs/welcome.txt')">
                                📄 Welcome Guide
                            </div>
                            <div class="access-card" onclick="setDocument('docs/config.ini')">
                                ⚙️ Configuration
                            </div>
                            <div class="access-card" onclick="setDocument('logs/access.log')">
                                📊 Access Logs
                            </div>
                            <div class="access-card" onclick="setDocument('docs/readme.txt')">
                                📖 Readme File
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Templates Tab -->
                <div id="templates-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">Template System</div>
                        <p>Load and preview document templates</p>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">Template Name:</label>
                                <input type="text" name="template" class="form-control" 
                                       placeholder="Enter template filename">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Load Template
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Archives Tab -->
                <div id="archives-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">Archive Access</div>
                        <p>Access archived documents and backups</p>
                        
                        <div class="form-group">
                            <label class="form-label">Archive Path:</label>
                            <input type="text" name="archive" class="form-control" 
                                   placeholder="Enter archive file path"
                                   onchange="loadArchive(this.value)">
                        </div>
                    </div>
                </div>
                
                <!-- Security Tab -->
                <div id="security-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">Security Testing</div>
                        <p>Advanced security testing environment</p>
                        
                        <div class="form-group">
                            <label class="form-label">Test Payload:</label>
                            <input type="text" id="testPayload" class="form-control" 
                                   placeholder="Enter security test payload">
                        </div>
                        <button class="btn btn-primary" onclick="testPayload()">
                            Execute Test
                        </button>
                        
                        <div class="payload-section">
                            <h4>Sample Payloads:</h4>
                            <div class="payload-grid">
                                <div class="payload-card" onclick="setPayload('....//....//....//etc/passwd')">
                                    <strong>Path Traversal</strong><br>
                                    <small>Basic traversal attempt</small>
                                </div>
                                <div class="payload-card" onclick="setPayload('....//....//....//etc/hosts')">
                                    <strong>Hosts File</strong><br>
                                    <small>System hosts file</small>
                                </div>
                                <div class="payload-card" onclick="setPayload('PhP://filter/convert.base64-encode/resource=docs/flag.txt')">
                                    <strong>PHP Wrapper</strong><br>
                                    <small>Base64 encoding</small>
                                </div>
                                <div class="payload-card" onclick="setPayload('....//....//....//proc/self/environ')">
                                    <strong>Process Info</strong><br>
                                    <small>Environment variables</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($content): ?>
                    <div class="output-container">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabName + '-tab').classList.add('active');
        }
        
        function setDocument(path) {
            document.getElementById('docPath').value = path;
        }
        
        function loadDocument() {
            const path = document.getElementById('docPath').value;
            if (path) {
                window.location.href = '?doc=' + encodeURIComponent(path);
            }
        }
        
        function loadFile(path) {
            window.location.href = '?doc=' + encodeURIComponent(path);
        }
        
        function loadArchive(path) {
            if (path) {
                window.location.href = '?archive=' + encodeURIComponent(path);
            }
        }
        
        function setPayload(payload) {
            document.getElementById('testPayload').value = payload;
        }
        
        function testPayload() {
            const payload = document.getElementById('testPayload').value;
            if (payload) {
                window.location.href = '?doc=' + encodeURIComponent(payload);
            }
        }
        
        // Initialize first tab
        document.addEventListener('DOMContentLoaded', function() {
            showTab('documents');
        });
    </script>
</body>
</html>

<?php
function createEnvironment() {
    $dirs = ['docs', 'templates', 'archives', 'logs'];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }
    
    $files = [
        'docs/welcome.txt' => "Welcome to Secure Document Repository\n\nThis system provides secure document management with advanced security features.\n\nFlags:\n- CTF{4dv4nc3d_LF1_Byp455}\n- CTF{S3cur3_R3p0_Uns3cur3}",
        
        'docs/config.ini' => "; System Configuration\n\ndatabase_host=localhost\ndatabase_user=admin\ndatabase_pass=secret123\napp_name=Secure Repository",
        
        'logs/access.log' => "2024-01-15 10:30:15 - User accessed welcome.txt\n2024-01-15 10:35:22 - User accessed config.ini\n2024-01-15 10:40:18 - Security: Blocked suspicious request",
        
        'docs/readme.txt' => "README\n\nThis is a secure document repository system.\nAll access is logged and monitored.",
        
        'templates/header.php' => "<!-- Document Header Template -->",
        
        'archives/backup.zip' => "System Backup Archive",
        
        'flag.txt' => "CTF{4dv4nc3d_LF1_Byp455}\n\nCongratulations! You've successfully exploited the document repository.\n\nAdditional flag: CTF{S3cur3_R3p0_Uns3cur3}"
    ];
    
    foreach ($files as $file => $content) {
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
}
?>