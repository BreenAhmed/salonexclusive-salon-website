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
    
    if ($action === 'add_post') {
        try {
        $title = $_POST['title'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';
        $author = $_POST['author'] ?? 'Børre';
        $status = $_POST['status'] ?? 'draft';
        $published_at = $status === 'published' ? date('Y-m-d H:i:s') : null;
        
        // Handle featured image upload
        $featured_image = 'images/work-1.jpg'; // Default image
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/blog/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $file_name = uniqid() . '_' . time() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $file_path)) {
                    $featured_image = 'uploads/blog/' . $file_name;
                }
            }
        }
        
        $query = "INSERT INTO blog_posts (title, slug, content, excerpt, featured_image, author, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = executeQuery($query, [$title, $slug, $content, $excerpt, $featured_image, $author, $status, $published_at]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Blogginnlegg lagt til successfully!";
        } else {
            $error_message = "Feil ved å legge til blogginnlegg.";
        }
        } catch (Exception $e) {
            $error_message = "Feil ved å legge til blogginnlegg: " . $e->getMessage();
            error_log("Blog creation error: " . $e->getMessage());
        }
    }
    
    if ($action === 'update_post') {
        try {
        $id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';
        $author = $_POST['author'] ?? 'Børre';
        $status = $_POST['status'] ?? 'draft';
        $published_at = $status === 'published' ? date('Y-m-d H:i:s') : null;
        
        // Get current featured image
        $current_query = "SELECT featured_image FROM blog_posts WHERE id = ?";
        $current_stmt = executeQuery($current_query, [$id]);
        $current_post = $current_stmt->fetch();
        $featured_image = $current_post['featured_image'] ?? 'images/work-1.jpg';
        
        // Handle featured image upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/blog/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $file_name = uniqid() . '_' . time() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $file_path)) {
                    // Delete old image if it's not a default image
                    if ($featured_image && !str_starts_with($featured_image, 'images/work-')) {
                        $old_file_path = '../' . $featured_image;
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                    $featured_image = 'uploads/blog/' . $file_name;
                }
            }
        }
        
        $query = "UPDATE blog_posts SET title=?, slug=?, content=?, excerpt=?, featured_image=?, author=?, status=?, published_at=? WHERE id=?";
        $stmt = executeQuery($query, [$title, $slug, $content, $excerpt, $featured_image, $author, $status, $published_at, $id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Blogginnlegg oppdatert successfully!";
        } else {
            $error_message = "Feil ved å oppdatere blogginnlegg.";
        }
        } catch (Exception $e) {
            $error_message = "Feil ved å oppdatere blogginnlegg: " . $e->getMessage();
            error_log("Blog update error: " . $e->getMessage());
        }
    }
    
    if ($action === 'delete_post') {
        $id = $_POST['id'] ?? 0;
        
        $query = "DELETE FROM blog_posts WHERE id=?";
        $stmt = executeQuery($query, [$id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Blogginnlegg slettet successfully!";
        } else {
            $error_message = "Feil ved å slette blogginnlegg.";
        }
    }
}

