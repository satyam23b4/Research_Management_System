<?php
session_start();
if (!isset($_SESSION["LoginAdmin"])) {
    header('location:../login/login.php');
    exit;
}
require_once "../connection/connection.php";

// Initialize departments array
$departments = [];

// Fetch departments from the database
$query = "SELECT * FROM departments";
$run = mysqli_query($con, $query);
if ($run) {
    $departments = mysqli_fetch_all($run, MYSQLI_ASSOC);
}

// Fetch department funding amounts from the database
$departmentFundingQuery = "SELECT * FROM department_funding";
$departmentFundingResult = mysqli_query($con, $departmentFundingQuery);
$departmentFunding = [];
while ($row = mysqli_fetch_assoc($departmentFundingResult)) {
    $departmentFunding[$row['department_id']] = $row['amount'];
}

// Fetch total funding amount
$totalFundingQuery = "SELECT amount FROM total_funding WHERE id = 1";
$totalFundingResult = mysqli_query($con, $totalFundingQuery);
$totalFundingRow = mysqli_fetch_assoc($totalFundingResult);
$totalFunding = $totalFundingRow['amount'];

// Handle form submission
$successMessage = "";
$errorMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get total funding amount from the form
    $newTotalFunding = $_POST['total_funding'];

    // Update total funding in the database
    $updateTotalQuery = "UPDATE total_funding SET amount = $newTotalFunding WHERE id = 1";
    mysqli_query($con, $updateTotalQuery);

    // Update individual department funding in the database
    $totalSliderAmount = 0;
    foreach ($_POST['department_funding'] as $departmentId => $departmentAmount) {
        $totalSliderAmount += $departmentAmount;
        $updateDeptQuery = "UPDATE department_funding SET amount = $departmentAmount WHERE department_id = $departmentId";
        mysqli_query($con, $updateDeptQuery);
    }

    // Check if total slider amount exceeds total funding
    if ($totalSliderAmount > $newTotalFunding) {
        $errorMessage = "Total slider amount cannot exceed total funding!";
    } else {
        $successMessage = "Funding saved successfully!";
        // Redirect to prevent form resubmission
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department Funding - Admin</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS for Manage Department Funding page */
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            padding-left: 250px; /* Adjusted for sidebar width */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-save,
        .btn-change-funding {
            margin-top: 20px;
        }

        .form-control[disabled],
        .btn-save {
            display: none;
        }

        /* Adjustments for main content to avoid overlap with sidebar */
        @media (max-width: 768px) {
            .container {
                padding-left: 250px;
            }
        }

        /* Custom CSS for slider */
        .form-range {
            width: 80%;
            margin-top: 10px;
        }

        .form-label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .funding-amount {
            display: inline-block;
            margin-left: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Include common header and sidebar -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/admin-sidebar.php') ?>

    <!-- Main Content -->
    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 mb-2 w-100">
        <h2 class="mt-3 mb-3">Manage Department Funding</h2>
        <!-- Success Message -->
        <?php if (!empty($successMessage)) { ?>
            <div class="alert alert-success success-message" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>
        <!-- Error Message -->
        <?php if (!empty($errorMessage)) { ?>
            <div class="alert alert-danger error-message" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="total_funding" class="form-label">Total Funding Amount (in ₹):</label>
                <input type="number" class="form-control" id="total_funding" name="total_funding" min="0" value="<?php echo $totalFunding; ?>" readonly>
                <input type="button" class="btn btn-primary btn-change-funding" value="Change Total Funding" onclick="enableTotalFunding()">
                <button type="submit" class="btn btn-primary btn-save">Save Total Funding</button>
            </div>
            <?php foreach ($departments as $department) { ?>
                <div class="form-group">
                    <label for="department_<?php echo $department['department_id']; ?>" class="form-label"><?php echo $department['department_name']; ?></label>
                    <input type="range" class="form-range" id="department_<?php echo $department['department_id']; ?>" name="department_funding[<?php echo $department['department_id']; ?>]" min="0" max="<?php echo $totalFunding; ?>" value="<?php echo $departmentFunding[$department['department_id']] ?? 0; ?>">
                    <span id="department_<?php echo $department['department_id']; ?>_value" class="funding-amount">₹<?php echo $departmentFunding[$department['department_id']] ?? 0; ?></span>
                </div>
            <?php } ?>
            <button type="submit" class="btn btn-primary btn-save">Save Funding</button>
        </form>
    </main>

    <!-- Include Bootstrap JS -->
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- Custom JS for slide bars and total funding calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sliders = document.querySelectorAll('.form-range');
            sliders.forEach(slider => {
                const output = document.getElementById(`${slider.id}_value`);
                output.textContent = `₹${slider.value}`;
                slider.oninput = function () {
                    output.textContent = `₹${this.value}`;
                    updateTotalFunding();
                };
            });

            document.getElementById('total_funding').addEventListener('input', updateTotalFunding);
        });

        function enableTotalFunding() {
            const totalFundingInput = document.getElementById('total_funding');
            totalFundingInput.removeAttribute('readonly');
            totalFundingInput.classList.remove('form-control');
            totalFundingInput.classList.remove('form-control-plaintext');

            const changeFundingBtn = document.querySelector('.btn-change-funding');
            changeFundingBtn.style.display = 'none';

            const saveFundingBtn = document.querySelector('.btn-save');
            saveFundingBtn.style.display = 'inline-block';
        }

        function updateTotalFunding() {
            const totalFundingInput = document.getElementById('total_funding');
            const totalFunding = parseInt(totalFundingInput.value);

            let totalSliderValue = 0;
            document.querySelectorAll('.form-range').forEach(slider => {
                totalSliderValue += parseInt(slider.value);
                slider.max = totalFunding; // Update slider max value based on total funding
            });

            // Display a warning if total slider value exceeds total funding
            if (totalSliderValue > totalFunding) {
                totalFundingInput.setCustomValidity('Total funding exceeded!');
                totalFundingInput.reportValidity();
            } else {
                totalFundingInput.setCustomValidity('');
            }
        }
    </script>
</body>
</html>
