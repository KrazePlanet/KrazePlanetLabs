<?php
/*
 * HealthCare Portal v2.0
 * Patient Management System with LFI Vulnerabilities
 */

session_start();

// Initialize patient session
if (!isset($_SESSION['patient'])) {
    $_SESSION['patient'] = [
        'id' => 'PAT_' . rand(10000, 99999),
        'name' => 'John Smith',
        'doctor' => 'Dr. Emily Wilson',
        'last_visit' => date('Y-m-d'),
        'access_level' => 'patient'
    ];
}

// Medical Security Filter (with vulnerabilities)
class MedicalSecurity {
    public function validateMedicalRequest($input) {
        // Basic medical data validation
        $filtered = $this->medicalFilter($input);
        
        // Check for obvious attacks
        if (strpos($filtered, '../') !== false) {
            return false;
        }
        
        if (strpos($filtered, '/etc/') !== false) {
            return false;
        }
        
        return true;
    }
    
    private function medicalFilter($input) {
        // Weak filtering for medical data
        $filtered = $input;
        $filtered = str_replace('..', '', $filtered);
        $filtered = urldecode($filtered);
        return $filtered;
    }
}

// Healthcare System with Vulnerabilities
class HealthcareSystem {
    private $security;
    
    public function __construct() {
        $this->security = new MedicalSecurity();
    }
    
    public function processMedicalRequest() {
        $output = '';
        
        // Medical Records Access
        if (isset($_GET['medical_record'])) {
            $output = $this->accessMedicalRecord($_GET['medical_record']);
        }
        
        // Lab Results
        if (isset($_GET['lab_result'])) {
            $output = $this->viewLabResult($_GET['lab_result']);
        }
        
        // Patient Documents
        if (isset($_POST['patient_doc'])) {
            $output = $this->viewPatientDocument($_POST['patient_doc']);
        }
        
        // Prescription System
        if (isset($_GET['prescription'])) {
            $output = $this->viewPrescription($_GET['prescription']);
        }
        
        return $output;
    }
    
    private function accessMedicalRecord($record_path) {
        if (!$this->security->validateMedicalRequest($record_path)) {
            return "⚠️ Medical security protocol violation!";
        }
        
        $file_path = "medical_records/" . $record_path;
        
        // VULNERABILITY: Direct file inclusion with weak filtering
        if (file_exists($file_path)) {
            return $this->readMedicalFile($file_path);
        } else {
            // Fallback vulnerability
            if (file_exists($record_path)) {
                return $this->readMedicalFile($record_path);
            }
        }
        
        return "Medical record not found in system.";
    }
    
    private function viewLabResult($lab_path) {
        $file_path = "lab_results/" . $lab_path;
        
        if (file_exists($file_path)) {
            return $this->readMedicalFile($file_path);
        }
        return "Lab result not found.";
    }
    
    private function viewPatientDocument($doc_path) {
        $file_path = "patient_docs/" . $doc_path;
        
        if (file_exists($file_path)) {
            ob_start();
            include($file_path);
            return ob_get_clean();
        }
        return "Patient document not available.";
    }
    
    private function viewPrescription($rx_path) {
        $file_path = "prescriptions/" . $rx_path;
        
        if (file_exists($file_path)) {
            return $this->readMedicalFile($file_path);
        }
        return "Prescription not found.";
    }
    
    private function readMedicalFile($path) {
        if (file_exists($path)) {
            $content = file_get_contents($path);
            return $content !== false ? htmlspecialchars($content) : "Unable to read medical file";
        }
        return "Medical file not accessible";
    }
}

// Initialize healthcare system
$healthcare = new HealthcareSystem();
$medical_content = $healthcare->processMedicalRequest();

