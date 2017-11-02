<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Define variables and initialize with empty values
	$numplate = $brand = $model = $driver= "";
	$driver_err = $numplate_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
		//Validate numplate
		$input_numplate = trim($_POST["numplate"]);
		//Nothing was submitted
		if(empty($input_numplate)){
			$driver_err = "Please enter a number plate.";
		}else{	//something was submitted
			//check if a car with this numplate exists
			$sql = "SELECT * FROM car c WHERE c.numplate ='$input_numplate';";
			$result = pg_query($dbconn,$sql);
			if(!$result){	//query was unsuccessful
				echo pg_last_error($dbconn);
			}else{	//query was successful
				if (pg_num_rows($result) != 0) {
					$numplate_err = "This number plate has already been taken";
				}else{
					$numplate = $input_numplate;
				}
			}			
		}
	
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
			$brand=$_POST['brand'];
			$model=$_POST['model'];
		//insert new car into car table, and new ownership data into owns_car
			$sql = "INSERT into car VALUES('$numplate','$brand','$model');";
			$sql .= "INSERT into owns_car VALUES('$driver','$numplate');";
			$result = pg_query($dbconn, $sql);
			
			if(!$result){
				echo pg_last_error($dbconn);
			} else {
				echo "<h3 class='text-center'>Car Created successfully</h3>"."<br>";
				echo "<h4 class='text-center'>Redirecting you back to View Cars page</h4>";
				header("refresh:3;url=admin-cars.php");
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
							<div class="form-group <?php echo (!empty($numplate_err)) ? 'has-error' : ''; ?>">
								<label>Number Plate</label>
								<input type="text" name="numplate" class="form-control" value="<?php echo $numplate; ?>" required>
								<span class="help-block"><?php echo $numplate_err;?></span>
							</div>
							<div class="form-group">
								<label>Brand</label>
								<input type="text" name="brand" class="form-control" value="<?php echo $brand; ?>" required>
							</div>
							<div class="form-group">
								<label>Model</label>
								<input type="text" name="model" class="form-control" value="<?php echo $model; ?>" required>
							</div>
							<div class="form-group <?php echo (!empty($driver_err)) ? 'has-error' : ''; ?>">
								<label>Driver (Owner)</label>
								<input type="text" name="driver" class="form-control" value="<?php echo $driver; ?>" required>
								<span class="help-block"><?php echo $driver_err;?></span>
							</div>
							<input type="submit" class="btn btn-primary" value="Submit">
							<input type="reset" class="btn btn-warning" value="Reset">
							<a href="admin-cars.php" class="btn btn-default">Go Back To Cars Page</a>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>