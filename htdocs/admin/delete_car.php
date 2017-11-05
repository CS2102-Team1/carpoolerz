<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Process delete operation after confirmation
	if(isset($_POST["numplate"]) && !empty($_POST["numplate"])){
		$numplate = trim($_POST["numplate"]);
		// Prepare a delete statement
		$sql = "DELETE FROM car c WHERE c.numplate = '$numplate'";
		// Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if(!$result){
			echo pg_last_error($dbconn);
			} else {
			echo "<h3 class='text-center'>Car Deleted successfully</h3>"."<br>";
			echo "<h4 class='text-center'>Redirecting you back to View Cars page</h4>";
			header("refresh:3;url=admin-cars.php");
		} 
		} else{
		// Check existence of numplate parameter
		if(empty(trim($_GET["numplate"]))){
			// URL doesn't contain numplate parameter.
			echo "Parameter was not passed to this page.";
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Delete Car</title>
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
							<h1>Delete Car</h1>
						</div>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="alert alert-danger fade in">
								<input type="hidden" name="numplate" value="<?php echo trim($_GET["numplate"]); ?>"/>
								<p>Are you sure you want to delete this car?</p><br>
								<p>
									<input type="submit" value="Yes" class="btn btn-danger">
									<a href="admin-cars.php" class="btn btn-default">No</a>
								</p>
							</div>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>