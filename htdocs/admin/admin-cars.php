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
			<h1>Cars</h1>
			<p>
				<a href="create_car.php" class="btn btn-success">Create A Car</a>
			</p>
		</div>
		<div class=container>
			<br>
			<!-- Display all cars information -->
			<div class="alert alert-info"><strong>Hello!</strong> This page shows all cars, with and without an owner. If you want to change car ownership, <a href="admin-carownership.php" class="alert-link">click here instead!</a></div>
			<h3  class="text-center">All Cars</h3>
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
						$query = 'SELECT c.numplate, c.brand, c.model, o.driver FROM car c LEFT JOIN owns_car o ON c.numplate=o.numplate ORDER BY c.brand,c.model ASC;';
						$result = pg_query($query);
						while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
							echo "\t<tr>\n";
							foreach ($row as $col_value) {
								echo "\t\t<td>$col_value</td>\n";
							}
							echo "\t\t<td><a class='btn btn-primary' href='view_car.php?numplate=".$row['numplate']."'>View</a>
							<a class='btn btn-warning' href='update_car.php?numplate=".$row['numplate']."'>Update</a>
							<a class='btn btn-danger' href='delete_car.php?numplate=".$row['numplate']."'>Delete</a>
							</td>\n";
							echo "\t</tr>\n";
						}
					?>
				</tbody>
			</table>
		</div>
	</body>
	
</html>

