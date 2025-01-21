<?php
// Start the session
session_start();

// Check if the session is already started
if (session_status() === PHP_SESSION_ACTIVE) {
    // Destroy all session data
    session_destroy();
}

// Redirect to login page
header('Location: login.php');
exit;