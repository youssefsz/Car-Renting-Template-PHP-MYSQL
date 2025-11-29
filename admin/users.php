<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pdo = getDBConnection();

// Get all users
$stmt = $pdo->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM bookings WHERE user_id = u.id) as booking_count,
           (SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE user_id = u.id AND status IN ('confirmed', 'completed')) as total_spent
    FROM users u 
    WHERE u.role = 'user'
    ORDER BY u.created_at DESC
");
$users = $stmt->fetchAll();

$totalUsers = count($users);

require_once 'includes/admin_header.php';
?>

<?php displayFlashMessage(); ?>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $totalUsers; ?></h3>
                    <span class="text-muted">Total Users</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users List -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-users me-2"></i>Registered Users
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <p class="text-muted text-center py-4">No users found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Bookings</th>
                            <th>Total Spent</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                            <span class="text-white fw-bold"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
                                        </div>
                                        <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo $user['phone'] ? htmlspecialchars($user['phone']) : '<span class="text-muted">-</span>'; ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $user['booking_count']; ?> bookings</span>
                                </td>
                                <td>
                                    <strong class="text-success"><?php echo formatPrice($user['total_spent']); ?></strong>
                                </td>
                                <td>
                                    <small><?php echo date('M d, Y', strtotime($user['created_at'])); ?></small>
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

