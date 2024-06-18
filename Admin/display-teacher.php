<?php  
	session_start();

	// Check if "LoginAdmin" and "LoginTeacher" keys are set in the session
	if (isset($_SESSION["LoginAdmin"]) && !isset($_SESSION['LoginTeacher'])) {
		$teacher_id = $_GET['teacher_id'];
	} else if (isset($_SESSION['LoginTeacher']) && !isset($_SESSION["LoginAdmin"])) {
		$teacher_email = $_SESSION['LoginTeacher'];
		$teacher_id = "";
	} else { 
		// Redirect unauthorized users
		?>
		<script> alert("You are not authorized to access this link.");</script>
	<?php
		header('location:../login/login.php');
		exit; // Add exit after header redirect
	}
	require_once "../connection/connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Teacher Information</title>
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
            height: 270px;
            border-radius: 50%;
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

    <div class="container">
		<?php
			if($teacher_id){
				$query="select * from teacher_info where teacher_id='$teacher_id'";
			}
			else{
				$query="select * from teacher_info where email='$teacher_email'";
			}
			
			$run=mysqli_query($con,$query);
			while ($row=mysqli_fetch_array($run)) {
		?>
        <div class="row pt-5">
            <div class="col-md-4">
                <?php  $profile_image= $row["profile_image"] ?>
                <img class="ml-5 mb-5 profile-image" src="<?php echo "images/$profile_image" ?>" alt="Profile Image">
            </div>
            <div class="col-md-8 profile-details">
                <h3 class="ml-5"><?php echo $row['first_name']." ".$row['middle_name']." ".$row['last_name'] ?></h3>
                <div class="row profile-info">
                    <div class="col-md-6">
                        <?php if (!empty($row['email'])) { ?>
                            <h5>Email:</h5> <?php echo $row['email'] ?><br>
                        <?php } ?>
                        <?php if (!empty($row['phone_no'])) { ?>
                            <h5>Contact:</h5> <?php echo $row['phone_no'] ?><br>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <?php if (!empty($row['teacher_id'])) { ?>
                            <h5>Teacher ID:</h5> <?php echo $row['teacher_id'] ?><br>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-divider"></div>
        <div class="row pt-3">
            <div class="col-md-4">
                <?php if (!empty($row['gender'])) { ?>
                    <h5>Gender:</h5> <?php echo $row['gender'] ?>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <?php if (!empty($row['teacher_status'])) { ?>
                    <h5>Status:</h5> <?php echo $row['teacher_status'] ?>
                <?php } ?>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-md-4">
                <?php if (!empty($row['other_phone'])) { ?>
                    <h5>Other Phone No:</h5> <?php echo $row['other_phone'] ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</body>
</html>
