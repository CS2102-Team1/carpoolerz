<?php
session_start();

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$is_admin = $_SESSION['is_admin'];

$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
or die('Could not connect: ' . pg_last_error());

$query = /** @lang text */
"SELECT * FROM systemuser WHERE '$username' = username AND '$password' = password AND is_admin = TRUE";

$result = pg_query($dbconn, $query);

if (pg_num_rows($result) == 0) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header.shtml'; ?>
    <?php include 'admin-navbar.shtml'; ?>
</head>

<body>
    <div class=container>
        <h1>Cars</h1>
    </div>
    <div class=container>
        <br>
        <!-- Display all cars information -->
        <h3>All Cars</h3>
        <table class="table table-striped table-hover custom-table">
            <thead class="thead-inverse">
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Number plate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = 'SELECT * FROM car';
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
    </div>
</body>

</html>

