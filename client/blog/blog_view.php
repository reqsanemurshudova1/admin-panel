<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../auth/login.php');
    exit();
}

include_once '../../config/database.php';

if (isset($_GET['id'])) {
    $blogId = $_GET['id'];

    
    $sql = "SELECT blogs.*, users.name AS author FROM blogs JOIN users ON blogs.user_id = users.id WHERE blogs.id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$blogId]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($blog) {
        // View count artırmaq
        $updateSql = "UPDATE blogs SET view_count = view_count + 1 WHERE id = ?";
        $updateStmt = $connection->prepare($updateSql);
        $updateStmt->execute([$blogId]);
    } else {
        echo "Blog tapılmadı!";
        exit();
    }
} else {
    header('Location: blogs.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<?php if ($blog['image']) { ?>
                        <img src="uploads/blog_images/<?= htmlspecialchars($blog['image']) ?>" class="card-img-top" alt="Blog Image">
                    <?php } ?>
    <h1 class="mb-4"><?= htmlspecialchars($blog['title']) ?></h1>
    <p><small class="text-muted">Author: <?= htmlspecialchars($blog['author']) ?>  Views: <?= $blog['view_count'] ?></small></p>
    <p><?= htmlspecialchars($blog['content']) ?></p>
    <a href="../../home/home.php" class="btn btn-secondary">Back to Blogs</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
