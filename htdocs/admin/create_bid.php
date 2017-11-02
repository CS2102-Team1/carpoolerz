<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Define variables and initialize with empty values
	$username = $ride_id = $bid_amount = $is_admin = "";
	$username_err = $ride_id_err = $amount_err = "";
	$highest_bid = '0';

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST") {
        $input_ride_id = trim($_POST["ride_id"]);
        $input_bid_amount = trim($_POST["bid_amount"]);
        $input_username = trim($_POST["username"]);

        // Validate username
        $check_bid_exists_query = /**@php text */
            "SELECT *
            FROM bid
            WHERE ride_id = '$input_ride_id' 
            AND passenger = '$input_username'";

        $check_bid_exists_result = pg_query($dbconn, $check_bid_exists_query);
        if (empty($input_username)) {
            $username_err = "Please enter a username.";
        } elseif (pg_num_rows($check_bid_exists_result) != 0) {
            $username_err = "Bid already exists. Use update bid page instead";
        } else {
            $username = $input_username;
        }

        // Validate ride id: Assumption that input types are correct, e.g. numeric is numeric not alphabets
        $check_ride_id_query = /**@php text */
            "SELECT ride_id, highest_bid
            FROM ride WHERE ride_id = '$input_ride_id'";
        $check_ride_id_result = pg_query($dbconn, $check_ride_id_query);
        $check_ride_id_array = pg_fetch_row($check_ride_id_result, null, PGSQL_BOTH);
        if (empty($input_ride_id)) {
            $ride_id_err = "Please enter a ride ID.";
        } elseif (pg_num_rows($check_ride_id_result) == 0) {
            $ride_id_err = "Ride ID does not exist";
        } else {
            $highest_bid = $check_ride_id_array['highest_bid'];
            $ride_id = $input_ride_id;
        }

        // Validate bid amount
        if (empty($input_bid_amount)) {
            $amount_err = "Please enter a bid amount.";
        } elseif ($highest_bid >= $input_bid_amount) {
            $amount_err = "Please enter a higher bid amount";
        } else {
            $bid_amount = $input_bid_amount;
        }
		
		// Check input errors before inserting in database
		if(empty($username_err) && empty($ride_id_err) && empty($amount_err)){
            // Check if bid has already been placed for user
            $already_placed_bid_query = /** @php text */
                    "SELECT r.ride_id, r.highest_bid, b.amount, b.passenger 
                    FROM ride r, bid b
                    WHERE r.ride_id = b.ride_id
                    AND r.ride_id = '$input_ride_id' AND r.passenger = '$input_username'";
            $result = pg_query($dbconn, $already_placed_bid_query);

            if (pg_num_rows($result) == 0) {
                $insert_bid_query = /** @php text */
                        "INSERT INTO bid(amount, ride_id, passenger) 
                        VALUES ('$input_bid_amount', '$input_ride_id', '$input_username')";

                pg_query($insert_bid_query);

                $update_highest_bid_query = /**@php text*/
                        "UPDATE ride SET highest_bid = '$input_bid_amount', passenger = '$input_username'
                        WHERE ride_id = '$ride_id'";

                pg_query($update_highest_bid_query);

                echo "<h3 class='text-center'>Bid Created successfully</h3>" . "<br>";
                echo "<h4 class='text-center'>Redirecting you back to Bids page</h4>";
                header("refresh:4;url=admin-bids.php");
            }
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Create User</title>
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
							<h2>Create Bid</h2>
						</div>
						<p>Please fill this form and submit to add a bid for a user into the database.</p>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
								<label>Username</label>
								<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
								<span class="help-block"><?php echo $username_err;?></span>
							</div>
							<div class="form-group <?php echo (!empty($ride_id_err)) ? 'has-error' : ''; ?>">
								<label>Ride ID</label>
								<input type="text" name="ride_id" class="form-control" value="<?php echo $ride_id; ?>">
								<span class="help-block"><?php echo $ride_id_err;?></span>
							</div>
                            <div class="form-group <?php echo (!empty($amount_err)) ? 'has-error' : ''; ?>">
                                <label>Bid Amount</label>
                                <input type="text" name="bid_amount" class="form-control" value="<?php echo $bid_amount; ?>">
                                <span class="help-block"><?php echo $amount_err;?></span>
                            </div>
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