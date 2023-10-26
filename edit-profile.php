<?php
include 'config.php';
session_start();

    if(isset($_SESSION['account_id'])){
        $acc_id = $_SESSION['account_id'];
        $user_id =$_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role == 'student') {
            $url = '../collaborate/student-dashboard/classroom';
            $display ="block";
            $info_query = "SELECT account_tbl.email, account_tbl.pass, student_tbl.name, student_tbl.course, student_tbl.contact_number, student_tbl.address, student_tbl.profile, research_tbl.research_title
                FROM student_tbl
                JOIN research_tbl ON research_tbl.student_id = student_tbl.student_id
                JOIN account_tbl ON student_tbl.student_id = account_tbl.user_id
                WHERE student_tbl.student_id = ? AND role = ?";
        } elseif ($role == 'adviser') {
            $url = '../collaborate/prof-dashboard/course';
            $display ='none';
            $info_query = "SELECT account_tbl.email, account_tbl.pass, adviser_tbl.name, adviser_tbl.course, adviser_tbl.contact_number, adviser_tbl.address, adviser_tbl.profile
                FROM adviser_tbl
                JOIN account_tbl ON adviser_tbl.adviser_id = account_tbl.user_id
                WHERE adviser_tbl.adviser_id = ? AND role = ?";
        }

        $info_stmt = $mysqli->prepare($info_query);
        $info_stmt->bind_param('is', $user_id, $role);
        $info_stmt->execute();
        $info_result = $info_stmt->get_result();
        $user_data = $info_result->fetch_assoc();

        $profile = $address = $contact_number = $course = $name = $email = $research_title ='';

        if ($user_data) {
            $email = $user_data['email'];
            $storedHashedPassword = $user_data['pass'];
            $name = $user_data['name'];
            $course = $user_data['course'];
            $contact_number = $user_data['contact_number'];
            $address = $user_data['address'];
            $profile = $user_data['profile'];

            if ($role == 'student') {
                $research_title = $user_data['research_title'];
            }
        }
    }else{
        header("Location:../index");
    }

    if (isset($_POST['update'])) {
        $form_name = htmlspecialchars($_POST['fullname']);
        $form_address = htmlspecialchars($_POST['address']);
        $form_course = htmlspecialchars($_POST['course']);
        $form_email = htmlspecialchars($_POST['email']);
        $form_number = htmlspecialchars($_POST['contact-no']);
        $form_research = htmlspecialchars($_POST['research']);
        $password = htmlspecialchars($_POST['psw']);
    
        if (password_verify($password, $storedHashedPassword)) {
            if ($role == 'student') {
                $update_user_tbl = "UPDATE student_tbl SET name=?, course=?, contact_number=?, address=?, profile=? WHERE student_id=?";
            } elseif ($role == 'adviser') {
                $update_user_tbl = "UPDATE adviser_tbl SET name=?, course=?, contact_number=?, address=?, profile=? WHERE adviser_id=?";
            }
    
            if (isset($_FILES['avatar-file']) && !empty($_FILES['avatar-file']['name'])) {
                $upload_directory = 'profiles/';
                $original_filename = $_FILES['avatar-file']['name'];
                $filename_parts = pathinfo($original_filename);
                $extension = $filename_parts['extension'];
                $form_profile = time() . '_' . uniqid() . '.' . $extension;
                $upload_path = $upload_directory . $form_profile;
    
                if (move_uploaded_file($_FILES['avatar-file']['tmp_name'], $upload_path)) {
                    $update_user_stmt = $mysqli->prepare($update_user_tbl);
                    $update_user_stmt->bind_param('sssssi', $form_name, $form_course, $form_number, $form_address, $form_profile, $user_id);
                    $update_user_stmt->execute();
                } else {
                    // Handle the case when avatar upload fails
                    // You might want to add error handling here
                }
            } else {
                // No new avatar provided, update the user's profile without changing the avatar
                $update_user_stmt = $mysqli->prepare($update_user_tbl);
                $update_user_stmt->bind_param('sssssi', $form_name, $form_course, $form_number, $form_address, $profile, $user_id);
                $update_user_stmt->execute();
            }
    
            // Update the email in the account table
            $update_account_tbl = "UPDATE account_tbl SET email=? WHERE account_id=?";
            $update_acc_stmt = $mysqli->prepare($update_account_tbl);
            $update_acc_stmt->bind_param('si', $form_email, $acc_id);
            $update_acc_stmt->execute();
        }
        header("Location:../collaborate/edit-profile");
        exit();
    }
    
    
?>


<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Edit Profile</title>
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
    <link rel="stylesheet" href="assets/css/create-course.css">
    <link rel="stylesheet" href="assets/css/Sign-Up-Form.css">
</head>

