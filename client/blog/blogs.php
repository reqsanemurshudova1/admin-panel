<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: auth/login.php');
    exit();
}

include_once '../../config/database.php';

// Bütün blogları əldə etmək
$sql = "SELECT blogs.*, users.name AS author FROM blogs JOIN users ON blogs.user_id = users.id";
$query = $connection->prepare($sql);
$query->execute();
$blogs = $query->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Blogs</h1>
    <div class="text-center mb-4" style="display: flex; justify-content: center; gap: 10px;">
    <a href="create.php" class="btn btn-primary">Yeni Bloq</a>
    <a href="../../home.php" class="btn btn-secondary">Back to Home</a>
</div>

    <?php if(count($blogs) > 0) { ?>
    <div class="row">
        <?php foreach ($blogs as $blog) { ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <?php if ($blog['image']) { ?>
                        <img src="uploads/blog_images/<?= htmlspecialchars($blog['image']) ?>" class="card-img-top" alt="Blog Image">
                    <?php } ?>
                    <div class="card-body" >
                        <h5 class="card-title"><?= htmlspecialchars($blog['title']) ?></h5>
                        <p class="card-text"><?= substr(htmlspecialchars($blog['content']), 0, 100) ?>...</p>
                        <p class="card-text"><small class="text-muted">Author: <?= htmlspecialchars($blog['author']) ?></small></p>
                 <div class="text-center"  style="display: flex; align-items: center; gap: 10px;">       <a href="blog_view.php?id=<?= $blog['id'] ?>" class="btn btn-primary">Read More</a>
                        <a href="edit.php?id=<?= $blog['id'] ?>" class="btn btn-warning mt-2">Update</a>
                
                        <a href="delete.php?id=<?= $blog['id'] ?>" class="btn btn-danger mt-2" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                  </div>
                    </div>

                    <div class="card-footer text-muted">
                        <?= htmlspecialchars($blog['created_at']) ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="alert alert-warning text-center">
        Hələ heç bir blog yoxdur.
    </div>
    <div class="text-center">
        <a href="create.php" class="btn btn-success">İlk Bloqunuzu Yaradın</a>
    </div>
<?php } ?>
 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
