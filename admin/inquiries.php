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
        
        $query = "UPDATE contact_inquiries SET status=? WHERE id=?";
        $stmt = executeQuery($query, [$status, $id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Henvendelsesstatus oppdatert successfully!";
        } else {
            $error_message = "Feil ved å oppdatere status.";
        }
    }
    
    if ($action === 'delete_inquiry') {
        $id = $_POST['id'] ?? 0;
        
        $query = "DELETE FROM contact_inquiries WHERE id=?";
        $stmt = executeQuery($query, [$id]);
        
        if ($stmt->rowCount() > 0) {
            $success_message = "Henvendelse slettet successfully!";
        } else {
            $error_message = "Feil ved å slette henvendelse.";
        }
    }
}

// Get all inquiries
$db = getDBConnection();
$stmt = $db->query("SELECT * FROM contact_inquiries ORDER BY created_at DESC");
$inquiries = $stmt->fetchAll();

// Get inquiry statistics
$total_inquiries = count($inquiries);
$new_inquiries = count(array_filter($inquiries, fn($i) => $i['status'] === 'new'));
$read_inquiries = count(array_filter($inquiries, fn($i) => $i['status'] === 'read'));
$replied_inquiries = count(array_filter($inquiries, fn($i) => $i['status'] === 'replied'));
$closed_inquiries = count(array_filter($inquiries, fn($i) => $i['status'] === 'closed'));
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Henvendelser - Salon Exclusive Admin</title>
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
        
        .stats-card.danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }
        
        .inquiry-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .inquiry-details h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .inquiry-details p {
            margin-bottom: 5px;
            color: #6c757d;
        }
        
        .inquiry-details strong {
            color: var(--primary-color);
        }
        
        .message-content {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid var(--secondary-color);
            margin-top: 10px;
        }
        
        .new-inquiry {
            border-left: 4px solid var(--danger-color);
        }
        
        .read-inquiry {
            border-left: 4px solid var(--warning-color);
        }
        
        .replied-inquiry {
            border-left: 4px solid var(--success-color);
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
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart"></i> Bestillinger
                        </a>
                        <a class="nav-link active" href="inquiries.php">
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
                        <h1><i class="fas fa-envelope"></i> Kontakt Henvendelser</h1>
                        <div>
                            <button class="btn btn-outline-primary" onclick="exportInquiries()">
                                <i class="fas fa-download"></i> Eksporter
                            </button>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stats-card success">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-2x mb-2"></i>
                                    <h3><?php echo $total_inquiries; ?></h3>
                                    <p class="mb-0">Totalt Henvendelser</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card danger">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                    <h3><?php echo $new_inquiries; ?></h3>
                                    <p class="mb-0">Nye</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-eye fa-2x mb-2"></i>
                                    <h3><?php echo $read_inquiries; ?></h3>
                                    <p class="mb-0">Leste</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card info">
                                <div class="card-body text-center">
                                    <i class="fas fa-reply fa-2x mb-2"></i>
                                    <h3><?php echo $replied_inquiries; ?></h3>
                                    <p class="mb-0">Besvart</p>
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
                    
                    <!-- Inquiries Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Alle Henvendelser</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Navn</th>
                                            <th>E-post</th>
                                            <th>Telefon</th>
                                            <th>Emne</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Mottatt</th>
                                            <th>Handlinger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($inquiries as $inquiry): ?>
                                        <tr class="<?php echo $inquiry['status'] === 'new' ? 'new-inquiry' : ($inquiry['status'] === 'read' ? 'read-inquiry' : ($inquiry['status'] === 'replied' ? 'replied-inquiry' : '')); ?>">
                                            <td>
                                                <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope"></i> 
                                                <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>">
                                                    <?php echo htmlspecialchars($inquiry['email']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($inquiry['phone']): ?>
                                                    <i class="fas fa-phone"></i> 
                                                    <a href="tel:<?php echo htmlspecialchars($inquiry['phone']); ?>">
                                                        <?php echo htmlspecialchars($inquiry['phone']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Ikke oppgitt</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($inquiry['subject']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $inquiry['inquiry_type'] === 'general' ? 'info' : 
                                                        ($inquiry['inquiry_type'] === 'reborn' ? 'success' : 
                                                        ($inquiry['inquiry_type'] === 'stas' ? 'primary' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($inquiry['inquiry_type']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $inquiry['status'] === 'new' ? 'danger' : 
                                                        ($inquiry['status'] === 'read' ? 'warning' : 
                                                        ($inquiry['status'] === 'replied' ? 'success' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($inquiry['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo date('d.m.Y H:i', strtotime($inquiry['created_at'])); ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="viewInquiry(<?php echo htmlspecialchars(json_encode($inquiry)); ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" 
                                                        onclick="updateStatus(<?php echo $inquiry['id']; ?>, '<?php echo $inquiry['status']; ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>?subject=Re: <?php echo urlencode($inquiry['subject']); ?>" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-reply"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteInquiry(<?php echo $inquiry['id']; ?>)">
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

    <!-- View Inquiry Modal -->
    <div class="modal fade" id="viewInquiryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-eye"></i> Henvendelsesdetaljer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="inquiryDetails">
                    <!-- Inquiry details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Lukk</button>
                    <a href="#" id="replyLink" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Svar
                    </a>
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
                        <input type="hidden" name="id" id="status_inquiry_id">
                        <div class="mb-3">
                            <label for="status" class="form-label">Ny Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="new">Ny</option>
                                <option value="read">Lest</option>
                                <option value="replied">Besvart</option>
                                <option value="closed">Lukket</option>
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
                    <p>Er du sikker på at du vil slette denne henvendelsen? Denne handlingen kan ikke angres.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete_inquiry">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-danger">Slett henvendelse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewInquiry(inquiry) {
            const details = `
                <div class="inquiry-details">
                    <h6><i class="fas fa-user"></i> Kontaktinformasjon</h6>
                    <p><strong>Navn:</strong> ${inquiry.name}</p>
                    <p><strong>E-post:</strong> <a href="mailto:${inquiry.email}">${inquiry.email}</a></p>
                    <p><strong>Telefon:</strong> ${inquiry.phone || 'Ikke oppgitt'}</p>
                    <p><strong>Type:</strong> <span class="badge bg-${inquiry.inquiry_type === 'general' ? 'info' : (inquiry.inquiry_type === 'reborn' ? 'success' : 'primary')}">${inquiry.inquiry_type.charAt(0).toUpperCase() + inquiry.inquiry_type.slice(1)}</span></p>
                </div>
                
                <div class="inquiry-details">
                    <h6><i class="fas fa-info-circle"></i> Henvendelsesdetaljer</h6>
                    <p><strong>Emne:</strong> ${inquiry.subject}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${inquiry.status === 'new' ? 'danger' : (inquiry.status === 'read' ? 'warning' : (inquiry.status === 'replied' ? 'success' : 'secondary'))}">${inquiry.status.charAt(0).toUpperCase() + inquiry.status.slice(1)}</span></p>
                    <p><strong>Mottatt:</strong> ${new Date(inquiry.created_at).toLocaleDateString('no-NO')} ${new Date(inquiry.created_at).toLocaleTimeString('no-NO')}</p>
                </div>
                
                <div class="message-content">
                    <h6><i class="fas fa-comment"></i> Melding</h6>
                    <p>${inquiry.message.replace(/\n/g, '<br>')}</p>
                </div>
            `;
            
            document.getElementById('inquiryDetails').innerHTML = details;
            document.getElementById('replyLink').href = `mailto:${inquiry.email}?subject=Re: ${encodeURIComponent(inquiry.subject)}`;
            new bootstrap.Modal(document.getElementById('viewInquiryModal')).show();
        }
        
        function updateStatus(id, currentStatus) {
            document.getElementById('status_inquiry_id').value = id;
            document.getElementById('status').value = currentStatus;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }
        
        function deleteInquiry(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
        
        function exportInquiries() {
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
            a.download = 'contact_inquiries.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
