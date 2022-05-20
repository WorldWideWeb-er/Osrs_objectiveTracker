<?php
	include 'gimDatabaseConnect.php';
	session_start();					//Allows  access to the sessions on this page

	if(isset($_SESSION['validUser'])){				//Valid user is set AND is True = we get to see the page
		//Valid user and Logged in
		$validUser = true;	
		$_SESSION['loginAttempt']=false;
	}else{											//User hasnt Logged in yet	
		
		//Havent Logged in yet
		$validUser = false;	
		$_SESSION['loginAttempt']=false;	
	}

	$errMessage = "";					//setting the error Message to nothing
	if(isset($_POST['login'])){		//Is looking for the name of the Submit button
		$loginName = $_POST['loginName'];
		$loginPassword = $_POST['loginPassword'];
		try{
			$sql = 'SELECT gim_username, gim_password FROM gim_user WHERE gim_username=:userName AND gim_password=:userPassword';
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':userName',$loginName);
			$stmt->bindParam(':userPassword',$loginPassword);
			$stmt->execute();

			//Checking if we have a valid user
			$resultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);			//seeing if there is a matching pair within the array
			$numRows = count($resultArray);								//counts how many results there are with the matching results
			
			if ($numRows == 1){
				//This is a valid user
				$validUser= true;						//we have a valid user
				$_SESSION['validUser'] = true;			//Set a session variable signaling we are a valid user
				$_SESSION['username'] = $loginName;		//Setting a session variable for the username
			}else{
				//This is an invalid user
				$_SESSION['loginAttempt'] = true;	
				$errMessage = "Invalid username or Password. Try Again!";
			}
		}
		catch(PDOException $error){
			$message = "There has been a problem. The system administrator has been contacted. Please try again later.";

			error_log($error->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
			error_log(var_dump(debug_backtrace()));

			//Clean up any variables or connections that have been left hanging by this error.		

			//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page		
			echo $error->getMessage();
		}
	}else{

	}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>OsRs Group Ironman Objective Tracker</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<script>
		//testing for mobile 
	if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
		  // true for mobile device
		let deviceType = 'Mobile';
		}else{
		  // false for not mobile device
		let deviceType = 'Desktop';
		};
	
	</script>
	
	<style>
		@media only screen and (max-width: 600px){
			#desktopTable{
				display: none;
			}
		}
		@media only screen and (min-width:600px){
			#mobileTable{
				display: none;
			}
		}
	
	
	</style>
	
	
	
	
	<?php
	if ($_SESSION['loginAttempt']){
		?>
		<script>
				$(document).ready(function(){$("#signOnModal").modal('show');});
		</script>
	<?php
	}else{
		?>
		<script>
				$(document).ready(function(){$("#signOnModal").modal('hide');});
		</script>
	<?php
		$_SESSION['loginAttempt']=false;
		}
		?>	
<header>
	<div class="container">
		<div class="jumbotron m-0">
			<h1><a href="gimSearch.php"> Group Ironman Progress Tracker</a></h1>
			<?php 
				if ($validUser){
					?>
				<div class="">Welcome, <?php echo($_SESSION['username']);?></div>
			<?php
					
				}else{
					
				}
			?>
		</div>
	
	</div>
	
</header>
<body>
	<div class="container">
<?php
	if ($validUser){
?>
			<nav class="navbar navbar-expand-sm bg-info navbar-light">
<?php
	}else{
?>
			<nav class="navbar navbar-expand-md bg-dark navbar-dark">
<?php
	}
