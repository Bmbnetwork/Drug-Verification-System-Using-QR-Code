<?php
require_once 'includes/db.php';

$verified = false;
$product = null;
$error = '';
$success = '';
$qr_info = null;

// Handle manual QR code input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_qr'])) {
    $manual_qr = trim($_POST['manual_qr']);
    if (!empty($manual_qr)) {
        $verification_result = verifyQRCodeData($manual_qr);
        if ($verification_result['verified']) {
            $verified = true;
            $product = $verification_result['product'];
        } else {
            $error = $verification_result['error'];
            $qr_info = $verification_result['qr_info'];
        }
    } else {
        $error = 'Please enter QR code data.';
    }
}

// Handle URL parameter (for direct QR code scanning with smartphone)
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $verification_result = verifyQRCodeData($code);
    if ($verification_result['verified']) {
        $verified = true;
        $product = $verification_result['product'];
    } else {
        $error = $verification_result['error'];
        $qr_info = $verification_result['qr_info'];
    }
}

function verifyQRCodeData($code) {
    global $pdo;
    
    $result = [
        'verified' => false,
        'product' => null,
        'error' => '',
        'qr_info' => null
    ];
    
    // Parse the QR code data (format: DRUG|company|manufacturer|drug_name|batch_number)
    $parts = explode('|', $code);
    
    if (count($parts) === 5 && $parts[0] === 'DRUG') {
        $company = $parts[1];
        $manufacturer = $parts[2];
        $drug_name = $parts[3];
        $batch_number = $parts[4];
        
        // Store QR info for display
        $result['qr_info'] = [
            'company' => $company,
            'manufacturer' => $manufacturer,
            'drug_name' => $drug_name,
            'batch_number' => $batch_number
        ];
        
        // Verify against database - check if all fields match an accepted product
        $stmt = $pdo->prepare("SELECT * FROM products WHERE company = ? AND manufacturer = ? AND drug_name = ? AND batch_number = ? AND status = 'accepted'");
        $stmt->execute([$company, $manufacturer, $drug_name, $batch_number]);
        $product = $stmt->fetch();
        
        if ($product) {
            $result['verified'] = true;
            $result['product'] = $product;
        } else {
            $result['error'] = 'Invalid or unverified drug. This product may not be registered or has been rejected.';
        }
    } else {
        $result['error'] = 'Invalid QR code format. Expected format: DRUG|Company|Manufacturer|DrugName|BatchNumber';
    }
    
    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Drug - Drug Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .verification-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .manual-entry-card {
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 30px;
        }
        .result-container {
            text-align: center;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .verified {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 2px solid #4caf50;
        }
        .not-verified {
            background-color: #ffebee;
            color: #c62828;
            border: 2px solid #f44336;
        }
        .instructions {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .qr-data-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .qr-data-item {
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .qr-data-item:last-child {
            border-bottom: none;
        }
        .qr-data-label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 4px;
        }
        .qr-data-value {
            color: #212529;
            font-size: 1.1em;
        }
        .example-box {
            background-color: #f1f3f4;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }
        .how-to-read {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .how-to-step {
            text-align: center;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .how-to-step h4 {
            color: #3498db;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">Drug Management System</div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container verification-container">
            <div class="card">
                <h2 class="card-title">Verify Drug Authenticity</h2>
                
                <div class="instructions">
                    <h3>📋 How to Verify Your Drug</h3>
                    <p>This system allows you to verify the authenticity of registered drugs by manually entering the QR code information.</p>
                    
                    <div class="how-to-read">
                        <div class="how-to-step">
                            <h4>Step 1</h4>
                            <p>Locate the QR code on your drug packaging</p>
                        </div>
                        <div class="how-to-step">
                            <h4>Step 2</h4>
                            <p>Read the QR code content (format shown below)</p>
                        </div>
                        <div class="how-to-step">
                            <h4>Step 3</h4>
                            <p>Enter the exact text in the form below</p>
                        </div>
                    </div>
                    
                    <h4>🔍 QR Code Format:</h4>
                    <p>All QR codes in this system follow this exact format:</p>
                    <div class="example-box">
                        DRUG|Company Name|Manufacturer Name|Drug Name|Batch Number
                    </div>
                    
                    <h4>✅ Example:</h4>
                    <div class="example-box">
                        DRUG|Bilal Pharmaceuticals|Bilal Manufacturing Facility|Tramadol|ABC123456
                    </div>
                </div>
                
                <?php if ($error): ?>
                    <div class="result-container not-verified">
                        <h2>❌ VERIFICATION FAILED</h2>
                        <p><?php echo htmlspecialchars($error); ?></p>
                        
                        <?php if ($qr_info): ?>
                            <div class="qr-data-display">
                                <h4>Entered Information:</h4>
                                <div class="qr-data-item">
                                    <span class="qr-data-label">Company:</span>
                                    <span class="qr-data-value"><?php echo htmlspecialchars($qr_info['company']); ?></span>
                                </div>
                                <div class="qr-data-item">
                                    <span class="qr-data-label">Manufacturer:</span>
                                    <span class="qr-data-value"><?php echo htmlspecialchars($qr_info['manufacturer']); ?></span>
                                </div>
                                <div class="qr-data-item">
                                    <span class="qr-data-label">Drug Name:</span>
                                    <span class="qr-data-value"><?php echo htmlspecialchars($qr_info['drug_name']); ?></span>
                                </div>
                                <div class="qr-data-item">
                                    <span class="qr-data-label">Batch Number:</span>
                                    <span class="qr-data-value"><?php echo htmlspecialchars($qr_info['batch_number']); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($verified && $product): ?>
                    <div class="result-container verified">
                        <h2>✅ VERIFIED DRUG</h2>
                        <p>Congratulations! This drug is registered and verified in our system.</p>
                        
                        <div class="qr-data-display">
                            <h4>Verified Drug Information:</h4>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Company:</span>
                                <span class="qr-data-value"><?php echo htmlspecialchars($product['company']); ?></span>
                            </div>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Manufacturer:</span>
                                <span class="qr-data-value"><?php echo htmlspecialchars($product['manufacturer']); ?></span>
                            </div>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Drug Name:</span>
                                <span class="qr-data-value"><?php echo htmlspecialchars($product['drug_name']); ?></span>
                            </div>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Batch Number:</span>
                                <span class="qr-data-value"><?php echo htmlspecialchars($product['batch_number']); ?></span>
                            </div>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Drug Type:</span>
                                <span class="qr-data-value"><?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $product['drug_type']))); ?></span>
                            </div>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Expiry Date:</span>
                                <span class="qr-data-value"><?php echo htmlspecialchars($product['expiry_date']); ?></span>
                            </div>
                            <div class="qr-data-item">
                                <span class="qr-data-label">Registration Date:</span>
                                <span class="qr-data-value"><?php echo date('M j, Y', strtotime($product['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="manual-entry-card">
                    <h3>📝 Manual QR Code Entry</h3>
                    <p>Enter the exact QR code content from your drug packaging:</p>
                    
                    <form method="POST" id="manual-form">
                        <div class="form-group">
                            <label for="manual_qr">QR Code Data <span style="color: #e74c3c;">*</span></label>
                            <textarea id="manual_qr" name="manual_qr" class="form-control" rows="4" placeholder="Enter the complete QR code text exactly as it appears. Example:&#10;DRUG|Company|Manufacturer|DrugName|BatchNumber" required><?php echo isset($_POST['manual_qr']) ? htmlspecialchars($_POST['manual_qr']) : ''; ?></textarea>
                        </div>
                        
                        <div style="margin-top: 15px; padding: 15px; background-color: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                            <strong>💡 Tip:</strong> Make sure to include the "DRUG|" prefix and use the exact pipe symbol "|" as separator. 
                            Copy the text exactly as it appears in the QR code.
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Verify Drug Authenticity</button>
                    </form>
                </div>
                
                <?php if ($verified || $error): ?>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="verify-qr.php" class="btn btn-primary">Verify Another Drug</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; Aliyu Damare 2025. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const manualForm = document.getElementById('manual-form');
            
            if (manualForm) {
                manualForm.addEventListener('submit', function(e) {
                    const qrInput = document.getElementById('manual_qr');
                    if (!qrInput.value.trim()) {
                        e.preventDefault();
                        alert('Please enter the QR code data.');
                        qrInput.focus();
                    }
                });
            }
        });
    </script>
</body>
</html>