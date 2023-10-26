<?php
include 'config.php';

if (isset($_POST["signup"])) {
    $name = htmlspecialchars($_POST["name"]);
    $course = htmlspecialchars($_POST["course"]);
    $number = htmlspecialchars($_POST["contact"]);
    $address = htmlspecialchars($_POST["address"]);
    $research_title = htmlspecialchars($_POST["research"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["psw"];
    $password2 = $_POST["psw2"];
    $currentDate = date("Y-m-d");
    $role = "student";
    $profile ="profile.png";

    // Validate password length or other criteria as needed
    if (strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password)) {
        if ($password == $password2) {
            $checkEmailSql = "SELECT COUNT(*) FROM account_tbl WHERE email = ?";
            $checkEmailStmt = $mysqli->prepare($checkEmailSql);

            if ($checkEmailStmt) {
                $checkEmailStmt->bind_param("s", $email);
                $checkEmailStmt->execute();
                $checkEmailStmt->bind_result($emailCount);
                $checkEmailStmt->fetch();
                $checkEmailStmt->close();

                if ($emailCount > 0) {
                    $error = 'Email already exists.';
                        
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Step 1: Insert into student_tbl
                    $insertStudentSql = "INSERT INTO student_tbl (name, course, contact_number, address, profile, date_created) VALUES (?, ?, ?, ?, ?, ?)";
                    $insertStudentStmt = $mysqli->prepare($insertStudentSql);

                    if ($insertStudentStmt) {
                        $insertStudentStmt->bind_param("ssssss", $name, $course, $number, $address, $profile, $currentDate);

                        if ($insertStudentStmt->execute()) {
                            $user_id = $mysqli->insert_id;

                            // Step 2: Insert into account_tbl using the obtained user_id
                            $insertAccountSql = "INSERT INTO account_tbl (user_id, email, pass, role, date_created) VALUES (?, ?, ?, ?, ?)";
                            $insertAccountStmt = $mysqli->prepare($insertAccountSql);

                            if ($insertAccountStmt) {
                                $insertAccountStmt->bind_param("issss", $user_id, $email, $hashed_password, $role, $currentDate);

                                if ($insertAccountStmt->execute()) {

                                    $insertResearch = "INSERT INTO research_tbl (student_id, research_title) VALUES (?,?)";
                                    $insertResearchStmt = $mysqli->prepare($insertResearch);
                                    if ($insertResearchStmt) {
                                        $insertResearchStmt->bind_param("is", $user_id, $research_title);
                                        if ($insertResearchStmt->execute()) {
                                            $insertResearchStmt->close();
                                            header("Location: login.php");
                                            
                                        }
                                        else{
                                            echo "ERROR: " . $insertResearchStmt->error;
                                        }
                                    }
                                } else {
                                    echo "Error inserting data into account_tbl: " . $insertAccountStmt->error;
                                }
                                $insertAccountStmt->close();
                            } else {
                                echo "Error preparing account_tbl insert statement: " . $mysqli->error;
                            }
                        } else {
                            echo "Error inserting data into student_tbl: " . $insertStudentStmt->error;
                        }
                        $insertStudentStmt->close();
                    } else {
                        $error = "Error preparing student_tbl insert statement: " . $mysqli->error;
                    }
                }
            } else {
                $error =  "Error preparing email check statement: ";
            }
        } else {
            $pswError = "Passwords do not match.";
        }
    } else {
        $pswError = "Password must be at least 8 characters long.";
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Actor&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alexandria&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Assistant&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bevan&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+Indic+Siyaq+Numbers&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,400i,700,700i&amp;display=swap">
    <link rel="stylesheet" href="assets/css/Community-ChatComments.css">
    <link rel="stylesheet" href="assets/css/Login-Box-En-login-box-en.css">
    <link rel="stylesheet" href="assets/css/Navbar-With-Button-icons.css">
    <link rel="stylesheet" href="assets/css/sign-up.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sign-Up-Form.css">
</head>

<body style="color: rgb(20,54,156);">
    <header class="pt-5" style="margin-top: -47px;"></header>
    <nav class="navbar navbar-expand-md bg-body py-3" style="border-bottom: 2px solid rgb(12,53,106) ;">
        <div class="container"><a href="index"><img src="assets/img/logo.png"></a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navcol-2" style="width: 60px;"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-2">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="login" style="font-size: 20px;"><strong><span style="color: rgb(33, 33, 33);">Login</span></strong></a></li>
                </ul><a class="btn btn-primary ms-md-2" role="button" href="signup" style="margin-bottom: 0px;border-radius: 5px;background: rgb(12,53,106);border-style: none;margin-left: 0px;">Sign up</a>
            </div>
        </div>
    </nav>
    <form action="" method="POST" style="border:none;">
    <div class="row" style="align-items: center;display: flex;justify-content: center;margin-top: 30px; margin-right: 0px;">
        <div class="col-sm-10 col-md-10 col-xl-10 col-xxl-7 offset-md-0 offset-lg-0 offset-xl-0 offset-xxl-0" style="box-shadow: 0px 0px 20px 2px;border-radius: 10px;">
        <div style="padding-left: 10%;padding-top: 20px;">
            <h2 style="font-size: 34px;border-color: rgb(12,53,106);color: rgb(12,53,106);"><strong>Join now!</strong></h2>
            <p style="margin-bottom: 3px;border-color: rgb(12,53,106);color: rgb(12,53,106);">Please fill in this form to create an account.</p>
        </div>
            <div class="row" style="margin-top: 20px;border-radius: 10px;">
                <div class="col-md-6 col-xl-5 offset-xl-1 offset-xxl-1" style="padding-top: 0px;padding-left: 20px;padding-right: 20px;">
                    <div class="gc004-container">
                        <label class="form-label fw-bold" for="name">Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="Juan D. Dela Cruz" oninput="textDot(event)">

                        <label class="form-label fw-bold" for="course">Course</label>
                        <input type="text" class="form-control" placeholder="Course" name="course" required oninput="textDot(event)">

                        <label class="form-label fw-bold" for="research">Research Title</label>
                        <input type="text" class="form-control" placeholder="Research Title" name="research" required oninput="addressReg(event)">

                        <label class="form-label fw-bold" for="address">Address</label>
                        <input type="text" class="form-control" placeholder="Address" name="address" required oninput="addressReg(event)">
                    </div>
                </div>
                <div class="col-md-6 col-xl-5 offset-xl-0 offset-xxl-0" style="padding-top: 0px;padding-left: 20px;padding-right: 20px;">
                    <div class="gc004-container">
                            <label class="form-label fw-bold" for="email">Email</label>
                            <div id="emailMessage" style="color: red; font-size: small">
                                <?php
                                if (isset($error)) {
                                    echo $error;
                                }
                                ?>
                            </div>
                            <input type="text" class="form-control" placeholder="Email" id="email" name="email" required oninput="checkEmail()">

                            <label class="form-label fw-bold" for="contact">Contact no.</label>
                            <input type="text" class="form-control" placeholder="09123456789" name="contact" required maxlength="12" oninput="numbers(event)">
                            
                            <label class="form-label fw-bold" for="psw">Password</label>
                            <input type="password" class="form-control" placeholder="Enter Password" id="psw" name="psw" required minlength="8" maxlength="12" oninput="handlePasswordInput(event)">

                            <label class="form-label fw-bold" for="psw-repeat">Repeat Password</label>
                            <div id="passwordMessage" style="color: red; font-size: small"><?php
                            if(isset($pswError)){
                                echo $pswError;
                            }?></div>
                            <input type="password" class="form-control" placeholder="Repeat Password" id="psw2" name="psw2" required minlength="8" maxlength="12" oninput="handlePasswordInput(event)"> 

                        <div class="d-flex d-xxl-flex align-items-center align-items-xxl-center gc-clearfix">
                            <button class="active text-center gc-cancelbtn" type="reset" style="background: rgb(255,210,0);color: rgb(255,255,255);">Cancel</button>
                            <button class="gc-signupbtn" name="signup" type="submit" style="background: rgb(12,53,106);">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <footer></footer>
    <div class="container py-4 py-lg-5">
        <hr>
        <div class="text-muted d-flex justify-content-between align-items-center pt-3">
            <p class="mb-0">Copyright © 2023 Brand</p>
            <ul class="list-inline mb-0">
                <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-facebook">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"></path>
                    </svg></li>
                <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-twitter">
                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path>
                    </svg></li>
                <li class="list-inline-item"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-instagram">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"></path>
                    </svg></li>
            </ul>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/preveiw-file.js"></script>
    <script src=".assets/js/preveiw-text.js"></script>
    <script src=".assets/js/Profile-Edit-Form-profile.js"></script>
    <script src="assets/js/validateInput.js"></script>
</body>

</html>