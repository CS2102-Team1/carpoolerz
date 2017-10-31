<?php
	session_start();
	
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	$query = /** @lang text */
	"SELECT * FROM systemuser s WHERE '$username' = s.username AND '$password' = s.password AND s.is_admin = 'TRUE'";
	
	$result = pg_query($dbconn, $query);

	if (pg_num_rows($result) == 0) {
		header("Location: /carpoolerz/login.php");
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
			<p>
				<a href="create_ride.php" class="btn btn-success">Create</a>
			</p>
		</div>
		<div class=container>
			<!-- Display all previous rides -->
			<br>
			<h3>Past Rides</h3>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Ride ID</th>
						<th>Highest Bid ($)</th>
						<th>Driver</th>
						<th>Passenger</th>
						<th>From</th>
						<th>To</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query = 'SELECT * FROM ride r WHERE r.end_time IS NOT NULL ORDER BY r.ride_id ASC';
						$result = pg_query($query);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
							echo "\t\t<td><a class='btn btn-primary' href='view_ride.php?id=".$row['ride_id']."'>View</a>
							<a class='btn btn-warning' href='update_ride.php?id=".$row['ride_id']."'>Update</a>
							<a class='btn btn-danger' href='delete_ride.php?id=".$row['ride_id']."'>Delete</a>
							</td>\n";
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						//need to use different query variable for storage.
						$query2 = 'SELECT * FROM ride r WHERE r.end_time IS NULL AND r.start_time < NOW()::timestamp';
						//ride has started but has not ended 
						$result = pg_query($query2);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
							echo "\t\t<td><a class='btn btn-primary' href='view_ride.php?id=".$row['ride_id']."'>View</a>
							<a class='btn btn-warning' href='update_ride.php?id=".$row['ride_id']."'>Update</a>
							<a class='btn btn-danger' href='delete_ride.php?id=".$row['ride_id']."'>Delete</a>
							</td>\n";
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						//need to use different query variable for storage.
						$query3 = 'SELECT * FROM ride r WHERE r.end_time IS NULL AND r.start_time >= NOW()::timestamp';
						//Ride has not started, so its available for bidding					
						$result = pg_query($query3);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
							echo "\t\t<td><a class='btn btn-primary' href='view_ride.php?id=".$row['ride_id']."'>View</a>
							<a class='btn btn-warning' href='update_ride.php?id=".$row['ride_id']."'>Update</a>
							<a class='btn btn-danger' href='delete_ride.php?id=".$row['ride_id']."'>Delete</a>
							</td>\n";
							echo "\t</tr>\n";
						}
					?>
				</tbody>
			</table>
		</div>
	</body>
	
</html>

