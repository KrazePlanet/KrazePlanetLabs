<?php
// Secure Document Portal - Advanced LFI with Complex Filters
session_start();

// Security initialization
if (!isset($_SESSION['security'])) {
    $_SESSION['security'] = [
        'attempts' => 0,
        'last_attempt' => 0,
        'blocked_until' => 0,
        'fingerprint' => md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])
    ];
}

// Advanced multi-layer filtering system
class AdvancedFilter {
    private $filters = [];
    
    public function __construct() {
        $this->initializeFilters();
    }
    
    private function initializeFilters() {
        // Layer 1: Basic pattern matching
        $this->filters[] = function($input) {
            // Block common patterns
            $patterns = [
                '/\.\.\//', '/\.\.\\\\/', '/\/etc\//', '/\/proc\//', 
                '/\/var\//', '/\/root\//', '/flag/', '/\.env/',
                '/config\./', '/database\./', '/passwd/', '/shadow/'
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $input)) {
                    return ['blocked' => true, 'reason' => 'Pattern match: ' . $pattern];
                }
            }
            return ['blocked' => false, 'output' => $input];
        };
        
        // Layer 2: Encoding detection and normalization
        $this->filters[] = function($input) {
            // Detect and handle multiple encoding types
            $decoded = $input;
            
            // Apply URL decoding up to 3 times (but not recursively)
            for ($i = 0; $i < 3; $i++) {
                $temp = urldecode($decoded);
                if ($temp === $decoded) break;
                $decoded = $temp;
            }
            
            // Check for base64 encoding
            if (preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $decoded) && strlen($decoded) % 4 == 0) {
                $base64_decoded = base64_decode($decoded, true);
                if ($base64_decoded !== false) {
                    $decoded = $base64_decoded;
                }
            }
            
            return ['blocked' => false, 'output' => $decoded];
        };
        
        // Layer 3: Path traversal protection with advanced checks
        $this->filters[] = function($input) {
            $path = $input;
            
            // Remove null bytes
            $path = str_replace(chr(0), '', $path);
            
            // Normalize path separators
            $path = str_replace('\\', '/', $path);
            
            // Resolve path traversal
            $parts = explode('/', $path);
            $result = [];
            foreach ($parts as $part) {
                if ($part === '..') {
                    if (!empty($result)) array_pop($result);
                } elseif ($part !== '' && $part !== '.') {
                    $result[] = $part;
                }
            }
            
            $normalized = implode('/', $result);
            
            // Check if still contains traversal
            if (strpos($normalized, '..') !== false) {
                return ['blocked' => true, 'reason' => 'Path traversal detected after normalization'];
            }
            
            return ['blocked' => false, 'output' => $normalized];
        };
        
        // Layer 4: Wrapper and protocol filtering
        $this->filters[] = function($input) {
            $wrappers = [
                'php', 'file', 'http', 'https', 'ftp', 'zlib', 
                'data', 'phar', 'glob', 'expect'
            ];
            
            foreach ($wrappers as $wrapper) {
                // Case-insensitive wrapper detection
                if (preg_match("/^$wrapper:\/\//i", $input)) {
                    return ['blocked' => true, 'reason' => "Wrapper detected: $wrapper"];
                }
            }
            
            return ['blocked' => false, 'output' => $input];
        };
        
        // Layer 5: File extension and type validation
        $this->filters[] = function($input) {
            $allowed_extensions = ['txt', 'log', 'xml', 'json', 'ini', 'conf'];
            $path_parts = pathinfo($input);
            
            if (isset($path_parts['extension'])) {
                $ext = strtolower($path_parts['extension']);
                if (!in_array($ext, $allowed_extensions)) {
                    // Check if it's trying to access PHP files
                    if (in_array($ext, ['php', 'phtml', 'php3', 'php4', 'php5', 'php7'])) {
                        return ['blocked' => true, 'reason' => 'PHP file access denied'];
                    }
                    // Replace with .txt for other extensions
                    $input = $path_parts['filename'] . '.txt';
                }
            }
            
            return ['blocked' => false, 'output' => $input];
        };
    }
    
    public function applyFilters($input) {
        $current = $input;
        $log = [];
        
        foreach ($this->filters as $index => $filter) {
            $result = $filter($current);
            $log[] = "Layer " . ($index + 1) . ": " . 
                    ($result['blocked'] ? 'BLOCKED - ' . $result['reason'] : 'PASSED');
            
            if ($result['blocked']) {
                return ['blocked' => true, 'reason' => $result['reason'], 'log' => $log];
            }
            $current = $result['output'];
        }
        
        return ['blocked' => false, 'output' => $current, 'log' => $log];
    }
}

