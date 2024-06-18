<?php  
session_start();
if (!isset($_SESSION["LoginStudent"])) {
    header('location:../login/login.php');
    exit;
}
require_once "../connection/connection.php";

// Initialize variables
$successMessage = $errorMessage = '';
$researchSubmitted = false; // Flag to check if research was submitted successfully

// Fetch student's information from the database
$student_email = $_SESSION['LoginStudent'];
$query1 = "SELECT * FROM student_info WHERE email='$student_email'";
$run1 = mysqli_query($con, $query1);
$student_info = mysqli_fetch_assoc($run1);
$student_id = $student_info['roll_no'];

// Fetch ongoing research collaborations with department names
$researchQuery = "SELECT sr.request_id, sr.teacher_id, ti.first_name, ti.last_name, d.department_name
                  FROM student_requests sr
                  JOIN teacher_info ti ON sr.teacher_id = ti.teacher_id
                  JOIN departments d ON sr.department_id = d.department_id
                  WHERE sr.student_id = '$student_id' AND sr.request_status = 'Approved'";
$researchResult = mysqli_query($con, $researchQuery);

// Handle form submission for adding new research
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitResearch'])) {
    $teacherId = $_POST['teacherId'];
    $researchName = $_POST['researchName'];
    $researchDescription = $_POST['researchDescription'];

    // File upload
    if (isset($_FILES['research'])) {
        $file_name = $_FILES['research']['name'];
        $file_temp = $_FILES['research']['tmp_name'];
        $file_path = "../research/" . $file_name;
        if (move_uploaded_file($file_temp, $file_path)) {
            // Insert research data into database
            $insert_query = "INSERT INTO research_submissions (student_id, teacher_id, research_name, research_description, file_path, submission_date) 
                             VALUES ('$student_id', '$teacherId', '$researchName', '$researchDescription', '$file_path', NOW())";

            if (mysqli_query($con, $insert_query)) {
                $researchSubmitted = true; // Set flag to true
                // Redirect after form submission
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $errorMessage = "Error submitting research. Please try again later.";
            }
        } else {
            $errorMessage = "File upload failed. Please try again.";
        }
    } else {
        $errorMessage = "No file uploaded.";
    }
}

// Check for success message from flag
if ($researchSubmitted) {
    $successMessage = "Research submitted successfully!";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Current Research - RMS</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS for Manage Current Research page */
        body {
            background-color: #f8f9fa;
        }
        
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            width: fit-content; /* Set width to fit content */
            max-width: 100%; /* Set max-width for responsiveness */
            margin-bottom: 20px; /* Add margin for spacing between cards */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow for card effect */
            border-radius: 5px; /* Add border-radius for rounded corners */
            padding-left:30px;
            padding-right:30px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        .overlay-content h3 {
            color: #000; /* Text color for h3 set to black */
        }

        /* Custom CSS for close button */
        .btn-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            background: transparent;
            border: none;
            color: #000;
            cursor: pointer;
        }

        /* Custom CSS for department and teacher names */
        .research-header {
            background-color: #007bff; /* Blue background color */
            color: #fff; /* White text color */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 20px; /* Larger font size */
        }
    </style>
</head>
<body>
    <!-- Include common header -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/student-sidebar.php') ?>

    <!-- Main Content -->
    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 main-background mb-2 w-100">
        <h2 class="mt-3 mb-3">Manage Current Research</h2>
        <!-- Display ongoing research collaborations -->
        <?php if (mysqli_num_rows($researchResult) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($researchResult)) { ?>
                <div class="card">
                    <div class="card-body">
                        <div class="research-header"><?php echo $row['department_name']; ?></div>
                        <div class="teacher-name"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></div>
                        <!-- Button to open overlay box -->
                        <button class="btn btn-primary" onclick="openOverlay('<?php echo $row['teacher_id']; ?>')">Add Research</button>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-info">No ongoing research collaborations found.</div>
        <?php } ?>

        <!-- Overlay for adding research -->
        <div class="overlay" id="overlay">
            <div class="overlay-content">
                <button type="button" class="btn btn-close" onclick="closeOverlay()">&times;</button>
                <h3>Add Research</h3>
                <?php if (isset($errorMessage) && !empty($errorMessage)) { ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php } ?>
                <?php if ($researchSubmitted) { ?>
                    <div class="alert alert-success"><?php echo $successMessage; ?></div>
                <?php } ?>
                <form action="manage-research.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="teacherId" name="teacherId" value="">
                    <div class="form-group">
                        <label for="researchName">Research Name</label>
                        <input type="text" class="form-control" id="researchName" name="researchName" required>
                    </div>
                    <div class="form-group">
                        <label for="researchDescription">Research Description</label>
                        <textarea class="form-control" id="researchDescription" name="researchDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="research">Upload Research (PDF)</label>
                        <input type="file" class="form-control-file" id="research" name="research" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submitResearch">Submit Research</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Include Bootstrap JS -->
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- Custom JS for overlay -->
    <script>
        function openOverlay(teacherId) {
            $('#teacherId').val(teacherId);
            $('#overlay').show();
        }

        $(document).ready(function(){
            // Hide overlay on page load
            $('.overlay').hide();

            // Check if research was submitted successfully and show modal
            <?php if ($researchSubmitted) { ?>
                $('#researchSuccessModal').modal('show');
            <?php } ?>
        });
        function closeOverlay() {
            $('#overlay').hide();
        }
    </script>

    <!-- Bootstrap Modal for success message -->
    <div class="modal" id="researchSuccessModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Research Submission</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php if ($researchSubmitted) { ?>
                        <p><?php echo $successMessage; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
