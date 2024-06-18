<?php 
session_start();
require_once "../connection/connection.php"; 

$message = "";

if(isset($_POST["btnlogin"])) {
    $username = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM login WHERE user_id='$username' AND Password='$password'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if ($row["Role"] == "Admin") {
                $_SESSION['LoginAdmin'] = $row["user_id"];
                header('Location: ../admin/admin-index.php');
                exit(); 
            } elseif ($row["Role"] == "Teacher") {
                if ($row["account"] == "Activate") {
                    $_SESSION['LoginTeacher'] = $row["user_id"];
                    header('Location: ../teacher/teacher-index.php');
                    exit(); 
                } else {
                    $message = "Your account is not activated yet. Please contact admin.";
                }
            } elseif ($row["Role"] == "Student") {
                if ($row["account"] == "Activate") {
                    $_SESSION['LoginStudent'] = $row['user_id'];
                    header('Location: ../student/student-index.php');
                    exit(); 
                } else {
                    $message = "Your account is not activated yet. Please contact admin.";
                }
            }
        }
    } else { 
        $message = "Incorrect username or password"; 
        echo "<script>alert('$message');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body class="login-background">
    <?php include('../common/common-header.php') ?>
    <div class="login-div mt-3 rounded">
        <div class="logo-div text-center">
            <img src="../Images/mit.jpg" alt="" class="align-items-center" width="100" height="100">
        </div>
        <div class="login-padding">
            <h2 class="text-center text-white">LOGIN</h2>
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form class="p-1" action="login.php" method="POST">
                <div class="form-group">
                    <label><h6>Enter Your ID/Email:</h6></label>
                    <input type="text" name="email" placeholder="Enter User ID" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><h6>Enter Password:</h6></label>
                    <input type="password" name="password" placeholder="Enter Password" class="form-control border-bottom" required>
                </div>
                <div class="form-group text-center mb-3 mt-4">
                    <input type="submit" name="btnlogin" value="LOGIN" class="btn btn-white pl-5 pr-5 ">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
