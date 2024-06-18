<!---------------- Session starts from here ----------------------->
<?php  
    session_start();
    
    if (isset($_SESSION["LoginAdmin"]) && !isset($_SESSION['LoginStudent'])) {
        $roll_no = $_GET['roll_no'];
    } elseif (isset($_SESSION['LoginStudent']) && !isset($_SESSION["LoginAdmin"])) {
        $student_email = $_SESSION['LoginStudent'];
        $roll_no ="";
    } else { ?>
        <script> alert("You are not an authorized person for this link");</script>
    <?php
        header('location:../login/login.php');
    }
    require_once "../connection/connection.php";
?>
<!---------------- Session Ends from here ------------------------>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Student Information</title>
    <!-- Custom CSS for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image {
            width: 250px;
            height: 290px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .profile-details {
            margin-top: 20px;
        }

        .profile-details h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .profile-info {
            margin-top: 10px;
        }

        .profile-info h5 {
            color: #555;
            margin-bottom: 5px;
        }

        .row-divider {
            border-top: 1px solid #ccc;
            margin-top: 20px;
            padding-top: 20px;
        }

        /* Additional styles for better spacing and aesthetics */
        .col-md-6 {
            margin-bottom: 10px;
        }

        .pt-3 {
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <?php include('../common/common-header.php') ?>
        <?php
            if($roll_no)
            {
                $query = "SELECT * FROM student_info WHERE roll_no='$roll_no'";
            }
            else{
                $query = "SELECT * FROM student_info 
                WHERE email='$student_email'";
            }
        $run = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($run)) {
        ?>
            <div class="container">
                <div class="row pt-5">
                    <div class="col-md-4">
                    <?php  $profile_image= $row["profile_image"] ?>
                    <img class="ml-5 mb-5 profile-image" src="<?php echo "../Admin/images/$profile_image" ?>" alt="Profile Image">
                    </div>
                    <div class="col-md-8 profile-details">
                        <h3 class="ml-5"><?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'] ?></h3><br>
                        <div class="row profile-info">
                            <div class="col-md-6">
                                <h5>Father Name:</h5> <?php echo $row['father_name'] ?><br><br>
                                <h5>Email:</h5> <?php echo $row['email'] ?><br><br>
                                <h5>Contact:</h5> <?php echo $row['mobile_no'] ?><br><br>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row-divider"></div>
                <div class="row pt-3">
                    <div class="col-md-4"><h5>Phone No:</h5> <?php echo $row['mobile_no'] ?></div>
                    <div class="col-md-4"><h5>Semester:</h5> <?php echo $row['semester'] ?></div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4"><h5>Gender:</h5> <?php echo $row['gender'] ?></div>
                </div>
                <div class="row pt-3">
                    <div class="col-md-4"><h5>Date of Birth:</h5> <?php echo $row['dob'] ?></div>
                </div>
            </div>
    <?php } ?>
</body>

</html>
