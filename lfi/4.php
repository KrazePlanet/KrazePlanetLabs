<?php
// Secure File Viewer Pro - Advanced LFI Lab with Multiple Filters
session_start();

// Initialize security tracking
if (!isset($_SESSION['security_log'])) {
    $_SESSION['security_log'] = [];
    $_SESSION['attempts'] = 0;
    $_SESSION['blocked_until'] = 0;
}

// Security functions
function logSecurityEvent($event) {
    $_SESSION['security_log'][] = date('Y-m-d H:i:s') . " - " . $event;
    if (count($_SESSION['security_log']) > 50) {
        array_shift($_SESSION['security_log']);
    }
}

function isBlocked() {
    if ($_SESSION['blocked_until'] > time()) {
        return true;
    }
    return false;
}

function incrementAttempts() {
    $_SESSION['attempts']++;
    if ($_SESSION['attempts'] >= 5) {
        $_SESSION['blocked_until'] = time() + 300; // Block for 5 minutes
        logSecurityEvent("BLOCKED: Too many failed attempts");
    }
}

function resetAttempts() {
    $_SESSION['attempts'] = 0;
    $_SESSION['blocked_until'] = 0;
}

// Advanced filtering functions
function advancedFilter($input) {
    // Multiple filter layers
    $filtered = $input;
    
    // Layer 1: Basic blacklist
    $blacklist = ['../', '..\\', '/etc/passwd', '/etc/shadow', '/proc/', '/var/log/', 'flag', '.env', 'config'];
    foreach ($blacklist as $pattern) {
        $filtered = str_ireplace($pattern, '[BLOCKED]', $filtered);
    }
    
    // Layer 2: Remove null bytes
    $filtered = str_replace(chr(0), '', $filtered);
    
    // Layer 3: URL decode only once
    $filtered = urldecode($filtered);
    
    // Layer 4: Remove multiple dots
    $filtered = preg_replace('/\.\.+/', '.', $filtered);
    
    // Layer 5: Restrict to certain extensions
    if (!preg_match('/\.(txt|log|ini|conf|xml|json)$/i', $filtered)) {
        $filtered = preg_replace('/\.(php|html|htm|phtml)$/i', '.txt', $filtered);
    }
    
    return $filtered;
}

function wrapperFilter($input) {
    // Filter PHP wrappers
    $wrappers = ['php://', 'file://', 'http://', 'https://', 'ftp://', 'zlib://', 'data://'];
    foreach ($wrappers as $wrapper) {
        if (stripos($input, $wrapper) !== false) {
            return false;
        }
    }
    return $input;
}

// Main file inclusion logic
$output = '';
$error = '';
$file_path = '';

if (isset($_GET['view']) && !isBlocked()) {
    $requested_file = $_GET['view'];
    
    // Log the attempt
    logSecurityEvent("File request: " . $requested_file);
    
    // Apply advanced filtering
    $filtered_file = advancedFilter($requested_file);
    
    // Check if filtering changed the input significantly
    if ($filtered_file !== $requested_file) {
        logSecurityEvent("FILTERED: $requested_file -> $filtered_file");
    }
    
    // Apply wrapper filter
    $wrapper_check = wrapperFilter($filtered_file);
    if ($wrapper_check === false) {
        $error = "Security Violation: Dangerous wrappers detected";
        incrementAttempts();
        logSecurityEvent("BLOCKED: Wrapper detected - $requested_file");
    } else {
        $file_path = "documents/" . $filtered_file;
        
        // Additional security: check if file exists and is within allowed directory
        $real_base = realpath('documents');
        $real_path = realpath($file_path);
        
        if ($real_path === false || strpos($real_path, $real_base) !== 0) {
            $error = "File not found or access denied";
            incrementAttempts();
        } else {
            // Check file size before including
            if (file_exists($file_path) && filesize($file_path) < 100000) {
                ob_start();
                $included = @include($file_path);
                $output = ob_get_clean();
                
                if (!$included && empty($output)) {
                    // Try reading as text
                    $content = @file_get_contents($file_path);
                    if ($content !== false) {
                        $output = htmlspecialchars($content);
                        resetAttempts();
                    } else {
                        $error = "Unable to read file";
                        incrementAttempts();
                    }
                } else {
                    resetAttempts();
                }
            } else {
                $error = "File too large or not found";
                incrementAttempts();
            }
        }
    }
}

