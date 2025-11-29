<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();
$cars = getCars($pdo, 'available');
?>
<?php include 'includes/header.php'; ?>

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb mb-5">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Our Vehicles</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">Vehicles</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- Car categories Start -->
    <div class="container-fluid categories py-5">
        <div class="container">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Our <span class="text-primary">Vehicles</span></h1>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut amet nemo expedita asperiores commodi accusantium at cum harum, excepturi, quia tempora cupiditate! Adipisci facilis modi quisquam quia distinctio,
                </p>
            </div>
            
            <?php displayFlashMessage(); ?>
            
            <div class="row g-4">
                <?php foreach ($cars as $car): ?>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
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
                                <a href="book.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary rounded-pill d-flex justify-content-center py-3">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($cars)): ?>
            <div class="text-center py-5">
                <h4>No vehicles available at the moment</h4>
                <p>Please check back later.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Car categories End -->

<?php include 'includes/footer.php'; ?>