// Get all blog posts
$db = getDBConnection();
$stmt = $db->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogg - Salon Exclusive Admin</title>
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
        
        .post-image {
            width: 80px;
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
        
        .post-preview {
            max-height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .post-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(transparent, white);
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
            <a class="nav-link" href="products.php">
                <i class="fas fa-box"></i> Produkter
            </a>
            <a class="nav-link active" href="blog.php">
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
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box"></i> Produkter
                        </a>
                        <a class="nav-link active" href="blog.php">
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
                        <h1><i class="fas fa-blog"></i> Blogginnlegg</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPostModal">
                            <i class="fas fa-plus"></i> Nytt innlegg
                        </button>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stats-card success">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                                    <h3><?php echo count($posts); ?></h3>
                                    <p class="mb-0">Totalt Innlegg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card info">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3><?php echo count(array_filter($posts, fn($p) => $p['status'] === 'published')); ?></h3>
                                    <p class="mb-0">Publiserte</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-edit fa-2x mb-2"></i>
                                    <h3><?php echo count(array_filter($posts, fn($p) => $p['status'] === 'draft')); ?></h3>
                                    <p class="mb-0">Kladder</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-user fa-2x mb-2"></i>
                                    <h3><?php echo count(array_unique(array_column($posts, 'author'))); ?></h3>
                                    <p class="mb-0">Forfattere</p>
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
                    
                    <!-- Blog Posts Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Alle Blogginnlegg</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Bilde</th>
                                            <th>Tittel</th>
                                            <th>Forfatter</th>
                                            <th>Status</th>
                                            <th>Publisert</th>
                                            <th>Opprettet</th>
                                            <th>Handlinger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td>
                                                <?php if ($post['featured_image']): ?>
                                                    <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                                         class="post-image" 
                                                         alt="<?php echo htmlspecialchars($post['title']); ?>">
                                                <?php else: ?>
                                                    <div class="post-image bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($post['title']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 50)) . '...'; ?></small>
                                            </td>
                                            <td>
                                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $post['status'] === 'published' ? 'success' : ($post['status'] === 'draft' ? 'warning' : 'secondary'); ?>">
                                                    <?php echo ucfirst($post['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($post['published_at']): ?>
                                                    <?php echo date('d.m.Y H:i', strtotime($post['published_at'])); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Ikke publisert</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="editPost(<?php echo htmlspecialchars(json_encode($post)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="../blog-single.php?slug=<?php echo $post['slug']; ?>" 
                                                   class="btn btn-sm btn-outline-info" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deletePost(<?php echo $post['id']; ?>)">
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

    <!-- Add Post Modal -->
    <div class="modal fade" id="addPostModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Nytt blogginnlegg</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_post">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Tittel</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft">Kladd</option>
                                        <option value="published">Publisert</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="author" class="form-label">Forfatter</label>
                                    <input type="text" class="form-control" id="author" name="author" value="Børre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Fremhevet bilde</label>
                                    <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                                    <div class="form-text">Støttede formater: JPG, PNG, GIF, WebP</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Utdrag (kort beskrivelse)</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="2" required></textarea>
                        </div>
                        
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Innhold</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                            <div class="form-text">Tips: Du kan kopiere HTML-koden fra opplastede media og lime den inn i innholdet.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                        <button type="submit" class="btn btn-primary">Lagre innlegg</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div class="modal fade" id="editPostModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Rediger blogginnlegg</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_post">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="edit_title" class="form-label">Tittel</label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="draft">Kladd</option>
                                        <option value="published">Publisert</option>
                                        <option value="archived">Arkivert</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_author" class="form-label">Forfatter</label>
                                    <input type="text" class="form-control" id="edit_author" name="author" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_featured_image" class="form-label">Fremhevet bilde</label>
                                    <input type="file" class="form-control" id="edit_featured_image" name="featured_image" accept="image/*">
                                    <div class="form-text">Støttede formater: JPG, PNG, GIF, WebP</div>
                                    <div id="current_image_preview" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_excerpt" class="form-label">Utdrag (kort beskrivelse)</label>
                            <textarea class="form-control" id="edit_excerpt" name="excerpt" rows="2" required></textarea>
                        </div>
                        
                        
                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Innhold</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="10" required></textarea>
                            <div class="form-text">Tips: Du kan kopiere HTML-koden fra opplastede media og lime den inn i innholdet.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                        <button type="submit" class="btn btn-primary">Oppdater innlegg</button>
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
                    <p>Er du sikker på at du vil slette dette blogginnlegget? Denne handlingen kan ikke angres.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete_post">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-danger">Slett innlegg</button>
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
        function editPost(post) {
            document.getElementById('edit_id').value = post.id;
            document.getElementById('edit_title').value = post.title;
            document.getElementById('edit_author').value = post.author;
            document.getElementById('edit_status').value = post.status;
            document.getElementById('edit_excerpt').value = post.excerpt;
            document.getElementById('edit_content').value = post.content;
            
            // Clear file input and show current image
            document.getElementById('edit_featured_image').value = '';
            const currentImagePreview = document.getElementById('current_image_preview');
            if (post.featured_image) {
                currentImagePreview.innerHTML = `
                    <small class="text-muted">Nåværende bilde:</small><br>
                    <img src="../${post.featured_image}" alt="Current image" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 4px;">
                `;
            } else {
                currentImagePreview.innerHTML = '<small class="text-muted">Ingen bilde valgt</small>';
            }
            
            
            new bootstrap.Modal(document.getElementById('editPostModal')).show();
        }
        
        function deletePost(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
        
    </script>
</body>
</html>
