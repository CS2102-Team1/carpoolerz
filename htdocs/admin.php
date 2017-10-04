<?php
	session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    $query = "SELECT * FROM systemuser WHERE '$username' = 'admin@admin.com' AND '$password' = 'password'";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        if (pg_num_rows($result) == 0) {
            header("Location: login.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Carpoolerz</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<link href="main.css" , rel="stylesheet" />
</head>

<body>
    <h1>HELLO WORLD THIS IS THE ADMIN WEBSITE</h1>
</body>
