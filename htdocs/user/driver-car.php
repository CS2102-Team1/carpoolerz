<?php
    // Check user login status
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @php text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password' AND licensenum IS NOT NULL";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {
        header("Location: ./driver-car-error.php");
    }

    $getCarDetailsQuery = /** @php text */
        "SELECT * FROM owns_car WHERE driver = '$username'";

    $carResult = pg_query($dbconn, $getCarDetailsQuery);

    $number_plate = '';
    $model = '';
    $make = '';
    $car_created = false;

    if (pg_num_rows($carResult) > 0) {
        $row = pg_fetch_row($carResult);

        $numplate = $row[1];
        $model = $row[2];
        $brand = $row[3];
        $car_created = true;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header.shtml'; ?>
</head>
    <body>
        <?php include 'navbar-user.shtml'; ?>
        <div class=container>
            <div class="container-fluid">
                <h1 class="text-center">DRIVERS: Update Your Car Details</h1>
                <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login-form">
                    <div class="form-group">
                        <label for="p_username">Driver Username: </label>
                        <h1><?php echo $username?></h1>
                    </div>

                    <div class="form-group">
                        <label for="p_numplate">Car Number Plate: </label>
                        <input type="text" name="p_numplate" required class="form-control" id="car_plate" value="<?php echo $numplate?>" placeholder="Enter Car Plate Number"/>
                    </div>

                    <div class="form-group">
                        <label for="p_brand">Car Brand (OPTIONAL): </label>
                        <input type="text" name="p_brand" required class="form-control" id="car_brand" value="<?php echo $brand?>" placeholder="Enter Car Brand"/>
                    </div>

                    <div class="form-group">
                        <label for="p_model">Car Model (OPTIONAL): </label>
                        <input type="text" name="p_model" required class="form-control" id="car_model" value="<?php echo $model?>" placeholder="Enter Car Model"/>
                    </div>

                    <button type="submit" name="updateCarTrigger" class="form-control btn btn-primary">UPDATE CAR DETAILS</button>
                    <br/>
                    <br/>
                    <button type="submit" name="deleteCarTrigger" class="form-control btn btn-danger">DELETE CAR DETAILS</button>
                </form>
            </div>
        </div>
        <?php include '../footer.shtml'; ?>
    </body>
    <?php
        if (isset($_POST['updateCarTrigger'])) {
            $numplate_updated = $_POST['p_numplate'];
            $brand_updated = $_POST['p_brand'];
            $model_updated = $_POST['p_model'];
            $action_query = "";

            if ($car_created) {
                $action_query = /** @php text */
                    "UPDATE owns_car SET numplate = '$numplate_updated', model = '$model_updated', brand = '$brand_updated' WHERE driver = '$username'";
            } else {
                $action_query = /** @php text */
                    "INSERT INTO owns_car (driver, numplate, model, brand) VALUES ('$username', '$numplate_updated', '$model_updated', '$brand_updated')";
                $car_created = true;
            }

            $update_car_result = pg_query($dbconn, $action_query);

            //Delete if number plate is not there
            if ($numplate_updated == '') {
                $delete_if_no_numplate_query = /** @php text */
                    "DELETE FROM owns_car WHERE driver = '$username'";
                $delete_if_no_numplate = pg_query($dbconn, $delete_if_no_numplate_query);
            }
            //Refresh page
            header("Refresh:0");
        }

        if (isset($_POST['deleteCarTrigger'])) {
            $delete_if_no_numplate_query = /** @php text */
                "DELETE FROM owns_car WHERE driver = '$username'";
            $delete_if_no_numplate = pg_query($dbconn, $delete_if_no_numplate_query);

            echo "<br/><h1>Car Details have been deleted successfully. Updating page now. Please wait...<h2/>";

            //Refresh page
            header("Refresh:0");
        }
    ?>
</html>