<body style="display: block;--bs-secondary: #171717;--bs-secondary-rgb: 23,23,23;border-style: none;">
    <div class="profile-card" style="border:none;">
        <div class="profile-back" style="background: rgb(12,53,106);border-style: none;"></div>
    </div>
    <div class="container profile profile-view" id="profile">
        <div class="ro">
            <div class="col-md-12 alert-col relative" style="display:block">
                <div class="alert alert-info absolue center" role="alert" >
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="alert"></button><span>Profile save with success</span></div>
            </div>
        </div>
        <form style="border-width: 0px;" method="POST" enctype="multipart/form-data">
            <div class="row profile-row">
                <div class="col-md-4 relative">
                    <div class="avatar">
                        <img class="rounded-circle" id="user-img" src="profiles/<?php echo $profile;?>" style="width: 200px;height: 200px;margin-left: 52px; object-fit: cover;"></div>
                        <label class="form-label" id="upload-img" for="avatar" style="transform: translateX(196px) translateY(-50px);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-camera-fill" style="font-size: 40px;color: rgb(72,72,72);display: flex;">
                            <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"></path>
                            <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"></path>
                        </svg></label>
                        <input class="form-control" type="file" id="avatar" name="avatar-file">
                </div>
                <div class="col-md-8">
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                            
                            <label class="form-label" style="font-weight: bold;color: rgb(12,53,106);">Fullname</label>
                                <input class="form-control" type="text" value="<?php echo $name; ?>" name="fullname" style="border-radius: 10px;font-family: Alexandria, sans-serif;" oninput="textDot(event)"></div>
                            <div class="form-group mb-3" style="margin-bottom: 0px;">
                            
                            <label class="form-label" style="font-weight: bold;color: rgb(12,53,106);">Address</label>
                                <input class="form-control" type="text" value="<?php echo $address; ?>" name="address" style="border-radius: 10px;font-family: Alexandria, sans-serif;" oninput="addressReg(event)">
                            
                            <label class="form-label" style="font-weight: bold;color: rgb(12,53,106);">Course</label>
                                <input class="form-control" type="text" value="<?php echo $course; ?>" name="course" style="border-radius: 10px;font-family: Alexandria, sans-serif;" oninput="textDot(event)">
                                </div>
                            
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                            <label class="form-label" style="font-weight: bold;color: rgb(12,53,106);">Email</label>
                            <div id="emailMessage" style="font-size:small"></div>
                                <input class="form-control" type="text" value="<?php echo $email; ?>" name="email" style="border-radius: 10px;font-family: Alexandria, sans-serif;" oninput="checkEmail()">
                            </div>
                            <div class="form-group mb-3">
                            <label class="form-label" style="font-weight: bold;color: rgb(12,53,106);">Contact no.</label>
                                <input class="form-control" type="text" value="<?php echo $contact_number; ?>" name="contact-no" style="border-radius: 10px;font-family: Alexandria, sans-serif;" maxlength="12" oninput="numbers(event)">

                                <label class="form-label" style="font-weight: bold;color: rgb(12,53,106); display: <?php echo $display;?>">Research Title:</label>
                                    <input class="form-control" type="text" value="<?php echo $research_title; ?>"  name="research" style="border-radius: 10px;font-family: Alexandria, sans-serif; display: <?php echo $display;?>" oninput="addressReg(event)">
                            </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 content-right">
                            <button class="btn btn-primary form-btn" id="add-class" style="background: rgb(12,53,106);border-style: none;">SAVE</button>
                            <button class="btn btn-danger form-btn" type="reset" id="cancelButton" style="background: rgb(255,210,0);border-style: none;">CANCEL</button></div>
                    </div>
                </div>
            </div>
            </div>

        <div class="create-course" id="popup">
        <div class="create-prev">
                <div class="create-box-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x-circle-fill" id="close-btn" style="--bs-danger: #dc3545;--bs-danger-rgb: 220,53,69;color: #dc3545;font-size: 29px;">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                    </svg>
                    <p class="create-paragraph">Type Password to save changes?</p>
                </div>
                <form method="POST" style="border:none">
                <input type="password" style="border-radius: 10px;" name="psw" required placeholder="Password" oninput="passwReg(event)">
                <button id="submit-id-submit" class="btn btn-primary text-center d-block box-shadow w-100" name="update" type="submit" style="background: rgb(12,53,106);padding-top: 10px;margin-right: 2px;margin-left: -2px; ">update</button>
                <form>
            </div>
        </div>
    
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/edit-profile.js"></script>
    <script src="assets/js/validateInput.js"></script>
    <script src="assets/js/preview-text.js"></script>
    <script>
        const cancelButton = document.getElementById('cancelButton');

        cancelButton.addEventListener('click', function() {
            window.location.href = '<?php echo $url; ?>';
        });
    </script>

</body>
</html>