// Create medical environment
createMedicalEnvironment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthCare Portal - Patient Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --medical-blue: #1e88e5;
            --medical-green: #43a047;
            --medical-red: #e53935;
            --medical-teal: #00897b;
            --medical-gray: #546e7a;
            --medical-light: #f5f5f5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            min-height: 100vh;
            color: #37474f;
        }
        
        .medical-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .medical-header {
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .medical-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .patient-info {
            background: white;
            margin: 1rem auto;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        
        .medical-nav {
            background: var(--medical-teal);
            padding: 1rem 0;
        }
        
        .nav-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        
        .nav-item {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-item:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .medical-main {
            display: grid;
            grid-template-columns: 300px 1fr;
            min-height: 700px;
        }
        
        .medical-sidebar {
            background: var(--medical-light);
            padding: 2rem;
            border-right: 1px solid #e0e0e0;
        }
        
        .medical-content {
            padding: 2rem;
            background: white;
        }
        
        .health-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 5px solid var(--medical-green);
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--medical-teal);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .medical-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--medical-gray);
        }
        
        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--medical-blue);
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
        
        .btn-medical {
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal));
            color: white;
        }
        
        .btn-medical:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.4);
        }
        
        .medical-output {
            background: #1a237e;
            color: #e8eaf6;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .quick-access {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .access-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .access-card:hover {
            border-color: var(--medical-blue);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .access-icon {
            font-size: 2rem;
            color: var(--medical-blue);
            margin-bottom: 1rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        
        .alert-warning {
            background: #fff3e0;
            border-color: var(--medical-red);
            color: #e65100;
        }
        
        .medical-tabs {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 1.5rem;
        }
        
        .medical-tab {
            padding: 1rem 2rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            color: var(--medical-gray);
        }
        
        .medical-tab.active {
            border-bottom-color: var(--medical-blue);
            color: var(--medical-blue);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .lab-section {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            border-radius: 15px;
            padding: 2rem;
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
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payload-card:hover {
            border-color: var(--medical-red);
            transform: translateY(-2px);
        }
        
        .patient-status {
            background: var(--medical-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="medical-container">
        <!-- Header -->
        <div class="medical-header">
            <h1><i class="fas fa-heartbeat"></i> HealthCare Portal</h1>
            <p>Patient Management System v2.0</p>
            <div class="medical-badge">
                <i class="fas fa-shield-alt"></i> HIPAA Compliant
            </div>
            
            <div class="patient-info">
                <h3>Patient: <?php echo $_SESSION['patient']['name']; ?></h3>
                <p>ID: <?php echo $_SESSION['patient']['id']; ?></p>
                <p>Physician: <?php echo $_SESSION['patient']['doctor']; ?></p>
                <div class="patient-status">ACTIVE</div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="medical-nav">
            <div class="nav-container">
                <a href="#" class="nav-item" onclick="showTab('dashboard')"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" class="nav-item" onclick="showTab('records')"><i class="fas fa-file-medical"></i> Medical Records</a>
                <a href="#" class="nav-item" onclick="showTab('labs')"><i class="fas fa-flask"></i> Lab Results</a>
                <a href="#" class="nav-item" onclick="showTab('prescriptions')"><i class="fas fa-prescription"></i> Prescriptions</a>
                <a href="#" class="nav-item" onclick="showTab('documents')"><i class="fas fa-file-pdf"></i> Documents</a>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="medical-main">
            <!-- Sidebar -->
            <aside class="medical-sidebar">
                <div class="health-card">
                    <h3><i class="fas fa-user-md"></i> Quick Access</h3>
                    <div class="quick-access">
                        <div class="access-card" onclick="loadMedicalRecord('blood_test.txt')">
                            <div class="access-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <div>Blood Test</div>
                        </div>
                        <div class="access-card" onclick="loadMedicalRecord('xray_report.txt')">
                            <div class="access-icon">
                                <i class="fas fa-x-ray"></i>
                            </div>
                            <div>X-Ray Report</div>
                        </div>
                        <div class="access-card" onclick="loadLabResult('urinalysis.txt')">
                            <div class="access-icon">
                                <i class="fas fa-vial"></i>
                            </div>
                            <div>Urinalysis</div>
                        </div>
                        <div class="access-card" onclick="loadPrescription('medication_rx.txt')">
                            <div class="access-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div>Prescription</div>
                        </div>
                    </div>
                </div>
                
                <div class="health-card">
                    <h3><i class="fas fa-calendar-check"></i> Upcoming</h3>
                    <ul style="list-style: none; padding-left: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                            <strong>Feb 15</strong> - Cardiology Follow-up
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                            <strong>Feb 20</strong> - Blood Work
                        </li>
                        <li style="padding: 0.5rem 0;">
                            <strong>Mar 01</strong> - Annual Physical
                        </li>
                    </ul>
                </div>
            </aside>
            
            <!-- Content Area -->
            <main class="medical-content">
                <!-- Dashboard Tab -->
                <div id="dashboard-tab" class="tab-content active">
                    <div class="health-card">
                        <div class="card-title">
                            <i class="fas fa-chart-line"></i> Health Dashboard
                        </div>
                        <p>Welcome to your patient portal. Access your medical information securely.</p>
                        
                        <div class="quick-access">
                            <div class="access-card" onclick="showTab('records')">
                                <div class="access-icon">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <div>Medical Records</div>
                            </div>
                            <div class="access-card" onclick="showTab('labs')">
                                <div class="access-icon">
                                    <i class="fas fa-microscope"></i>
                                </div>
                                <div>Lab Results</div>
                            </div>
                            <div class="access-card" onclick="showTab('prescriptions')">
                                <div class="access-icon">
                                    <i class="fas fa-prescription-bottle"></i>
                                </div>
                                <div>Prescriptions</div>
                            </div>
                            <div class="access-card" onclick="showTab('documents')">
                                <div class="access-icon">
                                    <i class="fas fa-file-medical"></i>
                                </div>
                                <div>Documents</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Medical Records Tab -->
                <div id="records-tab" class="tab-content">
                    <div class="health-card">
                        <div class="card-title">
                            <i class="fas fa-file-medical-alt"></i> Medical Records Access
                        </div>
                        <p>Access your complete medical history and treatment records.</p>
                        
                        <div class="medical-form">
                            <div class="form-group">
                                <label class="form-label">Medical Record File:</label>
                                <input type="text" id="recordPath" class="form-control" 
                                       placeholder="Enter medical record filename">
                            </div>
                            <button class="btn btn-medical" onclick="loadMedicalRecord()">
                                <i class="fas fa-search"></i> Access Record
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Lab Results Tab -->
                <div id="labs-tab" class="tab-content">
                    <div class="health-card">
                        <div class="card-title">
                            <i class="fas fa-flask"></i> Laboratory Results
                        </div>
                        <p>View your laboratory test results and analysis reports.</p>
                        
                        <div class="medical-form">
                            <div class="form-group">
                                <label class="form-label">Lab Result File:</label>
                                <input type="text" name="lab_result" class="form-control" 
                                       placeholder="Enter lab result filename"
                                       onchange="loadLabResult(this.value)">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Prescriptions Tab -->
                <div id="prescriptions-tab" class="tab-content">
                    <div class="health-card">
                        <div class="card-title">
                            <i class="fas fa-prescription-bottle"></i> Prescription Management
                        </div>
                        <p>Access your current and historical prescriptions.</p>
                        
                        <div class="medical-form">
                            <div class="form-group">
                                <label class="form-label">Prescription File:</label>
                                <input type="text" name="prescription" class="form-control" 
                                       placeholder="Enter prescription filename"
                                       onchange="loadPrescription(this.value)">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Documents Tab -->
                <div id="documents-tab" class="tab-content">
                    <div class="health-card">
                        <div class="card-title">
                            <i class="fas fa-file-pdf"></i> Patient Documents
                        </div>
                        <p>Access and manage your medical documents and forms.</p>
                        
                        <form method="POST">
                            <div class="medical-form">
                                <div class="form-group">
                                    <label class="form-label">Document File:</label>
                                    <input type="text" name="patient_doc" class="form-control" 
                                           placeholder="Enter document filename">
                                </div>
                                <button type="submit" class="btn btn-medical">
                                    <i class="fas fa-file-download"></i> View Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Medical Testing Section -->
                <div class="lab-section">
                    <h3><i class="fas fa-stethoscope"></i> Medical System Testing</h3>
                    <p>Advanced testing area for medical system security.</p>
                    
                    <div class="form-group">
                        <label class="form-label">Security Test Payload:</label>
                        <input type="text" id="testPayload" class="form-control" 
                               placeholder="Enter security testing payload">
                        <button class="btn btn-medical" onclick="testMedicalPayload()" style="margin-top: 1rem;">
                            <i class="fas fa-vial"></i> Execute Test
                        </button>
                    </div>
                    
                    <div class="payload-grid">
                        <div class="payload-card" onclick="setPayload('....//....//....//etc/passwd')">
                            <strong>System File Access</strong><br>
                            <small>Path traversal test</small>
                        </div>
                        <div class="payload-card" onclick="setPayload('....//....//....//etc/hosts')">
                            <strong>Hosts File</strong><br>
                            <small>Network configuration</small>
                        </div>
                        <div class="payload-card" onclick="setPayload('PhP://filter/convert.base64-encode/resource=medical_records/flag.txt')">
                            <strong>PHP Wrapper</strong><br>
                            <small>Base64 encoding</small>
                        </div>
                        <div class="payload-card" onclick="setPayload('....//....//....//proc/self/environ')">
                            <strong>Process Info</strong><br>
                            <small>Environment data</small>
                        </div>
                    </div>
                </div>
                
                <?php if ($medical_content): ?>
                    <div class="medical-output">
                        <?php echo $medical_content; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
        }
        
        function loadMedicalRecord(path = null) {
            const recordPath = path || document.getElementById('recordPath').value;
            if (recordPath) {
                window.location.href = '?medical_record=' + encodeURIComponent(recordPath);
            }
        }
        
        function loadLabResult(path) {
            if (path) {
                window.location.href = '?lab_result=' + encodeURIComponent(path);
            }
        }
        
        function loadPrescription(path) {
            if (path) {
                window.location.href = '?prescription=' + encodeURIComponent(path);
            }
        }
        
        function setPayload(payload) {
            document.getElementById('testPayload').value = payload;
        }
        
        function testMedicalPayload() {
            const payload = document.getElementById('testPayload').value;
            if (payload) {
                window.location.href = '?medical_record=' + encodeURIComponent(payload);
            }
        }
        
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            showTab('dashboard');
        });
    </script>
</body>
</html>

<?php
function createMedicalEnvironment() {
    $medical_dirs = [
        'medical_records',
        'lab_results', 
        'prescriptions',
        'patient_docs',
        'imaging_reports'
    ];
    
    foreach ($medical_dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    $medical_files = [
        'medical_records/blood_test.txt' => "BLOOD TEST RESULTS\n\nPatient: John Smith\nDate: 2024-01-15\n\nComplete Blood Count:\n- WBC: 7.2 K/uL (Normal)\n- RBC: 4.8 M/uL (Normal)\n- Hemoglobin: 14.2 g/dL (Normal)\n- Platelets: 250 K/uL (Normal)\n\nChemistry Panel:\n- Glucose: 95 mg/dL (Normal)\n- Creatinine: 0.9 mg/dL (Normal)\n- ALT: 25 U/L (Normal)\n\nFLAG: CTF{M3d1c4l_LF1_3xpl01t}\n\nAll values within normal range.",
        
        'medical_records/xray_report.txt' => "X-RAY REPORT - CHEST\n\nPatient: John Smith\nDate: 2024-01-10\n\nFindings:\n- Lungs: Clear, no infiltrates\n- Heart: Normal size and contour\n- Bones: No acute fractures\n- Mediastinum: Unremarkable\n\nImpression:\nNormal chest x-ray. No acute cardiopulmonary process.",
        
        'lab_results/urinalysis.txt' => "URINALYSIS REPORT\n\nPatient: John Smith\nDate: 2024-01-12\n\nPhysical Characteristics:\n- Color: Yellow (Normal)\n- Appearance: Clear (Normal)\n- Specific Gravity: 1.015 (Normal)\n\nChemical Analysis:\n- pH: 6.0 (Normal)\n- Protein: Negative\n- Glucose: Negative\n- Blood: Negative\n\nMicroscopic:\n- WBC: 0-2/HPF (Normal)\n- RBC: 0-1/HPF (Normal)\n\nImpression: Normal urinalysis.",
        
        'prescriptions/medication_rx.txt' => "PRESCRIPTION\n\nPatient: John Smith\nDate: 2024-01-15\nPhysician: Dr. Emily Wilson\n\nMedication: Lisinopril 10mg\nSig: Take one tablet daily\nQuantity: 30 tablets\nRefills: 3\n\nMedication: Atorvastatin 20mg\nSig: Take one tablet at bedtime\nQuantity: 30 tablets\nRefills: 3\n\nFLAG: CTF{H34lthc4r3_S3cur1ty_F41l}\n\nInstructions: Follow up in 3 months.",
        
        'patient_docs/consent_form.txt' => "PATIENT CONSENT FORM\n\nI, John Smith, consent to medical treatment and understand the risks and benefits.\n\nSignature: _________________\nDate: 2024-01-15",
        
        'flag.txt' => "CTF{M3d1c4l_LF1_3xpl01t}\n\nCongratulations! You've successfully exploited the healthcare portal.\n\nThis demonstrates the importance of proper input validation in medical systems.\n\nAdditional Flags:\n- CTF{H34lthc4r3_S3cur1ty_F41l}\n- CTF{P4t13nt_D4t4_3xpos3d}\n- CTF{M3d1c4l_R3c0rd_L34k}"
    ];
    
    foreach ($medical_files as $file => $content) {
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
}
?>