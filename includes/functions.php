<?php
require_once 'db.php';

function generateQRCode($product_data, $filename) {
    require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';
    
    // Format: DRUG|company|manufacturer|drug_name|batch_number
    $qr_data = "DRUG|" . 
               $product_data['company'] . "|" . 
               $product_data['manufacturer'] . "|" . 
               $product_data['drug_name'] . "|" . 
               $product_data['batch_number'];
    
    $filepath = __DIR__ . '/../qrcode/' . $filename . '.png';
    QRcode::png($qr_data, $filepath, QR_ECLEVEL_L, 10, 2);
    return 'qrcode/' . $filename . '.png';
}

function sendEmail($to, $subject, $message) {
    // Email functionality disabled for local testing
    return true;
}

function getDrugTypes() {
    return ['antibiotic', 'anti-infective', 'anti-malarial'];
}
?>