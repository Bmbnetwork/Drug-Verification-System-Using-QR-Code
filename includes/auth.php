<?php
session_start();
require_once 'db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

function redirect($path) {
    header("Location: $path");
    exit();
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('../login.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('../user/dashboard.php');
    }
}

function requireUser() {
    if (!isUser()) {
        redirect('../admin/dashboard.php');
    }
}
?>