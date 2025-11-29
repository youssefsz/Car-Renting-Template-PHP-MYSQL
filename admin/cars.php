<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$pdo = getDBConnection();

$action = $_GET['action'] ?? 'list';
$carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $pricePerDay = (float)($_POST['price_per_day'] ?? 0);
    $seats = (int)($_POST['seats'] ?? 4);
    $transmission = $_POST['transmission'] ?? 'AUTO';
    $fuelType = $_POST['fuel_type'] ?? 'Petrol';
    $year = (int)($_POST['year'] ?? date('Y'));
    $mileage = sanitize($_POST['mileage'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'available';
    
    // Handle image upload
    $image = '';
    $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $uploadDir = '../img/';
    
    // Check if file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        
        // Validate file size
        if ($fileSize > $maxFileSize) {
            $error = 'Image size must be less than 2MB.';
        }
        // Validate file type
        elseif (!in_array($fileType, $allowedTypes)) {
            $error = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
        }
        // Validate file extension
        elseif (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $error = 'Invalid file extension. Only JPG, JPEG, PNG, GIF, and WebP files are allowed.';
        }
        // Validate that it's actually an image
        elseif (!getimagesize($fileTmpName)) {
            $error = 'File is not a valid image.';
        }
        else {
            // Generate unique filename
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = 'car-' . uniqid() . '-' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;
            
            // Create upload directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                $image = 'img/' . $newFileName;
                
                // Delete old image if editing
                if ($action === 'edit' && $carId) {
                    $oldCar = getCarById($pdo, $carId);
                    if ($oldCar && !empty($oldCar['image']) && file_exists('../' . $oldCar['image'])) {
                        @unlink('../' . $oldCar['image']);
                    }
                }
            } else {
                $error = 'Failed to upload image. Please try again.';
            }
        }
    } elseif ($action === 'edit' && $carId) {
        // If editing and no new file uploaded, keep existing image
        $existingCar = getCarById($pdo, $carId);
        $image = $existingCar['image'] ?? '';
    } elseif ($action === 'add') {
        // For new cars, image is required
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Please upload a car image.';
        }
    }
    
    if (empty($error)) {
        if (empty($name) || ($action === 'add' && empty($image)) || $pricePerDay <= 0) {
            $error = 'Please fill in all required fields.';
        } else {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO cars (name, image, price_per_day, seats, transmission, fuel_type, year, mileage, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $image, $pricePerDay, $seats, $transmission, $fuelType, $year, $mileage, $description, $status])) {
                    setFlashMessage('success', 'Car added successfully.');
                    redirect('cars.php');
                } else {
                    $error = 'Failed to add car.';
                }
            } elseif ($action === 'edit' && $carId) {
                $stmt = $pdo->prepare("UPDATE cars SET name = ?, image = ?, price_per_day = ?, seats = ?, transmission = ?, fuel_type = ?, year = ?, mileage = ?, description = ?, status = ? WHERE id = ?");
                if ($stmt->execute([$name, $image, $pricePerDay, $seats, $transmission, $fuelType, $year, $mileage, $description, $status, $carId])) {
                    setFlashMessage('success', 'Car updated successfully.');
                    redirect('cars.php');
                } else {
                    $error = 'Failed to update car.';
                }
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && $carId) {
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    if ($stmt->execute([$carId])) {
        setFlashMessage('success', 'Car deleted successfully.');
    } else {
        setFlashMessage('error', 'Failed to delete car.');
    }
    redirect('cars.php');
}

// Get car for editing
$car = null;
if ($action === 'edit' && $carId) {
    $car = getCarById($pdo, $carId);
    if (!$car) {
        setFlashMessage('error', 'Car not found.');
        redirect('cars.php');
    }
}

// Get all cars for listing
$cars = getCars($pdo);

require_once 'includes/admin_header.php';
?>

<?php displayFlashMessage(); ?>

