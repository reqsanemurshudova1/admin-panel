<?php
$urlWithoutReq = [
    '/Backend/mohtesemTask2/auth/login.php',
    '/Backend/mohtesemTask2/auth/register.php',
    '/Backend/mohtesemTask2/auth/otp.php'
];

if (isset($_SESSION['id']) && in_array($_SERVER['REQUEST_URI'], $urlWithoutReq)) {
    header('location: ../index.php');
} else if (!isset($_SESSION['id']) && !in_array($_SERVER['REQUEST_URI'], $urlWithoutReq)) {
    header('location: /Backend/mohtesemTask2/auth/login.php');
}

?>