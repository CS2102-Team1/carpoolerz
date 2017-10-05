<?php
	session_start();

    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
                or die('Could not connect: ' . pg_last_error());

    $query = "SELECT * FROM systemuser WHERE '$username' = 'admin@admin.com' AND '$password' = 'password'";

    $result = pg_query($dbconn, $query);

    if (pg_num_rows($result) == 0) {

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        if (pg_num_rows($result) == 0) {
            header("Location: login.php");
        }
    }

	$targetusername = $_SESSION['targetusername'];

	if (isset($_POST['new'])) {
		echo "Writing data to database";
		echo "<p>username = '$username'</p>";
		echo "<p>password = '$password'</p>";
		echo "<p>targetusername = '$targetusername'</p>";
		// echo "<p>row[username] = '$_SESSION['targetpassword']'</p>";
		// echo "<p>post[password] = '$_SESSION['targetpassword']'</p>";

		// $updatequery = "UPDATE systemuser SET password = '$_POST[password-updated]',
		// fullname = '$_POST[fullname-updated]', licensenum = '$_POST[licensenum-updated]',
    	// numplate = '$_POST[numplate_updated]' WHERE username = '$targetusername'";
		//
		// $updatedResult = pg_query($dbconn, $updatequery);
		//
		// if (!$updatedResult) {
		// 	echo "<h2>Update Failed!</h2>";
		// 	die("Error in query: " . pg_last_error());
		// } else {
		// 	echo "<h2>Update Suceeded!</h2>"
		// }

	}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Carpoolerz</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<link href="main.css" , rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="#">Carpoolerz</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.php">Logout</a>
				</li>
			</ul>
		</div>
	</nav>

		<div id = "accordion" role="tablist">
			<div class = "card">
				<div class = "card-header" role = "tab" id = "headingOne">
					<h5 class="mb-0">
						<a data-toggle = "collapse" href = "#collapseOne" aria-expanded="false" aria-controls="collapseOne">
							View All Hitch Drivers
						</a>
					</h5>
				</div>
			</div>

			<div id = "collapseOne" class = "collapse" role = "tabpanel" aria-labelledby = "headingOne" data-parent = "#accordion">
				<div class="card-body">

				</div>
			</div>

			<div class = "card">
				<div class = "card-header" role = "tab" id = "headingTwo">
					<h5 class="mb-0">
						<a data-toggle = "collapse" href = "#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							View All Users
						</a>
					</h5>
				</div>
			</div>

			<div id = "collapseTwo" class = "collapse" role = "tabpanel" aria-labelledby = "headingOne" data-parent = "#accordion">
				<div class="card-body">
					<div class=container>
						<div class=container>
							<form name="search-userid" action="admin.php" method="POST">
								<div class="form-group">
									<label for="userid-search">Search UserID</label>
									<input type="text" name="userid" required class="form-control" id="usrid" placeholder="UserID/Email" />
								</div>
								<button type="submit" name="search-user-info" required class="form-control btn btn-primary">Submit</button>
								<br />
							</form>
						</div>

						<div class=container>
							<?php
								$result = pg_query($dbconn, "SELECT * FROM systemuser WHERE username = '$_POST[userid]'");
								$row = pg_fetch_assoc($result);
								$_SESSION['targetusername'] = $row[username];
								$_SESSION['targetpassword'] = $_POST['password-updated'];
								if (isset($_POST['search-user-info'])) {
									echo
									"
										<form name='update' action='admin.php' method='POST'>
											<h2 for='userid-update'>Username: '$row[username]' </h2>
											<div class=form-group>
												<label for='password-update'>Password</label>
												<input type='text' required class='form-control' name='password-updated' value='$row[password]' />
											</div>
											<div class=form-group>
												<label for='fullname-update'>Full Name</label>
												<input type='text' required class='form-control' name='fullname-updated' value='$row[fullname]' />
											</div>
											<div class=form-group>
												<label for='licensenum-update'>License Number</label>
												<input type='text' required class='form-control' name='licensenum-updated' value='$row[licensenum]' />
											</div>
											<div class=form-group>
												<label for='numplate-update'>Number Plate</label>
												<input type='text' class='form-control' name='numplate-updated'  value='$row[numplate]' />
											</div>
											<button type='submit' name='new' class='form-control btn btn-danger'>Update</button>
										</form>
									";
								}

								echo "falkefjcwaeljaksce";
							?>
						</div>

						<div class=container>
							<!-- Display all current driver offered rides -->
							<table class="table table-striped table-hover" style="overflow:auto; height: 500px">
								<thead class="thead-inverse">
									<tr>
										<th>Username</th>
										<th>Password</th>
										<th>Full Name</th>
										<th>License Number</th>
										<th>Vehicle Plate Number</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$query = 'SELECT * FROM systemuser';
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

				</div>
			</div>

		</div>

	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>
