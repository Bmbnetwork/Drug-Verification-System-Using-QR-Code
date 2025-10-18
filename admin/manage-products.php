<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

requireLogin();
requireAdmin();

// Handle product approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];
    $rejection_reason = trim($_POST['rejection_reason'] ?? '');
    
    // Get product and user details
    $stmt = $pdo->prepare("SELECT p.*, u.email FROM products p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if ($product) {
        if ($action === 'accept') {
            // Generate QR code with complete product information
            $qr_path = generateQRCode($product, "drug_" . $product['id']);
            
            // Update product status and QR code path
            $stmt = $pdo->prepare("UPDATE products SET status = 'accepted', qr_code_path = ? WHERE id = ?");
            $stmt->execute([$qr_path, $product_id]);
            
            // Send email notification
            $subject = "Drug Registration Approved";
            $message = "Your drug registration for '{$product['drug_name']}' (Batch: {$product['batch_number']}) has been approved. A QR code has been generated for verification.";
            sendEmail($product['email'], $subject, $message);
            
            $success = "Product approved successfully!";
        } elseif ($action === 'reject') {
            // Update product status and add rejection reason
            $stmt = $pdo->prepare("UPDATE products SET status = 'rejected', description = ? WHERE id = ?");
            $stmt->execute([$rejection_reason, $product_id]);
            
            // Send email notification
            $subject = "Drug Registration Rejected";
            $message = "Your drug registration for '{$product['drug_name']}' (Batch: {$product['batch_number']}) has been rejected.";
            if (!empty($rejection_reason)) {
                $message .= " Reason: " . $rejection_reason;
            }
            sendEmail($product['email'], $subject, $message);
            
            $success = "Product rejected successfully!";
        }
    }
}

// Get all products
$stmt = $pdo->prepare("SELECT p.*, u.username FROM products p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!-- Rest of your HTML remains the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Drug Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .rejection-form {
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">Drug Management System</div>
                <ul class="nav-links">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage-products.php">Manage Products</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="card-title">Manage Products</h2>
                <select id="status-filter" class="form-control" style="width: auto;">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            
            <?php if (isset($success)): ?>
                <div style="background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($products)): ?>
                <div class="card">
                    <p>No products registered yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-status="<?php echo $product['status']; ?>">
                        <div class="product-header">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['drug_name']); ?></h3>
                            <span class="status-badge status-<?php echo $product['status']; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                        </div>
                        
                        <div class="product-info">
                            <div class="info-item">
                                <span class="info-label">Type</span>
                                <span class="info-value"><?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $product['drug_type']))); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Company</span>
                                <span class="info-value"><?php echo htmlspecialchars($product['company']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Manufacturer</span>
                                <span class="info-value"><?php echo htmlspecialchars($product['manufacturer']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Batch Number</span>
                                <span class="info-value"><?php echo htmlspecialchars($product['batch_number']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Expiry Date</span>
                                <span class="info-value"><?php echo htmlspecialchars($product['expiry_date']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Registered By</span>
                                <span class="info-value"><?php echo htmlspecialchars($product['username']); ?></span>
                            </div>
                        </div>
                        
                        <?php if ($product['status'] === 'accepted' && $product['qr_code_path']): ?>
                            <div class="qr-container">
                                <h4>QR Code</h4>
                                <img src="../<?php echo htmlspecialchars($product['qr_code_path']); ?>" alt="QR Code">
                            </div>
                        <?php elseif ($product['status'] === 'rejected'): ?>
                            <div style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-top: 15px;">
                                <strong>Rejection Reason:</strong> 
                                <?php echo !empty($product['description']) ? htmlspecialchars($product['description']) : 'No reason provided'; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($product['status'] === 'pending'): ?>
                            <div class="action-buttons">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="btn btn-success">Accept</button>
                                </form>
                                
                                <button class="btn btn-danger" onclick="showRejectionForm(<?php echo $product['id']; ?>)">Reject</button>
                                
                                <div id="rejection-form-<?php echo $product['id']; ?>" class="rejection-form">
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <textarea name="rejection_reason" class="form-control" placeholder="Rejection reason (optional)" rows="2"></textarea>
                                        <button type="submit" class="btn btn-danger" style="margin-top: 10px;">Confirm Rejection</button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; Aliyu Damare 2025. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        function showRejectionForm(productId) {
            const form = document.getElementById('rejection-form-' + productId);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        }
    </script>
    <script src="../assets/js/main.js"></script>
</body>
</html>