?>
		
		
		
				<!--- Collapse Button --->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse justify-content-end mr-5" id="collapsibleNavbar">
				<ul class="navbar-nav mr-4">
					<li class="nav-item ">
						<a class="nav-link" href="gimSearch.php">Search</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="gimContact.php">Contact Us</a>
					</li>
				<?php
					if($validUser){
						?>
					<li class="nav-item">
						<a class="nav-link" href="gimObjManage.php" >Manage Objectives</a> 
					 </li>	
					<li class="nav-item">
						<a class="nav-link" href="gimLogout.php"> Sign Out</a>
					 </li>
				<?php
					}else{
					?>	
					 <li class="nav-item">
						<a class="nav-link" data-toggle="modal" data-target="#signOnModal"> Sign In</a>
					 </li> 
				<?php
					}
					?>
					<div class="modal" id="signOnModal" role="dialog">
							<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">Sign In</h4>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body" >
										<div class="container d-flex justify-content-center">
											<form method="post" action="gimSearch.php">
												<div class="form-group">
													<p style="color: firebrick;"><?php echo $errMessage ?></p>
												</div>
												<div class="form-group">
													<label for="loginName">Username:</label>
													<input type="text" name="loginName" id="loginName">
												</div>
												<div class="form-group">
													<label for="loginPassword">Password:</label>
													<input type="Password" name="loginPassword" id="loginPassword">
												</div>

												<div class="d-flex justify-content-around">
													<button type="submit" class="btn btn-primary" value="Sign In" id="login" name="login" style="text-decoration: none">Submit</button>
													<button type="Reset" class="btn btn-primary" style="text-decoration: none">Reset</button>
												</div>
												<br>
												<div class="d-flex justify-content-center">	
													<button type="button" class="btn btn-primary" value="Create Account" name="create" id="create" style="text-decoration: none">
														<a href="gimNewUser.php" style="text-decoration: none; color: white" >Create Account</a> 
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
					</div>
				</ul>
			</div>
		</nav>
	<script>
		if (deviceType="mobile"){
	
			document.write ('<div class="container">')
		}else{}				
	</script>
		<div class="container-fluid">
			<div class="container d-flex justify-content-center">
				<form method="post" action="gimSearch.php">
					<div class="form-group">
						<h3> Search for a user:</h3>
						<div class="row">
							<div class="col-6">
								<input type="text" class="form-control" placeholder="Enter Username" id="username" name="username">
							</div>
							<div class="col-6">
								<button type="submit" class="btn btn-primary" value="submit" name="submit" style="text-decoration: none">Submit</button>
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="container" id="desktopTable">
				<table class="table table-dark table-striped">
					<?php
					if(isset($_POST['submit'])){
						$user = $_POST['username'];
						?>
						<thead>
							<div class="text-center">
								<h3><?php echo($user)?> Objectives</h3>
							</div>
							<tr>
								<th>Username</th>
								<th>Objective Name</th>
								<th>Objective Type</th>
								<th>Date Created</th>
								<th>Objective Complete Percent</th>
							</tr>
						</thead>
						<tbody>
						
							
						
						<?php
							$username = $_POST['username'];	
							
								try{
									$sql = "SELECT gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':username',$username);
									$stmt->execute();
									
									$objectiveCount = $stmt->fetchAll(PDO::FETCH_ASSOC);			//seeing if there is a matching pair within the array
									$numofObj = count($objectiveCount);								//counts how many results there are with the matching results
									
									if ($numofObj > 0){
										$sql = "SELECT gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

										$stmt = $conn->prepare($sql);
										$stmt->bindParam(':username',$username);
										$stmt->execute();
											foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
													echo '<tr>';
													echo '<td>',$row["gim_username"],'</td>';
													echo '<td>',$row["gim_obj_name"],'</td>';
													echo '<td>',$row["gim_obj_type"],'</td>';
													echo '<td>',$row["gim_obj_date"],'</td>';
												    echo'<td> 
													 		<div class="progress">
													 			<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:',$row["gim_obj_complete"],'"></div> 
															</div>	
														</td>';
													echo ('</tr>');
												}									
											}else{
											echo('<div class="text-center" style="color: red"><h5>Either this user has no objectives, or they do not exist. </h5></div>');
										}
									}
								catch(PDOException $e){
									echo "Errors " . $e->getMessage();
								}
						}
							?>

					</tbody>
				</table>
			</div>
			<div class="container" id="mobileTable">
				<div id="accordion">
					<?php
					if(isset($_POST['submit'])){
						$user = $_POST['username'];
						?>
						<div class="text-center">
							<h3><?php echo($user)?> Objectives</h3>
						</div>
						<?php
							$username = $_POST['username'];	
							try{
								$sql = "SELECT gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

								$stmt = $conn->prepare($sql);
								$stmt->bindParam(':username',$username);
								$stmt->execute();

								$objectiveCount = $stmt->fetchAll(PDO::FETCH_ASSOC);			//seeing if there is a matching pair within the array
								$numofObj = count($objectiveCount);								//counts how many results there are with the matching results

								if ($numofObj > 0){
									$sql = "SELECT gim_obj_id,gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':username',$username);
									$stmt->execute();
										foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
												echo '<div class="card">
														<div class="card-header">
															<a class="card-link" data-toggle="collapse" href="#collapse',$row["gim_obj_id"],'">
															',$row["gim_obj_name"],'
															</a>
														</div>
														<div id="collapse',$row["gim_obj_id"],'" class="collapse" data-parent="#accordion">
															<div class="card-body">
																<h5>Objective Type:</h5>
																	<br>
																	<p>',$row["gim_obj_type"],'</p>
																<h5>Objective Date Created:</h5>
																	<br>
																	<p>',$row["gim_obj_date"],'</p>
																<h5>Completion Progress:</h5>
																	<br>
																	<div class="progress">
																<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:',$row["gim_obj_complete"],'"></div> 
															</div>
														  </div>
														</div>
													  </div>';

											}									
								}else{
								echo('<div class="text-center" style="color: red"><h5>Either this user has no objectives, or they do not exist. </h5></div>');
								}
							}
							catch(PDOException $e){
								echo "Errors " . $e->getMessage();
							}
						}
					?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
