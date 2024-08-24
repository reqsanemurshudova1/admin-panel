<?php
include "../config/database.php";

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];

    $query = "UPDATE blogs SET is_published = 1 WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindValue(':id', $blog_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: index.php"); // Geri yönləndir
    exit();
}
?>
