<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=carpoolerz user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	
	// Process delete operation after confirmation
	if(isset($_POST["username"]) && !empty($_POST["username"])){
		$username = trim($_POST["username"]);
		// Prepare a delete statement
		$sql = "DELETE FROM systemuser s WHERE s.username = '$username'";
		// Attempt to execute the prepared statement
		$result = pg_query($dbconn, $sql);
		if(!$result){
			echo pg_last_error($dbconn);
			} else {
			echo "<h3 class='text-center'>User Deleted successfully</h3>"."<br>";
			echo "<h4 class='text-center'>Redirecting you back to View Users page</h4>";
			header("refresh:3;url=admin-users.php");
		} 
		} else{
		// Check existence of username parameter
		if(empty(trim($_GET["username"]))){
			// URL doesn't contain username parameter.
			echo "Parameter was not passed to this page.";
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Delete User</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<style type="text/css">
			.wrapper{
            width: 500px;
            margin: 0 auto;
			}
		</style>
	</head>
	<body>
		<div class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="page-header">
							<h1>Delete User</h1>
						</div>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="alert alert-danger fade in">
								<input type="hidden" name="username" value="<?php echo trim($_GET["username"]); ?>"/>
								<p>Are you sure you want to delete this user?</p><br>
								<p>
									<input type="submit" value="Yes" class="btn btn-danger">
									<a href="admin-users.php" class="btn btn-default">No</a>
								</p>
							</div>
						</form>
					</div>
				</div>        
			</div>
		</div>
	</body>
</html>