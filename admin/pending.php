<?php

include "../config/database.php"; // Verilənlər bazasına qoşulun
include "../head.php";

// Səhifəni müəyyən etmək
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$startLimit = ($page - 1) * $limit;

// Bloqların ümumi sayını əldə etmək
$totalQuery = "SELECT COUNT(*) FROM blogs WHERE is_published = 0";
$totalStmt = $connection->prepare($totalQuery);
$totalStmt->execute();
$totalBlogs = $totalStmt->fetchColumn();

$totalPages = ceil($totalBlogs / $limit);

// Bloqları əldə etmək üçün sorğu
$query = "SELECT blogs.*, users.name AS author_name FROM blogs 
          JOIN users ON blogs.user_id = users.id 
          WHERE blogs.is_published = 0
          ORDER BY blogs.created_at DESC 
          LIMIT :startLimit, :limit";

$stmt = $connection->prepare($query);
$stmt->bindValue(':startLimit', $startLimit, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_OBJ);

?>

<?php
include "head.php";
include "../helper/help.php";
include "navbar.php";

?>
<div class="container mt-4">
    <h2>Pending Blogs</h2>
    <?php if (!empty($blogs)) { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blogs as $blog) { ?>
                    <tr>
                        <td><?= htmlspecialchars($blog->title); ?></td>
                        <td><?= htmlspecialchars($blog->content); ?></td>
                        <td>
                            <?php if (!empty($blog->image)) { ?>
                                <img src="../client/blog/uploads/blog_images/<?= htmlspecialchars($blog->image); ?>" alt="Blog Image" class="blog-image" style="width: 100px;">
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars($blog->author_name); ?></td>
                        <td>
                            <a href="accept.php?id=<?= $blog->id; ?>" class="btn btn-success btn-sm">Accept</a>
                            <a href="cancel.php?id=<?= $blog->id; ?>" class="btn btn-danger btn-sm">Cancel</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No pending blogs.</p>
    <?php } ?>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1; ?>" class="btn btn-secondary">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i; ?>" class="btn btn-primary <?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1; ?>" class="btn btn-secondary">Next</a>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
