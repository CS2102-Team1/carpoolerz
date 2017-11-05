<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Process delete operation after confirmation
	if(isset($_POST["numplate"]) && !empty($_POST["numplate"]) && isset($_POST["driver"]) && !empty($_POST["driver"])){
		$numplate = trim($_POST["numplate"]);
		$driver = trim($_POST["driver"]);
		// Prepare a delete statement
		$sql = "DELETE FROM owns_car o WHERE o.numplate = '$numplate' AND o.driver = '$driver'";
		// Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if(!$result){
			echo pg_last_error($dbconn);
			} else {
			echo "<h3 class='text-center'>Car ownership link deleted successfully</h3>"."<br>";
			echo "<h4 class='text-center'>Redirecting you back to View Car Ownership page</h4>";
			header("refresh:3;url=admin-carownership.php");
		} 
	} else{
		// Check existence of numplate & driver parameter
		if(empty(trim($_GET["numplate"])) || empty(trim($_GET["numplate"]))){
			// URL doesn't contain numplate & driver parameter.
			echo "Parameter(s) not passed to this page.";
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Delete Ownership Link</title>
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
							<h1>Delete Ownership Link</h1>
						</div>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="alert alert-danger fade in">
								<input type="hidden" name="numplate" value="<?php echo trim($_GET["numplate"]); ?>"/>
								<input type="hidden" name="driver" value="<?php echo trim($_GET["driver"]); ?>"/>
								<p>Are you sure you want to delete this car?</p><br>
								<p>
									<input type="submit" value="Yes" class="btn btn-danger">
									<a href="admin-carownership.php" class="btn btn-default">No</a>
								</p>
							</div>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>