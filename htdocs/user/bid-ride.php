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
            echo "<h1>$start<h1/>";
            echo "<h1>$end<h1/>";

            // Get relevant data from rides table
            // TODO: Fix this weird bug. Why isn't this select statement working
            $ride_matches_query = /** @php text */
                    "SELECT * FROM ride WHERE from_address LIKE '%'$start'%' AND to_address LIKE '%'$end'%' AND end_time IS NULL";

            $ride_matches_result = pg_query($dbconn, $ride_matches_query);

            while ($line = pg_fetch_array($ride_matches_result, NULL, PGSQL_ASSOC)) {
                echo "$line[0]";
            }

        }

    ?>

    <?php include '../footer.shtml'; ?>
</body>
