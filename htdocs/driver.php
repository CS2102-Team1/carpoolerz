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
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'common/header.shtml'; ?>
</head>

<body>
<?php include 'common/navbar.shtml'; ?>

<div class=container>
    <h1>Hello World Driver page</h1>
</div>

<?php include 'common/footer.shtml'; ?>
</body>

</html>

