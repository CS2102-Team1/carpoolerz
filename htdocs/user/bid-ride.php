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
    header("Location: htdocs/login.php");
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

<div class=container>

    <div class=container>
        <!-- Display all current driver offered rides -->
        <table class="table table-striped table-hover">
            <thead class="thead-inverse">
            <tr>
                <th>Ride ID</th>
                <th>Start Location</th>
                <th>End Location</th>
                <th>Start Time/Date</th>
                <th>End Time/Date</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = 'SELECT * FROM ride';
            $result = pg_query($query);
            while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($line as $col_value) {
                    echo "\t\t<td>$col_value</td>\n";
                }
                echo "\t</tr>\n";
            }
            ?>
            </tbody>
        </table>

        <br/>



    </div>

</div>

<?php include '../footer.shtml'; ?>
</body>
