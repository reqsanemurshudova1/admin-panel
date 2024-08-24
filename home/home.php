<?php

include "../config/database.php";

$categorySql = "SELECT * FROM categories";
$categoryQuery = $connection->prepare($categorySql);
$categoryQuery->execute();
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

$titleSearch = isset($_GET['title']) ? $_GET['title'] : '';
$descriptionSearch = isset($_GET['description']) ? $_GET['description'] : '';
$authorSearch = isset($_GET['author']) ? $_GET['author'] : '';
$categorySearch = isset($_GET['category']) ? $_GET['category'] : '';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$startLimit = ($page - 1) * $limit;

$query = "SELECT blogs.*, users.name AS author, categories.name AS category FROM blogs 
          JOIN users ON blogs.user_id = users.id 
          JOIN categories ON blogs.category_id = categories.id
          WHERE blogs.is_published = 1";

$conditions = [];

if (!empty($titleSearch)) {
    $conditions[] = "blogs.title LIKE :title";
}
if (!empty($descriptionSearch)) {
    $conditions[] = "blogs.content LIKE :description";
}
if (!empty($authorSearch)) {
    $conditions[] = "users.name LIKE :author";
}
if (!empty($categorySearch)) {
    $conditions[] = "categories.id = :category";
}

if (count($conditions) > 0) {
    $query .= " AND (" . implode(" OR ", $conditions) . ")";
}

$totalQuery = "SELECT COUNT(*) FROM blogs 
               JOIN users ON blogs.user_id = users.id 
               JOIN categories ON blogs.category_id = categories.id 
               WHERE blogs.is_published = 1";

if (count($conditions) > 0) {
    $totalQuery .= " AND (" . implode(" OR ", $conditions) . ")";
}

$totalStmt = $connection->prepare($totalQuery);
if (!empty($titleSearch)) {
    $totalStmt->bindValue(':title', '%' . $titleSearch . '%');
}
if (!empty($descriptionSearch)) {
    $totalStmt->bindValue(':description', '%' . $descriptionSearch . '%');
}
if (!empty($authorSearch)) {
    $totalStmt->bindValue(':author', '%' . $authorSearch . '%');
}
if (!empty($categorySearch)) {
    $totalStmt->bindValue(':category', $categorySearch);
}
$totalStmt->execute();
$totalBlogs = $totalStmt->fetchColumn();

$totalPages = ceil($totalBlogs / $limit);

$query .= " ORDER BY blogs.created_at DESC LIMIT :startLimit, :limit";

$stmt = $connection->prepare($query);

if (!empty($titleSearch)) {
    $stmt->bindValue(':title', '%' . $titleSearch . '%');
}
if (!empty($descriptionSearch)) {
    $stmt->bindValue(':description', '%' . $descriptionSearch . '%');
}
if (!empty($authorSearch)) {
    $stmt->bindValue(':author', '%' . $authorSearch . '%');
}
if (!empty($categorySearch)) {
    $stmt->bindValue(':category', $categorySearch);
}

$stmt->bindValue(':startLimit', $startLimit, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$last5Sql = "SELECT blogs.*, users.name AS author FROM blogs JOIN users ON blogs.user_id = users.id ORDER BY blogs.created_at DESC LIMIT 5";
$last5Query = $connection->prepare($last5Sql);
$last5Query->execute();
$last5Blogs = $last5Query->fetchAll(PDO::FETCH_ASSOC);

$top5Sql = "SELECT blogs.*, users.name AS author FROM blogs JOIN users ON blogs.user_id = users.id ORDER BY blogs.view_count DESC LIMIT 5";
$top5Query = $connection->prepare($top5Sql);
$top5Query->execute();
$top5Blogs = $top5Query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
 
 <?php 
  include "../helper/help.php";
 
 include "../navbar.php";

 
 ?>
    <h2>Blog Ara</h2>
    <form method="GET" action="">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?= htmlspecialchars($titleSearch) ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="description">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?= htmlspecialchars($descriptionSearch) ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" placeholder="Author" value="<?= htmlspecialchars($authorSearch) ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>" <?= $categorySearch == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Axtar</button>
        <a href="home.php" class="btn btn-secondary">Sıfırla</a>
    </form>
</div>

<div class="container mt-4">
    <h2>Published Blogs</h2>
    <?php if (!empty($blogs)) { ?>
        <div class="row">
            <?php foreach ($blogs as $blog) { ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <?php if ($blog['image']) { ?>
                            <img src="../client/blog/uploads/blog_images/<?= htmlspecialchars($blog['image']) ?>" class="card-img-top" alt="Blog Image">
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($blog['title']) ?></h5>
                            <p class="card-text"><?= substr(htmlspecialchars($blog['content']), 0, 100) ?>...</p>
                            <p class="card-text"><small class="text-muted">Author: <?= htmlspecialchars($blog['author']) ?></small></p>
                            <a href="../client/blog/blog_view.php?id=<?= $blog['id'] ?>" class="btn btn-primary">Read More</a>
                        </div>
                        <div class="card-footer text-muted">
                            <?= htmlspecialchars($blog['created_at']) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1; ?>&title=<?= htmlspecialchars($titleSearch) ?>&description=<?= htmlspecialchars($descriptionSearch) ?>&author=<?= htmlspecialchars($authorSearch) ?>&category=<?= htmlspecialchars($categorySearch) ?>" class="btn btn-secondary">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i; ?>&title=<?= htmlspecialchars($titleSearch) ?>&description=<?= htmlspecialchars($descriptionSearch) ?>&author=<?= htmlspecialchars($authorSearch) ?>&category=<?= htmlspecialchars($categorySearch) ?>" class="btn btn-primary <?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1; ?>&title=<?= htmlspecialchars($titleSearch) ?>&description=<?= htmlspecialchars($descriptionSearch) ?>&author=<?= htmlspecialchars($authorSearch) ?>&category=<?= htmlspecialchars($categorySearch) ?>" class="btn btn-secondary">Next</a>
            <?php endif; ?>
        </div>

        <h2 class="text-center mt-5">Son 5 Blog</h2>
        <div class="row">
            <?php foreach ($last5Blogs as $blog) { ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($blog['title']) ?></h5>
                            <p class="card-text"><small class="text-muted">Author: <?= htmlspecialchars($blog['author']) ?></small></p>
                            <a href="../client/blog/blog_view.php?id=<?= $blog['id'] ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h2 class="text-center mt-5">Ən Populyar 5 Blog</h2>
        <div class="row">
            <?php foreach ($top5Blogs as $blog) { ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($blog['title']) ?></h5>
                            <p class="card-text"><small class="text-muted">Author: <?= htmlspecialchars($blog['author']) ?></small></p>
                            <a href="../client/blog/blog_view.php?id=<?= $blog['id'] ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <p>No published blogs available.</p>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>