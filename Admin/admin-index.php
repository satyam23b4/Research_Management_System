<!---------------- Session starts form here ----------------------->
<?php  
session_start();
if (!$_SESSION["LoginAdmin"])
{
    header('location:../login/login.php');
}
require_once "../connection/connection.php";

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
<!---------------- Session Ends form here ------------------------>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RMS</title>
    <!-- Include CSS libraries and stylesheets -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin-dashboard.css">
</head>
<body>
    <!-- Include common header and sidebar -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/admin-sidebar.php') ?>  

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
                        <!-- Total number of registered researchers -->
                        <p>Total Registered Researchers: <?php echo $totalResearchers; ?></p>
                        <!-- Total number of research projects -->
                        <p>Total Research Projects: <?php echo $totalProjects; ?></p>
                        <!-- Recent research activities -->
                        <p>Recent Research Activities:</p>
                        <ul>
                            <?php while ($row = mysqli_fetch_assoc($recentActivitiesResult)) { ?>
                                <li><?php echo $row['research_title']; ?> - <?php echo $row['research_date']; ?></li>
                            <?php } ?>
                        </ul>
                        <!-- Charts or graphs showing trends in research registrations, funding, etc. -->
                        <div id="research-trends-chart">
                            <!-- Chart goes here -->
                        </div>
                    </div>
                </div>
                <!-- Quick Links Section -->
                <div class="card">
                    <div class="card-header">
                        Quick Links
                    </div>
                    <div class="card-body">
                        <a href="../admin/Teacher.php" class="btn btn-primary">Add New Researcher</a>
                        <a href="../admin/manage-accounts.php" class="btn btn-primary">Manage Accounts</a>
                        <a href="../admin/research-report.php" class="btn btn-primary">Generate Reports</a>
                        <!-- Add more quick links as needed -->
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
                        <!-- Include PHP code to fetch and display notifications -->
                        <p>No new notifications</p>
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
