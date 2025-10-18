<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

requireLogin();
requireUser();

// Get user's products
$stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Drug Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">Drug Management System</div>
                <ul class="nav-links">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="register-product.php">Register Product</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <h2 class="card-title">My Products</h2>
            
            <?php if (empty($products)): ?>
                <div class="card">
                    <p>You haven't registered any products yet.</p>
                    <a href="register-product.php" class="btn btn-primary" style="display: inline-block; margin-top: 15px;">Register Your First Product</a>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
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
                        </div>
                        
                        <?php if ($product['status'] === 'accepted' && $product['qr_code_path']): ?>
                            <div class="qr-container">
                                <h4>QR Code</h4>
                                <img src="../<?php echo htmlspecialchars($product['qr_code_path']); ?>" alt="QR Code">
                                <p style="margin-top: 10px;">Scan this QR code to verify the drug</p>
                            </div>
                        <?php elseif ($product['status'] === 'rejected'): ?>
                            <div style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-top: 15px;">
                                <strong>Rejection Reason:</strong> 
                                <?php echo !empty($product['description']) ? htmlspecialchars($product['description']) : 'No reason provided'; ?>
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
    
    <script src="../assets/js/main.js"></script>
</body>
</html>