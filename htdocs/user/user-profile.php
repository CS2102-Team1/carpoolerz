<?php
	session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    $query = /** @php text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ../login.php");
    }

    $row = pg_fetch_row($result);
    $full_name = $row[1];
    $license_id = $row[3];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header.shtml'; ?>
    <link href="../main.css" rel="stylesheet" />
</head>

<body>

    <?php include 'navbar-user.shtml'; ?>

    <div class=container>

		<div class="container-fluid">

            <h1 class="text-center">YOUR DETAILS</h1>
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login-form">
                <div class="form-group">
                    <label for="p_username">Username: </label>
                    <h1><?php echo $username?></h1>
                </div>

                <div class="form-group">
                    <label for="p_password">Password: </label>
                    <input type="password" name="p_password" required class="form-control" id="pwd" value="<?php echo $password?>" placeholder="Password"/>
                </div>

                <div class="form-group">
                    <label for="p_fullname">Full Name: </label>
                    <input type="text" name="p_fullname" required class="form-control" id="f_name" value="<?php echo $full_name?>" placeholder="Full Name"/>
                </div>

                <div class="form-group">
                    <label for="p_license">Driving License ID: </label>
                    <input type="text" name="p_license" class="form-control" id="lic" value="<?php echo $license_id?>" placeholder="Driving License ID"/>
                </div>
                <button type="submit" name="changeProfileDetails" class="form-control btn btn-danger">UPDATE DETAILS</button>
            </form>

            <?php

                if (isset($_POST['changeProfileDetails'])) {
                    $password_updated = $_POST['p_password'];
                    $fullname_updated = $_POST['p_fullname'];
                    $license_updated = $_POST['p_license'];

                    $update_query = /** @php text */
                        "UPDATE systemuser SET password = '$password_updated', fullname = '$fullname_updated', licensenum = '$license_updated' WHERE username = '$username'";

                    $result = pg_query($dbconn, $update_query);

                    //Cleanup by nulling "" values
                    $cleanup_query = /** @php text */
                        "UPDATE systemuser SET licensenum = DEFAULT WHERE licensenum = ''";
                    $cleanup = pg_query($dbconn, $cleanup_query);

                    // After updating, set session password to new password
                    $_SESSION['password'] = $password_updated;
                    $password = $_SESSION['password'];

                    header("Refresh:0");
                }

            ?>

		</div>

	</div>

    <?php include '../footer.shtml'; ?>
</body>
<html/>