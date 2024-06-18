<?php
session_start();
if (!$_SESSION["LoginAdmin"]) {
    header('location:../login/login.php');
}
require_once "../connection/connection.php";

// Fetch total registered students
$totalStudentsQuery = "SELECT COUNT(*) AS total_students FROM student_info";
$totalStudentsResult = mysqli_query($con, $totalStudentsQuery);
$totalStudentsRow = mysqli_fetch_assoc($totalStudentsResult);
$totalStudents = $totalStudentsRow['total_students'];

// Fetch total papers submitted
$totalPapersQuery = "SELECT COUNT(*) AS total_papers FROM research_submissions";
$totalPapersResult = mysqli_query($con, $totalPapersQuery);
$totalPapersRow = mysqli_fetch_assoc($totalPapersResult);
$totalPapers = $totalPapersRow['total_papers'];

// Fetch funding information
$fundingQuery = "SELECT SUM(amount) AS total_funding FROM department_funding";
$fundingResult = mysqli_query($con, $fundingQuery);
$fundingRow = mysqli_fetch_assoc($fundingResult);
$totalFunding = $fundingRow['total_funding'];

// Fetch unallocated funds
$unallocatedFundsQuery = "SELECT (SELECT amount FROM total_funding) - SUM(amount) AS unallocated_funds FROM department_funding";
$unallocatedFundsResult = mysqli_query($con, $unallocatedFundsQuery);
$unallocatedFundsRow = mysqli_fetch_assoc($unallocatedFundsResult);
$unallocatedFunds = $unallocatedFundsRow['unallocated_funds'];

// Fetch number of research papers by each teacher
$researchPapersQuery = "SELECT teacher_id, COUNT(*) AS papers_count FROM research_info GROUP BY teacher_id";
$researchPapersResult = mysqli_query($con, $researchPapersQuery);
$researchPapers = [];
while ($row = mysqli_fetch_assoc($researchPapersResult)) {
    $researchPapers[$row['teacher_id']] = $row['papers_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Report - Admin Dashboard</title>
    <!-- Include CSS libraries and stylesheets -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <style>
        /* Custom CSS for the research report page */
        body {
            background-color: #f8f9fa;
        }
        .container {
            padding: 20px;
        }
        .report-header {
            margin-bottom: 20px;
        }
        .report-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .report-card .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }
        .report-card .card-body {
            padding: 10px;
        }
        /* Styles for the chart canvas */
        #researchChart {
            max-width: 100%;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Include common header and sidebar -->
    <?php include('../common/common-header.php') ?>

    <!-- Main Content -->
    <main role="main" class="container">
        <div class="report-header">
            <h2 class="text-center">Research Report</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Total Registered Students -->
                <div class="report-card">
                    <div class="card-header">
                        Total Registered Students
                    </div>
                    <div class="card-body">
                        <p>Total Students: <?php echo $totalStudents; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Total Papers Submitted -->
                <div class="report-card">
                    <div class="card-header">
                        Total Papers Submitted
                    </div>
                    <div class="card-body">
                        <p>Total Papers: <?php echo $totalPapers; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Funding Information -->
                <div class="report-card">
                    <div class="card-header">
                        Funding Information
                    </div>
                    <div class="card-body">
                        <p>Funding Allocated: <?php echo $totalFunding; ?></p>
                        <p>Unallocated Funds: <?php echo $unallocatedFunds; ?></p>
                        <canvas id="fundingChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Research Papers by Teachers -->
                <div class="report-card">
                    <div class="card-header">
                        Research Papers by Teachers
                    </div>
                    <div class="card-body">
                        <?php foreach ($researchPapers as $teacherId => $papersCount): ?>
                            <p>Teacher ID <?php echo $teacherId; ?>: <?php echo $papersCount; ?> papers</p>
                        <?php endforeach; ?>
                        <!-- Chart Canvas -->
                        <canvas id="researchChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include JS libraries and scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Get funding data
        var totalFunding = <?php echo $totalFunding; ?>;
        var unallocatedFunds = <?php echo $unallocatedFunds; ?>;

        // Create the funding chart
        var ctxFunding = document.getElementById('fundingChart').getContext('2d');
        var fundingChart = new Chart(ctxFunding, {
            type: 'pie',
            data: {
                labels: ['Allocated Funding', 'Unallocated Funding'],
                datasets: [{
                    label: 'Funding Allocation',
                    data: [totalFunding, unallocatedFunds],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Create the research papers chart
        var ctxResearch = document.getElementById('researchChart').getContext('2d');
        var researchChart = new Chart(ctxResearch, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($researchPapers)); ?>,
                datasets: [{
                    label: 'Research Papers',
                    data: <?php echo json_encode(array_values($researchPapers)); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
