<?php
session_start();
include "../../helper/help.php";

// Admin yoxlaması
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1) {
    $route = route('auth/login.php');
    header("Location: $route"); // İstifadəçi admin deyilsə, giriş səhifəsinə yönləndir
    exit();
}
include_once "../../config/database.php";
include '../head.php';
$errors = [];

//yeni category yaratmaq
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $description = $_POST['description'];

    if (empty($name)) {
        $errors['name'] = "Kateqoriya adı daxil edilməlidir.";
    }
    if (empty($errors)) {

        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $query = $connection->prepare($sql);
        $check = $query->execute([$name, $description]);
        header('Location: categories.php');
        exit();
    }
}

//movcud olan butun kategoriyalar
$sql = "SELECT * FROM categories";
$query = $connection->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
include "../head.php";
include "../navbar.php";

?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Manage Categories</h1>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Yeni Kateqoriya Əlavə Et</div>
        <div class="card-body">
            <form method="POST" action="categories.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Kateqoriya Adı</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ad daxil edin">
                    <?php if (isset($errors["name"])) { ?>
                        <div class="text-danger" style="color:red">
                            <?= $errors["name"] ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Təsvir</label>
                    <textarea name="description" id="description" class="form-control"
                        placeholder="Təsvir daxil edin"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Əlavə Et</button>
            </form>
        </div>
    </div>


    <div class="card">
        <div class="card-header bg-secondary text-white">Mövcud Kateqoriyalar</div>
        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Adı</th>
                        <th>Təsvir</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($categories as $category) { ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><?= $category['name'] ?></td>
                            <td><?= $category['description'] ?></td>
                            <td>
                                <a href="edit.php?id=<?= $category['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete.php?id=<?= $category['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>