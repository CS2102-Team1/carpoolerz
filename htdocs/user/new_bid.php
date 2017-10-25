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

    $target_rideID = null;

    if (!empty($_GET['ride_id'])) {
        $target_rideID = $_REQUEST['ride_id'];
        echo "<h1>$target_rideID<h1/>";
    } else {
        echo "<br/><h1 class='text-center'>Ride ID Invalid<h1/>";
    }
?>

