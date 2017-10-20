<?php
    // Check user login status
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $check_auth_query = /** @php text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password' AND licensenum IS NOT NULL";

    $check_auth_result = pg_query($dbconn, $check_auth_query);

    // Logic: If user has a car then he must be a driver since car updating happens in the driver-car page
    $check_car_query = /** @php text */
        "SELECT * FROM owns_car WHERE driver = '$username'";

    $check_car_result = pg_query($dbconn, $check_car_query);

    if (pg_num_rows($check_auth_result) == 0) {
        header("Location: ../login.php");
    }

    if (pg_num_rows($check_car_result) == 0) {
        header("Location: ./drive-error.php");
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
                <h1 class="text-center">RIDE OFFERS YOU MADE</h1>
                <br/>

                <table class="table table-striped table-hover">
                    <thead class="thead-inverse">
                    <tr>
                        <th>Ride ID</th>
                        <th>Start Location</th>
                        <th>End Location</th>
                        <th>Start Time/Date</th>
                        <th>End Time/Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $query = "SELECT ride_id, from_address, to_address, start_time, end_time FROM ride WHERE driver = '$username' ORDER BY ride_id DESC";
                    $result = pg_query($query);

                    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                        echo "\t<tr>\n";
                        foreach ($line as $col_value) {
                            echo "\t\t<td>$col_value</td>\n";
                        }
                        echo "\t</tr>\n";
                    }
                    ?>
                    </tbody>
                </table>
                <br/>
            </div>
            <br/>
            <div class="container-fluid">
                <h1 class="text-center">CREATE NEW RIDE OFFER</h1>
                <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login-form">
                    <div class="form-group">
                        <label for="p_username">Username: </label>
                        <h1><?php echo $username?></h1>
                    </div>

                    <div class="form-group">
                        <label for="p_start">Starting Location: </label>
                        <input type="text" name="p_start" required class="form-control" id="start_location" placeholder="Enter Starting Location"/>
                    </div>

                    <div class="form-group">
                        <label for="p_destination">Destination: </label>
                        <input type="text" name="p_destination" required class="form-control" id="end_location" placeholder="Enter Destination"/>
                    </div>

                    <div class="form-group">
                        <label for="p_startDate">Start Date (DD/MM/YYYY): </label>
                        <input type="text" name="p_startDate" class="form-control" id="start_date" placeholder="DD/MM/YYYY"/>
                    </div>

                    <div class="form-group">
                        <label for="p_startTime">Start Time (HH:mm:ss): </label>
                        <input type="text" name="p_startTime" class="form-control" id="start_time" placeholder="HH:mm:ss"/>
                    </div>

                    <button type="submit" name="createNewRide" class="form-control btn btn-success">ADD RIDE OFFER</button>
                </form>
                <br/>
            </div>
        </div>
        <?php include '../footer.shtml'; ?>
    </body>
    <?php
        if (isset($_POST['createNewRide'])) {
            $startAddress = $_POST['p_start'];
            $endAddress = $_POST['p_destination'];
            $startDate = $_POST['p_startDate'];
            $startTime = $_POST['p_startTime'];

            $datetime = $startDate." ".$startTime;

            //TODO: Condition for timestamp check is not working. See query below
            $check_rides_query = /** @php text */
                "SELECT * FROM ride WHERE driver = '$username' AND start_time = to_timestamp('$datetime', 'DD/MM/YYYY HH24:MI:SS') AND end_time ISNULL";

            $check_rides_result = pg_query($dbconn, $check_rides_query);

            if (pg_num_rows($check_rides_result) > 0) {
                echo "<br/><h1 class='text-center'>You already have a ride scheduled for this time!</h1><br/><br/>";
            } else {
                //Note: Dummy value is put as a placeholder for bidder
                $add_rides_query = /** @php text */
                    "INSERT INTO ride(highest_bid, driver, passenger, from_address, to_address, start_time) VALUES(0, '$username', '', '$startAddress', '$endAddress', to_timestamp('$datetime', 'DD/MM/YYYY HH24:MI:SS'))";

                $add_rides_query = pg_query($dbconn, $add_rides_query);

                //Get Ride ID
                $next_ride_id_query = /** @php text */
                    "SELECT last_value FROM ride_id";

                $next_ride_id_result = pg_query($dbconn, $next_ride_id_query);

                $target_rideID = pg_fetch_row($next_ride_id_result)[0];

                $add_created_rides_query = /** @php text */
                    "INSERT INTO created_rides(driver, ride_id) VALUES('$username', '$target_rideID')";

                $add_created_rides_result = pg_query($dbconn, $add_created_rides_query);

                echo "<h1 class='text-center'>New ride created successfully...<h1/>";
                echo "<div class='container-fluid'><div class='panel panel-default'><form action='user-profile.php'><button type='submit' class='form-control btn btn-primary'>Return to Profile Page</button><form/></div></div>";
                echo "<meta http-equiv=\"refresh\" content=\"4;URL=drive.php\">";
            }

        }
    ?>
</html>