// Security monitoring
function securityCheck() {
    $security = &$_SESSION['security'];
    
    // Check if blocked
    if ($security['blocked_until'] > time()) {
        return ['blocked' => true, 'time_remaining' => $security['blocked_until'] - time()];
    }
    
    // Check fingerprint
    $current_fingerprint = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    if ($security['fingerprint'] !== $current_fingerprint) {
        $security['blocked_until'] = time() + 3600; // Block for 1 hour
        return ['blocked' => true, 'reason' => 'Security fingerprint mismatch'];
    }
    
    // Rate limiting
    $time_since_last = time() - $security['last_attempt'];
    if ($time_since_last < 2) { // 2 seconds between requests
        $security['attempts']++;
        if ($security['attempts'] > 10) {
            $security['blocked_until'] = time() + 600; // Block for 10 minutes
            return ['blocked' => true, 'reason' => 'Rate limit exceeded'];
        }
    } else {
        $security['attempts'] = max(0, $security['attempts'] - 1);
    }
    
    $security['last_attempt'] = time();
    return ['blocked' => false];
}

// Main application logic
$filter = new AdvancedFilter();
$output = '';
$error = '';
$success = '';
$debug_info = [];

// Check security
$security_result = securityCheck();
if ($security_result['blocked']) {
    $error = "Security violation: " . 
             ($security_result['reason'] ?? 'Temporarily blocked. Time remaining: ' . 
             ($security_result['time_remaining'] ?? 'Unknown'));
} else {
    // Process file requests
    if (isset($_GET['document'])) {
        $requested_file = $_GET['document'];
        $debug_info[] = "Original input: " . htmlspecialchars($requested_file);
        
        // Apply advanced filters
        $filter_result = $filter->applyFilters($requested_file);
        $debug_info = array_merge($debug_info, $filter_result['log'] ?? []);
        
        if ($filter_result['blocked']) {
            $error = "Security filter blocked request: " . $filter_result['reason'];
            $_SESSION['security']['attempts']++;
        } else {
            $filtered_file = $filter_result['output'];
            $debug_info[] = "Filtered output: " . htmlspecialchars($filtered_file);
            
            // Construct final path
            $base_dir = realpath('files');
            $file_path = $base_dir . DIRECTORY_SEPARATOR . $filtered_file;
            
            // Security: Ensure the file is within the base directory
            if (strpos(realpath($file_path), $base_dir) !== 0) {
                $error = "Access denied: Path traversal attempt detected";
                $_SESSION['security']['attempts']++;
            } elseif (file_exists($file_path) && is_file($file_path)) {
                // Read file content
                $content = file_get_contents($file_path);
                if ($content !== false) {
                    $output = htmlspecialchars($content);
                    $success = "File loaded successfully";
                    $_SESSION['security']['attempts'] = max(0, $_SESSION['security']['attempts'] - 2);
                } else {
                    $error = "Unable to read file";
                }
            } else {
                $error = "File not found: " . htmlspecialchars($filtered_file);
            }
        }
    }
    
    // Handle API requests (hidden endpoint)
    if (isset($_POST['api']) && $_POST['api'] === 'getResource') {
        $resource = $_POST['resource'] ?? '';
        if (!empty($resource)) {
            // Different filtering for API
            $api_result = $filter->applyFilters($resource);
            if (!$api_result['blocked']) {
                $api_file = 'files/' . $api_result['output'];
                if (file_exists($api_file)) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'success',
                        'content' => base64_encode(file_get_contents($api_file))
                    ]);
                    exit;
                }
            }
        }
    }
}

