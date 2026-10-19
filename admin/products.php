<?php
session_start();
require_once '../config/database.php';

// Authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_product') {
        $name = trim($_POST['name'] ?? '');
        $description = $_POST['description'] ?? '';
        $category = $_POST['category'] ?? '';
		$featured = isset($_POST['featured']) ? 1 : 0;
        
        // Validate product name length
        if (strlen($name) > 50) {
            $error_message = "Produktnavn kan ikke være lengre enn 50 tegn.";
        }

        
        // Handle file upload
        $image = '';
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/products/';
            $filename = time() . '_' . basename($_FILES['product_image']['name']);
            $uploadPath = $uploadDir . $filename;
            
            // Ensure upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['product_image']['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    $image = 'uploads/products/' . $filename;
                } else {
                    $error_message = "Feil ved å laste opp bilde.";
                }
            } else {
                $error_message = "Ugyldig filtype. Kun bilder (JPEG, PNG, GIF, WebP) er tillatt.";
            }
        } else {
            // Use default image if no upload
            $image = 'images/stylist-1.jpg';
        }
        
		if (!isset($error_message)) {
			$query = "INSERT INTO products (name, description, category, image, featured) VALUES (?, ?, ?, ?, ?)";
			$stmt = executeQuery($query, [$name, $description, $category, $image, $featured]);
            
            if ($stmt->rowCount() > 0) {
                $success_message = "Produkt lagt til successfully!";
            } else {
                $error_message = "Feil ved å legge til produkt.";
            }
        }
    }
    
    if ($action === 'update_product') {
		$id = $_POST['id'] ?? 0;
		$name = trim($_POST['name'] ?? '');
		$description = $_POST['description'] ?? '';
		$category = $_POST['category'] ?? '';
		$featured = isset($_POST['featured']) ? 1 : 0;
		$status = $_POST['status'] ?? 'active';
        
        // Validate product name length
        if (strlen($name) > 50) {
            $error_message = "Produktnavn kan ikke være lengre enn 50 tegn.";
        }
        
		// Handle file upload for update
        $image = $_POST['current_image'] ?? ''; // Keep current image by default
        
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/products/';
            $filename = time() . '_' . basename($_FILES['product_image']['name']);
            $uploadPath = $uploadDir . $filename;
            
            // Ensure upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['product_image']['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    // Delete old image if it's not a default image
                    $oldImage = $_POST['current_image'] ?? '';
                    if ($oldImage && !str_starts_with($oldImage, 'images/') && file_exists('../' . $oldImage)) {
                        unlink('../' . $oldImage);
                    }
                    $image = 'uploads/products/' . $filename;
                } else {
                    $error_message = "Feil ved å laste opp bilde.";
                }
            } else {
                $error_message = "Ugyldig filtype. Kun bilder (JPEG, PNG, GIF, WebP) er tillatt.";
            }
        }
        
		if (!isset($error_message)) {
			$query = "UPDATE products SET name=?, description=?, category=?, image=?, featured=?, status=? WHERE id=?";
			$stmt = executeQuery($query, [$name, $description, $category, $image, $featured, $status, $id]);
            
            if ($stmt->rowCount() > 0) {
                $success_message = "Produkt oppdatert successfully!";
            } else {
                $error_message = "Feil ved å oppdatere produkt.";
            }
        }
    }
    
    if ($action === 'delete_product') {
        $id = $_POST['id'] ?? 0;
        
        $query = "DELETE FROM products WHERE id=?";
        $stmt = executeQuery($query, [$id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Produkt slettet successfully!";
        } else {
            $error_message = "Feil ved å slette produkt.";
        }
    }
}

