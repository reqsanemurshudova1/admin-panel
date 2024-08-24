<?php
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/PHPMailer/Exception.php';
require '../vendor/PHPMailer/PHPMailer.php';
require '../vendor/PHPMailer/SMTP.php';
include_once "../index.php";
include_once "../helper/help.php";

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = validate(["name", "surname", "fatherName", "dob", "gender", "email", "password", "confirmPassword"]);
    $profilePicPath = null;

    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        $uploadDirectory = 'uploads/profile_pics/';
        $profilePicPath = file_upload($uploadDirectory, $_FILES['profile']);

        if (!$profilePicPath) {
            $errors['profile'] = "Profil şəkili yüklənə bilmədi.";
        }
    }

    if (empty($errors)) {
        if (post("password") != post("confirmPassword")) {
            $errors["confirmPassword"] = "Şifrələr uyğun gəlmir.";
        } else {
            $otp = rand(100000, 999999); // 6 rəqəmli OTP yaradırıq
            $password = password_hash(post("password"), PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, surname, fatherName, dob, gender, email, password, profile, otp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $regQuery = $connection->prepare($query);
            $check = $regQuery->execute([
                post("name"),
                post("surname"),
                post("fatherName"),
                post("dob"),
                post("gender"),
                post("email"),
                $password,
                $profilePicPath,
                $otp // OTP dəyərini məlumat bazasına əlavə edirik
            ]);
        }
    }

    if (isset($check) && $check) {
        $_SESSION['otp_email'] = post("email");
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_ttl'] = time() + 300; // 5 dəqiqə vaxt limiti

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "murshudova17@gmail.com";
            $mail->Password = "dxha etrn tueh imxp";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom("murshudova17@gmail.com", "Reqsane");
            $mail->addAddress(post("email")); // İstifadəçinin e-poçt ünvanı
            $mail->isHTML(true);
            $mail->Subject = "OTP";
            $mail->Body = "OTP: " . $otp;
            $mail->send();
            view(route('auth/otp.php'));
            exit();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}


?>

<div class="register-container">
    <h2>Register</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="name" placeholder="Enter your first name"
                required>
        </div>
        <div class="form-group">
            <label for="surname">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname"
                required>
        </div>
        <div class="form-group">
            <label for="fatherName">Father's Name</label>
            <input type="text" class="form-control" id="fatherName" name="fatherName"
                placeholder="Enter your father's name">
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label>Gender</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                <label class="form-check-label" for="female">Female</label>
            </div>
        </div>
        <div class="form-group">
            <label for="profilePic">Profile Picture</label>
            <input type="file" class="form-control" id="profilePic" name="profile">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password"
                required>
        </div>
        <div class="form-group">
            <label for="password">Password Confirm</label>
            <input type="password" class="form-control" id="password" name="confirmPassword" placeholder="Enter your password"
                required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>