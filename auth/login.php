<?php
include_once "../index.php";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = validate(["email", "password"]);
    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email=?";
        $logQuery = $connection->prepare($query);
        $logQuery->execute([post("email")]);

        $user = $logQuery->fetch(PDO::FETCH_OBJ);

        // İstifadəçinin deaktiv olub-olmadığını yoxla
        if ($user && $user->active == 0) {
            $errors['login'] = "Hesabınız deaktiv edilib. Admin ilə əlaqə saxlayın.";
        } elseif ($user && password_verify(post("password"), $user->password)) {
            $_SESSION["id"] = $user->id;
            $_SESSION['profile'] = $user->profile;
            $_SESSION['role'] = $user->role;

            if ($user->role == 1) {
                // Admin səhifəsinə yönləndir
                view(route("admin/index.php"));
                exit();
            } else {
                // Adi istifadəçi səhifəsinə yönləndir
                view(route("index.php"));
                exit();
            }
        } else {
            $errors['login'] = "Email və ya şifrə səhvdir.";
        }
    }
}

?>

<div class="login-container">
    <h2>Login</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email " name="email">
            <?php if (isset($errors["email"])) { ?>
                <div class="ERROR" style="color:red">
                    <?= $errors["email"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password">
            <?php if (isset($errors["password"])) { ?>
                <div class="ERROR" style="color:red">
                    <?= $errors["password"] ?>
                </div>
            <?php } ?>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <?php if (isset($errors["login"])) { ?>
            <div class="ERROR" style="color:red">
                <?= $errors["login"] ?>
            </div>
        <?php } ?>
    </form>
</div>