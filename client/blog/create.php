<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../auth/login.php');
    exit();
}

include_once '../../config/database.php';
include_once '../../helper/help.php';

$errors = [];
$imagePath = null;
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
    }
  
    if (empty($errors)) {
        echo 'Məlumatlar uğurla yazıldı';
        $sql = "INSERT INTO blogs (title, content, category_id, user_id, image) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            post("title"),
            post("content"),
            post("category_id"),
            $_SESSION['id'],
            $imagePath
        ]);
        header('Location: ../blog/blogs.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Create a New Blog</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="content" id="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Select a category</option>
                <?php
           

                foreach ($categories as $category) {
                    echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Şəkil</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