// Create sample environment
createAdvancedEnvironment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure File Viewer Pro</title>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --dark: #34495e;
            --light: #ecf0f1;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .security-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--success);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 350px 1fr;
            min-height: 700px;
        }
        
        .sidebar {
            background: var(--light);
            padding: 2rem;
            border-right: 1px solid #bdc3c7;
        }
        
        .content {
            padding: 2rem;
            background: white;
        }
        
        .panel {
            background: white;
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .panel-header {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary);
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
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
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
            background: var(--secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .alert {
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 5px solid;
        }
        
        .alert-error {
            background: #fed7d7;
            border-color: var(--danger);
            color: #c53030;
        }
        
        .alert-warning {
            background: #feebc8;
            border-color: var(--warning);
            color: #dd6b20;
        }
        
        .alert-success {
            background: #c6f6d5;
            border-color: var(--success);
            color: #276749;
        }
        
        .output-container {
            background: #1a202c;
            color: #cbd5e0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #4a5568;
        }
        
        .file-list {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .file-item {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            font-family: monospace;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .file-item:hover {
            background: #edf2f7;
        }
        
        .file-item:last-child {
            border-bottom: none;
        }
        
        .security-panel {
            background: #fff5f5;
            border: 2px solid #fed7d7;
        }
        
        .security-log {
            background: #1a202c;
            color: #cbd5e0;
            border-radius: 5px;
            padding: 1rem;
            max-height: 200px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.8rem;
        }
        
        .log-entry {
            margin-bottom: 0.25rem;
            padding: 0.25rem;
            border-radius: 3px;
        }
        
        .log-entry.blocked {
            background: #fed7d7;
            color: #c53030;
        }
        
        .log-entry.filtered {
            background: #feebc8;
            color: #dd6b20;
        }
        
        .challenge-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .tab-container {
            margin-top: 2rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1rem;
        }
        
        .tab {
            padding: 1rem 2rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: #718096;
        }
        
        .tab.active {
            border-bottom-color: var(--secondary);
            color: var(--secondary);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .hint {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            border-radius: 5px;
            padding: 1rem;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Secure File Viewer Pro v4.2</h1>
            <p>Enterprise-grade file viewing with advanced security</p>
            <div class="security-badge">🔒 Advanced Filtering Enabled</div>
        </div>
        
        <div class="main-content">
            <aside class="sidebar">
                <div class="panel">
                    <div class="panel-header">Security Status</div>
                    <div class="form-group">
                        <div class="form-label">Failed Attempts:</div>
                        <div style="background: #edf2f7; padding: 0.5rem; border-radius: 5px;">
                            <?php echo $_SESSION['attempts']; ?> / 5
                        </div>
                    </div>
                    
                    <?php if (isBlocked()): ?>
                        <div class="alert alert-warning">
                            ⚠️ Blocked for security reasons. Try again in 
                            <?php echo ($_SESSION['blocked_until'] - time()); ?> seconds.
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <div class="form-label">Recent Security Events:</div>
                        <div class="security-log">
                            <?php
                            $recent_logs = array_slice($_SESSION['security_log'], -10);
                            foreach ($recent_logs as $log_entry) {
                                $class = '';
                                if (strpos($log_entry, 'BLOCKED') !== false) $class = 'blocked';
                                if (strpos($log_entry, 'FILTERED') !== false) $class = 'filtered';
                                echo "<div class='log-entry $class'>$log_entry</div>";
                            }
                            ?>
                        </div>
                    </div>
                    
                    <button class="btn btn-danger" onclick="resetSecurity()" style="width: 100%;">
                        Reset Security Log
                    </button>
                </div>
                
                <div class="panel">
                    <div class="panel-header">Available Files</div>
                    <div class="file-list">
                        <?php
                        $files = [
                            'readme.txt' => 'System Documentation',
                            'config.ini' => 'Configuration File',
                            'logs/access.log' => 'Access Logs',
                            'logs/error.log' => 'Error Logs',
                            'data/users.xml' => 'User Data',
                            'data/settings.json' => 'Application Settings'
                        ];
                        
                        foreach ($files as $path => $description) {
                            echo "<div class='file-item' onclick=\"loadFile('$path')\">";
                            echo "📄 $description<br><small style='color: #718096;'>$path</small>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </aside>
            
            <main class="content">
                <?php if (isBlocked()): ?>
                    <div class="alert alert-error">
                        <h3>🚫 Access Temporarily Blocked</h3>
                        <p>Too many failed security attempts. Please wait <?php echo ($_SESSION['blocked_until'] - time()); ?> seconds.</p>
                    </div>
                <?php endif; ?>
                
                <div class="panel">
                    <div class="panel-header">File Viewer</div>
                    <p>Enter the path to the file you wish to view. Only authorized documents are accessible.</p>
                    
                    <form method="GET" id="fileForm">
                        <div class="form-group">
                            <label class="form-label">File Path:</label>
                            <input type="text" name="view" class="form-control" 
                                   placeholder="e.g., documents/readme.txt" 
                                   value="<?php echo isset($_GET['view']) ? htmlspecialchars($_GET['view']) : ''; ?>"
                                   <?php echo isBlocked() ? 'disabled' : ''; ?>>
                        </div>
                        <button type="submit" class="btn btn-primary" <?php echo isBlocked() ? 'disabled' : ''; ?>>
                            View File
                        </button>
                    </form>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            ❌ <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($file_path): ?>
                        <div class="alert alert-success">
                            ✅ Loaded: <?php echo htmlspecialchars($file_path); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($output): ?>
                        <div class="output-container">
                            <?php echo $output; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="challenge-section">
                    <h3>🔍 Advanced LFI Challenge</h3>
                    <p>This system implements multiple security layers. Can you bypass them?</p>
                    
                    <div class="hint">
                        <strong>Hint:</strong> The system uses multiple filter layers but has weaknesses in its implementation.
                        Try different encoding techniques and path normalization tricks.
                    </div>
                    
                    <div class="tab-container">
                        <div class="tabs">
                            <div class="tab active" onclick="showChallengeTab('filters')">Filter Details</div>
                            <div class="tab" onclick="showChallengeTab('payloads')">Payload Ideas</div>
                        </div>
                        
                        <div id="filters-tab" class="tab-content active">
                            <h4>Security Filters in Place:</h4>
                            <ul style="margin-left: 1.5rem; margin-top: 1rem;">
                                <li>Blacklist filtering for common LFI patterns</li>
                                <li>Null byte removal</li>
                                <li>Single URL decoding</li>
                                <li>Multiple dot reduction</li>
                                <li>File extension restrictions</li>
                                <li>PHP wrapper blocking</li>
                                <li>Path traversal checks</li>
                                <li>Attempt limiting with temporary blocks</li>
                            </ul>
                        </div>
                        
                        <div id="payloads-tab" class="tab-content">
                            <h4>Potential Bypass Techniques:</h4>
                            <div class="file-list" style="background: rgba(255,255,255,0.1); color: white;">
                                <div class="file-item" style="color: white; border-color: rgba(255,255,255,0.2);">
                                    Double URL encoding<br>
                                    <small>%252e%252e%252f for ../</small>
                                </div>
                                <div class="file-item" style="color: white; border-color: rgba(255,255,255,0.2);">
                                    Mixed case wrappers<br>
                                    <small>PHP:// or PhP://</small>
                                </div>
                                <div class="file-item" style="color: white; border-color: rgba(255,255,255,0.2);">
                                    Path normalization tricks<br>
                                    <small>././etc/passwd or ..././..././etc/passwd</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function loadFile(path) {
            if (!<?php echo isBlocked() ? 'true' : 'false'; ?>) {
                document.querySelector('input[name="view"]').value = path;
                document.getElementById('fileForm').submit();
            }
        }
        
        function showChallengeTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
        
        function resetSecurity() {
            if (confirm('Reset security log and attempts?')) {
                window.location.href = '?reset=1';
            }
        }
        
        // Handle reset parameter
        <?php
        if (isset($_GET['reset'])) {
            echo "session_destroy();";
            echo "window.location.href = window.location.pathname;";
        }
        ?>
    </script>
</body>
</html>

<?php
function createAdvancedEnvironment() {
    // Create directory structure
    $dirs = ['documents', 'documents/logs', 'documents/data', 'documents/backups'];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }
    
    // Create sample files
    $files = [
        'documents/readme.txt' => "Secure File Viewer Pro v4.2\n\nThis system provides secure file viewing capabilities with advanced security measures.\n\nAuthorized files only.",
        
        'documents/config.ini' => "; System Configuration\n\ndatabase_host=localhost\ndatabase_user=admin\ndatabase_pass=s3cr3t123\napp_name=Secure Viewer\n\n; Security settings\nfilter_level=high\nblock_attempts=5",
        
        'documents/logs/access.log' => "2024-01-15 10:30:15 - User admin accessed readme.txt\n2024-01-15 10:31:22 - User admin accessed config.ini\n2024-01-15 10:35:18 - Security: Blocked suspicious file request\n2024-01-15 10:40:05 - User admin logged out",
        
        'documents/logs/error.log' => "2024-01-15 09:15:33 - WARNING: High memory usage detected\n2024-01-15 10:20:18 - ERROR: Database connection timeout\n2024-01-15 10:35:18 - SECURITY: Blocked LFI attempt",
        
        'documents/data/users.xml' => "<?xml version=\"1.0\"?>\n<users>\n    <user>\n        <id>1</id>\n        <name>John Doe</name>\n        <role>admin</role>\n    </user>\n    <user>\n        <id>2</id>\n        <name>Jane Smith</name>\n        <role>user</role>\n    </user>\n</users>",
        
        'documents/data/settings.json' => "{\n    \"app_name\": \"Secure Viewer\",\n    \"version\": \"4.2\",\n    \"security\": {\n        \"filtering\": true,\n        \"logging\": true,\n        \"blocking\": true\n    }\n}",
        
        'documents/backups/backup.sql' => "-- Database Backup\nCREATE TABLE users (id INT, username VARCHAR(50), password VARCHAR(255));\nINSERT INTO users VALUES (1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99');\nINSERT INTO users VALUES (2, 'user', '5f4dcc3b5aa765d61d8327deb882cf99');",
        
        'documents/flag.txt' => "CTF{4dv4nc3d_lf1_byp455_3xpl01t3d}\n\nCongratulations! You've successfully bypassed multiple security layers.\n\nThis flag represents your ability to overcome:\n- Multi-layer filtering\n- Path traversal protection\n- Wrapper blocking\n- Extension restrictions\n\nWell done!"
    ];
    
    foreach ($files as $file => $content) {
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
    
    // Create a test PHP file to check if code execution is possible
    if (!file_exists('documents/test.php')) {
        file_put_contents('documents/test.php', '<?php echo "EXECUTED: " . __FILE__ . "\n"; phpinfo(); ?>');
    }
}
?>