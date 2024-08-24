<?php
session_start();
include "../helper/help.php";

// Admin yoxlaması
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1) {
    $route = route('auth/login.php');
    header("Location: $route"); // İstifadəçi admin deyilsə, giriş səhifəsinə yönləndir
    exit();
}

include "head.php";
include "navbar.php";

?>

<div class="container mt-4">
    <h1 class="text-center">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text">Create, edit, and delete users.</p>
                    <a href="users/manage_user.php" class="btn btn-light">Go to Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Categories</div>
                <div class="card-body">
                    <h5 class="card-title">Manage Categories</h5>
                    <p class="card-text">Create, edit, and delete categories.</p>
                    <a href="category/categories.php" class="btn btn-light">Go to Categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Products</div>
                <div class="card-body">
                    <h5 class="card-title">Manage Products</h5>
                    <p class="card-text">Create, edit, and delete products.</p>
                    <a href="pending.php" class="btn btn-light">Go to Products</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>