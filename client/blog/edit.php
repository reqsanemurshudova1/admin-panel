<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: auth/login.php');
    exit();
}

include_once '../../config/database.php';
include_once '../../helper/help.php';

$errors = [];
$imagePath = null;

// Blogu əldə etmək üçün blog ID-si lazımdır
if (!isset($_GET['id'])) {
    header('Location: blogs.php');
    exit();
}

$blogId = $_GET['id'];

// Blog məlumatlarını əldə etmək
$sql = "SELECT * FROM blogs WHERE id = ?";
$query = $connection->prepare($sql);
$query->execute([$blogId]);
$blog = $query->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    header('Location: blogs.php');
    exit();
}

// Kateqoriyaları bazadan çəkmək
$categoryQuery = $connection->prepare("SELECT * FROM categories");
$categoryQuery->execute();
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = validate(["title", "content", "category_id"]);

    // Şəkil yükləmə
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDirectory = 'uploads/blog_images/';
        $imagePath = file_upload($uploadDirectory, $_FILES['image']);

        if (!$imagePath) {
            $errors['image'] = "Şəkil yüklənə bilmədi.";
        }
    } else {
        $imagePath = $blog['image']; // Eger yeni bir şəkil yüklenmediyse kohne şəkil qalır
    }

    if (empty($errors)) {
        $sql = "UPDATE blogs SET title = ?, content = ?, category_id = ?, image = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            post("title"),
            post("content"),
            post("category_id"),
            $imagePath,
            $blogId
        ]);

        header('Location: blogs.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Edit Blog</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="content" id="description" class="form-control" rows="5" required><?= htmlspecialchars($blog['content']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Select a category</option>
                <?php
                foreach ($categories as $category) {
                    $selected = $category['id'] == $blog['category_id'] ? 'selected' : '';
                    echo '<option value="' . $category['id'] . '" ' . $selected . '>' . htmlspecialchars($category['name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Şəkil</label>
            <?php if (!empty($blog['image'])) { ?>
                <div class="mb-2">
                    <img src="uploads/blog_images/<?= htmlspecialchars($blog['image']) ?>" alt="Blog Image" style="max-width: 200px;">
                </div>
            <?php } ?>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Blog</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
