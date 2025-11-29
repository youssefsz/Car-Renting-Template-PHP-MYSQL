<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pdo = getDBConnection();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $bookingId = (int)$_POST['booking_id'];
    $status = $_POST['status'];
    
    $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (in_array($status, $validStatuses)) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $bookingId])) {
            setFlashMessage('success', 'Booking status updated successfully.');
        } else {
            setFlashMessage('error', 'Failed to update booking status.');
        }
    }
    redirect('bookings.php');
}

// Filter by status
$statusFilter = $_GET['status'] ?? '';

// Get bookings
$query = "
    SELECT b.*, u.name as user_name, u.email as user_email, c.name as car_name, c.image as car_image 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN cars c ON b.car_id = c.id
";

if ($statusFilter && in_array($statusFilter, ['pending', 'confirmed', 'completed', 'cancelled'])) {
    $query .= " WHERE b.status = ?";
    $stmt = $pdo->prepare($query . " ORDER BY b.created_at DESC");
    $stmt->execute([$statusFilter]);
} else {
    $stmt = $pdo->query($query . " ORDER BY b.created_at DESC");
}

$bookings = $stmt->fetchAll();

// Get counts
$statusCounts = [
    'all' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn(),
    'confirmed' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn(),
    'completed' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'completed'")->fetchColumn(),
    'cancelled' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled'")->fetchColumn(),
];

require_once 'includes/admin_header.php';
?>

