<?php
session_start();
include 'config.php';

if (isset($_SESSION['account_id'])) {
    $userID = $_SESSION['account_id'];

    $select_role = "SELECT user_id, role FROM account_tbl WHERE account_id = ?";
    $stmt = $mysqli->prepare($select_role);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt_result = $stmt->get_result();
    
    if ($stmt_result) {
        $user = $stmt_result->fetch_assoc();
    
        if ($user) {
            $user_id = $user['user_id'];
            $role = $user['role'];
        }
    }
    
    if ($role == 'student') {
        $display ="block";
        $url = '../collaborate/student-dashboard/classroom';
        $info_query = "SELECT account_tbl.email, student_tbl.name, student_tbl.course, student_tbl.contact_number, student_tbl.address, student_tbl.profile, research_tbl.research_title
            FROM student_tbl
            JOIN research_tbl ON research_tbl.student_id = student_tbl.student_id
            JOIN account_tbl ON student_tbl.student_id = account_tbl.user_id
            WHERE student_tbl.student_id = ? AND role = ?";
    } elseif ($role == 'adviser') {
        $display ='none';
        $url = '../collaborate/prof-dashboard/course';
        $info_query = "SELECT account_tbl.email, adviser_tbl.name, adviser_tbl.course, adviser_tbl.contact_number, adviser_tbl.address, adviser_tbl.profile
            FROM adviser_tbl
            JOIN account_tbl ON adviser_tbl.adviser_id = account_tbl.user_id
            WHERE adviser_tbl.adviser_id = ? AND role = ?";
    }    
    $info_stmt = $mysqli->prepare($info_query);
    $info_stmt->bind_param('is', $user_id, $role);
    $info_stmt->execute();
    $info_result = $info_stmt->get_result();
    $user_data = $info_result->fetch_assoc();
    $research_title ='';
    if ($user_data) {
        $email = $user_data['email'];
        $name = $user_data['name'];
        $course = $user_data['course'];
        $contact_number = $user_data['contact_number'];
        $address = $user_data['address'];
        $profile = $user_data['profile'];

        if ($role == 'student') {
            $research_title = $user_data['research_title'];
        }
    }
}
else{
    header("Location:../index");
}
?>



<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile</title>
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

<body style="display: block;--bs-secondary: #171717;--bs-secondary-rgb: 23,23,23;">
    <div class="profile-card" style="border-width: 0px;border-bottom-color: rgb(12,53,106);">
        <div class="profile-back" style="background: rgb(12,53,106);color: rgb(12,53,106);">
            <a href="<?php echo $url;?>" style="display: flex;width: 50px;padding-top: 12px;padding-left: 0px;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-house-door" style="color: rgb(255,255,255);margin-left: 10px;font-size: 52px;width: 55px;height: 55px;margin-top: 0px;">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"></path>
                </svg></a></div><img class="rounded-circle profile-pic" src="profiles/<?php echo $profile;?>">
        <h3 class="profile-name" style="background: rgb(12,53,106);"><?php echo $email;?></h3>
    </div>
    <div class="container">
        <div class="row">
            <div class="col" style="color: rgb(12,53,106);font-family: Assistant, sans-serif;font-size: 20px;border-bottom-style: none;">
                <h2><strong>Information</strong></h2>
                <hr style="border-bottom-color: rgb(84,84,84);">
                <div class="row">
                    <div class="col-sm-12 col-md-6"><label class="form-label" style="font-weight: bold;">Firstname:</label>
                        <div class="form-group mb-3"><label class="form-label"><?php echo $name;?></label></div>
                    </div>
                    <div class="col-sm-12 col-md-6"><label class="form-label" style="font-weight: bold;">Contact no:</label>
                        <div class="form-group mb-3"><label class="form-label"><?php echo $contact_number;?></label></div>
                    </div>
                    <div class="col-sm-12 col-md-6"><label class="form-label" style="font-weight: bold;">Program:</label>
                        <div class="form-group mb-3"><label class="form-label"><?php echo $course;?></label></div>
                    </div>
                    <div class="col-sm-12 col-md-6"><label class="form-label" style="font-weight: bold;">Address:</label>
                        <div class="form-group mb-3"><label class="form-label"><?php echo $address;?></label></div>
                    </div>
                    <div class="col-sm-12 col-md-6"><label class="form-label" style="font-weight: bold; display:<?php echo $display; ?>">Research Title:</label>
                        <div class="form-group mb-3"><label class="form-label" style="display:<?php echo $display; ?>"><?php if(isset($research_title)){ echo $research_title;}?></label></div>
                    </div>
                    <div class="col-sm-12 col-md-6"><label class="form-label" style="font-weight: bold; display:<?php echo $display; ?>">Date Started:</label>
                        <div class="form-group mb-3"><label class="form-label"style="display:<?php echo $display; ?>">2023-10-12</label></div>
                    </div>
                </div>
                <hr><a class="btn btn-primary form-btn" role="button" href="edit-profile" style="background: rgb(12,53,106);border-radius: 8px;border-bottom-width: 0px;">Edit</a>
                <a href="change-pass" style="color: var(--bs-info);">Change password?</a>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/preveiw-file.js"></script>
    <script src="assets/js/preveiw-text.js"></script>
    <script src="assets/js/Profile-Edit-Form-profile.js"></script>
    <script src="assets/js/progress-bar.js"></script>
</body>

</html>