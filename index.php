<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();
$cars = getCars($pdo, 'available');
?>
<?php include 'includes/header.php'; ?>

    <!-- Carousel Start -->
    <div class="header-carousel mb-5">
        <div id="carouselId" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <ol class="carousel-indicators">
                <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active" aria-current="true" aria-label="First slide"></li>
                <li data-bs-target="#carouselId" data-bs-slide-to="1" aria-label="Second slide"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item active">
                    <img src="img/carousel-2.jpg" class="img-fluid w-100" alt="First slide"/>
                    <div class="carousel-caption">
                        <div class="container py-4">
                            <div class="row g-5">
                                <div class="col-lg-6 fadeInLeft animated" data-animation="fadeInLeft" data-delay="1s" style="animation-delay: 1s;">
                                    <div class="bg-secondary rounded p-5">
                                        <h4 class="text-white mb-4">CONTINUE CAR RESERVATION</h4>
                                        <form action="book.php" method="GET">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <select name="car_id" class="form-select" required>
                                                        <option value="">Select Your Car type</option>
                                                        <?php foreach ($cars as $car): ?>
                                                            <option value="<?php echo $car['id']; ?>"><?php echo htmlspecialchars($car['name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-map-marker-alt"></span> <span class="ms-1">Pick Up</span>
                                                        </div>
                                                        <input name="pickup_location" class="form-control" type="text" placeholder="Enter a City or Airport" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <a href="#" class="text-start text-white d-block mb-2">Need a different drop-off location?</a>
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-map-marker-alt"></span><span class="ms-1">Drop off</span>
                                                        </div>
                                                        <input name="dropoff_location" class="form-control" type="text" placeholder="Enter a City or Airport" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-calendar-alt"></span><span class="ms-1">Pick Up</span>
                                                        </div>
                                                        <input name="pickup_date" class="form-control" type="date" required>
                                                        <select name="pickup_time" class="form-select ms-3" required>
                                                            <option value="08:00">8:00 AM</option>
                                                            <option value="09:00">9:00 AM</option>
                                                            <option value="10:00">10:00 AM</option>
                                                            <option value="11:00">11:00 AM</option>
                                                            <option value="12:00" selected>12:00 PM</option>
                                                            <option value="13:00">1:00 PM</option>
                                                            <option value="14:00">2:00 PM</option>
                                                            <option value="15:00">3:00 PM</option>
                                                            <option value="16:00">4:00 PM</option>
                                                            <option value="17:00">5:00 PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-calendar-alt"></span><span class="ms-1">Drop off</span>
                                                        </div>
                                                        <input name="dropoff_date" class="form-control" type="date" required>
                                                        <select name="dropoff_time" class="form-select ms-3" required>
                                                            <option value="08:00">8:00 AM</option>
                                                            <option value="09:00">9:00 AM</option>
                                                            <option value="10:00">10:00 AM</option>
                                                            <option value="11:00">11:00 AM</option>
                                                            <option value="12:00" selected>12:00 PM</option>
                                                            <option value="13:00">1:00 PM</option>
                                                            <option value="14:00">2:00 PM</option>
                                                            <option value="15:00">3:00 PM</option>
                                                            <option value="16:00">4:00 PM</option>
                                                            <option value="17:00">5:00 PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-light w-100 py-2">Book Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6 d-none d-lg-flex fadeInRight animated" data-animation="fadeInRight" data-delay="1s" style="animation-delay: 1s;">
                                    <div class="text-start">
                                        <h1 class="display-5 text-white">Get 15% off your rental Plan your trip now</h1>
                                        <p>Treat yourself in USA</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/carousel-1.jpg" class="img-fluid w-100" alt="First slide"/>
                    <div class="carousel-caption">
                        <div class="container py-4">
                            <div class="row g-5">
                                <div class="col-lg-6 fadeInLeft animated" data-animation="fadeInLeft" data-delay="1s" style="animation-delay: 1s;">
                                    <div class="bg-secondary rounded p-5">
                                        <h4 class="text-white mb-4">CONTINUE CAR RESERVATION</h4>
                                        <form action="book.php" method="GET">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <select name="car_id" class="form-select" required>
                                                        <option value="">Select Your Car type</option>
                                                        <?php foreach ($cars as $car): ?>
                                                            <option value="<?php echo $car['id']; ?>"><?php echo htmlspecialchars($car['name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-map-marker-alt"></span><span class="ms-1">Pick Up</span>
                                                        </div>
                                                        <input name="pickup_location" class="form-control" type="text" placeholder="Enter a City or Airport" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <a href="#" class="text-start text-white d-block mb-2">Need a different drop-off location?</a>
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-map-marker-alt"></span><span class="ms-1">Drop off</span>
                                                        </div>
                                                        <input name="dropoff_location" class="form-control" type="text" placeholder="Enter a City or Airport" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-calendar-alt"></span><span class="ms-1">Pick Up</span>
                                                        </div>
                                                        <input name="pickup_date" class="form-control" type="date" required>
                                                        <select name="pickup_time" class="form-select ms-3" required>
                                                            <option value="08:00">8:00 AM</option>
                                                            <option value="09:00">9:00 AM</option>
                                                            <option value="10:00">10:00 AM</option>
                                                            <option value="11:00">11:00 AM</option>
                                                            <option value="12:00" selected>12:00 PM</option>
                                                            <option value="13:00">1:00 PM</option>
                                                            <option value="14:00">2:00 PM</option>
                                                            <option value="15:00">3:00 PM</option>
                                                            <option value="16:00">4:00 PM</option>
                                                            <option value="17:00">5:00 PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <div class="d-flex align-items-center bg-light text-body rounded-start p-2">
                                                            <span class="fas fa-calendar-alt"></span><span class="ms-1">Drop off</span>
                                                        </div>
                                                        <input name="dropoff_date" class="form-control" type="date" required>
                                                        <select name="dropoff_time" class="form-select ms-3" required>
                                                            <option value="08:00">8:00 AM</option>
                                                            <option value="09:00">9:00 AM</option>
                                                            <option value="10:00">10:00 AM</option>
                                                            <option value="11:00">11:00 AM</option>
                                                            <option value="12:00" selected>12:00 PM</option>
                                                            <option value="13:00">1:00 PM</option>
                                                            <option value="14:00">2:00 PM</option>
                                                            <option value="15:00">3:00 PM</option>
                                                            <option value="16:00">4:00 PM</option>
                                                            <option value="17:00">5:00 PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-light w-100 py-2">Book Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6 d-none d-lg-flex fadeInRight animated" data-animation="fadeInRight" data-delay="1s" style="animation-delay: 1s;">
                                    <div class="text-start">
                                        <h1 class="display-5 text-white">Get 15% off your rental! Choose Your Model </h1>
                                        <p>Treat yourself in USA</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- About Start -->
    <div class="container-fluid overflow-hidden about py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="about-item">
                        <div class="pb-5">
                            <h1 class="display-5 text-capitalize">Cental <span class="text-primary">About</span></h1>
                            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut amet nemo expedita asperiores commodi accusantium at cum harum, excepturi, quia tempora cupiditate! Adipisci facilis modi quisquam quia distinctio,
                            </p>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="about-item-inner border p-4">
                                    <div class="about-icon mb-4">
                                        <img src="img/about-icon-1.png" class="img-fluid w-50 h-50" alt="Icon">
                                    </div>
                                    <h5 class="mb-3">Our Vision</h5>
                                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="about-item-inner border p-4">
                                    <div class="about-icon mb-4">
                                        <img src="img/about-icon-2.png" class="img-fluid h-50 w-50" alt="Icon">
                                    </div>
                                    <h5 class="mb-3">Our Mision</h5>
                                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-item my-4">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae, aliquam ipsum. Sed suscipit dolorem libero sequi aut natus debitis reprehenderit facilis quaerat similique, est at in eum. Quo, obcaecati in!
                        </p>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="text-center rounded bg-secondary p-4">
                                    <h1 class="display-6 text-white">17</h1>
                                    <h5 class="text-light mb-0">Years Of Experience</h5>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="rounded">
                                    <p class="mb-2"><i class="fa fa-check-circle text-primary me-1"></i> Morbi tristique senectus</p>
                                    <p class="mb-2"><i class="fa fa-check-circle text-primary me-1"></i> A scelerisque purus</p>
                                    <p class="mb-2"><i class="fa fa-check-circle text-primary me-1"></i> Dictumst vestibulum</p>
                                    <p class="mb-0"><i class="fa fa-check-circle text-primary me-1"></i> dio aenean sed adipiscing</p>
                                </div>
                            </div>
                            <div class="col-lg-5 d-flex align-items-center">
                                <a href="about.php" class="btn btn-primary rounded py-3 px-5">More About Us</a>
                            </div>
                            <div class="col-lg-7">
                                <div class="d-flex align-items-center">
                                    <img src="img/attachment-img.jpg" class="img-fluid rounded-circle border border-4 border-secondary" style="width: 100px; height: 100px;" alt="Image">
                                    <div class="ms-4">
                                        <h4>William Burgess</h4>
                                        <p class="mb-0">Carveo Founder</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="about-img">
                        <div class="img-1">
                            <img src="img/about-img.jpg" class="img-fluid rounded h-100 w-100" alt="">
                        </div>
                        <div class="img-2">
                            <img src="img/about-img-1.jpg" class="img-fluid rounded w-100" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Car categories Start -->
    <div class="container-fluid categories py-5">
        <div class="container">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Our <span class="text-primary">Vehicles</span></h1>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut amet nemo expedita asperiores commodi accusantium at cum harum, excepturi, quia tempora cupiditate! Adipisci facilis modi quisquam quia distinctio,
                </p>
            </div>
            <div class="categories-carousel owl-carousel wow fadeInUp" data-wow-delay="0.1s">
                <?php foreach ($cars as $car): ?>
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
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Car categories End -->

    <!-- Team Start -->
    <div class="container-fluid team py-5">
        <div class="container">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Customer<span class="text-primary"> Suport</span> Center</h1>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut amet nemo expedita asperiores commodi accusantium at cum harum, excepturi, quia tempora cupiditate! Adipisci facilis modi quisquam quia distinctio,
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item p-4 pt-0">
                        <div class="team-img">
                            <img src="img/team-1.jpg" class="img-fluid rounded w-100" alt="Image">
                        </div>
                        <div class="team-content pt-4">
                            <h4>MARTIN DOE</h4>
                            <p>Profession</p>
                            <div class="team-icon d-flex justify-content-center">
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item p-4 pt-0">
                        <div class="team-img">
                            <img src="img/team-2.jpg" class="img-fluid rounded w-100" alt="Image">
                        </div>
                        <div class="team-content pt-4">
                            <h4>MARTIN DOE</h4>
                            <p>Profession</p>
                            <div class="team-icon d-flex justify-content-center">
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item p-4 pt-0">
                        <div class="team-img">
                            <img src="img/team-3.jpg" class="img-fluid rounded w-100" alt="Image">
                        </div>
                        <div class="team-content pt-4">
                            <h4>MARTIN DOE</h4>
                            <p>Profession</p>
                            <div class="team-icon d-flex justify-content-center">
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="team-item p-4 pt-0">
                        <div class="team-img">
                            <img src="img/team-4.jpg" class="img-fluid rounded w-100" alt="Image">
                        </div>
                        <div class="team-content pt-4">
                            <h4>MARTIN DOE</h4>
                            <p>Profession</p>
                            <div class="team-icon d-flex justify-content-center">
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-square btn-light rounded-circle mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <!-- Blog Start -->
    <div class="container-fluid blog py-5">
        <div class="container">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Cental<span class="text-primary"> Blog & News</span></h1>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut amet nemo expedita asperiores commodi accusantium at cum harum, excepturi, quia tempora cupiditate! Adipisci facilis modi quisquam quia distinctio,
                </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="img/blog-1.jpg" class="img-fluid rounded-top w-100" alt="Image">
                        </div>
                        <div class="blog-content rounded-bottom p-4">
                            <div class="blog-date">30 Dec 2025</div>
                            <div class="blog-comment my-3">
                                <div class="small"><span class="fa fa-user text-primary"></span><span class="ms-2">Martin.C</span></div>
                                <div class="small"><span class="fa fa-comment-alt text-primary"></span><span class="ms-2">6 Comments</span></div>
                            </div>
                            <a href="#" class="h4 d-block mb-3">Rental Cars how to check driving fines?</a>
                            <p class="mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius libero soluta impedit eligendi? Quibusdam, laudantium.</p>
                            <a href="#" class="">Read More  <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="img/blog-2.jpg" class="img-fluid rounded-top w-100" alt="Image">
                        </div>
                        <div class="blog-content rounded-bottom p-4">
                            <div class="blog-date">25 Dec 2025</div>
                            <div class="blog-comment my-3">
                                <div class="small"><span class="fa fa-user text-primary"></span><span class="ms-2">Martin.C</span></div>
                                <div class="small"><span class="fa fa-comment-alt text-primary"></span><span class="ms-2">6 Comments</span></div>
                            </div>
                            <a href="#" class="h4 d-block mb-3">Rental cost of sport and other cars</a>
                            <p class="mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius libero soluta impedit eligendi? Quibusdam, laudantium.</p>
                            <a href="#" class="">Read More  <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="img/blog-3.jpg" class="img-fluid rounded-top w-100" alt="Image">
                        </div>
                        <div class="blog-content rounded-bottom p-4">
                            <div class="blog-date">27 Dec 2025</div>
                            <div class="blog-comment my-3">
                                <div class="small"><span class="fa fa-user text-primary"></span><span class="ms-2">Martin.C</span></div>
                                <div class="small"><span class="fa fa-comment-alt text-primary"></span><span class="ms-2">6 Comments</span></div>
                            </div>
                            <a href="#" class="h4 d-block mb-3">Document required for car rental</a>
                            <p class="mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius libero soluta impedit eligendi? Quibusdam, laudantium.</p>
                            <a href="#" class="">Read More  <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog End -->

<?php include 'includes/footer.php'; ?>

