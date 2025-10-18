<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

requireLogin();
requireUser();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drug_name = trim($_POST['drug_name']);
    $drug_type = $_POST['drug_type'];
    $company = trim($_POST['company']);  // NEW FIELD
    $manufacturer = trim($_POST['manufacturer']);
    $batch_number = trim($_POST['batch_number']);
    $expiry_date = $_POST['expiry_date'];
    $description = trim($_POST['description']);
    
    // Validation
    if (empty($drug_name) || empty($drug_type) || empty($company) || empty($manufacturer) || empty($batch_number) || empty($expiry_date)) {
        $error = 'All fields are required';
    } elseif (!in_array($drug_type, getDrugTypes())) {
        $error = 'Invalid drug type';
    } elseif (strtotime($expiry_date) < strtotime(date('Y-m-d'))) {
        $error = 'Expiry date must be in the future';
    } else {
        // Check if batch number already exists
        $stmt = $pdo->prepare("SELECT id FROM products WHERE batch_number = ?");
        $stmt->execute([$batch_number]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'A product with this batch number already exists';
        } else {
            // Insert product
            $stmt = $pdo->prepare("INSERT INTO products (user_id, drug_name, drug_type, company, manufacturer, batch_number, expiry_date, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $drug_name, $drug_type, $company, $manufacturer, $batch_number, $expiry_date, $description]);
            
            $success = 'Product registered successfully! It will be reviewed by an admin.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Product - Drug Management System</title>
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
            <div class="card">
                <h2 class="card-title">Register New Product</h2>
                
                <?php if ($error): ?>
                    <div style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div style="background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="drug_name">Drug Name *</label>
                        <input type="text" id="drug_name" name="drug_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="drug_type">Drug Type *</label>
                        <select id="drug_type" name="drug_type" class="form-control" required>
                            <option value="">Select Drug Type</option>
                            <?php foreach (getDrugTypes() as $type): ?>
                                <option value="<?php echo $type; ?>"><?php echo ucwords(str_replace('-', ' ', $type)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Company *</label>
                        <input type="text" id="company" name="company" class="form-control" placeholder="Enter pharmaceutical company name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer *</label>
                        <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="Enter manufacturing facility name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="batch_number">Batch Number *</label>
                        <input type="text" id="batch_number" name="batch_number" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date *</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Additional Information</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Register Product</button>
                </form>
                
                <p style="margin-top: 15px; font-size: 0.9rem; color: #666;">
                    * Required fields. Only antibiotics, anti-infective, and anti-malarial drugs are accepted.<br>
                    <strong>Note:</strong> Company refers to the pharmaceutical company that owns the drug brand.<br>
                    Manufacturer refers to the facility that produces the drug.
                </p>
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