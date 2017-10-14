<?php
    // Check user login status
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password' AND licensenum IS NOT NULL";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ../login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header.shtml'; ?>
</head>

<body>
    <?php include 'navbar-user.shtml'; ?>
    <div class=container>
        <div class="container-fluid">
            <h1 class="text-center">DRIVERS: CREATE NEW RIDE OFFER</h1>
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
        </div>
    </div>

    <?php include '../footer.shtml'; ?>
</body>

</html>