<?php if ($action === 'list'): ?>
    <!-- Cars List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-car me-2"></i>All Cars</span>
            <a href="?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add New Car</a>
        </div>
        <div class="card-body">
            <?php if (empty($cars)): ?>
                <p class="text-muted text-center py-4">No cars found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price/Day</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cars as $c): ?>
                                <tr>
                                    <td>
                                        <img src="../<?php echo htmlspecialchars($c['image']); ?>" alt="<?php echo htmlspecialchars($c['name']); ?>" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
                                    <td><?php echo formatPrice($c['price_per_day']); ?></td>
                                    <td>
                                        <small>
                                            <?php echo $c['seats']; ?> Seats | <?php echo $c['transmission']; ?> | <?php echo $c['fuel_type']; ?> | <?php echo $c['year']; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'bg-success';
                                        if ($c['status'] === 'rented') $statusClass = 'bg-warning';
                                        if ($c['status'] === 'maintenance') $statusClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($c['status']); ?></span>
                                    </td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=delete&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this car?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <!-- Add/Edit Car Form -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-<?php echo $action === 'add' ? 'plus' : 'edit'; ?> me-2"></i>
            <?php echo $action === 'add' ? 'Add New Car' : 'Edit Car'; ?>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Car Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?php echo $car ? htmlspecialchars($car['name']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Car Image <?php echo $action === 'add' ? '<span class="text-danger">*</span>' : ''; ?></label>
                        <input type="file" class="form-control" name="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" <?php echo $action === 'add' ? 'required' : ''; ?>>
                        <small class="text-muted">Maximum file size: 2MB. Allowed formats: JPG, PNG, GIF, WebP</small>
                        <?php if ($action === 'edit' && $car && !empty($car['image'])): ?>
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Current image:</small>
                                <img src="../<?php echo htmlspecialchars($car['image']); ?>" alt="Current car image" class="rounded" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                <small class="text-muted d-block mt-1">Leave empty to keep current image</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Price Per Day ($) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price_per_day" step="0.01" min="0" value="<?php echo $car ? $car['price_per_day'] : ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Seats</label>
                        <select class="form-select" name="seats">
                            <?php for ($i = 2; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($car && $car['seats'] == $i) ? 'selected' : ($i == 4 && !$car ? 'selected' : ''); ?>><?php echo $i; ?> Seats</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Year</label>
                        <input type="number" class="form-control" name="year" min="2000" max="<?php echo date('Y') + 1; ?>" value="<?php echo $car ? $car['year'] : date('Y'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Transmission</label>
                        <select class="form-select" name="transmission">
                            <option value="AUTO" <?php echo ($car && $car['transmission'] === 'AUTO') ? 'selected' : ''; ?>>Automatic</option>
                            <option value="MANUAL" <?php echo ($car && $car['transmission'] === 'MANUAL') ? 'selected' : ''; ?>>Manual</option>
                            <option value="AT/MT" <?php echo ($car && $car['transmission'] === 'AT/MT') ? 'selected' : ''; ?>>AT/MT</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fuel Type</label>
                        <select class="form-select" name="fuel_type">
                            <option value="Petrol" <?php echo ($car && $car['fuel_type'] === 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
                            <option value="Diesel" <?php echo ($car && $car['fuel_type'] === 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Electric" <?php echo ($car && $car['fuel_type'] === 'Electric') ? 'selected' : ''; ?>>Electric</option>
                            <option value="Hybrid" <?php echo ($car && $car['fuel_type'] === 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="available" <?php echo ($car && $car['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
                            <option value="rented" <?php echo ($car && $car['status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
                            <option value="maintenance" <?php echo ($car && $car['status'] === 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mileage</label>
                        <input type="text" class="form-control" name="mileage" placeholder="e.g., 27K" value="<?php echo $car ? htmlspecialchars($car['mileage']) : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"><?php echo $car ? htmlspecialchars($car['description']) : ''; ?></textarea>
                    </div>
                    <div class="col-12">
                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i><?php echo $action === 'add' ? 'Add Car' : 'Update Car'; ?>
                        </button>
                        <a href="cars.php" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/admin_footer.php'; ?>

