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

            // TODO: Update ride table with the new highest bids --> In case your bid is the one that is highest
            $updated_highest_bid = 0;
            $check_highest_bid_query = /** @php text */
                "";

            $check_highest_bid_result = pg_query($dbconn, $check_highest_bid_query);

            echo "<h1 class='text-center'>Bid deleted successfully<h1/>";
            echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='bid-ride.php'><button type='submit' class='form-control btn btn-large btn-success'>Return to Your Bids</button><form/></div></div></div>";
            echo "<div class='container'><div class='container-fluid'><div class='panel panel-default'><form action='user-profile.php'><button type='submit' class='form-control btn btn-warning'>Return to Profile Page</button><form/></div></div></div>";
        }

    ?>

    <?php include '../footer.shtml'; ?>
</body>

