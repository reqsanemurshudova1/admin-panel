<?php
include "../config/database.php"; // Verilənlər bazasına qoşulun
// Bu gün əlavə olunan bloglar
$queryToday = "SELECT COUNT(*) AS count FROM blogs WHERE DATE(created_at) = DATE(NOW())";
$stmtToday = $connection->prepare($queryToday);
$stmtToday->execute();
$todayCount = $stmtToday->fetch(PDO::FETCH_OBJ)->count;

// Bu həftə əlavə olunan bloglar
$queryWeek = "SELECT COUNT(*) AS count FROM blogs WHERE WEEK(created_at) = WEEK(DATE(NOW()))";
$stmtWeek = $connection->prepare($queryWeek);
$stmtWeek->execute();
$weekCount = $stmtWeek->fetch(PDO::FETCH_OBJ)->count;

// Bu ay əlavə olunan bloglar
$queryMonth = "SELECT COUNT(*) AS count FROM blogs WHERE MONTH(created_at) = MONTH(DATE(NOW()))";
$stmtMonth = $connection->prepare($queryMonth);
$stmtMonth->execute();
$monthCount = $stmtMonth->fetch(PDO::FETCH_OBJ)->count;

// Bu gün oxunma sayı
$queryViewsToday = "SELECT SUM(view_count) AS views FROM blogs WHERE DATE(created_at) = DATE(NOW())";
$stmtViewsToday = $connection->prepare($queryViewsToday);
$stmtViewsToday->execute();
$viewsToday = $stmtViewsToday->fetch(PDO::FETCH_OBJ)->views;

// Bu həftə oxunma sayı
$queryViewsWeek = "SELECT SUM(view_count) AS views FROM blogs WHERE WEEK(created_at) = WEEK(DATE(NOW()))";
$stmtViewsWeek = $connection->prepare($queryViewsWeek);
$stmtViewsWeek->execute();
$viewsWeek = $stmtViewsWeek->fetch(PDO::FETCH_OBJ)->views;

// Bu ay oxunma sayı
$queryViewsMonth = "SELECT SUM(view_count) AS views FROM blogs WHERE MONTH(created_at) = MONTH(DATE(NOW()))";
$stmtViewsMonth = $connection->prepare($queryViewsMonth);
$stmtViewsMonth->execute();
$viewsMonth = $stmtViewsMonth->fetch(PDO::FETCH_OBJ)->views;
?>

<body>
   <?php 
    include "head.php";
   include "../helper/help.php";
   include "navbar.php";

   ?>
    
<div class="container mt-4">
    <h2>Statistics</h2>
    <div class="row">
        <div class="col-md-4">
            <h4>Blogs Added</h4>
            <p>Today: <?php echo $todayCount; ?></p>
            <p>This Week: <?php echo $weekCount; ?></p>
            <p>This Month: <?php echo $monthCount; ?></p>
        </div>
        <div class="col-md-4">
            <h4>View Counts</h4>
            <p>Today: <?php echo $viewsToday ?: 0; ?></p>
            <p>This Week: <?php echo $viewsWeek ?: 0; ?></p>
            <p>This Month: <?php echo $viewsMonth ?: 0; ?></p>
        </div>
    </div>
</div>


</body>
</html>