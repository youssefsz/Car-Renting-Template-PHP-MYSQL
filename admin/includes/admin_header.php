<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

// Check if admin is logged in
if (!isLoggedIn() || !isAdmin()) {
    setFlashMessage('error', 'Access denied. Admin login required.');
    redirect('../index.php');
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cental Admin - Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --primary: #EA001E;
            --secondary: #1F2E4E;
        }
        
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f6f9;
        }
        
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: var(--secondary);
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .admin-sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
        }
        
        .admin-sidebar .sidebar-header h3 {
            color: white;
            margin: 0;
        }
        
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 15px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--primary);
        }
        
        .admin-sidebar .nav-link i {
            width: 25px;
        }
        
        .admin-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        
        .table th {
            font-weight: 600;
            background: #f8f9fa;
        }
        
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background: var(--secondary);
            border-color: var(--secondary);
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                margin-left: -250px;
            }
            
            .admin-sidebar.show {
                margin-left: 0;
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-car-alt me-2"></i>Cental Admin</h3>
        </div>
        <nav class="mt-4">
            <a href="dashboard.php" class="nav-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="cars.php" class="nav-link <?php echo $currentPage === 'cars' ? 'active' : ''; ?>">
                <i class="fas fa-car"></i> Manage Cars
            </a>
            <a href="bookings.php" class="nav-link <?php echo $currentPage === 'bookings' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
            <a href="users.php" class="nav-link <?php echo $currentPage === 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
            <hr class="bg-light my-4">
            <a href="../index.php" class="nav-link">
                <i class="fas fa-globe"></i> View Website
            </a>
            <a href="../auth/logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="admin-content">
        <div class="admin-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><?php echo ucfirst($currentPage); ?></h4>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-user text-white"></i>
                </div>
            </div>
        </div>

