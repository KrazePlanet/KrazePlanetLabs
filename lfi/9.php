<?php
/*
 * Secure Financial Portal v3.5
 * Banking & Investment Management System
 */

session_start();

// Initialize client session
if (!isset($_SESSION['financial_client'])) {
    $_SESSION['financial_client'] = [
        'client_id' => 'CLI_' . rand(100000, 999999),
        'name' => 'Robert Johnson',
        'account_type' => 'Premium Investor',
        'portfolio_value' => '$1,250,000',
        'risk_level' => 'Moderate',
        'last_login' => date('Y-m-d H:i:s')
    ];
}

// Financial Security System (with vulnerabilities)
class FinancialSecurity {
    private $audit_log = [];
    
    public function validateFinancialRequest($input, $context = 'documents') {
        $this->logAudit("Financial request in context: $context - Input: " . substr($input, 0, 50));
        
        // Basic financial data validation
        $filtered = $this->financialFilter($input);
        
        // Context-based security checks
        $checks = [
            $this->checkTraversalPatterns($filtered),
            $this->checkSensitivePaths($filtered),
            $this->checkFinancialWrappers($filtered)
        ];
        
        foreach ($checks as $check) {
            if (!$check['approved']) {
                $this->logAudit("SECURITY_BLOCK: " . $check['reason']);
                return false;
            }
        }
        
        return true;
    }
    
    private function financialFilter($input) {
        // Financial institution filtering
        $filtered = $input;
        
        // Remove basic malicious patterns
        $filtered = str_replace(['../', '..\\'], '', $filtered);
        
        // Single URL decode
        $filtered = urldecode($filtered);
        
        // Remove null bytes
        $filtered = str_replace(chr(0), '', $filtered);
        
        return $filtered;
    }
    
    private function checkTraversalPatterns($input) {
        if (preg_match('/(\.\.\/|\.\.\\\|%2e%2e)/i', $input)) {
            return ['approved' => false, 'reason' => 'Path traversal pattern detected'];
        }
        return ['approved' => true];
    }
    
    private function checkSensitivePaths($input) {
        $sensitive = ['/etc/', '/proc/', '/var/', 'flag', '.env', 'config'];
        foreach ($sensitive as $path) {
            if (stripos($input, $path) !== false) {
                return ['approved' => false, 'reason' => 'Sensitive path access attempt'];
            }
        }
        return ['approved' => true];
    }
    
    private function checkFinancialWrappers($input) {
        if (preg_match('/^(php|file|http|data):\/\//i', $input)) {
            return ['approved' => false, 'reason' => 'Dangerous wrapper detected'];
        }
        return ['approved' => true];
    }
    
    private function logAudit($event) {
        $this->audit_log[] = [
            'timestamp' => microtime(true),
            'event' => $event,
            'client' => $_SESSION['financial_client']['client_id']
        ];
    }
    
    public function getAuditLog() {
        return $this->audit_log;
    }
}

// Financial Management System with Vulnerabilities
class FinancialSystem {
    private $security;
    
    public function __construct() {
        $this->security = new FinancialSecurity();
    }
    
    public function processFinancialRequest() {
        $output = '';
        
        // Account Statements
        if (isset($_GET['statement'])) {
            $output = $this->viewAccountStatement($_GET['statement']);
        }
        
        // Transaction Records
        if (isset($_GET['transaction'])) {
            $output = $this->viewTransactionRecord($_GET['transaction']);
        }
        
        // Investment Reports
        if (isset($_POST['investment_report'])) {
            $output = $this->viewInvestmentReport($_POST['investment_report']);
        }
        
        // Tax Documents
        if (isset($_GET['tax_doc'])) {
            $output = $this->viewTaxDocument($_GET['tax_doc']);
        }
        
        // Audit Logs
        if (isset($_GET['audit_log'])) {
            $output = $this->viewAuditLog($_GET['audit_log']);
        }
        
        return [
            'output' => $output,
            'audit_log' => $this->security->getAuditLog()
        ];
    }
    
