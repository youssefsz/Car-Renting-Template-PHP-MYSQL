<?php
/**
 * Helper Functions
 * 
 * This file contains utility functions used throughout the application.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 * 
 * @return bool True if user is admin
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Sanitize user input
 * 
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Display flash message
 * 
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message to display
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * 
 * @return array|null Flash message array or null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Display flash message HTML
 */
function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $alertClass = 'alert-info';
        switch ($flash['type']) {
            case 'success':
                $alertClass = 'alert-success';
                break;
            case 'error':
                $alertClass = 'alert-danger';
                break;
            case 'warning':
                $alertClass = 'alert-warning';
                break;
        }
        echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
        echo $flash['message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}

/**
 * Get all cars from database
 * 
 * @param PDO $pdo Database connection
 * @param string $status Filter by status (optional)
 * @return array Array of cars
 */
function getCars($pdo, $status = null) {
    if ($status) {
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE status = ? ORDER BY created_at DESC");
        $stmt->execute([$status]);
    } else {
        $stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
    }
    return $stmt->fetchAll();
}

/**
 * Get single car by ID
 * 
 * @param PDO $pdo Database connection
 * @param int $id Car ID
 * @return array|false Car data or false
 */
function getCarById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Get user by ID
 * 
 * @param PDO $pdo Database connection
 * @param int $id User ID
 * @return array|false User data or false
 */
function getUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id, name, email, phone, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Get user bookings
 * 
 * @param PDO $pdo Database connection
 * @param int $userId User ID
 * @return array Array of bookings
 */
function getUserBookings($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT b.*, c.name as car_name, c.image as car_image 
        FROM bookings b 
        JOIN cars c ON b.car_id = c.id 
        WHERE b.user_id = ? 
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

/**
 * Calculate total price for booking
 * 
 * @param float $pricePerDay Daily rental price
 * @param string $pickupDate Pickup date
 * @param string $dropoffDate Dropoff date
 * @return float Total price
 */
function calculateTotalPrice($pricePerDay, $pickupDate, $dropoffDate) {
    $pickup = new DateTime($pickupDate);
    $dropoff = new DateTime($dropoffDate);
    $days = $pickup->diff($dropoff)->days;
    $days = max(1, $days); // Minimum 1 day
    return $pricePerDay * $days;
}

/**
 * Format price for display
 * 
 * @param float $price Price to format
 * @return string Formatted price
 */
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

/**
 * Get booking status badge class
 * 
 * @param string $status Booking status
 * @return string Bootstrap badge class
 */
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'confirmed':
            return 'bg-success';
        case 'pending':
            return 'bg-warning text-dark';
        case 'completed':
            return 'bg-info';
        case 'cancelled':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

/**
 * Get base URL path
 * 
 * @return string Base path
 */
function getBasePath() {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = dirname($scriptName);
    
    // Check if we're in a subdirectory
    if (strpos($basePath, '/admin') !== false || strpos($basePath, '/auth') !== false || strpos($basePath, '/user') !== false) {
        return dirname($basePath);
    }
    
    return $basePath;
}
?>

