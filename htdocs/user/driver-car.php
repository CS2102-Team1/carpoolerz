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
        "SELECT c.numplate, c.model, c.brand, o.driver FROM car c, owns_car o, systemuser u WHERE o.driver = u.username AND u.username = '$username' AND c.numplate = o.numplate";

    $carDetailsResult = pg_query($dbconn, $getCarDetailsQuery);

    $numplate = '';
    $model = '';
    $brand = '';
    $car_created = false;

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

            if ($car_created) {
                if ($numplate_updated == $numplate) {
                    // Number plate is same --> Just update car details
                    $update_car_query = /** @php text */
                        "UPDATE car SET model = '$model_updated', brand = '$brand_updated' WHERE numplate = '$numplate'";
                    pg_query($dbconn, $update_car_query);
                } else {
                    // Number plate change. Must reflect on owns_car relation as well as car entity
                    // New entity + relation creation
                    $new_car_entity_query = /** @php text */
                        "INSERT INTO car(numplate, model, brand) VALUES('$numplate_updated', '$model_updated', '$brand_updated')";
                    $create_new_car_result = pg_query($dbconn, $new_car_entity_query);

                    $new_owns_car_relation_query = /** @php text */
                        "INSERT INTO owns_car(driver, numplate) VALUES('$username', '$numplate_updated')";
                    $create_new_car_relation_result = pg_query($dbconn, $new_owns_car_relation_query);

                    // Deletion of old entities and relations from cars and owns_car
                    $delete_old_car_query = /** @php text */
                        "DELETE FROM car WHERE numplate = '$numplate'";
                    $delete_old_car_result = pg_query($dbconn, $delete_old_car_query);

                    $delete_old_car_relation_query = /** @php text */
                        "DELETE FROM owns_car WHERE numplate = '$numplate' AND driver = '$username'";
                    $delete_old_car_relation_result = pg_query($dbconn, $delete_old_car_relation_query);
                }
            } else {
                // Create new car entity and new owns_car relation
                $create_new_car_query = /** @php text */
                    "INSERT INTO car(numplate, model, brand) VALUES('$numplate_updated', '$model_updated', '$brand_updated')";
                $create_new_car_result = pg_query($dbconn, $create_new_car_query);

                $create_new_car_relation_query = /** @php text */
                    "INSERT INTO owns_car(driver, numplate) VALUES('$username', '$numplate_updated')";
                $create_new_car_relation_result = pg_query($dbconn, $create_new_car_relation_query);
            }

            echo "<br/><h1 class='text-center'>Car details updated successfully...<h1/>";
            echo "<br/><h2 class='text-center'>Updating page now. Please wait...</h2>";

            //Refresh page
            header("Refresh:0");
        }

        if (isset($_POST['deleteCarTrigger'])) {
            // Delete entity --> cascades to relation so deletion for relation owns_car is not required
            $delete_if_no_numplate_query = /** @php text */
                "DELETE FROM car WHERE numplate = '$numplate'";
            $delete_if_no_numplate = pg_query($dbconn, $delete_if_no_numplate_query);

            echo "<br/><h1 class='text-center'>Car Details have been deleted successfully...<h1/>";
            echo "<br/><h2 class='text-center'>Updating page now. Please wait...</h2>";

            //Refresh page
            header("Refresh:0");
        }
    ?>
</html>