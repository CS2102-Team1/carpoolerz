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
        <form class="row" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="search-form">
            <div class="form-group col-6">
                <label for="p_start">Starting Address: </label>
                <input type="text" name="p_start" class="form-control" id="s_start_address" placeholder="Enter Starting Address"/>
            </div>

            <div class="form-group col-6">
                <label for="p_end">Car Number Plate: </label>
                <input type="text" name="p_end" class="form-control" id="s_end_address" placeholder="Enter Destination Address"/>
            </div>
            <button type="submit" name="searchRidesTrigger" class="form-control btn btn-primary">SEARCH RIDES</button>
            <br/>
        </form>
    </div>
</div>

<?php

    $target_rideID = null;

    if (!empty($_GET['ride_id'])) {
        $target_rideID = $_REQUEST['ride_id'];
        echo "<h1>$target_rideID<h1/>";
    } else {
        echo "<br/><h1 class='text-center'>Ride ID Invalid<h1/>";
    }

?>

<?php include '../footer.shtml'; ?>
</body>
