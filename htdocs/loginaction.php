<?php
    session_start();

    // if(isset($_SESSION['username'])!="") {
    //     if ($_SESSION['usr_role'] === 1) {
    //       header("Location: admin/");
    //     } else {
    //       header("Location: user/");
    //     }
    // }

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    if(isset($_POST['loginbutton']) != "") {
        $username = $_POST['usrname'];
        $password = $_POST['pswd'];
        // echo <h1>$_POST['pswd']</h1>;

        $query = "SELECT * FROM systemuser s WHERE s.username = $username AND s.password = $password";
        $result = pg_query($query);

        // while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        //     echo "\t<tr>\n";
        //     foreach ($line as $col_value) {
        //         echo "\t\t<td>$col_value</td>\n";
        //     }
        //     echo "\t</tr>\n";
        // }

        if (pg_num_rows($query) == 1) {
            header("Location: ./users.php");
        } else {
            echo "\n Error. Cannot Log In... \n";
        }

    } else {
        echo "aaa";
    }
?>
