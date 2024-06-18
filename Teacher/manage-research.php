<?php
session_start();
if (!isset($_SESSION["LoginTeacher"])) {
    header('location:../login/login.php');
    exit;
}
require_once "../connection/connection.php";

// Fetch teacher's information from the database
$teacher_email = $_SESSION['LoginTeacher'];
$query = "SELECT * FROM teacher_info WHERE email='$teacher_email'";
$run = mysqli_query($con, $query);
$teacher_info = mysqli_fetch_assoc($run);
$teacher_id = $teacher_info['teacher_id'];

// Fetch student requests approved by this teacher
$query2 = "SELECT sr.request_id, sr.student_id, si.first_name, si.last_name, si.email, si.mobile_no, rs.research_name, rs.research_description, rs.file_path, rs.submission_date, d.department_name
           FROM student_requests sr
           JOIN student_info si ON sr.student_id = si.roll_no
           JOIN departments d ON sr.department_id = d.department_id
           LEFT JOIN research_submissions rs ON sr.student_id = rs.student_id AND sr.teacher_id = rs.teacher_id
           WHERE sr.teacher_id = '$teacher_id' AND sr.request_status = 'Approved'";
$run2 = mysqli_query($con, $query2);

$query3 = "SELECT submission_id 
           FROM student_requests sr
           JOIN student_info si ON sr.student_id = si.roll_no
           LEFT JOIN research_submissions rs ON sr.student_id = rs.student_id AND sr.teacher_id = rs.teacher_id
           WHERE rs.teacher_id = '$teacher_id' AND rs.student_id = sr.student_id";
$run3 = mysqli_query($con, $query3);

// Check if any research submissions exist
$researchSubmissionsExist = mysqli_num_rows($run3) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Research Submissions - RMS</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS for View Research Submissions page */
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .view-details {
            margin-top: auto;
            width:20%;
        }

        .no-data {
            margin-top: 20px;
            text-align: center;
        }

        /* Styling for overlay */
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
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .overlay-content h3 {
            color: #000;
            margin-bottom: 10px;
        }

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
    </style>
</head>
<body>
    <!-- Include common header -->
    <?php include('../common/common-header.php') ?>
    <?php include('../common/teacher-sidebar.php') ?>

      <!-- Main Content -->
    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 main-background mb-2 w-100">
        <h2 class="mt-3 mb-3">View Research Submissions</h2>
        <!-- Display research submissions -->
        <?php if ($researchSubmissionsExist) { ?>
            <?php while ($row = mysqli_fetch_assoc($run2)) { ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['department_name']; ?></h5>
                        <p><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></p>
                        <button class="btn btn-primary view-details" data-student='<?php echo json_encode($row); ?>'>View Details</button>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-data">
                <p>No research submissions found.</p>
            </div>
        <?php } ?>
    </main>


    <!-- Overlay for displaying research details -->
    <div class="overlay" id="overlay">
        <div class="overlay-content">
            <button class="btn-close" onclick="closeOverlay()">X</button>
            <h3>Research Details</h3>
            <p><strong>Name:</strong> <span id="studentName"></span></p>
            <p><strong>Email:</strong> <span id="studentEmail"></span></p>
            <p><strong>Mobile:</strong> <span id="studentMobile"></span></p>
            <p><strong>Research Name:</strong> <span id="researchName"></span></p>
            <p><strong>Research Description:</strong> <span id="researchDescription"></span></p>
            <p><strong>Submission Date:</strong> <span id="submissionDate"></span></p>
            <a href="#" id="downloadLink" download>Download Research Document</a>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- Custom JS for overlay -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewDetailsButtons = document.querySelectorAll('.view-details');
            viewDetailsButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var studentData = JSON.parse(this.getAttribute('data-student'));
                    openOverlay(studentData);
                });
            });
        });

        function openOverlay(rowData) {
            document.getElementById('studentName').textContent = rowData.first_name + ' ' + rowData.last_name;
            document.getElementById('studentEmail').textContent = rowData.email;
            document.getElementById('studentMobile').textContent = rowData.mobile_no;
            document.getElementById('researchName').textContent = rowData.research_name;
            document.getElementById('researchDescription').textContent = rowData.research_description;
            document.getElementById('submissionDate').textContent = rowData.submission_date;
            document.getElementById('downloadLink').href = rowData.file_path;
            document.getElementById('overlay').style.display = 'block';
        }

        function closeOverlay() {
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</body>
</html>
