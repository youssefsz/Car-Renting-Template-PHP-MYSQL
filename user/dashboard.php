<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Please login to access your dashboard.');
    redirect('../auth/login.php?return=' . urlencode('user/dashboard.php'));
}

$pdo = getDBConnection();

// Get user details
$user = getUserById($pdo, $_SESSION['user_id']);

// Get user bookings
$bookings = getUserBookings($pdo, $_SESSION['user_id']);

// Count bookings by status
$bookingCounts = [
    'total' => count($bookings),
    'pending' => 0,
    'confirmed' => 0,
    'completed' => 0,
    'cancelled' => 0
];

foreach ($bookings as $booking) {
    if (isset($bookingCounts[$booking['status']])) {
        $bookingCounts[$booking['status']]++;
    }
}
?>
<?php include '../includes/header.php'; ?>

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb mb-5">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">My Dashboard</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active text-primary">Dashboard</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- Dashboard Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <?php displayFlashMessage(); ?>
            
            <div class="row g-4">
                <!-- Sidebar / User Info -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded p-4 mb-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white" style="font-size: 36px;"></i>
                            </div>
                            <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted">Phone</small>
                            <p class="mb-0"><?php echo $user['phone'] ? htmlspecialchars($user['phone']) : 'Not provided'; ?></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Member Since</small>
                            <p class="mb-0"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="bg-light rounded p-4">
                        <h5 class="mb-4"><i class="fas fa-chart-bar text-primary me-2"></i>Booking Summary</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-white rounded p-3 text-center">
                                    <h3 class="text-primary mb-1"><?php echo $bookingCounts['total']; ?></h3>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white rounded p-3 text-center">
                                    <h3 class="text-warning mb-1"><?php echo $bookingCounts['pending']; ?></h3>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white rounded p-3 text-center">
                                    <h3 class="text-success mb-1"><?php echo $bookingCounts['confirmed']; ?></h3>
                                    <small class="text-muted">Confirmed</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white rounded p-3 text-center">
                                    <h3 class="text-info mb-1"><?php echo $bookingCounts['completed']; ?></h3>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bookings List -->
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="bg-light rounded p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0"><i class="fas fa-list text-primary me-2"></i>My Bookings</h5>
                            <a href="../vehicles.php" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>New Booking</a>
                        </div>
                        
                        <?php if (empty($bookings)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-car text-muted mb-3" style="font-size: 48px;"></i>
                                <h5>No Bookings Yet</h5>
                                <p class="text-muted mb-4">You haven't made any car bookings yet.</p>
                                <a href="../vehicles.php" class="btn btn-primary"><i class="fas fa-car me-2"></i>Browse Cars</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            <th>Car</th>
                                            <th>Dates</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../<?php echo htmlspecialchars($booking['car_image']); ?>" alt="<?php echo htmlspecialchars($booking['car_name']); ?>" class="rounded me-2" style="width: 60px; height: 40px; object-fit: cover;">
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($booking['car_name']); ?></strong>
                                                            <br><small class="text-muted">#<?php echo $booking['id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small>
                                                        <i class="fas fa-calendar text-primary me-1"></i>
                                                        <?php echo date('M d', strtotime($booking['pickup_date'])); ?> - <?php echo date('M d, Y', strtotime($booking['dropoff_date'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong class="text-primary"><?php echo formatPrice($booking['total_price']); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo getStatusBadgeClass($booking['status']); ?>"><?php echo ucfirst($booking['status']); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->

<?php include '../includes/footer.php'; ?>

