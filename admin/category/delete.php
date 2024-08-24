<?php
session_start();
include "../../helper/help.php";

// Admin yoxlaması
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1) {
    $route = route('auth/login.php');
    header("Location: $route"); // İstifadəçi admin deyilsə, giriş səhifəsinə yönləndir
    exit();
}
include_once "../../config/database.php";
if(isset($_GET['id']))//hansi kategoryanin edit olunacagini bilmek ucun bu uusldan istifade olunur
 {
    $category_id = $_GET['id'];
}
else{
    header('Location: categories.php');
    exit();
}

//kategoryni bazadan silir
$sql = "DELETE FROM categories WHERE id = ?";
$query=$connection->prepare($sql);
$query->execute([$category_id]);

header('Location: categories.php');
exit();


?>