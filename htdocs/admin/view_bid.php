<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	$passenger = $ride_id = null;
    if (!empty($_GET['passenger'])) {
        $passenger = $_REQUEST['passenger'];
	}
    if (!empty($_GET['ride_id'])) {
        $ride_id = $_REQUEST['ride_id'];
    }
	//Check existence of username parameter before processing further
	if($passenger == null || $ride_id == null){
        echo "Parameter was not received on this page";
        exit;
	}
    // Prepare a select statement
    $sql = /** @php text */
        "SELECT r.ride_id, r.highest_bid, r.driver, r.from_address, r.to_address, r.start_time, b.amount, b.passenger
        FROM  ride r, bid b WHERE b.ride_id = r.ride_id AND b.passenger LIKE '$passenger' AND r.ride_id = '$ride_id'";

    // Attempt to execute the prepared statement
    $result = pg_query($dbconn, $sql);
    if (!$result) {
        echo pg_last_error($dbconn);
        exit;
    }
    $row = pg_fetch_row($result, null,PGSQL_ASSOC);
    $ride_id = $row['ride_id'];
    $highest_bid = $row['highest_bid'];
    $driver = $row['driver'];
    $from_address = $row['from_address'];
    $to_address = $row['to_address'];
    $start_time = $row['start_time'];
    $amount = $row['amount'];
    $passenger = $row['passenger'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>View Bid</title>
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
							<h1>View Bid</h1>
						</div>
						<div class="form-group">
							<label>Ride ID</label>
							<p class="form-control-static"><?php echo $ride_id; ?></p>
						</div>
						<div class="form-group">
							<label>Driver</label>
							<p class="form-control-static"><?php echo $driver; ?></p>
						</div>
						<div class="form-group">
							<label>From Address</label>
							<p class="form-control-static"><?php echo $from_address; ?></p>
						</div>
						<div class="form-group">
							<label>Destination Address</label>
							<p class="form-control-static"><?php echo $to_address; ?></p>
						</div>
						<div class="form-group">
							<label>Start Time</label>
							<p class="form-control-static"><?php echo $start_time; ?></p>
						</div>
                        <div class="form-group">
                            <label>Highest Bid</label>
                            <p class="form-control-static"><?php echo $highest_bid; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Your Bid</label>
                            <p class="form-control-static"><?php echo $amount; ?></p>
                        </div>
						<p><a href="admin-bids.php" class="btn btn-primary">Back</a></p>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>	