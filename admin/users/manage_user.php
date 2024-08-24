<?php
session_start();
include "../../helper/help.php";

// Admin yoxlaması
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1) {
    $route = route('auth/login.php');
    header("Location: $route"); // İstifadəçi admin deyilsə, giriş səhifəsinə yönləndir
    exit();
}

include_once '../../config/database.php';

// Səhifəni müəyyən etmək
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3; // Səhifədə göstəriləcək istifadəçilərin sayı
$startLimit = ($page - 1) * $limit;

// İstifadəçilərin ümumi sayını əldə etmək
$totalQuery = "SELECT COUNT(*) FROM users";
$totalStmt = $connection->prepare($totalQuery);
$totalStmt->execute();
$totalUsers = $totalStmt->fetchColumn();

$totalPages = ceil($totalUsers / $limit);

// Bütün istifadəçiləri səhifələmə ilə əldə etmək
$sql = "SELECT * FROM users LIMIT :startLimit, :limit";
$query = $connection->prepare($sql);
$query->bindValue(':startLimit', $startLimit, PDO::PARAM_INT);
$query->bindValue(':limit', $limit, PDO::PARAM_INT);
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
<?php
include "../head.php";
include "../navbar.php";

?>
    <h1 class="text-center mb-4">Manage Users</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Ad</th>
                <th>Email</th>
                <th>Status</th>
                <th>Əməliyyatlar</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($users as $user) { ?>

                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?= $user['active'] ? 'Active' : 'Deactive' ?>
                    </td>
                    <td>
                        <a href="user_status.php?id=<?= $user['id'] ?>&status=<?= $user['active'] ? 'deactivate' : 'activate' ?>"
                            class="btn btn-sm <?= $user['active'] ? 'btn-danger' : 'btn-success' ?>">
                            <?= $user['active'] ? 'Deactive' : 'Activate' ?>
                        </a>
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>