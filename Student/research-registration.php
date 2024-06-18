<?php
session_start();
if (!$_SESSION["LoginStudent"]) {
    header('location:../login/login.php');
}
require_once "../connection/connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedTeacher'])) {
    $student_email = $_SESSION['LoginStudent'];
    $teacherId = $_POST['selectedTeacher'];
    $departmentId = $_POST['selectedDepartment'];


    $checkQuery = "SELECT roll_no FROM student_info WHERE email = '$student_email'";

    $result = mysqli_query($con, $checkQuery);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $studentId = $row['roll_no'];

    // Insert the request into student_requests table
    $insertQuery = "INSERT INTO student_requests (student_id, teacher_id, department_id, request_status)
                    VALUES ('$studentId', '$teacherId', '$departmentId', 'Pending')";

        // Show alert based on insertion result
     if (mysqli_query($con, $insertQuery)) {
        $message = "Your request has been sent successfully.";
        echo "<script>alert('$message');</script>";
    } else {
        $error = "Error: " . mysqli_error($con);
        echo "<script>alert('$error');</script>";
    }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Registration - RMS</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS for Research Registration page */
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        .department-btn {
            margin-bottom: 10px;
            width: 100%;
        }

        .modal-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
        }

        .modal-title {
            font-weight: bold;
        }

        .teacher-list {
            list-style-type: none;
            padding-left: 0;
        }

        .teacher-list li {
            margin-bottom: 10px;
        }

        .teacher-list li label {
            font-weight: bold;
            cursor: pointer;
        }

        .modal-footer .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .modal-footer .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Include common header and sidebar -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/student-sidebar.php') ?>

    <!-- Main Content -->
    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 mb-2 w-100 page-content-index">
        <div class="container">
            <h2>Select a Department</h2>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    // Fetch departments from the database
                    $departmentQuery = "SELECT * FROM departments";
                    $departmentResult = mysqli_query($con, $departmentQuery);

                    if (mysqli_num_rows($departmentResult) > 0) {
                        while ($row = mysqli_fetch_assoc($departmentResult)) {
                            $departmentId = $row['department_id'];
                            $departmentName = $row['department_name'];
                    ?>
                            <button type="button" class="btn btn-primary department-btn btn-select-teacher" data-toggle="modal" data-target="#teacherModal<?php echo $departmentId; ?>" data-department="<?php echo $departmentId; ?>"><?php echo $departmentName; ?></button>
                            <!-- Teacher Modal -->
                            <div class="modal fade" id="teacherModal<?php echo $departmentId; ?>" tabindex="-1" role="dialog" aria-labelledby="teacherModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="teacherModalLabel">Select a Teacher</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="teacher-list">
                                                <?php
                                                $teachersQuery = "SELECT * FROM teacher_info ti
                                                                  JOIN teacher_departments td ON ti.teacher_id = td.teacher_id
                                                                  WHERE td.department_name = '$departmentName'";
                                                $teachersResult = mysqli_query($con, $teachersQuery);
                                                if (mysqli_num_rows($teachersResult) > 0) {
                                                    while ($teacherRow = mysqli_fetch_assoc($teachersResult)) {
                                                        echo "<li>";
                                                        echo "<input type='radio' id='teacher" . $teacherRow['teacher_id'] . "' name='selectedTeacher' value='" . $teacherRow['teacher_id'] . "'>";
                                                        echo "<label for='teacher" . $teacherRow['teacher_id'] . "'>" . $teacherRow['first_name'] . " " . $teacherRow['last_name'] . "</label>";
                                                        echo "</li>";
                                                    }
                                                } else {
                                                    echo "<li>No teachers found for this department.</li>";
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="sendRequest()">Select Teacher</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<div class='alert alert-warning'>No departments found</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Hidden form for submitting the request -->
        <form id="requestForm" method="POST" style="display: none;">
            <input type="hidden" id="selectedTeacher" name="selectedTeacher">
            <input type="hidden" id="selectedDepartment" name="selectedDepartment">
        </form>

        <!-- Include Bootstrap JS and jQuery -->
        <script src="../bootstrap/js/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script>
            function sendRequest() {
                var selectedTeacher = document.querySelector('input[name="selectedTeacher"]:checked');
                var selectedDepartment = document.querySelector('input[name="selectedDepartment"]').value;

                if (selectedTeacher && selectedDepartment) {
                    var teacherId = selectedTeacher.value;
                    document.getElementById('selectedTeacher').value = teacherId;
                    document.getElementById('selectedDepartment').value = selectedDepartment;

                    document.getElementById('requestForm').submit();
                } else {
                    alert("Please select a teacher.");
                }
            }

            $(document).ready(function() {
                $('.btn-select-teacher').click(function() {
                    var selectedDepartment = $(this).data('department');
                    $('#selectedDepartment').val(selectedDepartment);
                });
            });
        </script>
    </main>
</body>

</html>
