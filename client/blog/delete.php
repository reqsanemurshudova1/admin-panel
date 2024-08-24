<?php

include_once "../../config/database.php";

if (isset($_GET['id'])) {
    $blogId = $_GET['id'];

    // Blogun mövcudluğunu yoxla
    $sql = "SELECT * FROM blogs WHERE id = ?";
    $query = $connection->prepare($sql);
    $query->execute([$blogId]);
    $blog = $query->fetch(PDO::FETCH_ASSOC);

    if ($blog) {
        // Əgər blog mövcuddursa, onu sil
        $sql = "DELETE FROM blogs WHERE id = ?";
        $query = $connection->prepare($sql);
        $query->execute([$blogId]);

    }
} else {
    // ID mövcud olmadıqda yönlə
    header('Location: blogs.php');
    exit();
}

// Hər halda blog səhifəsinə geri yönlən
header('Location: blogs.php');
exit();
?>
