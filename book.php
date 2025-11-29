<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();

// Get car ID from URL
$carId = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;

// If no car ID, redirect to vehicles page
if (!$carId) {
    setFlashMessage('error', 'Please select a car to book.');
    redirect('vehicles.php');
}

// Get car details
$car = getCarById($pdo, $carId);

if (!$car) {
    setFlashMessage('error', 'Car not found.');
    redirect('vehicles.php');
}

// Check if user is logged in
if (!isLoggedIn()) {
    // Store booking data in session and redirect to login
    $_SESSION['pending_booking'] = [
        'car_id' => $carId,
        'pickup_location' => $_GET['pickup_location'] ?? '',
        'dropoff_location' => $_GET['dropoff_location'] ?? '',
        'pickup_date' => $_GET['pickup_date'] ?? '',
        'pickup_time' => $_GET['pickup_time'] ?? '',
        'dropoff_date' => $_GET['dropoff_date'] ?? '',
        'dropoff_time' => $_GET['dropoff_time'] ?? ''
    ];
    setFlashMessage('warning', 'Please login or create an account to book a car.');
    redirect('auth/login.php?return=' . urlencode('../book.php?car_id=' . $carId));
}

// Get any pending booking data from session
$pendingBooking = $_SESSION['pending_booking'] ?? [];
unset($_SESSION['pending_booking']);

$error = '';
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickupLocation = sanitize($_POST['pickup_location'] ?? '');
    $dropoffLocation = sanitize($_POST['dropoff_location'] ?? '');
    $pickupDate = $_POST['pickup_date'] ?? '';
    $pickupTime = $_POST['pickup_time'] ?? '';
    $dropoffDate = $_POST['dropoff_date'] ?? '';
    $dropoffTime = $_POST['dropoff_time'] ?? '';
    
    // Validation
    if (empty($pickupLocation) || empty($dropoffLocation) || empty($pickupDate) || empty($pickupTime) || empty($dropoffDate) || empty($dropoffTime)) {
        $error = 'Please fill in all fields.';
    } elseif (strtotime($dropoffDate) < strtotime($pickupDate)) {
        $error = 'Drop-off date cannot be before pickup date.';
    } else {
        // Calculate total price
        $totalPrice = calculateTotalPrice($car['price_per_day'], $pickupDate, $dropoffDate);
        
        // Insert booking
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, car_id, pickup_location, dropoff_location, pickup_date, pickup_time, dropoff_date, dropoff_time, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        
        if ($stmt->execute([$_SESSION['user_id'], $carId, $pickupLocation, $dropoffLocation, $pickupDate, $pickupTime, $dropoffDate, $dropoffTime, $totalPrice])) {
            $success = true;
            $bookingId = $pdo->lastInsertId();
        } else {
            $error = 'An error occurred while processing your booking. Please try again.';
        }
    }
}

