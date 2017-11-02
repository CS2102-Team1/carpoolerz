<?php
    session_start();
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @php text */
        "SELECT * FROM systemuser s WHERE '$username' = s.username AND '$password' = s.password AND s.is_admin = 'TRUE'";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: /carpoolerz/login.php");
    }

	// Define variables and initialize with empty values
	$passenger = $ride_id = $bid = "";
	$passenger_err = $ride_id_err = $bid_err = "";

    if ($_GET['ride_id']) {
        $ride_id = $_GET['ride_id'];
    } elseif ($_POST['ride_id']) {
        $ride_id = $_POST['ride_id'];
    }

    if ($_GET['passenger']) {
        $passenger = $_GET['passenger'];
    } elseif ($_POST['passenger']) {
        $passenger = $_POST['passenger'];
    }

    // User Information
    $check_user_query = /** @php text */
        "SELECT * FROM systemuser WHERE username = '$passenger'";
    $check_user_result = pg_query($dbconn, $check_user_query);

    // Ride Information
    $check_ride_query = /** @php text */
        "SELECT * FROM ride WHERE ride_id = '$ride_id'";
    $check_ride_result = pg_query($dbconn, $check_ride_query);
    $check_ride_info = pg_fetch_row($check_ride_result, null, PGSQL_BOTH);

	//Check existence of username parameter before processing further
	//Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
        $bid = trim($_POST["bid"]);

        // Validate user exists
        if (empty($passenger)) {
            $passenger_err = "Passenger not specified";
            echo "<h2 class='text-center'>$passenger_err</h2><br/>";
        } elseif (pg_num_rows($check_user_result) == 0){
            $passenger_err = "Passenger not valid";
            echo "<h2 class='text-center'>$passenger_err</h2><br/>";
        }

        // Validate ride id exists
        if (empty($ride_id)) {
            $ride_id_err = "Ride ID not specified";
            echo "<h2 class='text-center'>$ride_id_err</h2><br/>";
        } elseif (pg_num_rows($check_user_result) == 0){
            $ride_id_err = "Ride ID not valid";
            echo "<h2 class='text-center'>$ride_id_err</h2><br/>";
        }

		// Validate bid
        $check_bid_query = /** @php text */
            "SELECT * FROM bid WHERE ride_id = '$ride_id' AND passenger = '$passenger'";
        $check_bid_result = pg_query($dbconn, $check_bid_query);
		if (empty($bid)) {
			$bid_err = "Please enter a new bid";
        } elseif (pg_num_rows($check_bid_result) == 0) {
			$bid_err = 'Bid does not exist. Please go to Create Bids to create one for this rider';
        } elseif ($check_ride_info['highest_bid'] >= $bid) {
		    $bid_err = "New bids must exceed current highest bid";
        }

		// Check input errors before updating in database
		if(empty($ride_id_err) && empty($passenger_err) && empty($bid_err)){
            $edit_bid_query = /** @php text */
                "UPDATE bid SET amount = '$bid' WHERE ride_id = '$ride_id' AND passenger = '$passenger'";
            pg_query($dbconn, $edit_bid_query);

            $update_ride_passenger_query = /** @php text */
                "UPDATE ride SET passenger = '$passenger', highest_bid = '$bid' WHERE ride_id = '$ride_id'";
            pg_query($dbconn, $update_ride_passenger_query);

			if(!$result){
				echo pg_last_error($dbconn);
            } else {
				echo "<h3 class='text-center'>Bid Updated Successfully</h3>"."<br>";
				echo "<h4 class='text-center'>Redirecting you back to View Bids page</h4>";
				header("refresh:4;url=admin-bids.php");
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Update Bid</title>
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
							<h2>Update Bid</h2>
                            <p>Please edit the input values and submit to update this bid.</p>
						</div>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label><b>Passenger:<b/> <?php echo $passenger ?></label>
                            </div>
                            <div class="form-group">
                                <label><b>Ride ID:<b/> <?php echo $ride_id ?></label>
                            </div>
                            <div class="form-group">
                                <label><b>Current Highest Bid:<b/> <?php echo $check_ride_info['highest_bid'] ?></label>
                            </div>
							<div class="form-group <?php echo (!empty($bid_err)) ? 'has-error' : ''; ?>">
								<label>New Bid</label>
								<input type="text" name="bid" class="form-control" value="<?php echo $bid; ?>">
								<span class="help-block"><?php echo $bid_err;?></span>
							</div>
							<input type="hidden" name="passenger" value="<?php echo $passenger; ?>"/>
                            <input type="hidden" name="ride_id" value="<?php echo $ride_id; ?>"/>
							<input type="submit" class="btn btn-primary" value="Submit">
							<input type="reset" class="btn btn-warning" value="Reset">
							<a href="admin-bids.php" class="btn btn-default">Go Back To Bids Page</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>