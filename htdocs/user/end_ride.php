<?php
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password' and licensenum IS NOT NULL";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ../login.php");
    }

    if ($_GET['ride_id']) {
        $target_rideID = $_GET['ride_id'];
    } else if (isset($_POST['p_rideID'])) {
        $target_rideID = $_POST['p_rideID'];
    } else {
        echo "<h1>Error. No ride ID detected.<h1/>";
exit;
}

$get_ride_info_query = /** @php text */
"SELECT * FROM ride WHERE ride_id = '$target_rideID'";

$ride_info_result = pg_query($dbconn, $get_ride_info_query);

$ride_info = pg_fetch_array($ride_info_result, NULL, PGSQL_ASSOC);

$ride_id = $ride_info["ride_id"];
$ride_driver = $ride_info["driver"];
$highest_bid = $ride_info["highest_bid"];
$current_passenger = $ride_info["passenger"];
$from_address = $ride_info["from_address"];
$to_address = $ride_info["to_address"];
$start_time = $ride_info["start_time"];
$end_time = $ride_info["end_time"];

date_default_timezone_set('Singapore');
$today = date('Y-m-d H:i:s');
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
<h1>We hope you had a pleasant ride!</h1>
<br/>
<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="delete_ride">
<div class="form-group">

<input type="hidden" name="p_rideID" value="<?php echo $target_rideID; ?>"/>
<button type="submit" name="endRideTrigger" class="form-control btn btn-primary">End Ride</button>
<br/>
</form>
</div>
</div>

<?php

        if (isset($_POST['endRideTrigger'])) {

            $end_ride_query = /** @php text */
                "UPDATE ride SET end_time = '$today' WHERE ride_id = '$target_rideID'";


            $end_ride_result = pg_query($dbconn, $end_ride_query);

            echo "<h1 class='text-center'>Ride Ended!  <h1/>";
echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='drive.php'><button type='submit' class='form-control btn btn-large btn-success'>Return to Your Ride Offers</button><form/></div></div></div>";
echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='user-profile.php'><button type='submit' class='form-control btn btn-warning'>Return to Profile Page</button><form/></div></div></div>";
}



?>

<?php include '../footer.shtml'; ?>
</body>

