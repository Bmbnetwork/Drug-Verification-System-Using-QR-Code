<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: user/dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drug Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">Drug Management System</div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <div class="card">
                <h2 class="card-title">Drug Management System with QR Code Verification</h2>
                
                <p style="margin-bottom: 20px;">
                    This system helps manage and verify essential drugs - specifically antibiotics, anti-infective, 
                    and anti-malarial medications. Each registered drug is assigned a unique QR code that can be 
                    scanned to verify its authenticity.
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="card" style="padding: 1.5rem;">
                        <h3>For Users</h3>
                        <ul style="margin: 15px 0; padding-left: 20px;">
                            <li>Create an account</li>
                            <li>Register your drug products</li>
                            <li>Track verification status</li>
                            <li>Access QR codes for verified drugs</li>
                        </ul>
                        <a href="register.php" class="btn btn-primary">Register as User</a>
                    </div>
                    
                    <div class="card" style="padding: 1.5rem;">
                        <h3>For Admins</h3>
                        <ul style="margin: 15px 0; padding-left: 20px;">
                            <li>Review drug registrations</li>
                            <li>Accept or reject products</li>
                            <li>Generate QR codes</li>
                        </ul>
                        <a href="login.php" class="btn btn-primary">Admin Login</a>
                    </div>
                </div>
                
                <h3>How It Works</h3>
                <ol style="margin: 20px 0; padding-left: 20px;">
                    <li>Users register their drug products through the system</li>
                    <li>Admins review the submissions and verify authenticity</li>
                    <li>Accepted drugs are assigned a unique QR code</li>
                    <li>Anyone can scan the QR code to verify the drug's authenticity</li>
                </ol>
                
                <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 4px; margin-top: 20px;">
                    <strong>Note:</strong> This system only covers antibiotics, anti-infective, and anti-malarial drugs. 
                    Herbal or locally made medicines are not included.
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; Aliyu Damare 2025. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="assets/js/main.js"></script>
</body>
</html>