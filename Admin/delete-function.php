<?php  
session_start();
if (!$_SESSION["LoginAdmin"])
{
	header('location:../login/login.php');
}
require_once "../connection/connection.php";

// Delete Student
if (isset($_GET['roll_no'])) {
	$roll_no=$_GET['roll_no'];
	
	// Delete from student_info
	$query1="DELETE FROM student_info WHERE roll_no='$roll_no'";
	$run1=mysqli_query($con,$query1);
	
	// Delete corresponding login
	$query_delete_login = "DELETE FROM login WHERE user_id='$roll_no'";
	$run_delete_login = mysqli_query($con, $query_delete_login);

	if ($run1 && $run_delete_login) {
		header('Location: student.php');
	} else {
		echo "Record not deleted. First delete records from the child table then you can delete from here ";
		header('Refresh: 5; url=student.php');
	}
}

// Delete Teacher
if (isset($_GET['teacher_id'])) {
	$teacher_id=$_GET['teacher_id'];

	// Delete from teacher_info
	$query2="DELETE FROM teacher_info WHERE teacher_id='$teacher_id'";
	$run2=mysqli_query($con,$query2);

	// Delete corresponding login
	$query_delete_login = "DELETE FROM login WHERE user_id='$teacher_id'";
	$run_delete_login = mysqli_query($con, $query_delete_login);

	if ($run2 && $run_delete_login) {
		header('Location: teacher.php');
	} else {
		echo "Record not deleted";
	}
}

?>