// Create environment
createSecureEnvironment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Document Portal v5.0</title>
    <style>
        :root {
            --primary: #1a365d;
            --secondary: #2d3748;
            --accent: #3182ce;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
            --dark: #2d3748;
            --light: #f7fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #2d3748;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        
        .header {
            background: var(--primary);
            color: white;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%);
        }
        
        .security-shield {
            position: absolute;
            top: 30px;
            right: 30px;
            background: var(--success);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 320px 1fr;
            min-height: 800px;
        }
        
        .sidebar {
            background: var(--light);
            padding: 2rem;
            border-right: 1px solid #e2e8f0;
        }
        
        .content {
            padding: 2.5rem;
            background: white;
        }
        
        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .card-header {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--accent);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1.1rem;
            transition: border 0.3s, box-shadow 0.3s;
            background: var(--light);
        }
        
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
            outline: none;
        }
        
        .btn {
            padding: 1.25rem 2.5rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #2c5aa0);
            color: white;
            box-shadow: 0 5px 15px rgba(49, 130, 206, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(49, 130, 206, 0.6);
        }
        
        .alert {
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border-left: 5px solid;
            font-weight: 500;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fed7d7, #feb2b2);
            border-color: var(--danger);
            color: #742a2a;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
            border-color: var(--success);
            color: #22543d;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #feebc8, #fbd38d);
            border-color: var(--warning);
            color: #744210;
        }
        
        .output-container {
            background: #1a202c;
            color: #cbd5e0;
            border-radius: 10px;
            padding: 2rem;
            margin-top: 1.5rem;
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
            border: 2px solid #2d3748;
        }
        
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .file-item {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-item:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .debug-panel {
            background: #2d3748;
            color: #cbd5e0;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
        }
        
        .debug-title {
            color: var(--accent);
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .debug-entry {
            padding: 0.5rem;
            border-bottom: 1px solid #4a5568;
        }
        
        .hidden-section {
            background: linear-gradient(135deg, #fff5f5, #fed7d7);
            border: 2px dashed var(--danger);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .api-form {
            background: rgba(255,255,255,0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1rem;
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
            color: #718096;
            transition: all 0.3s;
        }
        
        .tab.active {
            border-bottom-color: var(--accent);
            color: var(--accent);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .hint-box {
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
            border: 2px solid #38b2ac;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .payload-item {
            background: #edf2f7;
            border: 1px solid #cbd5e0;
            border-radius: 5px;
            padding: 1rem;
            margin: 0.5rem 0;
            font-family: monospace;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .payload-item:hover {
            background: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Secure Document Portal</h1>
            <p style="font-size: 1.2rem; opacity: 0.9;">Enterprise-grade file security with advanced threat protection</p>
            <div class="security-shield">
                🔒 5-Layer Security Active
            </div>
        </div>
        
        <div class="main-content">
            <aside class="sidebar">
                <div class="card">
                    <div class="card-header">Security Status</div>
                    <div class="form-group">
                        <div class="form-label">Attempts:</div>
                        <div style="background: #edf2f7; padding: 1rem; border-radius: 8px; text-align: center; font-weight: bold;">
                            <?php echo $_SESSION['security']['attempts']; ?> / 10
                        </div>
                    </div>
                    
                    <?php if ($security_result['blocked'] ?? false): ?>
                        <div class="alert alert-warning">
                            ⚠️ Security Block Active
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            ✅ Security Status: Normal
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <div class="form-label">Quick Access:</div>
                        <div class="file-grid">
                            <div class="file-item" onclick="loadDocument('documents/welcome.txt')">
                                📄 Welcome
                            </div>
                            <div class="file-item" onclick="loadDocument('logs/system.log')">
                                📊 System Logs
                            </div>
                            <div class="file-item" onclick="loadDocument('config/app.ini')">
                                ⚙️ Configuration
                            </div>
                            <div class="file-item" onclick="loadDocument('data/users.xml')">
                                👥 User Data
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">Advanced Tools</div>
                    <div class="api-form">
                        <div class="form-label">API Access (Debug):</div>
                        <input type="text" id="apiResource" class="form-control" placeholder="resource path" style="margin-bottom: 1rem;">
                        <button class="btn btn-primary" onclick="callAPI()" style="width: 100%;">
                            Fetch via API
                        </button>
                    </div>
                </div>
            </aside>
            
            <main class="content">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        ❌ <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        ✅ <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">Document Viewer</div>
                    <form method="GET" id="mainForm">
                        <div class="form-group">
                            <label class="form-label">Document Path:</label>
                            <input type="text" name="document" class="form-control" 
                                   placeholder="Enter document path (e.g., documents/welcome.txt)"
                                   value="<?php echo isset($_GET['document']) ? htmlspecialchars($_GET['document']) : ''; ?>"
                                   <?php echo ($security_result['blocked'] ?? false) ? 'disabled' : ''; ?>>
                        </div>
                        <button type="submit" class="btn btn-primary" <?php echo ($security_result['blocked'] ?? false) ? 'disabled' : ''; ?>>
                            Load Document
                        </button>
                    </form>
                    
                    <?php if ($output): ?>
                        <div class="output-container">
                            <?php echo $output; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($debug_info)): ?>
                    <div class="debug-panel">
                        <div class="debug-title">Security Filter Debug:</div>
                        <?php foreach ($debug_info as $entry): ?>
                            <div class="debug-entry"><?php echo htmlspecialchars($entry); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="hidden-section">
                    <h3>🔍 Advanced Testing Zone</h3>
                    <p>This section contains experimental features for security testing.</p>
                    
                    <div class="tab-container">
                        <div class="tabs">
                            <div class="tab active" onclick="showTab('payloads')">Payloads</div>
                            <div class="tab" onclick="showTab('encoding')">Encoding</div>
                            <div class="tab" onclick="showTab('wrappers')">Wrappers</div>
                        </div>
                        
                        <div id="payloads-tab" class="tab-content active">
                            <div class="hint-box">
                                <strong>Hint:</strong> The system applies multiple filter layers but has weaknesses in encoding handling and path normalization.
                            </div>
                            <div class="payload-item" onclick="setPayload(this.textContent)">....//....//....//etc/passwd</div>
                            <div class="payload-item" onclick="setPayload(this.textContent)">....//....//....//etc/hosts</div>
                            <div class="payload-item" onclick="setPayload(this.textContent)">files/../files/../files/../etc/passwd</div>
                        </div>
                        
                        <div id="encoding-tab" class="tab-content">
                            <div class="payload-item" onclick="setPayload(this.textContent)">%2e%2e%2f%2e%2e%2f%2e%2e%2f%2e%2e%2fetc%2fpasswd</div>
                            <div class="payload-item" onclick="setPayload(this.textContent)">..%252f..%252f..%252f..%252fetc%252fpasswd</div>
                            <div class="payload-item" onclick="setPayload(this.textContent)">....%2f%2e%2e%2f....%2f%2e%2e%2fetc%2fpasswd</div>
                        </div>
                        
                        <div id="wrappers-tab" class="tab-content">
                            <div class="payload-item" onclick="setPayload(this.textContent)">PhP://filter/convert.base64-encode/resource=files/flag.txt</div>
                            <div class="payload-item" onclick="setPayload(this.textContent)">PHP://filter/read=string.rot13/resource=files/flag.txt</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function loadDocument(path) {
            document.querySelector('input[name="document"]').value = path;
            document.getElementById('mainForm').submit();
        }
        
        function setPayload(payload) {
            document.querySelector('input[name="document"]').value = payload;
        }
        
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
        
        function callAPI() {
            const resource = document.getElementById('apiResource').value;
            if (!resource) return;
            
            const formData = new FormData();
            formData.append('api', 'getResource');
            formData.append('resource', resource);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const content = atob(data.content);
                    alert('API Response:\n\n' + content);
                } else {
                    alert('API Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('API Call failed: ' + error);
            });
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            showTab('payloads');
        });
    </script>
</body>
</html>

<?php
function createSecureEnvironment() {
    // Create directory structure
    $dirs = [
        'files',
        'files/documents', 
        'files/logs',
        'files/config',
        'files/data',
        'files/backups'
    ];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    // Create sample files
    $files = [
        'files/documents/welcome.txt' => "Welcome to Secure Document Portal v5.0\n\nThis system features advanced security measures including:\n- Multi-layer filtering\n- Rate limiting\n- Path traversal protection\n- Wrapper blocking\n\nAuthorized access only.",
        
        'files/logs/system.log' => "2024-01-15 10:30:15 - SYSTEM START\n2024-01-15 10:31:22 - User admin logged in\n2024-01-15 10:35:18 - Security: Blocked suspicious request\n2024-01-15 10:40:05 - Backup completed successfully\n2024-01-15 11:00:00 - Security scan: No threats detected",
        
        'files/config/app.ini' => "; Application Configuration\n\n[database]\nhost = localhost\nuser = secure_app\npassword = j8#kL!p2@mN9$\nname = secure_portal\n\n[security]\nfilter_level = maximum\nlogging = enabled\nblock_duration = 600",
        
        'files/data/users.xml' => "<?xml version=\"1.0\"?>\n<users>\n    <user>\n        <id>1</id>\n        <name>System Administrator</name>\n        <role>admin</role>\n        <email>admin@secureportal.com</email>\n    </user>\n    <user>\n        <id>2</id>\n        <name>Security Auditor</name>\n        <role>auditor</role>\n        <email>audit@secureportal.com</email>\n    </user>\n</users>",
        
        'files/flag.txt' => "CTF{Mul71_L4y3r_F1lt3r5_Byp4553d}\n\nCongratulations! You've successfully bypassed:\n- 5-layer security filtering\n- Advanced path normalization\n- Wrapper detection\n- Encoding filters\n- Extension validation\n\nThis demonstrates advanced LFI exploitation techniques.",
        
        'files/backups/database.sql' => "-- Database Backup File\n-- Contains sensitive information\n\nCREATE TABLE users (\n    id INT PRIMARY KEY,\n    username VARCHAR(50),\n    password_hash VARCHAR(255),\n    email VARCHAR(100)\n);\n\nINSERT INTO users VALUES (1, 'admin', '\$2y\$10\$8S2N5V1pBz7Q2VcE8fKjUeR9YwX3A6cH1jL4mN7bV0qP3rT5yG8i', 'admin@system.com');"
    ];
    
    foreach ($files as $file => $content) {
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
    
    // Create a test PHP file
    if (!file_exists('files/test.php')) {
        file_put_contents('files/test.php', '<?php echo "PHP Execution: " . __FILE__ . "\\n"; phpinfo(); ?>');
    }
}
?>