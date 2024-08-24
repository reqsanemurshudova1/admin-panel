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

if(isset($_GET['id']))//hansi kategoryanin edit olunacagini bilmek ucun bu uusldan istifade olunur
 {
    $category_id = $_GET['id'];
}
else{
    header('Location: categories.php');
    exit();
}
//kategory melumatlarini getirmek ucun asagidaki sorgu yaradilir
$sql="SELECT * FROM categories WHERE id = ?";
$query=$connection->prepare($sql);
$query->execute([$category_id]);
$categories=$query->fetch(PDO::FETCH_ASSOC);
if(!$categories){
    header('Location: categories.php');
    exit();
}
$errors = [];
//formdan doldurulan melumatlari yeniden deyisdirirek database-deki melumatlari yenileyir
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = $_POST['name'];
    $description=$_POST['description'];

    if(empty($name)){
        $errors['name']="Kateqoriya adini daxil edin";
    }
    if(empty($errors)){
        $sql="UPDATE categories SET name = ?, description = ? WHERE id = ?";
        $query=$connection->prepare($sql);
        $query->execute([$name,$description,$category_id]);
        header('Location: categories.php');
        exit();
    }
}
?>

<body>

<?php include_once "../navbar.php" ?>

<div class="container mt-4">
    <h1 class="text-center">Kateqoriya Redaktə Et</h1>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Kateqoriya Adı</label>
            <input type="text" name="name" id="name" class="form-control" >
            <?php if (isset($errors["name"])) { ?>
                <div class="text-danger" style="color:red">
                    <?= $errors["name"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Təsvir</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Yenilə</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
