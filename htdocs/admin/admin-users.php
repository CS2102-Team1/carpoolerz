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
<!-- TODO: Create scrollable table -->
<!DOCTYPE html>
<html lang="en">	
	<head>
		<?php include '../header.shtml'; ?>
		<?php include 'admin-navbar.shtml'; ?>
		<style type="text/css">
		.usersTable{
			overflow-y: auto;
		}
		</style>
		<script type="text/javascript">
		function formatTableRows() {
            var maxRows = 5;
            var table = document.getElementById('usersTable');
            var wrapper = table.parentNode;
            var rowsInTable = table.rows.length;
            var height = 0;
            if (rowsInTable > maxRows) {
                for (var i = 0; i < maxRows; i++) {
                    height += table.rows[i].clientHeight;
                }
                wrapper.style.height = height + "px";
            }
        }
		</script>	
	</head>
	
	<body onload="formatTableRows();">
		<div class=container>
			<h1>Users</h1>
			<p>
				<a href="create_user.php" class="btn btn-success">Create</a>
			</p>
		</div>
		<div class=container>
			<!-- Display all drivers -->
			<br>
			<h3>Drivers</h3>
			<div id=usersTable>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Username</th>
						<th>Full Name</th>
						<th>Password</th>
						<th>License Number</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query = 'SELECT username,fullname,password,licensenum FROM systemuser WHERE licensenum IS NOT NULL AND NOT is_admin ORDER BY fullname ASC;';
						if($result){
							$result = pg_query($query);
							while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
								echo "\t<tr>\n";
								foreach ($row as $col_value) {
									echo "\t\t<td>$col_value</td>\n";
								}
								echo "\t\t<td><a class='btn btn-primary' href='view_user.php?username=".$row['username']."'>View</a>
								<a class='btn btn-warning' href='update_user.php?username=".$row['username']."'>Update</a>
								<a class='btn btn-danger' href='delete_user.php?username=".$row['username']."'>Delete</a>
								</td>\n";
								echo "\t</tr>\n";
							}
						}else{
							echo pg_last_error($dbconn);
							exit;
						}
					?>
				</tbody>
			</table>
			</div>
		</div>
		<div class=container>
			<!-- Display all riders -->
			<br>
			<h3>Riders</h3>
			<div id=usersTable>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Username</th>
						<th>Full Name</th>
						<th>Password</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query = 'SELECT username, fullname, password FROM systemuser WHERE licensenum IS NULL AND NOT is_admin ORDER BY fullname ASC;';
						if($result){
							$result = pg_query($query);
							while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
								echo "\t<tr>\n";
								foreach ($row as $col_value) {
									echo "\t\t<td>$col_value</td>\n";
								}
								echo "\t\t<td><a class='btn btn-primary' href='view_user.php?username=".$row['username']."'>View</a>
								<a class='btn btn-warning' href='update_user.php?username=".$row['username']."'>Update</a>
								<a class='btn btn-danger' href='delete_user.php?username=".$row['username']."'>Delete</a>
								</td>\n";
								echo "\t</tr>\n";
							}
						}else{
							echo pg_last_error($dbconn);
							exit;
						}
					?>
				</tbody>
			</table>
			</div>
		</div>
		<div class=container>
			<!-- Display all admins -->
			<br>
			<h3>Admins</h3>
			<div id=usersTable>
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Username</th>
						<th>Full Name</th>
						<th>Password</th>
						<th>License Number</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query = 'SELECT username,fullname,password,licensenum FROM systemuser WHERE is_admin ORDER BY fullname ASC;';
						if($result){
							$result = pg_query($query);
							while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
								echo "\t<tr>\n";
								foreach ($row as $col_value) {
									echo "\t\t<td>$col_value</td>\n";
								}
								echo "\t\t<td><a class='btn btn-primary' href='view_user.php?username=".$row['username']."'>View</a>
								<a class='btn btn-warning' href='update_user.php?username=".$row['username']."'>Update</a>
								<a class='btn btn-danger' href='delete_user.php?username=".$row['username']."'>Delete</a>
								</td>\n";
								echo "\t</tr>\n";
							}
						}else{
							echo pg_last_error($dbconn);
							exit;
						}
					?>
				</tbody>
			</table>
			</div>
		</div>
	</body>
	
</html>

