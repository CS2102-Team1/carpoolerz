<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Process delete operation after confirmation
	if(isset($_POST["ride_id"]) && !empty($_POST["ride_id"])){
		$ride_id = trim($_POST["ride_id"]);
		// Prepare delete statements
		$sql = "DELETE FROM ride r WHERE r.ride_id = '$ride_id';";
		$sql .= "DELETE FROM bid b WHERE b.ride_id = '$ride_id';";
		// Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if(!$result){
			echo pg_last_error($dbconn);
			} else {
			echo "<h3>Ride Deleted successfully</h3>"."<br>";
			echo "<h4>Redirecting you back to View Rides page</h4>";
			header("refresh:3;url=admin-rides.php");
		} 
		} else{
		// Check existence of id parameter
		if(empty(trim($_GET["id"]))){
			// URL doesn't contain id parameter.
			echo "Parameter was not passed to this page.";
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Delete Ride</title>
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
							<h1>Delete Ride</h1>
						</div>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="alert alert-danger fade in">
								<input type="hidden" name="ride_id" value="<?php echo trim($_GET["id"]); ?>"/>
								<p>Are you sure you want to delete this ride?</p><br>
								<p>
									<input type="submit" value="Yes" class="btn btn-danger">
									<a href="admin-rides.php" class="btn btn-default">No</a>
								</p>
							</div>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>