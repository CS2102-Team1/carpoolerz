<?php
    session_start();

    // if(isset($_SESSION['username'])!="") {
    //     if ($_SESSION['username'] == 'admin@admin.com'
    //         && $SESSION['password'] != "") {
    //         header("Location: admin.php");
    //     } else {
    //         header("Location: users.php");
    //     }
    // }

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    if(isset($_POST['loginbutton']) != "") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // echo "$username";
        // echo "$password";

        $query = "SELECT * FROM systemuser WHERE username = '$username' AND password = '$password'";

        $result = pg_query($dbconn, $query);

        // while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        //     echo "<p>In LIne loop</p>";
        //     echo "\t<tr>\n";
        //     foreach ($line as $col_value) {
        //         echo "\t\t<td>$col_value</td>\n";
        //     }
        //     echo "\t</tr>\n";
        // }

        if (pg_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;

            ob_start();
            if ($_SESSION['username'] == 'admin@admin.com') {
                header("Location: admin.php");
            } else {
                header("Location: users.php");
            }
            ob_end_flush();
            end();
        } else {
            echo "\n Error. Incorrect Email or Password... \n";
        }

    } else {
        echo "<h2>Not logged in...</h2>";
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Carpoolerz: Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <!-- <link href="./main.css" , rel="stylesheet" /> -->

    </head>

    <body>
        <div class="container">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login-form"/>
                <div class "form-group">
                    <label for="username">Username: </label>
                    <input type="text" name="username" required class="form-control" id="usr" placeholder="Email"/>
                </div>
                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" name="password" required class="form-control" id="pwd" placeholder="Password"/>
                </div>
                <button type="submit" name="loginbutton" required class="form-control btn btn-primary">Submit</button>
                <br />
            </form>
        </div>
    </body>

</html>
