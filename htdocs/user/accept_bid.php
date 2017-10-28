<?php
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password' AND licensenum IS NOT NULL";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ../login.php");
    }

    $target_rideID = $ride_driver = $highest_bid = $current_passenger = $from_address = $to_address = $start_time = $end_time = "";

    if ($_GET['ride_id']) {
        $target_rideID = $_GET['ride_id'];
    } elseif (isset($_POST['acceptBidsTrigger'])) {
        $target_rideID = $_POST['p_rideID'];
    } else {
        echo "<h1>Error. No ride ID detected.<h1/>";
}

$get_ride_info_query = /** @php text */
"SELECT * FROM ride WHERE ride_id = '$target_rideID'";

$ride_info_result = pg_query($dbconn, $get_ride_info_query);

$ride_info = pg_fetch_array($ride_info_result, NULL, PGSQL_ASSOC);

$ride_driver = $ride_info["driver"];
$highest_bid = $ride_info["highest_bid"];
$current_passenger = $ride_info["passenger"];
$from_address = $ride_info["from_address"];
$to_address = $ride_info["to_address"];
$start_time = $ride_info["start_time"];
$end_time = $ride_info["end_time"];

$view_bid_query = /** @php text */
"SELECT * FROM bid WHERE passenger = '$current_passenger' AND ride_id = '$target_rideID'";
$view_bid_result = pg_query($dbconn, $view_bid_query);

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
                <div class="page-header">
                        <h2 class="text-center">Accept Bid</h2>
                </div>
                <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="accept_bid">
                        <div class="form-group">
                                <label><b>Driver: <?php echo $username ?></label>
                        </div>

                        <div class="form-group">
                                <label><b>Pick Up Point:<b/> <?php echo $from_address ?></label>
                        </div>

                        <div class="form-group">
                                <label><b>Drop Off Point:<b/> <?php echo $to_address ?></label>
                        </div>

                        <div class="form-group">
                                <label><b>Start Time:<b/> <?php echo $start_time ?></label>
                        </div>

                        <div class="form-group">
                                <label><b>Highest Bid:<b/> SGD <?php echo $highest_bid ?></label>
                        </div>

                        <div class="form-group">
                                <label><b>Bid Owner:<b/> <?php echo $current_passenger ?></label>
                        </div>
                       
                        <input type="hidden" name="p_rideID" value="<?php echo $target_rideID; ?>"/>
                        <button type="submit" name="acceptBidsTrigger" class="form-control btn btn-primary">CONFIRM ACCEPT BID</button>
                        <br/>
                </form>
        </div>
</div>

<?php

    if (isset($_POST['acceptBidsTrigger'])) {


        $check_bids_query = /** @php text */
                "UPDATE bid SET success = true WHERE passenger = '$current_passenger' AND ride_id = '$target_rideID' ";

        $check_bids_result = pg_query($dbconn, $check_bids_query);

        echo "<h1 class='text-center'>Bid Accepted<h1/>";

        echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='drive.php'><button type='submit' class='form-control btn btn-large btn-success'>Return to Your Ride Offers</button><form/></div></div></div>";
        echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='user-profile.php'><button type='submit' class='form-control btn btn-warning'>Return to Profile Page</button><form/></div></div></div>";
        }
?>

<?php include '../footer.shtml'; ?>
</body>