// Pre-fill form data
$pickupLocation = $pendingBooking['pickup_location'] ?? $_GET['pickup_location'] ?? '';
$dropoffLocation = $pendingBooking['dropoff_location'] ?? $_GET['dropoff_location'] ?? '';
$pickupDate = $pendingBooking['pickup_date'] ?? $_GET['pickup_date'] ?? '';
$pickupTime = $pendingBooking['pickup_time'] ?? $_GET['pickup_time'] ?? '12:00';
$dropoffDate = $pendingBooking['dropoff_date'] ?? $_GET['dropoff_date'] ?? '';
$dropoffTime = $pendingBooking['dropoff_time'] ?? $_GET['dropoff_time'] ?? '12:00';
?>
<?php include 'includes/header.php'; ?>

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb mb-5">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Book Your Car</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="vehicles.php">Vehicles</a></li>
                <li class="breadcrumb-item active text-primary">Book</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- Booking Section Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <?php if ($success): ?>
                <!-- Booking Success -->
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center wow fadeInUp" data-wow-delay="0.1s">
                        <div class="bg-light rounded p-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                            </div>
                            <h2 class="text-primary mb-4">Booking Confirmed!</h2>
                            <p class="mb-4">Thank you for your booking. Your reservation has been submitted successfully.</p>
                            <div class="bg-white rounded p-4 mb-4">
                                <h5 class="mb-3">Booking Details</h5>
                                <p class="mb-2"><strong>Booking ID:</strong> #<?php echo $bookingId; ?></p>
                                <p class="mb-2"><strong>Car:</strong> <?php echo htmlspecialchars($car['name']); ?></p>
                                <p class="mb-2"><strong>Pick-up:</strong> <?php echo htmlspecialchars($pickupLocation); ?> on <?php echo date('M d, Y', strtotime($_POST['pickup_date'])); ?> at <?php echo date('h:i A', strtotime($_POST['pickup_time'])); ?></p>
                                <p class="mb-2"><strong>Drop-off:</strong> <?php echo htmlspecialchars($dropoffLocation); ?> on <?php echo date('M d, Y', strtotime($_POST['dropoff_date'])); ?> at <?php echo date('h:i A', strtotime($_POST['dropoff_time'])); ?></p>
                                <p class="mb-0"><strong>Total Price:</strong> <span class="text-primary"><?php echo formatPrice($totalPrice); ?></span></p>
                            </div>
                            <p class="text-muted mb-4">Your booking status is currently <span class="badge bg-warning text-dark">Pending</span>. We will confirm your booking shortly.</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="user/dashboard.php" class="btn btn-primary py-3 px-4"><i class="fas fa-tachometer-alt me-2"></i>View Dashboard</a>
                                <a href="vehicles.php" class="btn btn-secondary py-3 px-4"><i class="fas fa-car me-2"></i>Browse More Cars</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Booking Form -->
                <div class="row g-5">
                    <!-- Car Details -->
                    <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="categories-item p-4">
                            <div class="categories-item-inner">
                                <div class="categories-img rounded-top">
                                    <img src="<?php echo htmlspecialchars($car['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($car['name']); ?>">
                                </div>
                                <div class="categories-content rounded-bottom p-4">
                                    <h4><?php echo htmlspecialchars($car['name']); ?></h4>
                                    <div class="categories-review mb-4">
                                        <div class="me-3">4.5 Review</div>
                                        <div class="d-flex justify-content-center text-secondary">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star text-body"></i>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h4 class="bg-white text-primary rounded-pill py-2 px-4 mb-0"><?php echo formatPrice($car['price_per_day']); ?>/Day</h4>
                                    </div>
                                    <div class="row gy-2 gx-0 text-center mb-4">
                                        <div class="col-4 border-end border-white">
                                            <i class="fa fa-users text-dark"></i> <span class="text-body ms-1"><?php echo $car['seats']; ?> Seat</span>
                                        </div>
                                        <div class="col-4 border-end border-white">
                                            <i class="fa fa-car text-dark"></i> <span class="text-body ms-1"><?php echo $car['transmission']; ?></span>
                                        </div>
                                        <div class="col-4">
                                            <i class="fa fa-gas-pump text-dark"></i> <span class="text-body ms-1"><?php echo $car['fuel_type']; ?></span>
                                        </div>
                                        <div class="col-4 border-end border-white">
                                            <i class="fa fa-car text-dark"></i> <span class="text-body ms-1"><?php echo $car['year']; ?></span>
                                        </div>
                                        <div class="col-4 border-end border-white">
                                            <i class="fa fa-cogs text-dark"></i> <span class="text-body ms-1"><?php echo $car['transmission']; ?></span>
                                        </div>
                                        <div class="col-4">
                                            <i class="fa fa-road text-dark"></i> <span class="text-body ms-1"><?php echo $car['mileage']; ?></span>
                                        </div>
                                    </div>
                                    <?php if (!empty($car['description'])): ?>
                                        <p class="text-muted"><?php echo htmlspecialchars($car['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Form -->
                    <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="bg-secondary rounded p-5">
                            <h4 class="text-white mb-4"><i class="fas fa-calendar-check me-2"></i>Complete Your Reservation</h4>
                            
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <?php displayFlashMessage(); ?>
                            
                            <form method="POST" action="">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="text-white mb-2">Pick-up Location</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-primary"></i></span>
                                            <input type="text" class="form-control" name="pickup_location" placeholder="Enter city or airport" value="<?php echo htmlspecialchars($pickupLocation); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-white mb-2">Drop-off Location</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-primary"></i></span>
                                            <input type="text" class="form-control" name="dropoff_location" placeholder="Enter city or airport" value="<?php echo htmlspecialchars($dropoffLocation); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-white mb-2">Pick-up Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="date" class="form-control" name="pickup_date" value="<?php echo htmlspecialchars($pickupDate); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-white mb-2">Pick-up Time</label>
                                        <select class="form-select" name="pickup_time" required>
                                            <?php for ($h = 8; $h <= 17; $h++): ?>
                                                <option value="<?php echo sprintf('%02d:00', $h); ?>" <?php echo $pickupTime === sprintf('%02d:00', $h) ? 'selected' : ''; ?>><?php echo date('g:i A', strtotime(sprintf('%02d:00', $h))); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-white mb-2">Drop-off Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="date" class="form-control" name="dropoff_date" value="<?php echo htmlspecialchars($dropoffDate); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-white mb-2">Drop-off Time</label>
                                        <select class="form-select" name="dropoff_time" required>
                                            <?php for ($h = 8; $h <= 17; $h++): ?>
                                                <option value="<?php echo sprintf('%02d:00', $h); ?>" <?php echo $dropoffTime === sprintf('%02d:00', $h) ? 'selected' : ''; ?>><?php echo date('g:i A', strtotime(sprintf('%02d:00', $h))); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="bg-white rounded p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Daily Rate:</span>
                                                <span class="text-primary fw-bold"><?php echo formatPrice($car['price_per_day']); ?></span>
                                            </div>
                                            <hr>
                                            <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i>Total will be calculated based on rental duration</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-light w-100 py-3">
                                            <i class="fas fa-check-circle me-2"></i>Confirm Booking
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Booking Section End -->

<?php include 'includes/footer.php'; ?>

