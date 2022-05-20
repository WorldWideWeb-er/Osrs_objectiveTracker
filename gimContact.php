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
	
	<style>
		#contactForm div:nth-child(3){
			display: none;	
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
	
	
<body>
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
		<a class="navbar-brand"></a>
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
					<a class="nav-link" href="gimObjManage.php" > Manage Objectives</a> 
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
				<div class="modal fade" id="signOnModal">
						<div class="modal-dialog modal-dialog-centered" >
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Sign In</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body" >
									<div class="container d-flex justify-content-center">
										<form method="post" action="gimContact.php">
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
		<div class="container-fluid">
			<div class="container d-flex justify-content">
				<div class="container text-center">
					<div class="row d-flex align-items-center justify-content-center">
						<div class="col-12">
							<h3>Contact Us</h3>
						</div>
						<form id="contactForm" name="contactForm" method="post" action="gimContactHandler.php" >
							<div class="form-group">
								<label for="clientName">Your Name </label>
								<input type="text" class="form-control" name="clientName" id="clientName">
							</div>
							<div class="form-group">
								<label for="emailAddress">Email Address:</label>
								<input type="email" class="form-control" name="userEmail" id="userEmail">
							</div>
							<div class="form-group">
								<label for="contact_phone_number" class="mr-sm-3">Phone Number:</label>
								<input type="email" class="form-control" name="contactPhone" id="contactPhone">
							</div>
							<div class="form-group">
								<label for="whyContact">Reason of Contact </label>
								<select class="form-control" name="whyContact" id="whyContact">
									<option value="No Reason Selected">Please Select an Option</option>
									<option value="Bug Report">Bug Report</option>
									<option value="General Questions">General Question</option>
									<option value="Contact Developers">Contact with Developers</option>
								</select>
							</div>
							<div class="form-group">
								 <label for="comment">Comment:</label>
  								<textarea class="form-control" rows="5" id="comment" name="userComments"></textarea>
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-primary" value="Send" name="submit">
								<input type="reset" class="btn btn-primary" value="Reset Fields">
							</div>
						</form>
					</div>
			</div>
		</div>
	</div>
</body>
</html>