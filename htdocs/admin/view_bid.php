<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	$username = null;
    if (!empty($_GET['username'])) {
        $username = $_REQUEST['username'];
	}
	//Check existence of username parameter before processing further
	if(null != $username){
		// Prepare a select statement
		$sql = "SELECT * FROM systemuser s WHERE s.username = '$username'";
        
        // Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if (!$result) {
			echo pg_last_error($dbconn);
			exit;
		}
		$row = pg_fetch_row($result);
	}else{
		echo "Parameter was not received on this page";
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>View User</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<style type="text/css">
			.wrapper{
            width: 500px;
            margin: 0 auto;
			}
		</style>
	</head>
	<body>
		<div class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="page-header">
							<h1>View User</h1>
						</div>
						<div class="form-group">
							<label>Name</label>
							<p class="form-control-static"><?php echo $row[1]; ?></p>
						</div>
						<div class="form-group">
							<label>Username</label>
							<p class="form-control-static"><?php echo $row[0]; ?></p>
						</div>
						<div class="form-group">
							<label>Password</label>
							<p class="form-control-static" type="password"><?php echo $row[2]; ?></p>
						</div>
						<div class="form-group">
							<label>License Number</label>
							<p class="form-control-static"><?php echo $row[3]; ?></p>
						</div>
						<div class="form-group">
							<label>Admin?</label>
							<p class="form-control-static"><?php echo $row[4]; ?></p>
						</div>
						<p><a href="admin-users.php" class="btn btn-primary">Back</a></p>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>	