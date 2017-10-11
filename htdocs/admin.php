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
    <title>Carpoolerz</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="main.css" rel="stylesheet" />
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Carpoolerz</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="login.php">Sign Out</a>
            </li>
        </ul>
    </div>
</nav>

<div class=container>
    <h1>Hello World</h1>
</div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>

</html>

