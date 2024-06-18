<!---------------- Session starts form here ----------------------->
<?php  
	session_start();
	if (!$_SESSION["LoginAdmin"])
	{
		header('location:../login/login.php');
	}
	require_once "../connection/connection.php";
	$_SESSION['LoginStudent']="";
?>
<!---------------- Session Ends form here ------------------------>

<!--*********************** PHP code starts from here for data insertion into database ******************************* -->
<?php  
	if (isset($_POST['btn_save'])) {

		$roll_no=$_POST["roll_no"];

		$first_name=$_POST["first_name"];

		$middle_name=$_POST["middle_name"];

		$last_name=$_POST["last_name"];
		
		$email=$_POST["email"];
		
		$mobile_no=$_POST["mobile_no"];
		
		$semester=$_POST["semester"];
		
		$dob=$_POST["dob"];
		
		$other_phone=$_POST["other_phone"];
		
		$gender=$_POST["gender"];

		$password=$_POST['password'];

		$role=$_POST['role'];
		
		$profile_image = $_FILES['profile_image']['name'];
		$tmp_name=$_FILES['profile_image']['tmp_name'];
		$path = "images/".$profile_image;
		move_uploaded_file($tmp_name, $path);

		$query="INSERT INTO student_info(roll_no, first_name, middle_name, last_name, email, mobile_no, profile_image, semester, dob, other_phone, gender) VALUES ('$roll_no','$first_name','$middle_name','$last_name','$email','$mobile_no','$profile_image','$semester','$dob','$other_phone','$gender')";
		$run=mysqli_query($con, $query);
		if ($run) {
			echo "<script>alert('Your Data has been submitted');</script>";
		}
		else {
			echo "<script>alert('Your Data has not been submitted');</script>";
		}
		$query2="INSERT INTO login(user_id, Password, Role) VALUES ('$email','$password','$role')";
		$run2=mysqli_query($con, $query2);
		if ($run2) {
			echo "<script>alert('Your Data has been submitted into login');</script>";
		}
		else {
			echo "<script>alert('Your Data has not been submitted into login');</script>";
		}
	}
?>
<!--*********************** PHP code end from here for data insertion into database ******************************* -->

<!doctype html>
<html lang="en">
<head>
	<title>Admin - Register Student</title>
</head>
<body>
	<?php include('../common/common-header.php') ?>
	<?php include('../common/admin-sidebar.php') ?>
	<main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 mb-2 w-100">
		<div class="sub-main">
			<div class="text-center d-flex flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 text-white admin-dashboard pl-3">
				<div class="d-flex">
					<h4 class="mr-5">Student Management System </h4>
					<button type="button" class="btn btn-primary ml-5" data-toggle="modal" data-target=".bd-example-modal-lg">Add Student</button>
				</div>
			</div>
			<div class="row w-100">
				<div class=" col-lg-6 col-md-6 col-sm-12 mt-1 ">
					<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header bg-info text-white">
									<h4 class="modal-title text-center">Add New Student</h4>
								</div>
								<div class="modal-body">
									<form action="student.php" method="post" enctype="multipart/form-data">
										<div class="row mt-3">
											<div class="col-md-4">
												<div class="form-group">
													<label for="exampleInputEmail1">Roll No: </label>
													<input type="text" name="roll_no" class="form-control" required="" placeholder="Roll No">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="exampleInputEmail1">First Name: </label>
													<input type="text" name="first_name" class="form-control" required="" placeholder="First Name">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="exampleInputEmail1">Middle Name: </label>
													<input type="text" name="middle_name" class="form-control" required="" placeholder="Middle Name">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="exampleInputEmail1">Last Name: </label>
													<input type="text" name="last_name" class="form-control" required="" placeholder="Last Name">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="formp">
													<label for="exampleInputPassword1">Student Email:</label>
													<input type="text" name="email" class="form-control" required="" placeholder="Enter Email">
												</div>
											</div>
											<div class="col-md-4">
												<div class="formp">
													<label for="exampleInputPassword1">Mobile No</label>
													<input type="number" name="mobile_no" class="form-control" placeholder="Enter Mobile Number">
												</div>
											</div>
											<div class="col-md-4">
												<div class="formp">
													<label for="exampleInputPassword1">Select Your Profile </label>
													<input type="file" name="profile_image" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="exampleInputEmail1">Semester: </label>
													<select class="browser-default custom-select" name="semester">
														<option selected>Select Semester</option>
														<option value="1">1st Semester</option>
														<option value="2">2nd Semester</option>
														<!-- Add more options as needed -->
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="exampleInputEmail1">Date of Birth: </label>
													<input type="date" name="dob" class="form-control">
												</div>
											</div>
											<div class="col-md-4">
												<div class="formp">
													<label for="exampleInputPassword1">Other Phone:</label>
													<input type="number" name="other_phone" class="form-control" placeholder="Other Phone No">
												</div>
											</div>
											<div class="col-md-4">
												<div class="formp">
													<label for="exampleInputPassword1">Gender:</label>
													<select class="browser-default custom-select" name="gender">
														<option selected>Select Gender</option>
														<option value="Male">Male</option>
														<option value="Female">Female</option>
														<!-- Add more options as needed -->
													</select>
												</div>
											</div>
										</div>
										<!-- _________________________________________________________________________________
																			Hidden Values are here
										_________________________________________________________________________________ -->
										<div>
											<input type="hidden" name="password" value="student123*">
											<input type="hidden" name="role" value="Student">
										</div>
										<!-- _________________________________________________________________________________
																			Hidden Values are end here
										_________________________________________________________________________________ -->
										<div class="modal-footer">
											<input type="submit" class="btn btn-primary" name="btn_save" value="Save Data">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row w-100">
				<div class="col-md-12 ml-2">
					<section class="mt-3">
						<table class="w-100 table-elements mb-5 table-three-tr" cellpadding="10">
							<tr class="table-tr-head table-three text-white">
								<th>Roll No</th>
								<th>Name</th>
								<th>Email</th>
								<th>Mobile No</th>
								<th>Semester</th>
								<th>Operations</th>
							</tr>
							<?php 
							$query="SELECT roll_no, first_name, middle_name, last_name, email, mobile_no, semester FROM student_info";
							$run=mysqli_query($con, $query);
							while($row=mysqli_fetch_array($run)) {
								echo "<tr>";
								echo "<td>".$row["roll_no"]."</td>";
								echo "<td>".$row["first_name"]." ".$row["middle_name"]." ".$row["last_name"]."</td>";
								echo "<td>".$row["email"]."</td>";
								echo "<td>".$row["mobile_no"]."</td>";
								echo "<td>".$row["semester"]."</td>";
								echo "<td width='170'><a class='btn btn-primary' href='display-student.php?roll_no=".$row['roll_no']."'>Profile</a> <a class='btn btn-danger' href='delete-function.php?roll_no=".$row['roll_no']."'>Delete</a></td>";
								echo "</tr>";
							}
							?>
						</table>				
					</section>
				</div>
			</div>	 	
		</div>
	</main>
	<script type="text/javascript" src="../bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
