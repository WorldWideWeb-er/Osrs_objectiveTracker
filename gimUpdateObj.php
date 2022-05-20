<?php
	include 'gimDatabaseConnect.php';
	session_start();														//Allows  access to the sessions on this page
	$username = $_SESSION['username'];		
	if(isset($_SESSION['validUser'])&& $_SESSION['validUser']){				//Valid user is set AND is True = we get to see the page
		//Allow access
	}else{																	//Invalid Login
		//Do not allow access
		header("Location: gimSearch.php");				//redirect to login
	}
	$gimObjId = $_GET['gimObj'];
	if(isset($_POST['submit'])){
		$objName = $_POST['objName'];
		$objType = $_POST['objType'];
		$objComp = $_POST['objComp'];

		try{
			require("gimDatabaseConnect.php");

			$sql = "UPDATE gim_user_objectives SET gim_obj_name=:objName,gim_obj_type=:objType,gim_obj_complete=:objComp WHERE gim_obj_id=:gimObjId";
			$stmt = $conn->prepare($sql);
			//Binding Parameters for those variables
			$stmt->bindParam(':gimObjId',$gimObjId);
			$stmt->bindParam(':objName',$objName);
			$stmt->bindParam(':objType',$objType);
			$stmt->bindParam(':objComp',$objComp);
			//execute
			$stmt->execute();
			
			header("Location: gimObjManage.php");
		}
		catch(PDOException $error){
			$message = "There has been a problem. The system administrator has been contacted. Please try again later.";

			error_log($error->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
			error_log(var_dump(debug_backtrace()));

			//Clean up any variables or connections that have been left hanging by this error.		

			header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
		}


	}else{
		echo "Form NOT Submitted!";
		//get data and display it on the form
		$sql = 'SELECT * FROM gim_user_objectives WHERE gim_obj_id=:gimObjId';
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':gimObjId',$gimObjId);
		$stmt->execute();
		$gimDataResult = $stmt->fetch(PDO::FETCH_ASSOC);

		//check for success
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
					<a class="nav-link" href="gimContact.php">Contact Us</a>
			 	</li>
				<li class="nav-item">
					<a class="nav-link" href="gimObjManage.php" > Manage Objectives</a> 
				 </li>	
				<li class="nav-item">
					<a class="nav-link" href="gimLogout.php"> Sign Out</a>
				 </li>
			</ul>
		  </div>
		</nav>
		<div class="container-fluid">
			<div class="container d-flex justify-content-center">
				<div class="container text-center">
					<div class="col-12">
						<h3>Update Objective</h3>
					</div>
				</div>
			</div>
		</div>
		<form method="post" action="gimUpdateObj.php?eventId=<?php echo $gimObjId?>">
			<div class="form-group">
				<label for="objName">Objective Name </label>
				<input type="text" class="form-control" name="objName" id="objName" value="<?php echo $gimDataResult['gim_obj_name'] ?>">
			</div>
			<div class="form-group">
				<label for="objType">Objective Type </label>
				<select class="form-control" name="objType" id="objType" value="<?php echo $gimDataResult['gim_obj_type'] ?>">
					<option>Quest Completion</option>
					<option>Collect Item</option>
					<option>Increase Level</option>
				</select>
			</div>
			<div class="form-group">
				<label for="objComp">Objective Complete Percentage </label>
				<select class="form-control" name="objComp" id="objComp" value="<?php echo $gimDataResult['gim_obj_complete'] ?>">
					<option value="0%">0%</option>
					<option value="10%">10%</option>
					<option value="20%">20%</option>
					<option value="30%">30%</option>
					<option value="40%">40%</option>
					<option value="50%">50%</option>
					<option value="60%">60%</option>
					<option value="70%">70%</option>
					<option value="80%">80%</option>
					<option value="90%">90%</option>
					<option value="100%">100%</option>
				</select>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Update" name="submit">
				<input type="reset" class="btn btn-primary" value="Reset">
			</div>
		</form>
	</div>
</body>
</html>
<?php
}
?>
