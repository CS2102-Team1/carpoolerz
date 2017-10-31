<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Define variables and initialize with empty values
	$numplate = $brand = $model = $driver= "";
	$driver_err = "";
	$curr_numplate = null;
	if (!empty($_GET['numplate'])) {
        $curr_numplate = $_REQUEST['numplate'];
	}
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
		//Validate driver username
		$input_driver = trim($_POST["driver"]);
		//Nothing was submitted
		if(empty($input_driver)){
			$driver_err = "Please enter a driver username.";
		}else{	//something was submitted
			//check if a driver with this username exists
			$sql1 = "SELECT * FROM systemuser s WHERE s.username ='$input_driver' AND s.licensenum IS NOT NULL;";
			$result = pg_query($dbconn,$sql1);
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
		//update car info in car table
			$sql2 = "UPDATE car SET brand='$brand', model='$model' WHERE numplate='$numplate';";
			$result = pg_query($dbconn, $sql2);
			
			if(!$result){
				echo pg_last_error($dbconn);
			} else {
				echo "<h3>Car Updated successfully</h3>"."<br>";
				echo "<h4>Redirecting you back to View Cars page</h4>";
				header("refresh:3;url=admin-cars.php");
			} 
		}
	}elseif(null != $curr_numplate){//there is no form submission, pull existing data from current numplate to view it in the form		
		
		// Prepare a select statement
		$sql3 = "SELECT c.numplate, c.brand, c.model, s.username FROM car c, systemuser s, owns_car o WHERE c.numplate=o.numplate AND s.username=o.driver AND c.numplate='$curr_numplate';";
        
        // Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql3);
		if (!$result) {
			echo pg_last_error($dbconn);
			exit;
		}
		$row = pg_fetch_row($result);
		$numplate = $row[0];
		$brand = $row[1];
		$model = $row[2];
		$driver = $row[3];
	} 
	else{//couldnt even detect this ride
		echo "Parameter was not received on this page";
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
								<input type="text" name="numplate" class="form-control" value="<?php echo $numplate; ?>" readonly>
							</div>
							<div class="form-group">
								<label>Brand</label>
								<input type="text" name="brand" class="form-control" value="<?php echo $brand; ?>" required>
								<span class="help-block"><?php echo $username_err;?></span>
							</div>
							<div class="form-group">
								<label>Model</label>
								<input type="text" name="model" class="form-control" value="<?php echo $model; ?>" required>
								<span class="help-block"><?php echo $password_err;?></span>
							</div>
							<div class="form-group <?php echo (!empty($driver_err)) ? 'has-error' : ''; ?>">
								<label>Driver (Owner)</label>
								<input type="text" name="driver" class="form-control" value="<?php echo $driver; ?>" readonly>
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