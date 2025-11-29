<?php
require_once '../config/database.php';

$pdo = getDBConnection();

// Get statistics
$totalCars = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pendingBookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();

// Get recent bookings
$recentBookings = $pdo->query("
    SELECT b.*, u.name as user_name, c.name as car_name 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN cars c ON b.car_id = c.id 
    ORDER BY b.created_at DESC 
    LIMIT 5
")->fetchAll();

// Get total revenue
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE status IN ('confirmed', 'completed')")->fetchColumn();

require_once 'includes/admin_header.php';
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-car"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $totalCars; ?></h3>
                    <span class="text-muted">Total Cars</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $totalUsers; ?></h3>
                    <span class="text-muted">Total Users</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $totalBookings; ?></h3>
                    <span class="text-muted">Total Bookings</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon bg-info bg-opacity-10 text-info me-3">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo formatPrice($totalRevenue); ?></h3>
                    <span class="text-muted">Revenue</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-calendar-check me-2"></i>Recent Bookings</span>
                <a href="bookings.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recentBookings)): ?>
                    <p class="text-muted text-center py-4">No bookings yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Car</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['id']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                        <td><?php echo formatPrice($booking['total_price']); ?></td>
                                        <td><span class="badge <?php echo getStatusBadgeClass($booking['status']); ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Quick Actions
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="cars.php?action=add" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Car
                    </a>
                    <a href="bookings.php" class="btn btn-outline-warning">
                        <i class="fas fa-clock me-2"></i>Pending Bookings (<?php echo $pendingBookings; ?>)
                    </a>
                    <a href="users.php" class="btn btn-outline-secondary">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                </div>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>System Info
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
                <p class="mb-2"><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></p>
                <p class="mb-0"><strong>Date:</strong> <?php echo date('M d, Y H:i'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>

