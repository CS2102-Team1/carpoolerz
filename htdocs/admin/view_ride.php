<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	$id = null;
    if (!empty($_GET['id'])) {
        $id = $_REQUEST['id'];
	}
	//Check existence of id parameter before processing further
	if(null != $id){
		// Prepare a select statement
		$sql = "SELECT * FROM ride r WHERE r.ride_id = '$id'";
        
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
		<title>View Ride</title>
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
							<h1>View Ride</h1>
						</div>
						<div class="form-group">
							<label>Ride ID</label>
							<p class="form-control-static"><?php echo $row[0]; ?></p>
						</div>
						<div class="form-group">
							<label>Highest Bid</label>
							<p class="form-control-static"><?php echo $row[1]; ?></p>
						</div>
						<div class="form-group">
							<label>Driver</label>
							<p class="form-control-static"><?php echo $row[2]; ?></p>
						</div>
						<div class="form-group">
							<label>Passenger</label>
							<p class="form-control-static"><?php echo $row[3]; ?></p>
						</div>
						<div class="form-group">
							<label>From Address</label>
							<p class="form-control-static"><?php echo $row[4]; ?></p>
						</div>
						<div class="form-group">
							<label>To Address</label>
							<p class="form-control-static"><?php echo $row[5]; ?></p>
						</div>
						<div class="form-group">
							<label>Start Time & Date</label>
							<p class="form-control-static"><?php echo $row[6]; ?></p>
						</div>
						<div class="form-group">
							<label>End Time & Date</label>
							<p class="form-control-static"><?php echo $row[7]; ?></p>
						</div>
						<p><a href="admin-rides.php" class="btn btn-primary">Back</a></p>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>	