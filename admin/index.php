<?php
session_start();
require_once '../config/database.php';

// Simple authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Exclusive - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--secondary-color);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: var(--secondary-color);
            color: #fff;
            border-left-color: #fff;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .main-content {
            background: var(--light-bg);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stats-card.purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stats-card.orange {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }
        
        .stats-card.teal {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }
        
        .stats-card .card-body {
            position: relative;
            z-index: 2;
            padding: 2rem;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            transform: rotate(45deg);
        }
        
        .stats-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: rotate(-30deg);
        }
        
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .category-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .category-icon.reborn {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
        }
        
        .category-icon.stas {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        
        .category-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .category-label {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .quick-actions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .quick-actions::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }
        
        .quick-actions::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            transform: rotate(-30deg);
        }
        
        .quick-actions h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }
        
        .action-card {
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            cursor: pointer;
            position: relative;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        
        .action-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            background: rgba(255,255,255,1);
        }
        
        .action-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--secondary-color), #9b59b6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
            transition: all 0.3s ease;
        }
        
        .action-card:hover .action-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 25px rgba(52, 152, 219, 0.4);
        }
        
        .action-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
        }
        
        .action-desc {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.4;
        }
        
        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), #9b59b6);
            border-radius: 16px 16px 0 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .badge {
            border-radius: 20px;
            padding: 8px 12px;
            font-weight: 500;
        }
        
        .activity-item {
            border-left: 3px solid var(--secondary-color);
            padding-left: 15px;
            margin-bottom: 15px;
        }
        
        .activity-item:last-child {
            border-left-color: var(--success-color);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #9b59b6 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .welcome-section h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        /* Mobile Navigation */
        .mobile-navbar {
            display: none;
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .mobile-navbar .navbar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .mobile-menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            z-index: 1040;
            transition: left 0.3s ease;
            overflow-y: auto;
        }
        
        .mobile-sidebar.show {
            left: 0;
        }
        
        .mobile-sidebar .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .mobile-sidebar .sidebar-header h4 {
            color: white;
            margin: 0;
            font-size: 1.2rem;
        }
        
        .mobile-sidebar .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .mobile-sidebar .close-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .mobile-sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
        }
        
        .mobile-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--secondary-color);
        }
        
        .mobile-sidebar .nav-link.active {
            background: var(--secondary-color);
            color: #fff;
            border-left-color: #fff;
        }
        
        .mobile-sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1030;
            display: none;
        }
        
        .mobile-overlay.show {
            display: block;
        }
        
        .mobile-content {
            margin-top: 0;
            transition: margin-top 0.3s ease;
        }
        
        .mobile-content.with-navbar {
            margin-top: 80px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .mobile-content {
                margin-top: 80px;
            }
            
            .welcome-section {
                margin-top: 1rem;
            }
            
            .stats-card .card-body {
                padding: 1.5rem;
            }
            
            .quick-actions {
                padding: 1.5rem;
            }
            
            .action-card {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .mobile-navbar {
                padding: 0.75rem;
            }
            
            .mobile-navbar .navbar-brand {
                font-size: 1rem;
            }
            
            .mobile-sidebar {
                width: 100%;
            }
            
            .stats-card .card-body {
                padding: 1rem;
            }
            
            .quick-actions {
                padding: 1rem;
            }
            
            .action-card {
                padding: 1rem;
            }
            
            .action-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Navbar -->
    <nav class="mobile-navbar">
        <div class="navbar-brand">
            <i class="fas fa-cut"></i> Salon Exclusive
        </div>
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-header">
            <h4>
                <i class="fas fa-cut"></i> Salon Exclusive
            </h4>
            <button class="close-btn" onclick="closeMobileSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link" href="products.php">
                <i class="fas fa-box"></i> Produkter
            </a>
            <a class="nav-link" href="blog.php">
                <i class="fas fa-blog"></i> Blogg
            </a>
            <a class="nav-link" href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> Se nettsted
            </a>
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logg ut
            </a>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Desktop Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0 d-none d-md-block">
                <div class="p-3">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-cut"></i> Salon Exclusive
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box"></i> Produkter
                        </a>
                        <a class="nav-link" href="blog.php">
                            <i class="fas fa-blog"></i> Blogg
                        </a>
                        <a class="nav-link" href="../index.php" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Se nettsted
                        </a>
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logg ut
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content mobile-content">
                <div class="p-4">
                    <!-- Welcome Section -->
                    <div class="welcome-section">
                        <h1><i class="fas fa-tachometer-alt"></i> Velkommen til Admin Panel</h1>
                        <p>Administrer din salong nettsted med enkle verktøy og oversikt over all aktivitet.</p>
                    </div>
                    
                    <!-- Main Stats Cards -->
                    <div class="row mb-5">
                        <div class="col-lg-6 col-md-6 mb-4">
                            <div class="card stats-card success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <i class="fas fa-box stats-icon"></i>
                                            <div class="stats-number"><?php
                                                $db = getDBConnection();
                                                $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'");
                                                echo $stmt->fetch()['count'];
                                            ?></div>
                                            <div class="stats-label">Aktive Produkter</div>
                                        </div>
                                        <div class="ms-3">
                                            <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-4">
                            <div class="card stats-card info">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <i class="fas fa-blog stats-icon"></i>
                                            <div class="stats-number"><?php
                                                $stmt = $db->query("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
                                                echo $stmt->fetch()['count'];
                                            ?></div>
                                            <div class="stats-label">Publiserte Artikler</div>
                                        </div>
                                        <div class="ms-3">
                                            <i class="fas fa-edit fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Cards -->
                    <div class="row mb-5">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="category-card">
                                <div class="category-icon reborn">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div class="category-number"><?php
                                    $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE category = 'reborn'");
                                    echo $stmt->fetch()['count'];
                                ?></div>
                                <div class="category-label">Re-born Produkter</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="category-card">
                                <div class="category-icon stas">
                                    <i class="fas fa-spray-can"></i>
                                </div>
                                <div class="category-number"><?php
                                    $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE category = 'stas'");
                                    echo $stmt->fetch()['count'];
                                ?></div>
                                <div class="category-label">STAS Produkter</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="quick-actions">
                                <h4><i class="fas fa-rocket"></i> Hurtighandlinger</h4>
                                <div class="row g-4">
                                    <div class="col-lg-4 col-md-6">
                                        <a href="products.php" class="text-decoration-none">
                                            <div class="action-card">
                                                <div class="action-icon">
                                                    <i class="fas fa-plus"></i>
                                                </div>
                                                <div class="action-title">Legg til Produkt</div>
                                                <div class="action-desc">Opprett nytt produkt i systemet</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <a href="blog.php" class="text-decoration-none">
                                            <div class="action-card">
                                                <div class="action-icon">
                                                    <i class="fas fa-pen-fancy"></i>
                                                </div>
                                                <div class="action-title">Skriv Blogg</div>
                                                <div class="action-desc">Opprett ny bloggpost</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <a href="../index.php" target="_blank" class="text-decoration-none">
                                            <div class="action-card">
                                                <div class="action-icon">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                                <div class="action-title">Se Nettsted</div>
                                                <div class="action-desc">Forhåndsvisning av nettstedet</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar functionality
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileOverlay');
            const content = document.querySelector('.mobile-content');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            content.classList.toggle('with-navbar');
        }
        
        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileOverlay');
            const content = document.querySelector('.mobile-content');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            content.classList.remove('with-navbar');
        }
        
        // Close sidebar when clicking on nav links
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.mobile-sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', closeMobileSidebar);
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
            }
        });
    </script>
</body>
</html>
