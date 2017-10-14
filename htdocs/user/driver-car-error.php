<?php
// Check user login status
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
    </head>
    <body>
        <?php include 'navbar-user.shtml'; ?>
        <div class=container>
            <div class="container-fluid">
                <br/>
                <h1 class="text-center">You must have a driving license in order to add a car!</h1>
                <br/>
                <div class='panel panel-default'>
                    <form action='user-profile.php'>
                        <button type='submit' class='form-control btn btn-primary'>Return to Profile Page</button>
                    <form/>
                </div>
            </div>
        </div>
        <?php include '../footer.shtml'; ?>
    </body>
    <?php

    ?>
</html>
