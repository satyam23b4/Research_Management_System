<?php  
session_start();
if (!$_SESSION["LoginAdmin"]) {
    header('location:../login/login.php');
}
require_once "../connection/connection.php";

$message = "";

if (isset($_POST['submit'])) {
    $account = $_POST['account'];
    $user_id = $_POST['user_id'];
    
    // Check if user ID exists in the database
    $check_query = "SELECT * FROM login WHERE user_id = '$user_id'";
    $check_result = mysqli_query($con, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // User ID exists, proceed to update account status
        $update_query = "UPDATE login SET account='$account' WHERE user_id = '$user_id'";
        $update_run = mysqli_query($con, $update_query);
        
        if ($update_run) {
            $message = $account == "Activate" ? "Account Activated Successfully" : "Account Deactivated Successfully";
        } else {
            $message = "Account Not Activated Successfully";
        }
    } else {
        // User ID does not exist
        $message = "User ID not found in the database";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Admin - Manage Accounts</title>
</head>
<body>
    <?php include('../common/common-header.php') ?>
    <?php include('../common/admin-sidebar.php') ?>  

    <main role="main" class="col-xl-10 col-lg-9 col-md-8 ml-sm-auto px-md-4 mb-2 w-100">
        <div class="sub-main">
            <div class="bar-margin text-center d-flex flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 text-white admin-dashboard pl-3">
                <h4>User Account Management System </h4>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (!empty($message)): ?>
                                <h5 class="py-2 pl-3 <?php echo $update_run ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php echo $message; ?>
                                </h5>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12">
                            <form action="manage-accounts.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Enter User ID</label>
                                            <input type="text" name="user_id" class="form-control" required placeholder="Enter ID">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Enter Account Status:</label>
                                            <select name="account" class="form-control">
                                                <option>Select Account Status</option>
                                                <option value="Activate">Activate</option>
                                                <option value="Deactivate">Deactivate</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="submit" name="submit" value="Change" class="btn btn-primary px-5">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
