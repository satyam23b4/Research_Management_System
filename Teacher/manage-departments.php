<?php
session_start();

// Check if teacher is logged in
if (!isset($_SESSION["LoginTeacher"])) {
    header('location:../login/login.php');
    exit;
}

// Include database connection
require_once "../connection/connection.php";

// Fetch department names from the database
$departmentQuery = "SELECT department_name FROM departments";
$departmentResult = mysqli_query($con, $departmentQuery);

// Array to store department names
$departments = [];
while ($row = mysqli_fetch_assoc($departmentResult)) {
    $departments[] = $row['department_name'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $teacher_email = $_SESSION['LoginTeacher'];
    $selected_departments = isset($_POST['departments']) ? $_POST['departments'] : [];

    if (!empty($selected_departments)) {
        // Get teacher ID from the database
        $getTeacherIDQuery = "SELECT teacher_id FROM teacher_info WHERE email = '$teacher_email'";
        $teacherIDResult = mysqli_query($con, $getTeacherIDQuery);
        $teacherIDRow = mysqli_fetch_assoc($teacherIDResult);
        $teacher_id = $teacherIDRow['teacher_id'];

        // Remove existing department entries for this teacher
        $deleteDeptQuery = "DELETE FROM teacher_departments WHERE teacher_id = '$teacher_id'";
        $deleteDeptResult = mysqli_query($con, $deleteDeptQuery);

        // Insert selected departments into teacher_departments
        foreach ($selected_departments as $department) {
            $insertDeptQuery = "INSERT INTO teacher_departments (teacher_id, department_name) VALUES ('$teacher_id', '$department')";
            $insertDeptResult = mysqli_query($con, $insertDeptQuery);
        }

        if ($insertDeptResult) {
            // Redirect after successful submission
            header("location: manage-departments.php?success=1");
            exit;
        } else {
            echo "<script>alert('Error updating departments');</script>";
        }
    } else {
        echo "<script>alert('Please select at least one department');</script>";
    }
}

// Fetch departments again for the form
$departmentResult = mysqli_query($con, $departmentQuery);
$departments = [];
while ($row = mysqli_fetch_assoc($departmentResult)) {
    $departments[] = $row['department_name'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Include jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        #saveDepartmentBtn {
            background-color: #007bff;
            border-color: #007bff;
        }

        #saveDepartmentBtn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-container {
            text-align: center;
        }

        .alert-message {
            margin-top: 20px;
        }

        .alert {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <?php include('../common/common-header.php') ?>
    <?php include('../common/teacher-sidebar.php') ?>  

    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 main-background mb-2 w-100">
        <div class="sub-main">
            <div class="text-center d-flex flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 text-white admin-dashboard pl-3" style="background-color: #343a40;">
                <h4 class="">Manage Departments</h4>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form id="departmentForm" action="manage-departments.php" method="post">
                            <div class="form-group">
                                <label for="department">Choose Departments:</label>
                                <br>
                                <?php
                                foreach ($departments as $dept) {
                                    echo "<input type='checkbox' name='departments[]' value='$dept'> $dept<br>";
                                }
                                ?>
                            </div>
                            <div class="form-group btn-container">
                                <input type="submit" id="saveDepartmentBtn" value="Save Departments" name="submit" class="btn btn-primary">
                            </div>
                        </form>
                        <?php
                        if (isset($_POST['submit'])) {
                            if (!empty($selected_departments)) {
                                echo "<div class='alert alert-success alert-message' role='alert'>
                                        Departments updated successfully
                                    </div>";
                            } else {
                                echo "<div class='alert alert-danger alert-message' role='alert'>
                                        Please select at least one department
                                    </div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include necessary JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        // Your JavaScript code for form validation and functionality here
    </script>
</body>
</html>

