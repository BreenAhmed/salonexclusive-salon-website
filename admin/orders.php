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
    
    if ($action === 'update_status') {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        $query = "UPDATE logomatter_orders SET status=? WHERE id=?";
        $stmt = executeQuery($query, [$status, $id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Bestillingsstatus oppdatert successfully!";
        } else {
            $error_message = "Feil ved å oppdatere status.";
        }
    }
    
    if ($action === 'delete_order') {
        $id = $_POST['id'] ?? 0;
        
        $query = "DELETE FROM logomatter_orders WHERE id=?";
        $stmt = executeQuery($query, [$id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Bestilling slettet successfully!";
        } else {
            $error_message = "Feil ved å slette bestilling.";
        }
    }
}

// Get all orders
$db = getDBConnection();
$stmt = $db->query("SELECT * FROM logomatter_orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();

// Get order statistics
$total_orders = count($orders);
$pending_orders = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
$in_progress_orders = count(array_filter($orders, fn($o) => $o['status'] === 'in_progress'));
$completed_orders = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestillinger - Salon Exclusive Admin</title>
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
        
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .order-details h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .order-details p {
            margin-bottom: 5px;
            color: #6c757d;
        }
        
        .order-details strong {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
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
                        <a class="nav-link" href="blog.php">
                            <i class="fas fa-blog"></i> Blogg
                        </a>
                        <a class="nav-link active" href="orders.php">
                            <i class="fas fa-shopping-cart"></i> Bestillinger
                        </a>
                        <a class="nav-link" href="inquiries.php">
                            <i class="fas fa-envelope"></i> Henvendelser
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
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1><i class="fas fa-shopping-cart"></i> Logomatte Bestillinger</h1>
                        <div>
                            <button class="btn btn-outline-primary" onclick="exportOrders()">
                                <i class="fas fa-download"></i> Eksporter
                            </button>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stats-card success">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <h3><?php echo $total_orders; ?></h3>
                                    <p class="mb-0">Totalt Bestillinger</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h3><?php echo $pending_orders; ?></h3>
                                    <p class="mb-0">Venter</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card info">
                                <div class="card-body text-center">
                                    <i class="fas fa-cog fa-2x mb-2"></i>
                                    <h3><?php echo $in_progress_orders; ?></h3>
                                    <p class="mb-0">Under Arbeid</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3><?php echo $completed_orders; ?></h3>
                                    <p class="mb-0">Fullført</p>
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
                    
                    <!-- Orders Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Alle Bestillinger</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Bedrift</th>
                                            <th>Kontaktperson</th>
                                            <th>E-post</th>
                                            <th>Telefon</th>
                                            <th>Størrelse</th>
                                            <th>Status</th>
                                            <th>Bestilt</th>
                                            <th>Handlinger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($order['company_name']); ?></strong>
                                            </td>
                                            <td>
                                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($order['contact_person']); ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope"></i> 
                                                <a href="mailto:<?php echo htmlspecialchars($order['email']); ?>">
                                                    <?php echo htmlspecialchars($order['email']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <i class="fas fa-phone"></i> 
                                                <a href="tel:<?php echo htmlspecialchars($order['phone']); ?>">
                                                    <?php echo htmlspecialchars($order['phone']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($order['size']); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $order['status'] === 'pending' ? 'warning' : 
                                                        ($order['status'] === 'in_progress' ? 'info' : 
                                                        ($order['status'] === 'completed' ? 'success' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="viewOrder(<?php echo htmlspecialchars(json_encode($order)); ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" 
                                                        onclick="updateStatus(<?php echo $order['id']; ?>, '<?php echo $order['status']; ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteOrder(<?php echo $order['id']; ?>)">
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

    <!-- View Order Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-eye"></i> Bestillingsdetaljer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetails">
                    <!-- Order details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Lukk</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Oppdater Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" id="status_order_id">
                        <div class="mb-3">
                            <label for="status" class="form-label">Ny Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Venter</option>
                                <option value="in_progress">Under Arbeid</option>
                                <option value="completed">Fullført</option>
                                <option value="cancelled">Kansellert</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                        <button type="submit" class="btn btn-primary">Oppdater Status</button>
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
                    <p>Er du sikker på at du vil slette denne bestillingen? Denne handlingen kan ikke angres.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete_order">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-danger">Slett bestilling</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewOrder(order) {
            const details = `
                <div class="order-details">
                    <h6><i class="fas fa-building"></i> Bedriftsinformasjon</h6>
                    <p><strong>Bedriftsnavn:</strong> ${order.company_name}</p>
                    <p><strong>Kontaktperson:</strong> ${order.contact_person}</p>
                    <p><strong>E-post:</strong> <a href="mailto:${order.email}">${order.email}</a></p>
                    <p><strong>Telefon:</strong> <a href="tel:${order.phone}">${order.phone}</a></p>
                </div>
                
                <div class="order-details">
                    <h6><i class="fas fa-ruler"></i> Bestillingsdetaljer</h6>
                    <p><strong>Størrelse:</strong> ${order.size}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${order.status === 'pending' ? 'warning' : (order.status === 'in_progress' ? 'info' : (order.status === 'completed' ? 'success' : 'secondary'))}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></p>
                    <p><strong>Bestilt:</strong> ${new Date(order.created_at).toLocaleDateString('no-NO')} ${new Date(order.created_at).toLocaleTimeString('no-NO')}</p>
                </div>
                
                <div class="order-details">
                    <h6><i class="fas fa-palette"></i> Designbeskrivelse</h6>
                    <p>${order.design_description || 'Ingen beskrivelse oppgitt'}</p>
                </div>
            `;
            
            document.getElementById('orderDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewOrderModal')).show();
        }
        
        function updateStatus(id, currentStatus) {
            document.getElementById('status_order_id').value = id;
            document.getElementById('status').value = currentStatus;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }
        
        function deleteOrder(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
        
        function exportOrders() {
            // Simple CSV export
            const table = document.querySelector('table');
            const rows = Array.from(table.querySelectorAll('tr'));
            const csv = rows.map(row => 
                Array.from(row.querySelectorAll('th, td'))
                    .map(cell => `"${cell.textContent.trim()}"`)
                    .join(',')
            ).join('\n');
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'orders.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
