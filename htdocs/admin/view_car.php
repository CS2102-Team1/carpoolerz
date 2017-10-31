<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	$numplate = null;
    if (!empty($_GET['numplate'])) {
        $numplate = $_REQUEST['numplate'];
	}
	//Check existence of numplate parameter before processing further
	if(null != $numplate){
		// Prepare a select statement
		$sql = "SELECT c.numplate, c.brand, c.model, s.username FROM car c, systemuser s, owns_car o WHERE c.numplate=o.numplate AND s.username=o.driver AND c.numplate='$numplate';";
        
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
		<title>View Car</title>
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
							<h1>View Car</h1>
						</div>
						<div class="form-group">
							<label>Number Plate</label>
							<p class="form-control-static"><?php echo $row[0]; ?></p>
						</div>
						<div class="form-group">
							<label>Model</label>
							<p class="form-control-static"><?php echo $row[1]; ?></p>
						</div>
						<div class="form-group">
							<label>Brand</label>
							<p class="form-control-static"><?php echo $row[2]; ?></p>
						</div>
						<div class="form-group">
							<label>Driver (Owner)</label>
							<p class="form-control-static"><?php echo $row[3]; ?></p>
						</div>
						<p><a href="admin-cars.php" class="btn btn-primary">Back</a></p>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>	