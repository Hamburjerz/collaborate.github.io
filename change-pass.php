<?php
include 'config.php';

if (isset($_POST['change-pass'])) {
    $token = htmlspecialchars($_GET['token']); //hashed token

    $psw = $_POST['psw'];
    $psw2 = $_POST['psw2'];

    // Check if the token is available in the database
    $checkTokenSql = 'SELECT * FROM account_tbl WHERE token = ?';
    $checkTokenStmt = $mysqli->prepare($checkTokenSql);

    if ($checkTokenStmt) {
        $checkTokenStmt->bind_param("s", $token);
        $checkTokenStmt->execute();
        $result = $checkTokenStmt->get_result();

        if ($result->num_rows === 0) {
            $color = "red";
            $error = "Token not found. It may have expired or is invalid.";
        } else {
            // Token is valid; continue with password update
            if ($psw != $psw2 || strlen($psw) < 8) {
                $color = "red";
                $error = "Invalid password! Password must be at least 8 characters.";
            } else {
                $hashedPassword = password_hash($psw, PASSWORD_DEFAULT);

                $updatePassSql = 'UPDATE account_tbl SET pass = ? WHERE token = ?';
                $updatePassStmt = $mysqli->prepare($updatePassSql);

                if ($updatePassStmt) {
                    $updatePassStmt->bind_param("ss", $hashedPassword, $token);
                    $updatePassStmt->execute();

                    $updateTokenSql = 'UPDATE account_tbl SET token = ? WHERE token = ?';
                    $updateTokenStmt = $mysqli->prepare($updateTokenSql);

                    if ($updateTokenStmt) {
                        $emptyToken = '';
                        $updateTokenStmt->bind_param("ss", $emptyToken, $token);
                        $updateTokenStmt->execute();

                        header("Location: login");
                        exit();
                    } else {
                        $color = "red";
                        $error = "Token Invalid!";
                    }
                } else {
                    $color = "red";
                    $error = "Error updating password.";
                }
            }
        }
    } else {
        $color = "red";
        $error = "Token Invalid!";
    }
}
$mysqli->close();
?>


<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Password</title>
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
    <footer style="padding-top: 0px;">
        <nav class="navbar navbar-expand-md bg-body py-3" style="border-bottom: 2px solid rgb(12,53,106) ;">
            <div class="container"><a href="index"><img src="assets/img/logo.png"></a>
                <div class="collapse navbar-collapse" id="navcol-2">
                    
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="d-flex flex-column justify-content-center" id="login-box">
            <form method="POST" style="border:none;">
                <div class="login-box-header">
                    <p style="font-weight: bolder">"Empower Your Security:<br>Forge a Fortified Password"</p>
                </div>
                <div class="email-login" style="background-color: #ffffff;">
                <div id="passwordMessage" style="font-size:small; color: 
                <?php if(isset($color)){
                            echo $color;
                        }else{
                            echo $color;
                        } ?>">
                <?php if(isset($error)){
                        echo $error; }
                        else{
                            $error="";
                        }?></div>
                <input name="psw" type="password" class="password-input form-control" style="margin-top: 10px;border-radius: 5px;margin-bottom: 0px;" required placeholder="Create Password" minlength="8" maxlength="20" oninput="handlePasswordInput(event)">
                <input name="psw2" type="password" class="password-input form-control" style="margin-top: 10px;border-radius: 5px;margin-bottom: 0px;" required placeholder="Re-type Password" minlength="8" maxlength="20" oninput="handlePasswordInput(event)"></div>
                <div class="submit-row" style="margin-bottom: 8px;padding-top: 0px;">
                <button class="btn btn-primary text-center d-block box-shadow w-100" name="change-pass" type="submit" id="submit-id-submit" style="background: rgb(12,53,106);padding-top: 10px;margin-right: 2px;margin-left: -2px;">Submit</button>
                    <div class="d-flex justify-content-between"></div>
                </div>
                </form>
            </div>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/validateInput.js"></script>

</body>

</html>