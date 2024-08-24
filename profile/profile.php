<?php

include_once "../index.php";


$user=getUserDetails($connection);
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




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Profilim</h1>


    <div id="profileInfo">
        <div class="card mb-4">
            <div class="card-body">
                <h4>Profil Məlumatları</h4>
                <?php if ($user['profile']) { ?>
                    <img src="/Backend/mohtesemTask2/auth/uploads/profile_pics/<?= htmlspecialchars($user['profile']) ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <?php } ?>
                <p><strong>Ad:</strong> <?= htmlspecialchars($user['name']) ?></p>
                <p><strong>Soyad:</strong> <?= htmlspecialchars($user['surname']) ?></p>
                <p><strong>Ata Adı:</strong> <?= htmlspecialchars($user['fatherName']) ?></p>
                <p><strong>Doğum Tarixi:</strong> <?= htmlspecialchars($user['dob']) ?></p>
                <p><strong>Cinsiyyət:</strong> <?= htmlspecialchars($user['gender']) == 'male' ? 'Kişi' : 'Qadın' ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
               <a href="editProfile.php"> <button id="editButton" class="btn btn-warning mt-3">Redaktə et</button></a>
            </div>
        </div>
    </div>

   
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