    private function viewAccountStatement($statement_path) {
        if (!$this->security->validateFinancialRequest($statement_path, 'statements')) {
            return "🚫 FINANCIAL_SECURITY: Statement access denied";
        }
        
        $filtered_path = $this->applyFinancialFilters($statement_path);
        $file_path = "financial_statements/" . $filtered_path;
        
        // VULNERABILITY: Weak path validation with fallback
        if (file_exists($file_path)) {
            return $this->readFinancialFile($file_path);
        } else {
            // Fallback vulnerability
            if (file_exists($filtered_path)) {
                return $this->readFinancialFile($filtered_path);
            }
        }
        
        return "Account statement not found in financial records.";
    }
    
    private function viewTransactionRecord($transaction_path) {
        $file_path = "transaction_records/" . $transaction_path;
        
        if (file_exists($file_path)) {
            return $this->readFinancialFile($file_path);
        }
        return "Transaction record not available.";
    }
    
    private function viewInvestmentReport($report_path) {
        $file_path = "investment_reports/" . $report_path;
        
        if (file_exists($file_path)) {
            ob_start();
            include($file_path);
            return ob_get_clean();
        }
        return "Investment report not found.";
    }
    
    private function viewTaxDocument($tax_path) {
        $file_path = "tax_documents/" . $tax_path;
        
        if (file_exists($file_path)) {
            return $this->readFinancialFile($file_path);
        }
        return "Tax document not available.";
    }
    
    private function viewAuditLog($log_path) {
        $file_path = "audit_logs/" . $log_path;
        
        if (file_exists($file_path)) {
            return $this->readFinancialFile($file_path);
        }
        return "Audit log not found.";
    }
    
    private function applyFinancialFilters($input) {
        // Financial institution filtering with vulnerabilities
        $filtered = $input;
        
        // Remove traversal patterns (incompletely)
        $filtered = str_replace(['../', '..\\'], '', $filtered);
        
        // Single decode
        $filtered = urldecode($filtered);
        
        return $filtered;
    }
    
    private function readFinancialFile($path) {
        if (file_exists($path) && is_readable($path)) {
            $content = file_get_contents($path);
            return $content !== false ? htmlspecialchars($content) : "Unable to read financial document";
        }
        return "Financial document not accessible";
    }
}

// Initialize financial system
$financial_system = new FinancialSystem();
$result = $financial_system->processFinancialRequest();
$financial_content = $result['output'];
$audit_log = $result['audit_log'];

