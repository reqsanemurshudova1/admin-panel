<?php

$errors = [];
include_once "../index.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validate(["otp"]);
    
    if (empty($errors)) {
        $otp = $_POST["otp"];
        $confOtp = $_SESSION["otp"];
        $otp_ttl = $_SESSION['otp_ttl'];
        $user_email = $_SESSION['otp_email'];

        if (time() <= $otp_ttl) {
            if ($otp == $confOtp) {
                $sql = "UPDATE users SET otp = null WHERE email = ?";
                $updateQuery = $connection->prepare($sql);
                $check = $updateQuery->execute([$user_email]);

                if ($check) {
                    // Sessiyanı sıfırlamaq
                    $_SESSION = [];
                    session_destroy();
                    header('Location: ' . route("auth/login.php"));
                    exit();
                } else {
                    $errors['otp'] = "OTP təsdiqlənmədi. Təkrar cəhd edin.";
                }
            } else {
                $errors['otp'] = "Daxil edilmiş OTP yanlışdır.";
            }
        } else {
            $errors['otp'] = "OTP vaxtı bitmişdir.";
        }
    }
}
?>
<div class="otp-container">
        <h2>OTP Verification</h2>
        <form method="POST">
            <div class="form-group" >
                <label for="otp">Enter OTP</label>
                <input type="text" class="form-control" id="otp" maxlength="6" placeholder="Enter OTP" required name="otp">
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>
