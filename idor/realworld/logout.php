<?php
require_once 'config.php';

// Log security event
log_security_event('logout', 'User logged out');

// Destroy session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
