<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './header.shtml'; ?>
    <link href="main.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <br/>
        <div class="panel panel-default">
            <h1 class="text-center">Register For New Carpoolerz Account</h1>
            <form role="form" action="register.php" method="post" name="login-form">
                <div class="form-group">
                    <label for="n_username">Username: </label>
                    <input type="text" name="n_username" required class="form-control" id="n_usr" placeholder="Enter Your Username"/>
                </div>
                <div class="form-group">
                    <label for="n_password">Password: </label>
                    <input type="password" name="n_password" required class="form-control" id="n_pwd" placeholder="Enter Your Password"/>
                </div>
                <div class="form-group">
                    <label for="cn_password">Confirm Password: </label>
                    <input type="password" name="cn_password" required class="form-control" id="cn_pwd" placeholder="Confirm Password"/>
                </div>
                <div class="form-group">
                    <label for="n_fullname">Full Name: </label>
                    <input type="text" name="n_fullname" required class="form-control" id="n_fname" placeholder="Enter Your Full Name"/>
                </div>
                <div class="form-group">
                    <label for="dr_license">Driving License Number: </label>
                    <input type="text" name="n_license" class="form-control" id="dr_lic" placeholder="Driving License Number (OPTIONAL)"/>
                </div>
                <button type="submit" name="createNewAccount" class="form-control btn btn-success">Create New Account</button>
            </form>
            <br/>
        </div>
    </div>

    <?php
        session_start();
        $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
        or die('Could not connect: ' . pg_last_error());

        if (isset($_POST['createNewAccount'])) {

            $new_username = $_POST['n_username'];
            $new_password = $_POST['n_password'];
            $confirm_password = $_POST['cn_password'];
            $new_fullname = $_POST['n_fullname'];
            $new_license = $_POST['n_license'];

            $check_username_query = /** @php text */
                "SELECT * FROM systemuser WHERE username = '$new_username'";

            $username_check_result = pg_query($dbconn, $check_username_query);

            if ($new_password != $confirm_password) {
                echo "<h2 class='text-center'>Passwords entered do not match. Please try again...</h2>";
            } elseif (pg_num_rows($username_check_result) > 0) {
                echo "<h2 class='text-center'>Username has already been used. Please select a different one...</h2>";
            } else {

                if ($new_license == "") {
                    $add_user_query = /** @php text */
                        "INSERT INTO systemuser (username, password, fullname) VALUES ('$new_username', '$new_password', '$new_fullname')";

                    pg_query($dbconn, $add_user_query);

                } else {
                    $add_user_query = /** @php text */
                        "INSERT INTO systemuser (username, password, fullname, licensenum) VALUES ('$new_username', '$new_password', '$new_fullname', '$new_license')";

                    pg_query($dbconn, $add_user_query);
                }

                echo "<h2 class='text-center'>User created successfully.</h2><br/>";
                echo "<div class='container-fluid'><div class='panel panel-default'><form action='login.php'><button type='submit' class='form-control btn btn-primary'>Return To Login Page</button><form/></div></div>";
            }
        }
        //TODO: Could add session data so that users don't have to enter everything again if mistakes were made
    ?>
</body>
</html>