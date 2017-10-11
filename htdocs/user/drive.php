<?php
    session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

    $query = /** @lang text */
        "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

    $checkHasCar = /** @lang text */
        "SELECT * FROM owns_car WHERE '$username' = username";

    $result = pg_query($dbconn, $query);
    $checkHasCarResult = pg_query($dbconn, $checkHasCarResult);

    if (pg_num_rows($result) == 0 || pg_num_rows($checkHasCarResult) == 0) {
        header("Location: ../login.php");
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
    <h1>Hello World Driver page</h1>
</div>

<?php include '../footer.shtml'; ?>
</body>

</html>

