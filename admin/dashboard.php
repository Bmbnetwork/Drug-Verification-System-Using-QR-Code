<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireLogin();
requireAdmin();

// Get statistics
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$pending_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'pending'")->fetchColumn();
$accepted_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'accepted'")->fetchColumn();
$rejected_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'rejected'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Drug Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
            <h2 class="card-title">Admin Dashboard</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div class="card" style="text-align: center; background-color: #3498db; color: white;">
                    <h3>Total Products</h3>
                    <p style="font-size: 2rem; font-weight: bold;"><?php echo $total_products; ?></p>
                </div>
                
                <div class="card" style="text-align: center; background-color: #f39c12; color: white;">
                    <h3>Pending Review</h3>
                    <p style="font-size: 2rem; font-weight: bold;"><?php echo $pending_products; ?></p>
                </div>
                
                <div class="card" style="text-align: center; background-color: #2ecc71; color: white;">
                    <h3>Accepted</h3>
                    <p style="font-size: 2rem; font-weight: bold;"><?php echo $accepted_products; ?></p>
                </div>
                
                <div class="card" style="text-align: center; background-color: #e74c3c; color: white;">
                    <h3>Rejected</h3>
                    <p style="font-size: 2rem; font-weight: bold;"><?php echo $rejected_products; ?></p>
                </div>
            </div>
            
            <div class="card">
                <h3>Recent Activity</h3>
                <p>Go to <a href="manage-products.php">Manage Products</a> to review pending drug registrations.</p>
            </div>
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