// Create financial environment
createFinancialEnvironment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Financial Portal - Banking & Investments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --financial-blue: #1e40af;
            --financial-dark: #0f172a;
            --financial-green: #059669;
            --financial-gold: #d97706;
            --financial-red: #dc2626;
            --financial-gray: #64748b;
            --financial-light: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            min-height: 100vh;
            color: #334155;
        }
        
        .financial-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .financial-header {
            background: linear-gradient(135deg, var(--financial-blue), #1e3a8a);
            color: white;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .security-badge {
            position: absolute;
            top: 25px;
            right: 25px;
            background: rgba(255,255,255,0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .client-card {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 15px;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .client-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .client-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .detail-item {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 10px;
        }
        
        .financial-nav {
            background: var(--financial-dark);
            padding: 1.5rem 0;
        }
        
        .nav-container {
            display: flex;
            justify-content: center;
            gap: 3rem;
        }
        
        .nav-item {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateY(-2px);
        }
        
        .financial-main {
            display: grid;
            grid-template-columns: 320px 1fr;
            min-height: 800px;
        }
        
        .financial-sidebar {
            background: var(--financial-light);
            padding: 2.5rem;
            border-right: 1px solid #e2e8f0;
        }
        
        .financial-content {
            padding: 2.5rem;
            background: white;
        }
        
        .portfolio-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-left: 5px solid var(--financial-green);
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--financial-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .portfolio-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--financial-green);
            margin-bottom: 1rem;
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 10px;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--financial-dark);
        }
        
        .stat-label {
            color: var(--financial-gray);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .financial-form {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--financial-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--financial-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .btn {
            padding: 1.25rem 2.5rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-financial {
            background: linear-gradient(135deg, var(--financial-blue), #1e3a8a);
            color: white;
        }
        
        .btn-financial:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
        }
        
        .financial-output {
            background: #1e293b;
            color: #cbd5e1;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 1.5rem;
            font-family: 'Fira Code', monospace;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
            border: 2px solid #334155;
        }
        
        .quick-access {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .access-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .access-card:hover {
            border-color: var(--financial-blue);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .access-icon {
            font-size: 2.5rem;
            color: var(--financial-blue);
            margin-bottom: 1rem;
        }
        
        .financial-tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 2rem;
        }
        
        .financial-tab {
            padding: 1.25rem 2.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            color: var(--financial-gray);
            transition: all 0.3s;
        }
        
        .financial-tab.active {
            border-bottom-color: var(--financial-blue);
            color: var(--financial-blue);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .security-lab {
            background: linear-gradient(135deg, #fffbeb, #f59e0b);
            border-radius: 15px;
            padding: 2.5rem;
            margin-top: 2rem;
        }
        
        .payload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .payload-card {
            background: rgba(255,255,255,0.9);
            border-radius: 10px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .payload-card:hover {
            border-color: var(--financial-blue);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .audit-panel {
            background: var(--financial-dark);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .market-indicators {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .market-item {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .market-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--financial-green);
        }
        
        .market-change {
            font-size: 0.9rem;
            color: var(--financial-green);
        }
        
        .market-change.negative {
            color: var(--financial-red);
        }
    </style>
</head>
<body>
    <div class="financial-container">
        <!-- Header -->
        <div class="financial-header">
            <div class="header-content">
                <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-chart-line"></i> Secure Financial Portal
                </h1>
                <p style="font-size: 1.2rem; opacity: 0.9;">Wealth Management & Investment Banking</p>
                
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i> ENCRYPTED & SECURE
                </div>
                
                <div class="client-card">
                    <div class="client-name"><?php echo $_SESSION['financial_client']['name']; ?></div>
                    <div style="opacity: 0.9;">Client ID: <?php echo $_SESSION['financial_client']['client_id']; ?></div>
                    
                    <div class="client-details">
                        <div class="detail-item">
                            <div style="font-size: 0.9rem; opacity: 0.8;">Account Type</div>
                            <div style="font-weight: 600;"><?php echo $_SESSION['financial_client']['account_type']; ?></div>
                        </div>
                        <div class="detail-item">
                            <div style="font-size: 0.9rem; opacity: 0.8;">Risk Level</div>
                            <div style="font-weight: 600;"><?php echo $_SESSION['financial_client']['risk_level']; ?></div>
                        </div>
                        <div class="detail-item">
                            <div style="font-size: 0.9rem; opacity: 0.8;">Last Login</div>
                            <div style="font-weight: 600;"><?php echo $_SESSION['financial_client']['last_login']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="financial-nav">
            <div class="nav-container">
                <a href="#" class="nav-item" onclick="showFinancialTab('dashboard')">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="#" class="nav-item" onclick="showFinancialTab('statements')">
                    <i class="fas fa-file-invoice-dollar"></i> Statements
                </a>
                <a href="#" class="nav-item" onclick="showFinancialTab('transactions')">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </a>
                <a href="#" class="nav-item" onclick="showFinancialTab('investments')">
                    <i class="fas fa-chart-pie"></i> Investments
                </a>
                <a href="#" class="nav-item" onclick="showFinancialTab('tax')">
                    <i class="fas fa-receipt"></i> Tax Documents
                </a>
                <a href="#" class="nav-item" onclick="showFinancialTab('security')">
                    <i class="fas fa-user-shield"></i> Security Center
                </a>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="financial-main">
            <!-- Sidebar -->
            <aside class="financial-sidebar">
                <div class="portfolio-card">
                    <div class="card-title">
                        <i class="fas fa-wallet"></i> Portfolio Value
                    </div>
                    <div class="portfolio-value">
                        <?php echo $_SESSION['financial_client']['portfolio_value']; ?>
                    </div>
                    <div style="color: var(--financial-green); font-weight: 600;">
                        <i class="fas fa-arrow-up"></i> +2.4% Today
                    </div>
                </div>
                
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-value">$245K</div>
                        <div class="stat-label">Cash Balance</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">$805K</div>
                        <div class="stat-label">Equities</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">$200K</div>
                        <div class="stat-label">Bonds</div>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h3 style="margin-bottom: 1rem; color: var(--financial-dark);">Quick Access</h3>
                    <div class="quick-access">
                        <div class="access-card" onclick="loadStatement('q1_statement.pdf')">
                            <div class="access-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>Q1 Statement</div>
                        </div>
                        <div class="access-card" onclick="loadTransaction('recent_transactions.csv')">
                            <div class="access-icon">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div>Transactions</div>
                        </div>
                        <div class="access-card" onclick="loadInvestment('portfolio_report.pdf')">
                            <div class="access-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>Portfolio Report</div>
                        </div>
                        <div class="access-card" onclick="loadTaxDocument('w2_form.pdf')">
                            <div class="access-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div>Tax Forms</div>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Content Area -->
            <main class="financial-content">
                <!-- Dashboard Tab -->
                <div id="dashboard-tab" class="tab-content active">
                    <div class="portfolio-card">
                        <div class="card-title">
                            <i class="fas fa-tachometer-alt"></i> Financial Dashboard
                        </div>
                        <p>Welcome to your secure financial portal. Monitor your investments and access financial documents.</p>
                        
                        <div class="market-indicators">
                            <div class="market-item">
                                <div>S&P 500</div>
                                <div class="market-value">4,890.21</div>
                                <div class="market-change">+0.8%</div>
                            </div>
                            <div class="market-item">
                                <div>NASDAQ</div>
                                <div class="market-value">15,632.45</div>
                                <div class="market-change">+1.2%</div>
                            </div>
                            <div class="market-item">
                                <div>DOW JONES</div>
                                <div class="market-value">38,415.67</div>
                                <div class="market-change">+0.5%</div>
                            </div>
                            <div class="market-item">
                                <div>BITCOIN</div>
                                <div class="market-value">$42,189</div>
                                <div class="market-change negative">-2.1%</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statements Tab -->
                <div id="statements-tab" class="tab-content">
                    <div class="portfolio-card">
                        <div class="card-title">
                            <i class="fas fa-file-invoice-dollar"></i> Account Statements
                        </div>
                        <p>Access your account statements and financial reports.</p>
                        
                        <div class="financial-form">
                            <div class="form-group">
                                <label class="form-label">Statement File Path:</label>
                                <input type="text" id="statementPath" class="form-control" 
                                       placeholder="Enter statement filename (e.g., q1_statement.pdf)">
                            </div>
                            <button class="btn btn-financial" onclick="loadStatement()">
                                <i class="fas fa-search"></i> Access Statement
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Transactions Tab -->
                <div id="transactions-tab" class="tab-content">
                    <div class="portfolio-card">
                        <div class="card-title">
                            <i class="fas fa-exchange-alt"></i> Transaction Records
                        </div>
                        <p>View your transaction history and financial activity.</p>
                        
                        <div class="financial-form">
                            <div class="form-group">
                                <label class="form-label">Transaction File:</label>
                                <input type="text" name="transaction" class="form-control" 
                                       placeholder="Enter transaction filename"
                                       onchange="loadTransaction(this.value)">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Investments Tab -->
                <div id="investments-tab" class="tab-content">
                    <div class="portfolio-card">
                        <div class="card-title">
                            <i class="fas fa-chart-pie"></i> Investment Reports
                        </div>
                        <p>Access detailed investment performance reports and analytics.</p>
                        
                        <form method="POST">
                            <div class="financial-form">
                                <div class="form-group">
                                    <label class="form-label">Investment Report:</label>
                                    <input type="text" name="investment_report" class="form-control" 
                                           placeholder="Enter investment report filename">
                                </div>
                                <button type="submit" class="btn btn-financial">
                                    <i class="fas fa-chart-line"></i> View Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tax Documents Tab -->
                <div id="tax-tab" class="tab-content">
                    <div class="portfolio-card">
                        <div class="card-title">
                            <i class="fas fa-receipt"></i> Tax Documents
                        </div>
                        <p>Access your tax documents and filing information.</p>
                        
                        <div class="financial-form">
                            <div class="form-group">
                                <label class="form-label">Tax Document:</label>
                                <input type="text" name="tax_doc" class="form-control" 
                                       placeholder="Enter tax document filename"
                                       onchange="loadTaxDocument(this.value)">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Security Center Tab -->
                <div id="security-tab" class="tab-content">
                    <div class="portfolio-card">
                        <div class="card-title">
                            <i class="fas fa-user-shield"></i> Security Center
                        </div>
                        <p>Financial system security monitoring and testing.</p>
                        
                        <div class="financial-form">
                            <div class="form-group">
                                <label class="form-label">Audit Log File:</label>
                                <input type="text" name="audit_log" class="form-control" 
                                       placeholder="Enter audit log filename"
                                       onchange="loadAuditLog(this.value)">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Testing Lab -->
                    <div class="security-lab">
                        <h3><i class="fas fa-flask"></i> Financial Security Testing</h3>
                        <p>Advanced security testing environment for financial systems.</p>
                        
                        <div class="form-group">
                            <label class="form-label">Security Test Payload:</label>
                            <input type="text" id="securityPayload" class="form-control" 
                                   placeholder="Enter security testing payload">
                            <button class="btn btn-financial" onclick="testSecurityPayload()" style="margin-top: 1rem;">
                                <i class="fas fa-bolt"></i> Execute Security Test
                            </button>
                        </div>
                        
                        <div class="payload-grid">
                            <div class="payload-card" onclick="setPayload('....//....//....//etc/passwd')">
                                <strong>System File Access</strong><br>
                                <small>Path traversal technique</small>
                            </div>
                            <div class="payload-card" onclick="setPayload('....//....//....//etc/hosts')">
                                <strong>Network Configuration</strong><br>
                                <small>Hosts file access</small>
                            </div>
                            <div class="payload-card" onclick="setPayload('PhP://filter/convert.base64-encode/resource=financial_statements/flag.txt')">
                                <strong>PHP Wrapper</strong><br>
                                <small>Base64 encoding filter</small>
                            </div>
                            <div class="payload-card" onclick="setPayload('....//....//....//proc/self/environ')">
                                <strong>Process Environment</strong><br>
                                <small>Environment variables</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Audit Log Display -->
                    <div class="audit-panel">
                        <h4><i class="fas fa-clipboard-list"></i> Security Audit Log</h4>
                        <div style="font-family: 'Fira Code', monospace; font-size: 0.85rem; margin-top: 1rem; max-height: 200px; overflow-y: auto;">
                            <?php
                            if (!empty($audit_log)) {
                                foreach (array_slice($audit_log, -6) as $entry) {
                                    echo "<div style='color: #cbd5e1; margin-bottom: 0.5rem; padding: 0.5rem; background: rgba(255,255,255,0.1); border-radius: 5px;'>";
                                    echo "[" . date('H:i:s', (int)$entry['timestamp']) . "] {$entry['event']}";
                                    echo "</div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <?php if ($financial_content): ?>
                    <div class="financial-output">
                        <?php echo $financial_content; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        function showFinancialTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabName + '-tab').classList.add('active');
        }
        
        function loadStatement(path = null) {
            const statementPath = path || document.getElementById('statementPath').value;
            if (statementPath) {
                window.location.href = '?statement=' + encodeURIComponent(statementPath);
            }
        }
        
        function loadTransaction(path) {
            if (path) {
                window.location.href = '?transaction=' + encodeURIComponent(path);
            }
        }
        
        function loadInvestment(path) {
            if (path) {
                window.location.href = '?investment_report=' + encodeURIComponent(path);
            }
        }
        
        function loadTaxDocument(path) {
            if (path) {
                window.location.href = '?tax_doc=' + encodeURIComponent(path);
            }
        }
        
        function loadAuditLog(path) {
            if (path) {
                window.location.href = '?audit_log=' + encodeURIComponent(path);
            }
        }
        
        function setPayload(payload) {
            document.getElementById('securityPayload').value = payload;
        }
        
        function testSecurityPayload() {
            const payload = document.getElementById('securityPayload').value;
            if (payload) {
                window.location.href = '?statement=' + encodeURIComponent(payload);
            }
        }
        
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            showFinancialTab('dashboard');
        });
    </script>
</body>
</html>

<?php
function createFinancialEnvironment() {
    $financial_dirs = [
        'financial_statements',
        'transaction_records',
        'investment_reports',
        'tax_documents',
        'audit_logs',
        'compliance_docs'
    ];
    
    foreach ($financial_dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    $financial_files = [
        'financial_statements/q1_statement.pdf' => "QUARTERLY FINANCIAL STATEMENT - Q1 2024\n\nClient: Robert Johnson\nAccount: Premium Investor (CLI_384726)\n\nPortfolio Summary:\n- Total Assets: $1,250,000\n- Cash & Equivalents: $245,000\n- Equities: $805,000\n- Fixed Income: $200,000\n\nPerformance:\n- Quarterly Return: +8.2%\n- YTD Return: +12.5%\n- Risk Level: Moderate\n\nFLAG: CTF{F1n4nc14l_LF1_3xpl01t}\n\nThis statement contains confidential financial information.",
        
        'transaction_records/recent_transactions.csv' => "Date,Type,Description,Amount,Balance\n2024-01-15,BUY,AAPL - Apple Inc.,-$25,000,$1,225,000\n2024-01-14,SELL,TSLA - Tesla Inc.,+$18,500,$1,250,000\n2024-01-10,DIVIDEND,MSFT - Microsoft Corp.,+$2,150,$1,231,500\n2024-01-08,BUY,GOOGL - Alphabet Inc.,-$15,000,$1,229,350\n\nConfidential: Unauthorized access prohibited.",
        
        'investment_reports/portfolio_report.pdf' => "INVESTMENT PORTFOLIO ANALYSIS\n\nClient: Robert Johnson\nReport Date: January 15, 2024\n\nAsset Allocation:\n- US Stocks: 45%\n- International Stocks: 25%\n- Bonds: 16%\n- Cash: 10%\n- Alternatives: 4%\n\nTop Holdings:\n1. Apple Inc. (AAPL) - $85,000\n2. Microsoft Corp. (MSFT) - $72,000\n3. Amazon.com Inc. (AMZN) - $68,000\n4. Alphabet Inc. (GOOGL) - $65,000\n\nFLAG: CTF{B4nk1ng_S3cur1ty_Byp455}\n\nThis report contains sensitive investment information.",
        
        'tax_documents/w2_form.pdf' => "TAX FORM W-2 - 2023\n\nEmployee: Robert Johnson\nEmployer: Secure Financial Corp\n\nEarnings:\n- Wages: $185,000\n- Federal Tax: $38,500\n- Social Security: $11,470\n- Medicare: $2,682\n\nConfidential Tax Information - Handle with care.",
        
        'audit_logs/security_audit.log' => "2024-01-15 09:30:15 - Client CLI_384726 accessed Q1 statement\n2024-01-15 09:35:22 - Security: Suspicious activity detected\n2024-01-15 10:00:00 - System backup completed\n2024-01-15 11:30:45 - Security audit: No critical issues\n\nFLAG: CTF{4ud1t_L0g_L34k}",
        
        'flag.txt' => "CTF{F1n4nc14l_LF1_3xpl01t}\n\nCongratulations! You've successfully exploited the financial portal.\n\nThis demonstrates critical security vulnerabilities in financial systems.\n\nAdditional Flags:\n- CTF{B4nk1ng_S3cur1ty_Byp455}\n- CTF{4ud1t_L0g_L34k}\n- CTF{F1n4nc14l_D4t4_3xpos3d}"
    ];
    
    foreach ($financial_files as $file => $content) {
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
}
?>