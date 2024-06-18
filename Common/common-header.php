<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="$baseUrl/../Images/mit.jpg" type="image/x-icon">
    
    <!-- CSS styles -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!-- End of CSS styles -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark header-back sticky-top header-navbar-fonts">
    <a class="navbar-brand d-flex align-items-center" href="../index.php">
        <img src="../images/mit.png" class="logo-image" width="100" height="50">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button> 
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">HOME<span class="sr-only">(current)</span></a>
            </li>
            <!-- Check if it's a teacher login -->
            <?php if (isset($_SESSION["LoginTeacher"])) { ?>
                <li class="nav-item dropdown">
                    <?php
                    // Access teacher profile image
                    $teacher_email = $_SESSION['LoginTeacher'];
                    $query = "SELECT * FROM teacher_info WHERE email='$teacher_email'";
                    $run = mysqli_query($con, $query);
                    $row = mysqli_fetch_array($run);
                    $profile_image = $row ? "../admin/images/" . $row["profile_image"] : "../admin/images/default_profile.jpg"; // Path to teacher images directory
                    ?>
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if ($profile_image) { ?>
                            <img src="<?php echo $profile_image ?>" class="rounded-circle mr-2" width="30" height="30" alt="Profile Image">
                        <?php } else { ?>
                            <img src="../admin/images/default_profile.jpg" class="rounded-circle mr-2" width="30" height="30" alt="Default Profile Image">
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../admin/display-teacher.php">Personal Profile</a>
                        <a class="dropdown-item" href="../teacher/teacher-password.php">Change Password</a>
                        <a class="dropdown-item" href="../login/logout.php">Logout</a>
                    </div>
                </li>
            <?php } ?>
            <!-- Check if it's a student login -->
            <?php if (isset($_SESSION["LoginStudent"])) { ?>
                <li class="nav-item dropdown">
                    <?php
                    // Access student profile image
                    $student_email = $_SESSION['LoginStudent'];
                    $query = "SELECT * FROM student_info WHERE email='$student_email'";
                    $run = mysqli_query($con, $query);
                    $row = mysqli_fetch_array($run);
                    $profile_image = $row ? "../admin/images/" . $row["profile_image"] : "../admin/images/default_image.jpg"; // Path to student images directory
                    ?>
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if ($profile_image) { ?>
                            <img src="<?php echo $profile_image ?>" class="rounded-circle mr-2" width="30" height="30" alt="Profile Image">
                        <?php } else { ?>
                            <img src="../admin/images/default_profile.jpg" class="rounded-circle mr-2" width="30" height="30" alt="Default Profile Image">
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../admin/display-student.php">Personal Profile</a>
                        <a class="dropdown-item" href="../student/student-password.php">Change Password</a>
                        <a class="dropdown-item" href="../login/logout.php">Logout</a>
                    </div>
                </li>
            <?php } ?>
            <!-- For admin login and unauthorized login -->
            <?php if (isset($_SESSION["LoginAdmin"])) { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item disabled" href="#">Personal Profile</a>
                        <a class="dropdown-item disabled" href="#">Change Password</a>
                        <a class="dropdown-item" href="../login/logout.php">Logout</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<!-- Bootstrap JavaScript -->
<script src="../bootstrap/js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Initialize Bootstrap dropdown -->
<script>
    $(document).ready(function(){
        $('.dropdown-toggle').dropdown();
    });
</script>

<!-- Custom CSS -->
<style>
    .dropdown-menu-right {
        right: 0 !important;
        left: auto !important;
    }
    .dropdown-menu .disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .navbar-nav {
        align-items: center; /* Center items vertically */
    }
    .nav-link {
        margin-right: 10px; /* Add some space between nav links */
    }
</style>
</body>
</html>
