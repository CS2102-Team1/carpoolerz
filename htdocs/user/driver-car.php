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
        "SELECT c.numplate, c.model, c.brand, o.driver FROM car c, owns_car o WHERE o.driver = '$username' AND c.numplate = o.numplate";

    $carDetailsResult = pg_query($dbconn, $getCarDetailsQuery);
    $numplate = null;
    $model = null;
    $brand = null;

    if (pg_num_rows($carDetailsResult) > 0) {
        $carInfo = pg_fetch_row($carDetailsResult);
        $numplate = $carInfo[0];
        $model = $carInfo[1];
        $brand = $carInfo[2];
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
                <br/>
                <h1 class="text-center">Your Car Details</h1>
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

            if ($numplate_updated == null || $brand_updated == null || $model_updated == null){
                echo "<br/><h1 class='text-center'>Car details not updated properly...<h1/>";

            } else {
                //user has license number but does not have any car registered
                if ($numplate == null){
                    $insert_car_query = /** @php text */
                    "INSERT INTO car(model, brand, numplate) VALUES ('$model_updated', '$brand_updated', '$numplate_updated')";
                    $insert_car_result = pg_query($dbconn, $insert_car_query);

                    $insert_owns_car_query = /** @php text */
                    "INSERT INTO owns_car(numplate, driver) VALUES ('$numplate_updated', '$username')";
                    $insert_owns_car_result = pg_query($dbconn, $insert_owns_car_query);

                //user already has a car registered
                } else {
                    $delete_car_query = /** @php text */
                    "DELETE FROM car WHERE numplate = '$numplate'";
                    $delete_car_result = pg_query($dbconn, $delete_car_query);

                    $insert_car_query = /** @php text */
                    "INSERT INTO car(model, brand, numplate) VALUES ('$model_updated', '$brand_updated', '$numplate_updated')";
                    $insert_car_result = pg_query($dbconn, $insert_car_query);

                    $insert_owns_car_query = /** @php text */
                    "INSERT INTO owns_car(numplate, driver) VALUES ('$numplate_updated', '$username')";
                    $insert_owns_car_result = pg_query($dbconn, $insert_owns_car_query);
                }

                echo "<br/><h1 class='text-center'>Car details updated successfully...<h1/>";
                echo "<br/><h2 class='text-center'>Updating page now. Please wait...</h2>";

            }
            //Refresh page
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=driver-car.php\">";
        }

        if (isset($_POST['deleteCarTrigger'])) {
            // Delete entity --> cascades to relation so deletion for relation owns_car is not required
            $delete_car_query = /** @php text */
                "DELETE FROM car WHERE numplate = '$numplate'";
            $delete_car_result = pg_query($dbconn, $delete_car_query);

            echo "<br/><h1 class='text-center'>Car Details have been deleted successfully...<h1/>";
            echo "<br/><h2 class='text-center'>Updating page now. Please wait...</h2>";

            //Refresh page
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=driver-car.php\">";
        }
    ?>
</html>