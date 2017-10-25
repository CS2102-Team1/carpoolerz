<?php
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ../login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../header.shtml'; ?>
    <link href="../main.css" rel="stylesheet" />
</head>

<body>
    <?php include 'navbar-user.shtml'; ?>

    <div class="container">
        <div class="container-fluid">
            <br/>
            <form class="row" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="search-form">
                <div class="form-group col-6">
                    <label for="p_start">Starting Address: </label>
                    <input type="text" name="p_start" class="form-control" id="s_start_address" placeholder="Enter Starting Address"/>
                </div>

                <div class="form-group col-6">
                    <label for="p_end">Car Number Plate: </label>
                    <input type="text" name="p_end" class="form-control" id="s_end_address" placeholder="Enter Destination Address"/>
                </div>
                <button type="submit" name="searchRidesTrigger" class="form-control btn btn-primary">SEARCH RIDES</button>
                <br/>
            </form>
        </div>
    </div>

    <?php

        if (isset($_POST['searchRidesTrigger'])) {

            $start = $_POST['p_start'];
            $end = $_POST['p_end'];

            $start_query = pg_escape_string($start);
            $end_query = pg_escape_string($end);

            // Get relevant data from rides table
            $ride_matches_query = /** @php text */
                    "SELECT * FROM ride WHERE from_address LIKE '%{$start_query}%' AND to_address LIKE '%{$end_query}%' AND end_time IS NULL";

            $ride_matches_result = pg_query($dbconn, $ride_matches_query);

            echo "<br/><h1 class='text-center'>Matches:</h1>";
            while ($row = pg_fetch_array($ride_matches_result, NULL, PGSQL_ASSOC)) {
                $rideID = $row["ride_id"];
                $highest_bid = $row["highest_bid"];
                $driver = $row["driver"];
                $from_address = $row["from_address"];
                $to_address = $row["to_address"];

                // Note: Start time must be processed when echoing.
                $start_time = $row["start_time"];

                echo /** @html text */
                "
                <div class='container'>
                    <div class='container-fluid'>
                        <div class=\"card\">
                            <div class=\"card-header\">
                                <h1>Driver: $driver</h1>
                            </div>
                            <div class=\"card-body\">
                                <p class='\card-subtitle\'>Ride ID: $rideID</p>
                                <h4 class=\"card-text\">Start Time: $start_time</h4>
                                <p class=\"card-text\">From Address: $from_address</p>
                                <p class=\"card-text\">Destination: $to_address</p>
                                <h3 class='\card-text\'>Highest Bid: SGD $highest_bid</h3>
                                <br/>
                                <h5>Make new bid:</h5>
                                <form role=\"form\" action=\"bid-ride.php\" method=\"post\" name=\"$rideID\">
                                    <div class=\"form-group\">
                                        <input type=\"text\" name=\"p_newBid\" class=\"form-control\" id=\"newBid_$rideID\" placeholder=\"$highest_bid\"/>
                                    </div>
                                    
                                    <button type=\"submit\" name=\"submitNewBid\" class=\"form-control btn btn-danger\">SUBMIT NEW BID</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                ";
            }
            echo "<br/>";
        }
    ?>

    <?php include '../footer.shtml'; ?>
</body>
