<?php
session_start();

// Database credentials
$host = 'localhost';
$dbname = 'idor_labs';
$username = 'root';
$password = ''; // Default for XAMPP/LAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to get current user's ID
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// Function to get current user's username
function get_username() {
    return $_SESSION['username'] ?? 'Guest';
}

// Function to get current user's role
function get_user_role() {
    return $_SESSION['role'] ?? 'user';
}

// Function to check if current user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to check if user owns a resource
function owns_resource($resource_user_id) {
    return get_user_id() == $resource_user_id;
}

// Function to check if user can access resource
function can_access_resource($resource_user_id, $required_role = null) {
    $user_id = get_user_id();
    $user_role = get_user_role();
    
    // Admin can access everything
    if ($user_role === 'admin') {
        return true;
    }
    
    // User can access their own resources
    if ($user_id == $resource_user_id) {
        return true;
    }
    
    // Check role-based access
    if ($required_role && $user_role === $required_role) {
        return true;
    }
    
    return false;
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect if not admin
function require_admin() {
    if (!is_admin()) {
        header("Location: index.php");
        exit();
    }
}

// Function to log security events
function log_security_event($event, $details = '') {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO security_logs (user_id, event, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([
            get_user_id(),
            $event,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        // Log error silently
    }
}

// Function to get user by ID
function get_user_by_id($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

// Function to get user's documents
function get_user_documents($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM documents WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// Function to get user's orders
function get_user_orders($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// Function to get all users (admin only)
function get_all_users() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// Function to get all documents (admin only)
function get_all_documents() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT d.*, u.username FROM documents d JOIN users u ON d.user_id = u.id ORDER BY d.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// Function to get all orders (admin only)
function get_all_orders() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}
?>
