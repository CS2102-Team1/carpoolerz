<?php
	session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    $usernameQuery = /** @lang text */
        "SELECT * FROM systemuser WHERE '$username' = username AND '$password' = password";

    $adminQuery = /** @lang text */
        "SELECT * FROM is_admin WHERE username = '$username'";

    $usernameResult = pg_query($dbconn, $usernameQuery);
    $adminResult = pg_query($dbconn, $adminQuery);

    if (pg_num_rows($usernameResult) == 0 || pg_num_rows($adminResult) == 0) {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include 'common/header.shtml'; ?>
    </head>

    <body>
        <?php include 'common/navbar-authenticated.shtml'; ?>

        <div class=container>
            <h1>Hello World</h1>
        </div>

        <?php include 'common/footer.shtml'; ?>
    </body>

</html>

