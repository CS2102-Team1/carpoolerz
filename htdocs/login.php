<?php
    session_start();
    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

    $result = pg_query($dbconn, $query);

    if(isset($_POST['userLogin']) != "") {

        if (pg_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;

            ob_start();
            header("Location: rider.php");
            ob_end_flush();

        }
    }
//
//    if (isset($_POST['driverLogin']) != "") {
//        if (pg_num_rows($result) == 1) {
//            $_SESSION['username'] = $username;
//            $_SESSION['password'] = $password;
//
//            ob_start();
//            header("Location: drivers.php");
//            ob_end_flush();
//        }
//    }

    if(isset($_POST['adminLogin']) != "") {

        if (pg_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;

            ob_start();
            header("Location: admin.php");
            ob_end_flush();

        }
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Carpoolerz: Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <!-- <link href="./main.css" , rel="stylesheet" /> -->

    </head>

    <body>
        <div class="container">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login-form">
                <div class="form-group">
                    <label for="username">Username: </label>
                    <input type="text" name="username" required class="form-control" id="usr" placeholder="Email"/>
                </div>
                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" name="password" required class="form-control" id="pwd" placeholder="Password"/>
                </div>
                <button type="submit" name="userLogin" class="form-control btn btn-primary">Login as Rider</button>
                <br />
                <br />
                <button type="submit" name="driverLogin" class="form-control btn btn-success">Login as Driver</button>
                <br />
                <br />
                <button type="submit" name="adminLogin" class="form-control btn btn-danger">Login as Admin</button>
                <br />
            </form>
        </div>
    </body>

</html>