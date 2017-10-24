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
	</head>
	
	<body>
		<div class=container>
			<h1>Users</h1>
		</div>
		<div class=container>
			<!-- Display all user information -->
			<br>
			<h3>All Users</h3>
			<p>
				<a href="create_user.php" class="btn btn-success">Create</a>
			</p>		
			<table class="table table-striped table-hover custom-table">
				<thead class="thead-inverse">
					<tr>
						<th>Username</th>
						<th>Full Name</th>
						<th>Password</th>
						<th>License Number</th>
						<th>Is Admin?</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query = 'SELECT * FROM systemuser ORDER BY fullname ASC';
						if($result){
							$result = pg_query($query);
							while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
								echo "\t<tr>\n";
								foreach ($row as $col_value) {
									/*if($col_value = 'f'){
										echo "\t\t<td>No</td>\n";
										}else if($col_value = 't'){
										echo "\t\t<td>Yes</td>\n";
									}*/
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
	</body>
	
</html>

