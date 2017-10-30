<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Define variables and initialize with empty values
	$highest_bid = $driver = $passenger = $from_address = $to_address = $start_time= $start_date = $end_date = $end_time = $start = $end = "";
	$driver_err = $passenger_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		//Validate driver username
		$input_driver = trim($_POST["driver"]);
			//Nothing was submitted
		if(empty($input_driver)){
			$driver_err = "Please enter a driver username.";
		}else{	//something was submitted
			//check if a driver with this username exists
			$sql = "SELECT * FROM systemuser s WHERE s.username ='$input_driver' AND s.licensenum IS NOT NULL";
			$result = pg_query($dbconn,$sql);
			if(!$result){	//query was unsuccessful
				echo pg_last_error($dbconn);
			}else{	//query was successful
				if (pg_num_rows($result) == 0) {
					$driver_err = "Driver does not exist in the system";
				}else{
					$driver = $input_driver;
				}
			}			
		}
		
		// Validate passenger username
		$input_passenger = trim($_POST["passenger"]);
			//Nothing was submitted
		if(empty($input_passenger)){
			$passenger_err = "Please enter a passenger username.";
		}else{	//something was submitted
			//check if a passenger with this username exists
			$sql = "SELECT * FROM systemuser s WHERE s.username ='$input_passenger'";
			$result = pg_query($dbconn,$sql);
			if(!$result){	//query was unsuccessful
				echo pg_last_error($dbconn);
			}else{	//query was successful
				if (pg_num_rows($result) == 0) {
					$passenger_err = "Passenger does not exist in the system";
				}else{
					$passenger = $input_passenger;
				}
			}			
		}
		
		
		// Check input errors before inserting in database
		if(empty($driver_err) && empty($passenger_err)){
			$start_date = $_POST['start_date'];
			$start_time = $_POST['start_time'];
			$end_date = $_POST['end_date'];
			$end_time = $_POST['end_time'];
			$from_address = $_POST['from_address'];
			$to_address = $_POST['to_address'];
			$highest_bid = $_POST['highest_bid'];
			
			$start = $start_date." ".$start_time;
			$end = $end_date." ".$end_time;
			
			$sql = "INSERT INTO ride(highest_bid, driver, passenger, from_address, to_address, start_time, end_time) VALUES('$highest_bid', '$driver', '$passenger', '$from_address', '$to_address', '$start', '$end')";
			
			$result = pg_query($dbconn, $sql);
			
			if(!$result){
				echo pg_last_error($dbconn);
				} else {
				echo "<h3>Ride Created successfully</h3>"."<br>";
				echo "<h4>Redirecting you back to View Rides page</h4>";
				header("refresh:3;url=admin-rides.php");
			} 
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Create Ride</title>
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
							<h2>Create Ride</h2>
						</div>
						<p>Please fill this form and submit to add ride to the database.</p>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							
							<div class="form-group">
								<label>Start Time</label>
								<input required type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
								<label>Start Date</label>
								<input required type="time" name="start_time" class="form-control" value="<?php echo $start_time; ?>">
							</div>
							<div class="form-group">
								<label>End Time</label>
								<input required type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
								<label>End Date</label>
								<input required type="time" name="end_time" class="form-control" value="<?php echo $end_time; ?>">
							</div>							
							<div class="form-group">
								<label>Origin Address</label>
								<input required type="text" name="from_address" class="form-control" value="<?php echo $from_address; ?>">
							</div>
							<div class="form-group">
								<label>Destination Address</label>
								<input required type="text" name="to_address" class="form-control" value="<?php echo $to_address; ?>">
							</div>							
							<div class="form-group">
								<label>Highest Bid Amount ($)</label>
								<input type="number" placeholder= "Default: 0" name="highest_bid" min="0" class="form-control" value="<?php echo $highest_bid; ?>" step="0.01">
							</div>
							<div class="form-group <?php echo (!empty($driver_err)) ? 'has-error' : ''; ?>">
								<label>Driver</label>
								<input required type="text" name="driver" class="form-control" value="<?php echo $driver; ?>">
								<span class="help-block"><?php echo $driver_err;?></span>
							</div>
							<div class="form-group <?php echo (!empty($passenger_err)) ? 'has-error' : ''; ?>">
								<label>Passenger</label>
								<input required type="text" name="passenger" class="form-control" value="<?php echo $passenger; ?>">
								<span class="help-block"><?php echo $passenger_err;?></span>
							</div>							
							<input type="submit" class="btn btn-primary" value="Submit">
							<input type="reset" class="btn btn-warning" value="Reset">
							<a href="admin-rides.php" class="btn btn-default">Go Back To Rides Page</a>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>