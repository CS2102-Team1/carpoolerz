<?php
    // Check user login status
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $check_auth_query = /** @php text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

    $check_auth_result = pg_query($dbconn, $check_auth_query);

    if (pg_num_rows($check_auth_result) == 0) {
        header("Location: ../login.php");
    }
    date_default_timezone_set('Singapore');
    $today = date('Y-m-d H:i:s');
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
<h1 class="text-center">YOUR SCHEDULED RIDES</h1>
<br/>

<table class="table table-striped table-hover">
<thead class="thead-inverse">
<tr>
<th>Ride ID</th>
<th>Driver</th>
<th>Start Location</th>
<th>End Location</th>
<th>Start Time/Date</th>
<th>End Time/Date</th>
<th>Scheduled/Taken</th>
</tr>
</thead>
<tbody>
<?php

                    $query = /** @php text */
                    "SELECT ride.ride_id, ride.driver, ride.from_address, ride.to_address, ride.start_time, ride.end_time,
                    CASE WHEN '$today' < ride.start_time THEN 'SCHEDULED'
                    WHEN '$today' >= ride.start_time AND bid.success = true THEN 'TAKEN'
                    WHEN '$today' >= ride.start_time AND bid.success = false THEN 'IGNORED'
                    END
                    FROM ride, bid WHERE ride.passenger = bid.passenger and ride.passenger = '$username' ORDER BY ride.ride_id DESC";
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
<h1 class="text-center">YOUR BIDS</h1>
<br/>

<table class="table table-striped table-hover">
<thead class="thead-inverse">
<tr>
<th>Ride ID</th>
<th>Driver</th>
<th>From</th>
<th>To</th>
<th>Start Time</th>
<th>End Time</th>
<th>Amount</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php

                    $query = /** @php text */
                    "SELECT bid.ride_id, ride.driver, ride.from_address, ride.to_address, ride.start_time, ride.end_time, bid.amount,
                    CASE WHEN '$today' < ride.start_time THEN 'PENDING'
                         WHEN bid.success = true THEN 'SUCCESSFUL'
                         WHEN '$today' >= ride.start_time and bid.success = false THEN 'UNSUCCESSFUL'
                         END
                         FROM bid,ride WHERE ride.ride_id = bid.ride_id and bid.passenger = '$username' ORDER BY bid.ride_id DESC";
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
</div>
<?php include '../footer.shtml'; ?>
</body>
</html>

