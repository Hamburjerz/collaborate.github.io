<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include 'config.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $sql = "SELECT email FROM account_tbl WHERE email = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $token = bin2hex(random_bytes(32));
            $inputToken = "UPDATE account_tbl SET token = ? WHERE email = ?";
            $stmt2 = $mysqli->prepare($inputToken);
        
            if ($stmt2) {
                
                $stmt2->bind_param("ss", $token, $email);
                $stmt2->execute();
                
            }         
                else{
                    echo "invalid token";
                }
            try {
                
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'martinezlloydangelo21@gmail.com'; //gmail account
                $mail->Password = 'djec vooy qemk fdbb'; //sub password
                $mail->Port = 587; 

                $mail->setFrom('martinezlloydangelo21@gmail.com');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset';
                $mail->Body = "Click the following link to reset your password: <a href='http://localhost/collaborate/change-pass?token=$token'>Reset Password</a>";
                $mail->AltBody = '';

                $mail->send();
                $error = '<script>
                var errorMessage = document.getElementById("errorMessage");
                errorMessage.style.color = "green";
                errorMessage.innerHTML = "Password reset link sent to your email";
                </script>';
                
            } catch (Exception $e) {
                $error = '<script>
                var errorMessage = document.getElementById("errorMessage");
                errorMessage.style.color = "red";
                errorMessage.innerHTML = "System Error.";
                </script>';
            }
        } else {
            $error = '<script>
            var errorMessage = document.getElementById("errorMessage");
            errorMessage.style.color = "red";
            errorMessage.innerHTML = "Email not found";
            </script>';
        }
        $stmt->close();
    } else {
        $error = '<script>
            var errorMessage = document.getElementById("errorMessage");
            errorMessage.style.color = "red";
            errorMessage.innerHTML = "System Error";
            </script>';
    }
}
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Actor&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alexandria&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Assistant&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bevan&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,400i,700,700i&amp;display=swap">
    <link rel="stylesheet" href="assets/css/Community-ChatComments.css">
    <link rel="stylesheet" href="assets/css/Login-Box-En-login-box-en.css">
    <link rel="stylesheet" href="assets/css/Navbar-With-Button-icons.css">
    <link rel="stylesheet" href="assets/css/Profile-Card.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sign-Up-Form.css">
</head>

<body>
    <footer style="padding-top: 0px; font-family: Alexandria, sans-serif;" >
        <nav class="navbar navbar-expand-md bg-body py-3" style="border-bottom: 2px solid rgb(12,53,106) ;">
            <div class="container"><a href="index"><img src="assets/img/logo.png"></a></div>
        </nav>
        <div class="container">
            <div class="d-flex flex-column justify-content-center" id="login-box">
                <div class="login-box-header">
                    <a href="index"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x-circle-fill" id="close-btn" style="--bs-danger: #dc3545;--bs-danger-rgb: 220,53,69;color: #dc3545;font-size: 29px;" >
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                    </svg></a>
                    <p style="margin-bottom: 1px;margin-top: 31px;">Engage in cooperative work by entering the class code.</p>
                </div>
                <div class="email-login" style="background-color: #ffffff;">
                <form action="" method="POST" style="border:none">
                <div id="errorMessage" style="font-size:small;"> 
                    <?php
                        if (isset($error)) {
                            echo $error;
                            } else {
                            $error = "";
                            }
                    ?></div>
                    <input type="text" name="email" placeholder="email" style="border-radius: 10px;" required style="font-family: Alexandria, sans-serif;" oninput="emailReg(event)">
                </div>
                <div class="submit-row" style="margin-bottom: 8px;padding-top: 0px;">
                <button class="btn btn-primary text-center d-block box-shadow w-100" type="submit" name="submit" id="submit-id-submit" style="background: rgb(12,53,106);margin-right: 2px;margin-left: -2px;">submit</button>
                </form>
                    <div class="d-flex justify-content-between"></div>
                </div>
                <div id="login-box-footer" style="padding: 10px 20px;padding-bottom: 23px;padding-top: 18px;">
                    <p style="margin-bottom: 0px;">Don't you have an account?<a id="register-link" href="signup.php">Sign Up!</a></p>
                </div>
            </div>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/validateInput.js"></script>
</body>

</html>