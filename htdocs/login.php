<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './header.shtml'; ?>
    <link href="main.css" rel="stylesheet" />
</head>

<body>
    <?php include "./public-navbar.shtml" ?>
    <div class="container">
        <div class="container-fluid">
            <br/>
            <div class="panel panel-default">
                <h1 class="text-center">Log In to Carpoolerz</h1>
                <form role="form" action="login.php" method="post" name="login-form">
                    <div class="form-group">
                        <label for="username">Username: </label>
                        <input type="text" name="username" class="form-control" id="usr" placeholder="Username"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: </label>
                        <input type="password" name="password" class="form-control" id="pwd" placeholder="Password"/>
                    </div>
                    <button type="submit" name="userLogin" class="form-control btn btn-primary">Login as a User</button>
                    <br />
                    <br />
                    <button type="submit" name="adminLogin" class="form-control btn btn-danger">Login as a Admin</button>
                    <br />
                    <br />
                    <button type="submit" name="registerUser" class="form-control btn btn-success">Register Now</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    session_start();
    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    if(isset($_POST['userLogin'])) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];

        $query = /** @php text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

        $result = pg_query($dbconn, $query);

        if (pg_num_rows($result) == 1) {
            ob_start();
            header("Location: ./user/user-profile.php");
            ob_end_flush();
        }
    }

    if(isset($_POST['adminLogin'])) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
		$username = $_SESSION['username'];
        $password = $_SESSION['password'];

        $query = /** @php text */
        "SELECT * FROM systemuser s WHERE s.username = '$username' AND s.password = '$password' AND s.is_admin='TRUE'";

        $result = pg_query($dbconn, $query);

        if (pg_num_rows($result) == 1) {
            ob_start();
            header("Location: ./admin/admin-users.php");
            ob_end_flush();
        }
    }

    if (isset($_POST['registerUser'])) {
        ob_start();
        header("Location: register.php");
        ob_end_flush();
    }
    ?>
    <?php include "./footer.shtml" ?>
</body>
</html>