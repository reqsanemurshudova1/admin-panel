<?php
include "../config/database.php";

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    $query = "DELETE FROM blogs WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindValue(':id', $blog_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: pending.php"); // Geri yönləndir
    exit();
}
?>
