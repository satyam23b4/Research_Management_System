<?php
session_start();
require_once "../connection/connection.php";

// Fetch total funding amount
$totalFundingQuery = "SELECT amount FROM total_funding WHERE id = 1";
$totalFundingResult = mysqli_query($con, $totalFundingQuery);
$totalFundingRow = mysqli_fetch_assoc($totalFundingResult);
$totalFunding = $totalFundingRow['amount'];

// Fetch department-wise funding amounts
$departmentsQuery = "SELECT department_name, amount FROM department_funding JOIN departments ON department_funding.department_id = departments.department_id";
$departmentsResult = mysqli_query($con, $departmentsQuery);
$departments = mysqli_fetch_all($departmentsResult, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funding Overview</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Custom CSS for Funding Overview page */
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            width: 80%;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <!-- Include common header -->
    <?php include('../common/common-header.php') ?>

    <!-- Main Content -->
    <main role="main" class="container">
        <h2 class="mt-3 mb-3">Funding Overview</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Total Funding:</h4>
                <p>Total: ₹<?php echo $totalFunding; ?></p>
            </div>
            <div class="col-md-6">
                <h4>Department-wise Funding:</h4>
                <?php if (!empty($departments)) { ?>
                    <ul>
                        <?php foreach ($departments as $department) { ?>
                            <li><?php echo $department['department_name']; ?>: ₹<?php echo $department['amount']; ?></li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p>No department funding data available.</p>
                <?php } ?>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="fundingChart"></canvas>
        </div>
    </main>

    <!-- Include Bootstrap JS -->
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- Custom JS for Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('fundingChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [
                        <?php foreach ($departments as $department) { ?>
                            '<?php echo $department['department_name']; ?>',
                        <?php } ?>
                    ],
                    datasets: [{
                        label: 'Department-wise Funding',
                        data: [
                            <?php foreach ($departments as $department) { ?>
                                <?php echo $department['amount']; ?>,
                            <?php } ?>
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        text: 'Department-wise Funding'
                    }
                }
            });
        });
    </script>
</body>
</html>
