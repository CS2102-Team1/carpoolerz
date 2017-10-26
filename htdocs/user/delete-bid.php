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

    $target_rideID = "";

    if ($_GET['ride_id']) {
        $target_rideID = $_GET['ride_id'];
    } else if (isset($_POST['deleteBidsTrigger'])) {
        $target_rideID = $_POST['p_rideID'];
    } else {
        echo "<h1>Error. No ride ID detected.<h1/>";
        exit;
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
            <h1>Are you sure you want to delete this bid?</h1>
            <br/>
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="edit_bid">
                <div class="form-group">
                    <label><b>Driver: <?php echo $ride_driver ?></label>
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
                    <label><b>Your Current Bid:<b/> SGD <?php echo $your_current_bid ?></label>
                </div>

                <input type="hidden" name="p_rideID" value="<?php echo $target_rideID; ?>"/>
                <button type="submit" name="deleteBidsTrigger" class="form-control btn btn-primary">CONFIRM DELETE BID</button>
                <br/>
            </form>
        </div>
    </div>

    <?php

        if (isset($_POST['deleteBidsTrigger'])) {
            $delete_bid_query = /** @php text */
                "DELETE FROM bid b WHERE b.passenger = '$username' AND b.ride_id = '$target_rideID'";

            $delete_bid_result = pg_query($dbconn, $delete_bid_query);

            $updated_highest_bid = 0;
            $check_highest_bid_query = /** @php text */
                "SELECT MAX(amount) from bid WHERE ride_id = '$target_rideID'";

            $check_highest_bid_result = pg_query($dbconn, $check_highest_bid_query);
            $new_highest_bid = 0;
            if (pg_num_rows($check_highest_bid_result) != 0) {
                $row = pg_fetch_row($check_highest_bid_result, PGSQL_ASSOC);
                $max_bid = $row['max'];
                if ($max_bid > $new_highest_bid) {
                    $new_highest_bid = $max_bid;
                }
            }

            $update_highest_bid_query = /** @php text */
                    "UPDATE ride SET highest_bid = '$new_highest_bid' WHERE ride_id = $target_rideID";
            pg_query($dbconn, $update_highest_bid_query);

            echo "<h1 class='text-center'>Bid deleted successfully<h1/>";
            echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='bid-ride.php'><button type='submit' class='form-control btn btn-large btn-success'>Return to Your Bids</button><form/></div></div></div>";
            echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='user-profile.php'><button type='submit' class='form-control btn btn-warning'>Return to Profile Page</button><form/></div></div></div>";
        }

    ?>

    <?php include '../footer.shtml'; ?>
</body>

