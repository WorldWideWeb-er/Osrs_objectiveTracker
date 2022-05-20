<?php
	//pass the selected event ida as aGET paramet on the URL
	include 'gimDatabaseConnect.php';
	session_start();
	$deleteId = $_GET["gimObj"];
	
	try{
		$sql = 'DELETE FROM gim_user_objectives WHERE gim_obj_id=:objId';
		$stmt = $conn->prepare($sql);				
		$stmt->bindParam(':objId',$deleteId);		
		$stmt->execute();;
		//Once Done, Redirect to Input page
		header("Location: gimObjManage.php");	
	}
	catch(PDOException $e){
		echo "Errors " . $e->getMessage();
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
			<h1><a href="gimContact.php"> Group Ironman Progress Tracker</a></h1>
			<div class="">Welcome, <?php echo($_SESSION['username']);?></div>
		</div>
		<nav class="navbar navbar-expand-md bg-dark navbar-dark just">
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
					<a class="nav-link" href="#">Contact Us</a>
			 	</li>
				<li class="nav-item">
					<a class="nav-link" href="gimInput.php" > Manage Objectives</a> 
				 </li>	
				<li class="nav-item">
					<a class="nav-link" href="gimLogout.php"> Sign Out</a>
				 </li>
				<div class="modal fade" id="signOnModal">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">Sign In</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body">
								<div class="container d-flex justify-content-center">
									<form method="post" action="gimSearch.php">
										<div class="form-group">
											<label for="loginName">Username:</label>
											<input type="text" name="loginName" id="loginName">
										</div>
										<div class="form-group">
											<label for="loginPassword">Password:</label>
											<input type="Password" name="loginPassword" id="loginPassword">
										</div>
										<div class="d-flex justify-content-around">
											<input type="submit" value="Sign In" id="login" name="login">
											<input type="reset">
											<input type="button" value="Create Account" id="create" name="create">
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
				<div class="row">
					
				
				
				<div class="container text-center">
					<div class="row d-flex align-items-center justify-content-center">
						<div class="col-12">
							<h3>Deleted Objective <?php echo($deleteId);?></h3>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</body>
</html>
