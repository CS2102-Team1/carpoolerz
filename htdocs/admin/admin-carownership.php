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
			<h1>Car Ownership</h1>
		</div>
		<div class=container>
			<br>
			<!-- Display all cars with owners -->
			<h3 class="text-center">Cars With Owners</h3>
			<p>
				<a href="create_carownership.php" class="btn btn-success">Create New Link Between Car & Driver</a>
			</p>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Number Plate</th>
						<th>Brand</th>
						<th>Model</th>
						<th>Driver</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query1 = 'SELECT c.numplate, c.brand, c.model, o.driver FROM car c, owns_car o WHERE c.numplate=o.numplate ORDER BY c.numplate ASC;';
						$result = pg_query($query1);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
							echo "\t\t<td><a class='btn btn-primary' href='view_carownership.php?numplate=".$row['numplate']."&driver=".$row['driver']."'>View</a>
							<a class='btn btn-danger' href='delete_carownership.php?numplate=".$row['numplate']."&driver=".$row['driver']."'>Delete</a>
							</td>\n";
							echo "\t</tr>\n";
						}
					?>
				</tbody>
			</table>
		</div>
		<div class=container>
			<br>
			<!-- Display all cars without owners -->
			<h3 class="text-center">Cars Without Owners</h3>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Number Plate</th>
						<th>Brand</th>
						<th>Model</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query2 = 'SELECT * FROM car c WHERE c.numplate NOT IN(SELECT o.numplate FROM owns_car o);';
						$result = pg_query($query2);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
						}
					?>
				</tbody>
			</table>
		</div>
		<div class=container>
			<br>
			<!-- Display all drivers without cars -->
			<h3 class="text-center">Drivers Without Cars</h3>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Username</th>
						<th>Full Name</th>
						<th>License Number</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query3 = 'SELECT s.username, s.fullname, s.licensenum FROM systemuser s WHERE s.username NOT IN(SELECT o.driver FROM owns_car o) AND s.licensenum IS NOT NULL ORDER BY s.username ASC;';
						$result = pg_query($query3);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</body>
	
</html>

