<?php
session_start();
include "../../helper/help.php";

// Admin yoxlaması
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1) {
    $route = route('auth/login.php');
    header("Location: $route"); // İstifadəçi admin deyilsə, giriş səhifəsinə yönləndir
    exit();
}
include_once '../../config/database.php';
include '../navbar.php';
// İstifadəçinin ID və yeni statusu əldə etmək
if (isset($_GET['id']) && isset($_GET['status'])) {
    $userId = $_GET['id'];
    $newStatus = ($_GET['status'] === 'activate') ? 1 : 0;

    // İstifadəçinin statusunu yeniləmək
    $sql = "UPDATE users SET active = ? WHERE id = ?";
    $query = $connection->prepare($sql);
    $query->execute([$newStatus, $userId]);

    header('Location: manage_user.php');
    exit();
} else {
    header('Location: manage_user.php');
    exit();
}
?>
