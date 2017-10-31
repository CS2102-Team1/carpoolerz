<?php
	session_start();
	
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	$query = /** @lang text */
	"SELECT * FROM systemuser s WHERE '$username' = s.username AND '$password' = s.password AND s.is_admin = 'TRUE'";
	
	$result = pg_query($dbconn, $query);
	
	if (pg_num_rows($result) == 0) {
		header("Location: /carpoolerz/login.php");
	}
	
	// Define variables and initialize with empty values
	$ride_id = $highest_bid = $driver = $passenger = $from_address = $to_address = $start_time= $start_date = $end_date = $end_time = $start = $end = "";
	$end = null; //to allow for update of ride to an ongoing ride
	$driver_err = $passenger_err = "";
	
	$curr_id = null;
	//extract ride_id for this selected row from the url parameter 'id'
    if (!empty($_GET['id'])) {
		$curr_id = intval($_REQUEST['id']);
	}
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Get hidden input value
		$curr_id = $_POST["this_ride"];
		//Validate driver username
		$input_driver = trim($_POST["driver"]);
		//Nothing was submitted
		if(empty($input_driver)){
			$driver_err = "Please enter a driver username.";
			}else{	//something was submitted
			//check if a driver with this username exists
			$sql = "SELECT * FROM systemuser s WHERE s.username ='$input_driver' AND s.licensenum <> 'NULL'";
			$result = pg_query($dbconn,$sql);
			if(!$result){	//query was unsuccessful
				echo pg_last_error($dbconn);
				}else{	//query was successful
				if (pg_num_rows($result) == 0) {
					$driver_err = "This is not a valid driver";
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
			$start = $start_date." ".$start_time;
			$start = str_replace('-','/',$start);//must submit with '/' instead of '-'
			$from_address = $_POST['from_address'];
			$to_address = $_POST['to_address'];		
			
			//if highest bid is no empty, extract that value. Else, put in 0.
			if(!empty(trim($_POST['highest_bid']))){
				$highest_bid = $_POST['highest_bid'];
				}else{
				$highest_bid = 0;
			}
			//if end date and time are not empty strings, extract values and concatenate into end
			if(!empty(trim($_POST['end_date'])) && !empty(trim($_POST['end_time']))){
				$end_date = $_POST['end_date'];
				$end_time = $_POST['end_time'];
				$end = $end_date." ".$end_time;
				$end = str_replace('-','/',$end);//must submit with '/' instead of '-'
			}
			
			if(!is_null($end)){//end time & date were entered, so input that into database
				$sql = "UPDATE ride SET highest_bid = '$highest_bid', driver='$driver', passenger='$passenger', from_address='$from_address', to_address='$to_address', start_time=to_timestamp('$start', 'YYYY/MM/DD HH24:MI:SS'), end_time=to_timestamp('$end', 'YYYY/DD/MM HH24:MI:SS') WHERE ride_id='$curr_id'";
				}else{//$end remains null, so there wasnt any end time & date entered
				$sql = "UPDATE ride SET highest_bid = '$highest_bid', driver='$driver', passenger='$passenger', from_address='$from_address', to_address='$to_address', start_time=to_timestamp('$start', 'YYYY/MM/DD HH24:MI:SS') WHERE ride_id='$curr_id'";
			}
			
			
			$result = pg_query($dbconn, $sql);
			
			if(!$result){
				echo pg_last_error($dbconn);
				} else {
				echo "<h3>Ride Updated successfully</h3>"."<br>";
				echo "<h4>Redirecting you back to View Rides page</h4>";
				header("refresh:3;url=admin-rides.php");
			} 
		}
	} 
	elseif(null != $curr_id){//there is no form submission, pull existing data from current ride id to view it in the form		
		// Prepare a select statement
		$sql = "SELECT * FROM ride r WHERE r.ride_id = '$curr_id'";
        
        // Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if (!$result) {
			echo pg_last_error($dbconn);
			exit;
		}
		$row = pg_fetch_row($result);
		$ride_id = $row[0];
		$highest_bid = $row[1];
		$driver = $row[2];
		$passenger = $row[3];
		$from_address = $row[4];
		$to_address = $row[5];
		$start = $row[6];
		//split the stored start timestamp back to date and time
		$split_start_time = explode(" ",$start); 
		$start_date = $split_start_time[0];
		$start_time = $split_start_time[1];
		$end = $row[7];
		//if end date IS NOT NULL, split the stored end timestamp back to date and time
		if(!is_null($end)){
			$split_end_time = explode(" ",$end);
			$end_date = $split_end_time[0];
			$end_time = $split_end_time[1];
		}
	} 
	else{//couldnt even detect this ride
		echo "Parameter was not received on this page";
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Update Ride</title>
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
							<h2>Update Ride</h2>
						</div>
						<p>Please fill this form and submit to update ride.</p>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="form-group">
								<label>Start Date</label>
								<input required type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
								<label>Start Time</label>
								<input required type="time" name="start_time" class="form-control" value="<?php echo $start_time; ?>">
							</div>
							<div class="form-group">
								<label>End Date</label>
								<input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
								<label>End Time</label>
								<input type="time" name="end_time" class="form-control" value="<?php echo $end_time; ?>">
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
								<input required type="text" name="driver" class="form-control" value="<?php echo $driver; ?>" readonly>
								<span class="help-block"><?php echo $driver_err;?></span>
							</div>
							<div class="form-group <?php echo (!empty($passenger_err)) ? 'has-error' : ''; ?>">
								<label>Passenger</label>
								<input required type="text" name="passenger" class="form-control" value="<?php echo $passenger; ?>" readonly>
								<span class="help-block"><?php echo $passenger_err;?></span>
							</div>
							<input type="hidden" name="this_ride" value="<?php echo $curr_id; ?>"/>
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