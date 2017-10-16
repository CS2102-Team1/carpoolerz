<?php
session_start();

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$is_admin = $_SESSION['is_admin'];

$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
or die('Could not connect: ' . pg_last_error());

$query = /** @lang text */
"SELECT * FROM systemuser WHERE '$username' = username AND '$password' = password AND is_admin = TRUE";

$result = pg_query($dbconn, $query);

if (pg_num_rows($result) == 0) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header.shtml'; ?>
    <?php include 'admin-navbar.shtml'; ?>
</head>

<body>
    <div class=container>
        <h1>Rides</h1>
    </div>
    <div class=container>
        <!-- Display all previous rides -->
        <br>
        <h3>Past Rides</h3>
        <table class="table table-striped table-hover custom-table">
            <thead class="thead-inverse">
                <tr>
                    <th>Ride ID</th>
                    <th>Highest Bid (Ride Amount)</th>
                    <th>Driver</th>
                    <th>Passenger</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = 'SELECT * FROM ride r WHERE r.end_time IS NOT NULL';
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
    </div>
    <div class=container>
        <!-- Display all rides currently on journey-->
        <h3>Ongoing Rides</h3>
        <table class="table table-striped table-hover custom-table">
            <thead class="thead-inverse">
                <tr>
                    <th>Ride ID</th>
                    <th>Highest Bid (Ride Amount)</th>
                    <th>Driver</th>
                    <th>Passenger</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = 'SELECT * FROM ride r WHERE r.start_time < CURRENT_TIMESTAMP';
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
    </div>
    <div class=container>
        <!-- Display rides currently available for bidding-->
        <h3>Rides Available For Bidding</h3>
        <table class="table table-striped table-hover custom-table">
            <thead class="thead-inverse">
                <tr>
                    <th>Ride ID</th>
                    <th>Highest Bid (Ride Amount)</th>
                    <th>Driver</th>
                    <th>Passenger</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = '
                SELECT cr.ride_id, cr.driver, r.start_time, r.from_address, r.to_address  
                FROM ride r, created_rides cr
                WHERE r.ride_id = cr.ride_id AND r.start_time > CURRENT_TIMESTAMP';
                
                //If ride start time is later than current time, ride has not started and hence is available for bidding

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
    </div>
</body>

</html>

