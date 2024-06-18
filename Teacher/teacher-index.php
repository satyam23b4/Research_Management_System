<?php  
session_start();
if (!$_SESSION["LoginTeacher"]) {
    header('location:../login/login.php');
    exit;
}
require_once "../connection/connection.php";

// Fetch teacher's information from the database
$teacher_email = $_SESSION['LoginTeacher'];
$query1 = "SELECT * FROM teacher_info WHERE email='$teacher_email'";
$run1 = mysqli_query($con, $query1);
$teacher_info = mysqli_fetch_assoc($run1);

// Fetch departments managed by the teacher from teacher_departments
$teacher_id = $teacher_info['teacher_id'];
$query2 = "SELECT department_name FROM teacher_departments WHERE teacher_id='$teacher_id'";
$run2 = mysqli_query($con, $query2);
$departments_managed = [];
while ($row = mysqli_fetch_assoc($run2)) {
    $departments_managed[] = $row['department_name'];
}

// Fetch total research projects supervised by the teacher
$query3 = "SELECT COUNT(*) AS total_projects FROM research_info WHERE teacher_id='$teacher_id'";
$run3 = mysqli_query($con, $query3);
$total_projects_row = mysqli_fetch_assoc($run3);
$total_projects_supervised = $total_projects_row['total_projects'];

// Approve or dismiss request
if (isset($_GET['action']) && isset($_GET['request_id'])) {
    $action = $_GET['action'];
    $request_id = $_GET['request_id'];
    if ($action === 'approve' && isset($_GET['request_id'])) {
        $request_id = $_GET['request_id'];

        // Update student_requests table to mark the request as approved
        $approveQuery = "UPDATE student_requests SET request_status = 'Approved' WHERE request_id = '$request_id'";
        $result = mysqli_query($con, $approveQuery);

        if ($result) {
            // Send notification to the student (you need to implement this part)
            // Example: Send an email or a message to the student

            // Redirect back to the teacher dashboard after approval
            header('location:teacher-index.php');
            exit;
        } else {
            echo "Error: Unable to approve request.";
        }
    } elseif ($action === 'dismiss' && isset($_GET['request_id'])) {
        $request_id = $_GET['request_id'];

        // Delete the request from student_requests table
        $dismissQuery = "DELETE FROM student_requests WHERE request_id = '$request_id'";
        $result = mysqli_query($con, $dismissQuery);

        if ($result) {
            // Redirect back to the teacher dashboard after dismissal
            header('location:teacher-index.php');
            exit;
        } else {
            echo "Error: Unable to dismiss request.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - RMS</title>
    <!-- Include CSS libraries and stylesheets -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/teacher-index.css">
</head>
<body>
    <!-- Include common header and sidebar -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/teacher-sidebar.php') ?>  

    <!-- Main Content -->
    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 mb-2 w-100 page-content-index">
        <div class="flex-wrap flex-md-no-wrap pt-3 pb-2 mb-3 text-white teacher-index pl-3">
            <h4>Teacher Dashboard</h4>
        </div>
        <div class="row">
            <!-- Main Content Section -->
            <div class="col-md-8">
                <!-- Display key metrics and insights -->
                <div class="card mb-3">
                    <div class="card-header">
                        Key Metrics and Insights
                    </div>
                    <div class="card-body">
                        <!-- Total departments managed -->
                        <p>Departments Managed: <?php echo implode(", ", $departments_managed); ?></p>
                        <!-- Total research projects supervised -->
                        <p>Total Research Projects: <?php echo $total_projects_supervised; ?></p>
                        <!-- Number of students under supervision -->
                        <p>Number of Students Under Your Supervision: <?php /* PHP code to fetch and display number of students */ ?></p>
                        <!-- Charts or graphs showing trends in research projects, publications, etc. -->
                        <div id="research-trends-chart">
                            <!-- Chart goes here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifications Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Notifications
                    </div>
                    <div class="card-body">
                        <?php
                        // Fetch notifications for the teacher
                        $notificationQuery = "SELECT sr.request_id, sr.student_id, d.department_name, sr.request_date, si.first_name, si.last_name
                                            FROM student_requests sr
                                            JOIN student_info si ON sr.student_id = si.roll_no
                                            JOIN departments d ON sr.department_id = d.department_id
                                            WHERE sr.teacher_id = '$teacher_id' AND sr.request_status = 'Pending'";
                        $notificationResult = mysqli_query($con, $notificationQuery);
                        if (mysqli_num_rows($notificationResult) > 0) {
                            while ($notificationRow = mysqli_fetch_assoc($notificationResult)) {
                                echo "<div class='alert alert-info'>";
                                echo "<p>Student Request: " . $notificationRow['first_name'] . " " . $notificationRow['last_name'] . "</p>";
                                echo "<p>Department: " . $notificationRow['department_name'] . "</p>";
                                echo "<p>Date: " . $notificationRow['request_date'] . "</p>";
                                echo "<a href='teacher-index.php?action=approve&request_id=" . $notificationRow['request_id'] . "' class='btn btn-success btn-sm'>Approve</a>";
                                echo "<a href='teacher-index.php?action=dismiss&request_id=" . $notificationRow['request_id'] . "' class='btn btn-danger btn-sm'>Dismiss</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No new notifications</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include JS libraries and scripts -->
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/teacher-index.js"></script>
</body>
</html>
