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

	//Logging in 
	$errMessage = "";
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

	//Seeing if account is valid
	$accountErrMessage = "";
	$accountPasswordErrMessage= "";
	if(isset($_POST['createAccount'])){		//Is looking for the name of the Submit button
			$loginName = $_POST['loginName'];
			try{
				$sql = 'SELECT gim_username FROM gim_user WHERE gim_username=:userName';
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':userName',$loginName);
				$stmt->execute();

				//Checking if we have a valid user
				$resultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);			//seeing if there is a matching pair within the array
				$numRows = count($resultArray);								//counts how many results there are with the matching results
				if ($numRows == 1){
					//Name has been taken
					$accountErrMessage = "Username has been taken";
				}else{
					$accountPassword1 = $_POST["loginPassword1"];
					$accountPassword2 = $_POST["loginPassword2"];
					if ($accountPassword1 != $accountPassword2){
						//Passwords Dont Match
						$accountPasswordErrMessage= "Passwords do not match";
					}else{
						//Both Valid Names and Passwords
						include 'gimDatabaseConnect.php';
		
						$userName = $_POST['loginName'];
						$userEmail = $_POST['emailAddress'];
						$userPassword = $_POST['loginPassword1'];
						global $userPermissions;
						$userPermissions = 'default';
						

						try{
							$sql = 'INSERT INTO gim_user (gim_username,gim_password,gim_permissions,gim_email) VALUES (:userName,:userPassword,:userPermissions,:userEmail)';

							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':userName',$userName);
							$stmt->bindParam(':userPassword',$userPassword);
							$stmt->bindParam(':userPermissions',$userPermissions);
							$stmt->bindParam(':userEmail',$userEmail);
							$stmt->execute();
							
							$validUser= true;						//Created User is a valid user
							$_SESSION['validUser'] = true;			//Set a session variable signaling we are a valid user
							$_SESSION['username'] =$_POST['loginName'];
							

						}
						catch(PDOException $error){
							$message = "There has been a problem. The system administrator has been contacted. Please try again later.";

							error_log($error->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
							error_log(var_dump(debug_backtrace()));

							//Clean up any variables or connections that have been left hanging by this error.		

							header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
						}
					}			
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
</head>

<body>
	<div class="container">
		<div class="jumbotron m-0">
			<h1><a href="gimSearch.php"> Group Ironman Progress Tracker</a></h1>
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
		<div class="container-fluid">
			<div class="container d-flex justify-content-center">
				<div class="container text-center">
<?php
				if($validUser){
?>
					<div class="col-12">
						<h3>You have Created an Account!</h3>
					</div>
						<h4>Your account Information is as follows:</h4>
						<h5>Username:<?php echo $_POST['loginName'] ?></h5>
						<h5>Email Address:<?php echo $_POST['emailAddress'] ?></h5>
						<h5>Password:<?php echo $_POST['loginPassword1'] ?></h5>
				</div>
<?php
				}else{
?>
					<div class="col-12">
						<h3>Create an Account</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="container d-flex justify-content-center">
			<form method="post" action="gimNewUser.php">
				<div class="form-group">
					<label for="loginName">Username:</label>
					<input type="text" class="form-control" name="loginName" id="loginName">
				</div>
				<div class="form-group">
					<p style="color: firebrick;"><?php echo $accountErrMessage ?></p>
				</div>
				<div class="form-group">
					<label for="emailAddress">Email Address:</label>
					<input type="email" class="form-control" name="emailAddress" id="emailAddress">
				</div>
				<div class="form-group">
					<label for="loginPassword1">Password:</label>
					<input type="Password" class="form-control" name="loginPassword1" id="loginPassword1">
				</div>
				<div class="form-group">
					<label for="loginPassword2">Re-type Password:</label>
					<input type="Password" class="form-control" name="loginPassword2" id="loginPassword2">
				</div>
				<div class="form-group">
					<p style="color: firebrick;"><?php echo $accountPasswordErrMessage ?></p>
				</div>
				<div class="d-flex justify-content-center">	
					<button type="submit" class="btn btn-primary" value="Create Account" name="createAccount" id="createAccount" style="text-decoration: none">
						Create Account
					</button>
				</div>
			</form>
		</div>
<?php
		}
?>
	</div>
</body>
</html>
