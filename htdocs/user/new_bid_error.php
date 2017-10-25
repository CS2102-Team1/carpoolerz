<?php
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ../login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../header.shtml'; ?>
    <link href="../main.css" rel="stylesheet" />
</head>

<body>
    <?php include 'navbar-user.shtml'; ?>

    <div class="container">
        <div class="container-fluid">
            <br/>
            <h1>Error. Ride not found/available</h1>
            <br/>
            <a class='btn btn-warning center' href="./bid-ride.php">Bid Another Ride</a>
        </div>
    </div>

    <?php include '../footer.shtml'; ?>
</body>