<!-- Custom styles for bookings table -->
<style>
    /* Make the bookings card container wider */
    .bookings-card-container {
        width: 100%;
        max-width: 100%;
        overflow: visible !important;
    }
    
    .bookings-card-container .card-body {
        overflow: visible !important;
        padding: 0 !important;
    }
    
    /* Table container - responsive, no horizontal scroll */
    .bookings-table-wrapper {
        width: 100%;
        overflow: visible;
        position: relative;
    }
    
    .bookings-table {
        width: 100%;
        table-layout: auto;
    }
    .bookings-table th,
    .bookings-table td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        word-wrap: break-word;
    }
    .bookings-table th {
        white-space: nowrap;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
        background-color: #f8f9fa;
    }
    .bookings-table .col-id { 
        width: 60px;
        min-width: 60px;
    }
    .bookings-table .col-customer { 
        min-width: 150px;
        max-width: 200px;
    }
    .bookings-table .col-car { 
        min-width: 180px;
        max-width: 250px;
    }
    .bookings-table .col-dates { 
        min-width: 140px;
        max-width: 180px;
    }
    .bookings-table .col-location { 
        min-width: 160px;
        max-width: 220px;
    }
    .bookings-table .col-total { 
        width: 100px;
        min-width: 100px;
    }
    .bookings-table .col-status { 
        width: 120px;
        min-width: 120px;
    }
    .bookings-table .col-actions { 
        width: 140px;
        min-width: 140px;
    }
    
    /* Ensure dropdowns can overflow */
    .bookings-table td {
        position: relative;
    }
    
    .bookings-table .dropdown {
        position: static !important;
    }
    
    .bookings-table .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        margin-top: 0.125rem;
        z-index: 1050;
    }
    
    .car-thumbnail {
        width: 100px;
        height: 65px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .customer-info .customer-name {
        font-weight: 600;
        color: #1F2E4E;
        margin-bottom: 4px;
        font-size: 0.95rem;
    }
    .customer-info .customer-email {
        font-size: 0.8rem;
        color: #6c757d;
        word-break: break-word;
    }
    
    .location-info {
        font-size: 0.9rem;
        line-height: 1.8;
    }
    .location-info .pickup-loc,
    .location-info .dropoff-loc {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 4px;
    }
    .location-info .pickup-loc i { color: #28a745; }
    .location-info .dropoff-loc i { color: #dc3545; }
    
    .date-range {
        font-size: 0.9rem;
        line-height: 1.7;
    }
    .date-range .date-start,
    .date-range .date-end {
        display: block;
        margin-bottom: 4px;
    }
    .date-range .date-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #999;
        letter-spacing: 0.5px;
        margin-right: 4px;
    }
    
    .price-display {
        font-size: 1.1rem;
        font-weight: 700;
        color: #EA001E;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 50px;
    }
    
    .dropdown-menu form {
        margin: 0;
    }
    .dropdown-menu .dropdown-item {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }
    
    .filter-tabs .btn {
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 50px;
        font-size: 0.9rem;
    }
    
    .car-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .car-info .car-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1400px) {
        .bookings-table th,
        .bookings-table td {
            padding: 1rem 0.75rem;
        }
        .car-thumbnail {
            width: 80px;
            height: 55px;
        }
    }
    
    @media (max-width: 1200px) {
        .bookings-table .col-customer { 
            min-width: 120px;
            max-width: 160px;
        }
        .bookings-table .col-car { 
            min-width: 150px;
            max-width: 200px;
        }
        .bookings-table .col-location { 
            min-width: 140px;
            max-width: 180px;
        }
    }
</style>

<?php displayFlashMessage(); ?>

<!-- Filter Tabs -->
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap gap-2 filter-tabs">
            <a href="bookings.php" class="btn <?php echo !$statusFilter ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                <i class="fas fa-list me-1"></i> All (<?php echo $statusCounts['all']; ?>)
            </a>
            <a href="?status=pending" class="btn <?php echo $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                <i class="fas fa-clock me-1"></i> Pending (<?php echo $statusCounts['pending']; ?>)
            </a>
            <a href="?status=confirmed" class="btn <?php echo $statusFilter === 'confirmed' ? 'btn-success' : 'btn-outline-success'; ?>">
                <i class="fas fa-check me-1"></i> Confirmed (<?php echo $statusCounts['confirmed']; ?>)
            </a>
            <a href="?status=completed" class="btn <?php echo $statusFilter === 'completed' ? 'btn-info' : 'btn-outline-info'; ?>">
                <i class="fas fa-check-double me-1"></i> Completed (<?php echo $statusCounts['completed']; ?>)
            </a>
            <a href="?status=cancelled" class="btn <?php echo $statusFilter === 'cancelled' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                <i class="fas fa-times me-1"></i> Cancelled (<?php echo $statusCounts['cancelled']; ?>)
            </a>
        </div>
    </div>
</div>

<!-- Bookings List -->
<div class="card bookings-card-container">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <span><i class="fas fa-calendar-check me-2"></i>Bookings Management</span>
        <span class="badge bg-secondary"><?php echo count($bookings); ?> record(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($bookings)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">No bookings found.</p>
            </div>
        <?php else: ?>
            <div class="bookings-table-wrapper">
                <table class="table table-hover bookings-table mb-0">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-customer">Customer</th>
                            <th class="col-car">Vehicle</th>
                            <th class="col-dates">Rental Period</th>
                            <th class="col-location">Locations</th>
                            <th class="col-total">Total</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td class="col-id">
                                    <span class="fw-bold text-secondary">#<?php echo $booking['id']; ?></span>
                                </td>
                                <td class="col-customer">
                                    <div class="customer-info">
                                        <div class="customer-name"><?php echo htmlspecialchars($booking['user_name']); ?></div>
                                        <div class="customer-email"><?php echo htmlspecialchars($booking['user_email']); ?></div>
                                    </div>
                                </td>
                                <td class="col-car">
                                    <div class="car-info">
                                        <img src="../<?php echo htmlspecialchars($booking['car_image']); ?>" alt="<?php echo htmlspecialchars($booking['car_name']); ?>" class="car-thumbnail">
                                        <span class="car-name"><?php echo htmlspecialchars($booking['car_name']); ?></span>
                                    </div>
                                </td>
                                <td class="col-dates">
                                    <div class="date-range">
                                        <span class="date-start">
                                            <span class="date-label">From:</span>
                                            <strong><?php echo date('M d, Y', strtotime($booking['pickup_date'])); ?></strong>
                                        </span>
                                        <span class="date-end">
                                            <span class="date-label">To:</span>
                                            <strong><?php echo date('M d, Y', strtotime($booking['dropoff_date'])); ?></strong>
                                        </span>
                                    </div>
                                </td>
                                <td class="col-location">
                                    <div class="location-info">
                                        <div class="pickup-loc">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo htmlspecialchars($booking['pickup_location']); ?></span>
                                        </div>
                                        <div class="dropoff-loc">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo htmlspecialchars($booking['dropoff_location']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-total">
                                    <span class="price-display"><?php echo formatPrice($booking['total_price']); ?></span>
                                </td>
                                <td class="col-status">
                                    <span class="badge status-badge <?php echo getStatusBadgeClass($booking['status']); ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-edit me-1"></i> Update
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="position: absolute; right: 0; left: auto;">
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="dropdown-item <?php echo $booking['status'] === 'pending' ? 'active bg-warning text-dark' : ''; ?>">
                                                        <i class="fas fa-clock me-2 text-warning"></i>Pending
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="dropdown-item <?php echo $booking['status'] === 'confirmed' ? 'active bg-success text-white' : ''; ?>">
                                                        <i class="fas fa-check me-2 text-success"></i>Confirmed
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item <?php echo $booking['status'] === 'completed' ? 'active bg-info text-white' : ''; ?>">
                                                        <i class="fas fa-check-double me-2 text-info"></i>Completed
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item text-danger <?php echo $booking['status'] === 'cancelled' ? 'active bg-danger text-white' : ''; ?>">
                                                        <i class="fas fa-times me-2"></i>Cancelled
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>

