<?php
require_once __DIR__ . '/functions.php';

// Determine base path for links based on directory depth
$basePath = '';
$scriptPath = $_SERVER['SCRIPT_NAME'];
$depth = substr_count(str_replace('\\', '/', dirname($scriptPath)), '/');
$rootDepth = substr_count(str_replace('\\', '/', dirname($_SERVER['DOCUMENT_ROOT'] . $scriptPath)), '/');

// Check if we're in a subdirectory (auth, user, admin)
$currentDir = dirname($scriptPath);
if (preg_match('/(\/admin|\/auth|\/user|\\\\admin|\\\\auth|\\\\user)/', $currentDir)) {
    $basePath = '..';
}

// Get current page for active nav highlighting
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cental - Car Rent</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo $basePath ? $basePath . '/' : ''; ?>lib/animate/animate.min.css" rel="stylesheet">
    <link href="<?php echo $basePath ? $basePath . '/' : ''; ?>lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo $basePath ? $basePath . '/' : ''; ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link href="<?php echo $basePath ? $basePath . '/' : ''; ?>css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid topbar bg-secondary d-none d-xl-block w-100">
        <div class="container">
            <div class="row gx-0 align-items-center" style="height: 45px;">
                <div class="col-lg-6 text-center text-lg-start mb-lg-0">
                    <div class="d-flex flex-wrap">
                        <a href="#" class="text-muted me-4"><i class="fas fa-map-marker-alt text-primary me-2"></i>Find A Location</a>
                        <a href="tel:+01234567890" class="text-muted me-4"><i class="fas fa-phone-alt text-primary me-2"></i>+01234567890</a>
                        <a href="mailto:example@gmail.com" class="text-muted me-0"><i class="fas fa-envelope text-primary me-2"></i>Example@gmail.com</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-0"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar sticky-top px-0 px-lg-4 py-2 py-lg-0">
        <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light">
                <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>index.php" class="navbar-brand p-0">
                    <h1 class="display-6 text-primary"><i class="fas fa-car-alt me-3"></i>Cental</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>index.php" class="nav-item nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a>
                        <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>about.php" class="nav-item nav-link <?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a>
                        <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>vehicles.php" class="nav-item nav-link <?php echo $currentPage === 'vehicles' ? 'active' : ''; ?>">Vehicles</a>
                        <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>team.php" class="nav-item nav-link <?php echo $currentPage === 'team' ? 'active' : ''; ?>">Team</a>
                        <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>blog.php" class="nav-item nav-link <?php echo $currentPage === 'blog' ? 'active' : ''; ?>">Blog</a>
                        <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>contact.php" class="nav-item nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <?php if (isLoggedIn()): ?>
                            <div class="dropdown">
                                <a class="btn btn-primary rounded-pill py-2 px-4 dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?php echo $basePath ? $basePath . '/' : ''; ?>user/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo $basePath ? $basePath . '/' : ''; ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo $basePath ? $basePath . '/' : ''; ?>auth/login.php" class="btn btn-primary rounded-pill py-2 px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar & Hero End -->

