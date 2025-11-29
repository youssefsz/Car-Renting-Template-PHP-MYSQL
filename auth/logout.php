<?php
require_once '../includes/functions.php';

// Destroy all session data
session_destroy();

// Start new session for flash message
session_start();
setFlashMessage('success', 'You have been logged out successfully.');

// Redirect to home page
redirect('../index.php');
?>