// Get all products
$db = getDBConnection();
$stmt = $db->query("SELECT * FROM products ORDER BY category, name");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkter - Salon Exclusive Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #9b59b6 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            overflow: hidden;
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
        
        /* File Upload Styles */
        .file-upload-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-input {
            position: absolute;
            left: -9999px;
        }
        
        .file-upload-label {
            display: block;
            padding: 10px 15px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background: #e9ecef;
            border-color: var(--secondary-color);
        }
        
        .file-upload-label.has-file {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .image-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .upload-progress {
            display: none;
            margin-top: 10px;
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
            <a class="nav-link" href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link active" href="products.php">
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
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link active" href="products.php">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1><i class="fas fa-box"></i> Produkter</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Legg til produkt
                        </button>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stats-card success">
                                <div class="card-body text-center">
                                    <i class="fas fa-box fa-2x mb-2"></i>
                                    <h3><?php echo count($products); ?></h3>
                                    <p class="mb-0">Totalt Produkter</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card info">
                                <div class="card-body text-center">
                                    <i class="fas fa-star fa-2x mb-2"></i>
                                    <h3><?php echo count(array_filter($products, fn($p) => $p['featured'])); ?></h3>
                                    <p class="mb-0">Fremhevede</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3><?php echo count(array_filter($products, fn($p) => $p['status'] === 'active')); ?></h3>
                                    <p class="mb-0">Aktive</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-tags fa-2x mb-2"></i>
                                    <h3><?php echo count(array_unique(array_column($products, 'category'))); ?></h3>
                                    <p class="mb-0">Kategorier</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages -->
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Products Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Alle Produkter</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Bilde</th>
                                            <th>Navn</th>
                                            <th>Kategori</th>
                                            
                                            <th>Status</th>
                                            <th>Fremhevet</th>
                                            <th>Handlinger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <img src="../<?php echo htmlspecialchars($product['image']); ?>" 
                                                     class="product-image" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $product['category'] === 'reborn' ? 'success' : ($product['category'] === 'stas' ? 'info' : 'warning'); ?>">
                                                    <?php echo ucfirst($product['category']); ?>
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <span class="badge bg-<?php echo $product['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($product['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($product['featured']): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-star text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Legg til nytt produkt</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_product">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Produktnavn (maks 50 tegn)</label>
                                    <input type="text" class="form-control" id="name" name="name" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Velg kategori</option>
                                        <option value="reborn">Re-born</option>
                                        <option value="stas">STAS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Produktbilde</label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" class="file-upload-input" id="product_image" name="product_image" accept="image/*">
                                        <label for="product_image" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt"></i><br>
                                            <span class="upload-text">Klikk for å laste opp bilde</span>
                                        </label>
                                        <img id="product_image_preview" class="image-preview" style="display: none;">
                                    </div>
                                    <small class="form-text text-muted">Kun bilder (JPEG, PNG, GIF, WebP) opptil 5MB</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Beskrivelse</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured">
                                <label class="form-check-label" for="featured">
                                    Fremhev dette produktet
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                        <button type="submit" class="btn btn-primary">Legg til produkt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Rediger produkt</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_product">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Produktnavn (maks 50 tegn)</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" maxlength="50" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_category" class="form-label">Kategori</label>
                                    <select class="form-select" id="edit_category" name="category" required>
                                        <option value="reborn">Re-born</option>
                                        <option value="stas">STAS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Produktbilde</label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" class="file-upload-input" id="edit_product_image" name="product_image" accept="image/*">
                                        <label for="edit_product_image" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt"></i><br>
                                            <span class="upload-text">Klikk for å laste opp nytt bilde</span>
                                        </label>
                                        <img id="edit_product_image_preview" class="image-preview" style="display: none;">
                                        <div id="current_image_display" class="mt-2"></div>
                                    </div>
                                    <small class="form-text text-muted">La stå tom for å beholde nåværende bilde</small>
                                    <input type="hidden" id="edit_current_image" name="current_image">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="active">Aktiv</option>
                                        <option value="inactive">Inaktiv</option>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="edit_featured" name="featured">
                                        <label class="form-check-label" for="edit_featured">
                                            Fremhev dette produktet
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Beskrivelse</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                        <button type="submit" class="btn btn-primary">Oppdater produkt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Bekreft sletting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Er du sikker på at du vil slette dette produktet? Denne handlingen kan ikke angres.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete_product">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-danger">Slett produkt</button>
                    </form>
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
    <script>
        function editProduct(product) {
            document.getElementById('edit_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
			document.getElementById('edit_category').value = product.category;
            
            document.getElementById('edit_current_image').value = product.image;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_status').value = product.status;
            document.getElementById('edit_featured').checked = product.featured == 1;
            
            // Clear the file input and preview
            document.getElementById('edit_product_image').value = '';
            document.getElementById('edit_product_image_preview').style.display = 'none';
            
            // Show current image
            const currentImageDisplay = document.getElementById('current_image_display');
            currentImageDisplay.innerHTML = `
                <small class="text-muted">Nåværende bilde:</small><br>
                <img src="../${product.image}" class="image-preview" style="display: block;">
            `;
            
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }
        
        function deleteProduct(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
        
        // File upload preview functionality
        function setupFileUpload(inputId, previewId, labelId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const label = document.querySelector(`label[for="${inputId}"]`);
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Update label
                    label.classList.add('has-file');
                    label.querySelector('.upload-text').textContent = file.name;
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Reset label
                    label.classList.remove('has-file');
                    label.querySelector('.upload-text').textContent = 'Klikk for å laste opp bilde';
                    preview.style.display = 'none';
                }
            });
        }
        
        // Initialize file uploads when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setupFileUpload('product_image', 'product_image_preview', 'product_image');
            setupFileUpload('edit_product_image', 'edit_product_image_preview', 'edit_product_image');
        });
    </script>
</body>
</html>