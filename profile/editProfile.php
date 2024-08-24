<?php

include_once "../index.php";

// İstifadəçi daxil olub-olmadığını yoxla
if (!isset($_SESSION['id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = $_SESSION['id'];

// İstifadəçinin məlumatlarını əldə et
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Məlumatları yoxla
    $profilePicPath = $user['profile']; // Əgər yeni şəkil yüklənməzsə, mövcud şəkil saxlanılacaq

    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        $uploadDirectory = '/Backend/mohtesemTask2/auth/uploads/profile_pics/';
        $profilePicPath = file_upload($uploadDirectory, $_FILES['profile']);

        if (!$profilePicPath) {
            $errors['profile'] = "Profil şəkili yüklənə bilmədi.";
        }
    }

    if (empty($errors)) {
        // Məlumatları yenilə
        $updateQuery = "UPDATE users SET name = ?, surname = ?, fatherName = ?, dob = ?, gender = ?, email = ?, profile = ? WHERE id = ?";
        $stmt = $connection->prepare($updateQuery);
        $stmt->execute([
            post("name"),
            post("surname"),
            post("fatherName"),
            post("dob"),
            post("gender"),
            post("email"),
            $profilePicPath,
            $userId
        ]);
        // Yenidən profil səhifəsinə yönləndir
        header('Location: profile.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
     <div id="editForm;">
        <form method="POST" action="" enctype="multipart/form-data">

        <?php print_r($user); ?>
            <div class="form-group mb-3">
                <label for="name">Ad</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="surname">Soyad</label>
                <input type="text" class="form-control" id="surname" name="surname" value="<?= htmlspecialchars($user['surname']) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="fatherName">Ata Adı</label>
                <input type="text" class="form-control" id="fatherName" name="fatherName" value="<?= htmlspecialchars($user['fatherName']) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="dob">Doğum Tarixi</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>">
            </div>
            <div class="form-group mb-3">
                <label>Cinsiyyət</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?= $user['gender'] == 'male' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="male">Kişi</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?= $user['gender'] == 'female' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="female">Qadın</label>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="profilePic">Profil Şəkli</label>
                <input type="file" class="form-control" id="profilePic" name="profile">
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Məlumatları Yenilə</button>
            <button type="button" id="cancelButton" class="btn btn-secondary mt-3" style="display: none;" onclick="toggleEditMode()">Ləğv et</button>
        </form>
    </div>
</body>
</html>