<?php  
session_start();
if (!$_SESSION["LoginStudent"]) {
    header('location:../login/login.php');
}
require_once "../connection/connection.php";

// Fetch student's information from the database
$student_email = $_SESSION['LoginStudent'];
$query1 = "SELECT * FROM student_info WHERE email='$student_email'";
$run1 = mysqli_query($con, $query1);
while ($row = mysqli_fetch_array($run1)) {
    $student_id = $row["roll_no"];
}

// Fetch notifications for the student
$notificationQuery = "SELECT * FROM student_requests WHERE student_id = '$student_id' AND request_status = 'Approved'";
$notificationResult = mysqli_query($con, $notificationQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - RMS</title>
    <!-- Include CSS libraries and stylesheets -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin-dashboard.css">
</head>
<body>
    <!-- Include common header and sidebar -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/student-sidebar.php') ?>  

    <!-- Main Content -->
    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 mb-2 w-100 page-content-index">
        <div class="flex-wrap flex-md-no-wrap pt-3 pb-2 mb-3 text-white admin-dashboard pl-3">
            <h4>Admin Dashboard </h4>
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
                    <?php
                    // PHP code to fetch total registered researchers
                    $totalResearchersQuery = "SELECT COUNT(*) AS total_researchers FROM teacher_info";
                    $totalResearchersResult = mysqli_query($con, $totalResearchersQuery);
                    $totalResearchersRow = mysqli_fetch_assoc($totalResearchersResult);
                    $totalResearchers = $totalResearchersRow['total_researchers'];

                    // PHP code to fetch total research projects
                    $totalProjectsQuery = "SELECT COUNT(*) AS total_projects FROM research_info";
                    $totalProjectsResult = mysqli_query($con, $totalProjectsQuery);
                    $totalProjectsRow = mysqli_fetch_assoc($totalProjectsResult);
                    $totalProjects = $totalProjectsRow['total_projects'];

                    // PHP code to fetch recent research activities
                    $recentActivitiesQuery = "SELECT * FROM research_info ORDER BY research_date DESC LIMIT 5";
                    $recentActivitiesResult = mysqli_query($con, $recentActivitiesQuery);
                    ?>
                    <!-- Total number of registered researchers -->
                    <p>Total Number of Researchers: <?php echo $totalResearchers; ?></p>
                    <!-- Total number of research projects -->
                    <p>Total Research Projects: <?php echo $totalProjects; ?></p>
                    <!-- Recent research activities -->
                    <p>Recent Research Activities:</p>
                    <ul>
                        <?php
                        while ($activityRow = mysqli_fetch_assoc($recentActivitiesResult)) {
                            echo "<li>" . $activityRow['research_title'] . " - " . $activityRow['research_date'] . "</li>";
                        }
                        ?>
                    </ul>
                    <!-- Charts or graphs showing trends in research registrations, funding, etc. -->
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
                        if (mysqli_num_rows($notificationResult) > 0) {
                            while ($notificationRow = mysqli_fetch_assoc($notificationResult)) {
                                echo "<div class='alert alert-success'>";
                                echo "<p>Your research request has been approved.</p>";
                                echo "<p>Date: " . $notificationRow['request_date'] . "</p>";
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
    <script src="../js/admin-dashboard.js"></script>
</body>
</html>
