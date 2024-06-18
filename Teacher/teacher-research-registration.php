<?php
session_start();

// Redirect if teacher is not logged in
if (!isset($_SESSION["LoginTeacher"])) {
    header('location:../login/login.php');
    exit;
}

// Include database connection
require_once "../connection/connection.php";

// Process research registration form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $teacher_email = $_SESSION['LoginTeacher'];
    $journalName = mysqli_real_escape_string($con, $_POST["journalName"]);
    $researchTitle = mysqli_real_escape_string($con, $_POST["researchTitle"]);
    $researchDate = mysqli_real_escape_string($con, $_POST["researchDate"]);
    $authorsArray = isset($_POST['authors']) ? json_decode($_POST['authors'], true) : []; // Initialize as empty array if authors' data is not set

    // Fetch teacher_id based on email
    $checkQuery = "SELECT teacher_id FROM teacher_info WHERE email = '$teacher_email'";
    $result = mysqli_query($con, $checkQuery);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $teacher_id = $row['teacher_id'];

        // Combine authors into a comma-separated string
        $authorsString = implode(", ", array_map(function ($author) use ($con) {
            return mysqli_real_escape_string($con, $author);
        }, $authorsArray));

        // Insert data into research_info table
        $query = "INSERT INTO research_info (teacher_id, journal_name, authors, research_title, research_date) 
                  VALUES ('$teacher_id', '$journalName', '$authorsString', '$researchTitle', '$researchDate')";
        $run = mysqli_query($con, $query);

        // Show alert based on insertion result
        if ($run) {
            echo "<script>alert('Research registered successfully');</script>";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<script>alert('Error registering research');</script>";
        }
    } else {
        echo '<script>alert("Invalid teacher ID.");</script>';
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Research Registration</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-container {
            text-align: center;
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
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
        }
        .overlay-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin: 50px auto;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        .dialog-form {
            margin-top: 20px;
        }
        .dialog-form .form-group {
            margin-bottom: 15px;
        }
        .non-editable {
            background-color: #f4f4f4;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .author-textbox {
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 5px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .author-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .loading-overlay {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            z-index: 9999;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
        }
        .loading-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .loading-text {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #333;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include('../common/common-header.php') ?>
    <?php include('../common/teacher-sidebar.php') ?>  

    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 main-background mb-2 w-100">
        <div class="sub-main">
            <div class="text-center d-flex flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 text-white admin-dashboard pl-3">
                <h4 class="">Register your Research</h4>
            </div>
            <div class="container pt-5">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form id="doiForm" action="teacher-research-registration.php" method="post">
                            <div class="form-group">
                                <label>Enter your DOI number</label>
                                <input type="text" id="doiInput" name="DOI" class="form-control" placeholder="Enter DOI">
                            </div>
                            <div class="form-group btn-container">
                                <input type="button" id="fetchDataBtn" value="Save and Continue" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="overlay" class="overlay">
        <div class="overlay-content">
            <span class="close-btn" onclick="closeOverlay()">&times;</span>
            <h2>Research Registration Form</h2>
            <div class="dialog-form">
                <form action="teacher-research-registration.php" method="post">

                    <input type="hidden" id="authorsInput" name="authors" value="">
                    <div class="form-group">
                        <label for="journalName"><strong>Journal Name:</strong></label>
                        <input type="text" id="journalName" name="journalName" class="form-control non-editable" readonly>
                    </div>
                    <div>
                        <label class="author-label">Authors:</label>
                        <div id="authors" name="authors"></div>
                    </div>
                    <div class="form-group">
                        <label for="researchTitle"><strong>Research Title:</strong></label>
                        <input type="text" id="researchTitle" name="researchTitle" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="researchDate"><strong>Date of Research Publication:</strong></label>
                        <input type="text" id="researchDate" name="researchDate" class="form-control datepicker" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" name="submit">Register Research</button>
                        <button type="button" id="closeBtn" class="btn btn-danger">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Please wait...</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $("#fetchDataBtn").click(function() {
                var doi = $("#doiInput").val().trim();
                if (doi === "") {
                    alert("Please enter a DOI number.");
                    return;
                }

                // Show loading overlay
                $("#loadingOverlay").fadeIn();

                $.post("../Common/webscraping.php", { doi: doi }, function(data) {
                    console.log(data);

                    // Hide loading overlay
                    $("#loadingOverlay").fadeOut();

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    $("#journalName").val(data.journalName);
                    $("#authors").html("");
                    var authorsArray = [];
                    // Assuming data.authors is an array of objects with 'authors' arrays inside
                    data.authors.forEach(function(authorObj) {
                        authorObj.authors.forEach(function(authorName) {
                            authorsArray.push(authorName);
                            var authorTextbox = $('<input type="text" class="form-control author-textbox" readonly>');
                            authorTextbox.val(authorName);
                            $("#authors").append(authorTextbox);
                        });
                    });
                    console.log(authorsArray);
                    // Update the hidden input field with authors' names array
                    $("#authorsInput").val(JSON.stringify(authorsArray));

                    $("#overlay").fadeIn();
                }, "json");
            });

            $("#closeBtn").click(function() {
                closeOverlay();
            });

            // Initialize datepicker on the researchDate input field
            $("#researchDate").datepicker({
                dateFormat: "yy-mm-dd", // Set the date format as YYYY-MM-DD
                changeMonth: true, // Allow changing of months
                changeYear: true, // Allow changing of years
                showButtonPanel: true, // Show a button panel for easy navigation
                onClose: function(selectedDate) {
                    // Additional logic if needed when the date is selected
                }
            });
        });

        function closeOverlay() {
            $("#overlay").fadeOut();
        }
    </script>
</body>
</html>
