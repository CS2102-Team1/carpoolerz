<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	$numplate = $driver = null;
    if (!empty($_GET['numplate'])) {
        $numplate = $_REQUEST['numplate'];
	}
	if (!empty($_GET['driver'])) {
        $driver = $_REQUEST['driver'];
	}
	//Check existence of numplate & driver parameters before processing further
	if(null != $numplate && null != $driver){
		// Prepare a select statement
		$sql = "SELECT c.numplate, c.brand, c.model, o.driver FROM car c, owns_car o WHERE c.numplate=o.numplate AND o.driver='$driver' AND o.numplate='$numplate';";
        
        // Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if (!$result) {
			echo pg_last_error($dbconn);
			exit;
		}
		$row = pg_fetch_row($result);
	}else{
		echo "Parameter(s) was not received on this page";
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>View Ownership Link</title>
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
							<h1>View Ownership Link</h1>
						</div>
						<div class="form-group">
							<label>Number Plate</label>
							<p class="form-control-static"><?php echo $row[0]; ?></p>
						</div>
						<div class="form-group">
							<label>Brand</label>
							<p class="form-control-static"><?php echo $row[1]; ?></p>
						</div>
						<div class="form-group">
							<label>Model</label>
							<p class="form-control-static"><?php echo $row[2]; ?></p>
						</div>
						<div class="form-group">
							<label>Driver (Owner)</label>
							<p class="form-control-static"><?php echo $row[3]; ?></p>
						</div>
						<p><a href="admin-carownership.php" class="btn btn-primary">Back</a></p>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>	