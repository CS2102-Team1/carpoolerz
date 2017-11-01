<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Define variables and initialize with empty values
	$numplate = $driver= "";
	$driver_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
		//Validate driver username
		$input_driver = trim($_POST["driver"]);
		//Nothing was submitted
		if(empty($input_driver)){
			$driver_err = "Please enter a driver username.";
		}else{	//something was submitted
			//check if a DRIVER with this username exists
			$sql = "SELECT * FROM systemuser s WHERE s.username ='$input_driver' AND s.licensenum IS NOT NULL;";
			$result = pg_query($dbconn,$sql);
			if(!$result){	//query was unsuccessful
				echo pg_last_error($dbconn);
			}else{	//query was successful
				if (pg_num_rows($result) == 0) {
					$driver_err = "This is not a valid driver!";
				}else{
					$driver = $input_driver;
				}
			}			
		}
		
		// Check input errors before inserting in database
		if(empty($driver_err)){
			$numplate=$_POST['numplate'];
		//insert new ownership link into owns_car table
			$sql = "INSERT into owns_car VALUES('$driver','$numplate');";
			$result = pg_query($dbconn, $sql);
			
			if(!$result){
				echo pg_last_error($dbconn);
			} else {
				echo "<h3>Ownership link successfully</h3>"."<br>";
				echo "<h4>Redirecting you back to View Car Ownership page</h4>";
				header("refresh:3;url=admin-carownership.php");
			} 
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Create Car</title>
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
							<h2>Create Car</h2>
						</div>
						<p>Please fill this form and submit to add car to the database.</p>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="form-group">
								<label>Number Plate</label>
								<input type="text" name="numplate" class="form-control" value="<?php echo $numplate; ?>" required>
							</div>
							<div class="form-group <?php echo (!empty($driver_err)) ? 'has-error' : ''; ?>">
								<label>Driver (Owner)</label>
								<input type="text" name="driver" class="form-control" value="<?php echo $driver; ?>" required>
								<span class="help-block"><?php echo $driver_err;?></span>
							</div>
							<input type="submit" class="btn btn-primary" value="Submit">
							<input type="reset" class="btn btn-warning" value="Reset">
							<a href="admin-carownership.php" class="btn btn-default">Go Back To Car Ownership Page</a>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>