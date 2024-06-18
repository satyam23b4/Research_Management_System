<?php  
session_start();
if (!$_SESSION["LoginAdmin"]) {
    header('location:../login/login.php');
}
require_once "../connection/connection.php";

// Fetch research data with teacher names from the database
$query = "SELECT r.research_id, t.first_name, t.last_name, r.journal_name, r.authors, r.research_title, r.research_date 
          FROM research_info r
          INNER JOIN teacher_info t ON r.teacher_id = t.teacher_id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research List - Admin Dashboard</title>
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
            <h4>Research List</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        List of Research Done by Teachers
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Teacher Name</th>
                                        <th>Journal Name</th>
                                        <th>Authors</th>
                                        <th>Research Title</th>
                                        <th>Research Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        $count = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $count++ . "</td>";
                                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                            echo "<td>" . $row['journal_name'] . "</td>";
                                            echo "<td>" . $row['authors'] . "</td>";
                                            echo "<td>" . $row['research_title'] . "</td>";
                                            echo "<td>" . $row['research_date'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No research data available.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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
