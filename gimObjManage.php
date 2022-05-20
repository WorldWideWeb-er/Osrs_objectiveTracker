<?php
	include 'gimDatabaseConnect.php';
	session_start();														//Allows  access to the sessions on this page
	$username = $_SESSION['username'];		
	if(isset($_SESSION['validUser'])&& $_SESSION['validUser']){				//Valid user is set AND is True = we get to see the page
		//Allow access
		//echo(" Valid session for:" . $_SESSION['username']);
	}else{																	//Invalid Login
		//Do not allow access
		header("Location: userSearch.php");				//redirect to login
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
</head>

<body>
	<div class="container">
		<div class="jumbotron m-0">
			<h1><a href="gimSearch.php"> Group Ironman Progress Tracker</a></h1>
			<div class="">Welcome, <?php echo($_SESSION['username']);?></div>
		</div>
		<nav class="navbar navbar-expand-sm bg-info navbar-light">
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
		
		<div class="container">
			<div class="container d-flex justify-content-center">
				<div class="container text-center">
					<div class="col-12">
							<h2>Admin Options</h2>
						</div>
					<div class="row d-flex align-items-center justify-content-center">
						<div class="col-8">
							<h3>Manage Objectives</h3>
						</div>
						<div class="col-4">
							<button type="button" class="btn btn-success">
								<a href="gimInputObj.php" style="text-decoration: none; color: white;" > Add Objective</a></button>
						</div>
					</div>
					<div class="container" id="desktopTable">	
						<table class="table table-dark table-striped">
							<?php
								$user = $_SESSION['username'];
							?>
								<thead>
									<tr>
										<th>Objective Name</th>
										<th>Objective Type</th>
										<th>Date Created</th>
										<th>Objective Complete</th>
										<th>Edit Options</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$username = $_SESSION['username'];	
									try{
										$sql = "SELECT gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

										$stmt = $conn->prepare($sql);
										$stmt->bindParam(':username',$username);
										$stmt->execute();

										$objectiveCount = $stmt->fetchAll(PDO::FETCH_ASSOC);			//seeing if there is a matching pair within the array
										$numofObj = count($objectiveCount);								//counts how many results there are with the matching results
										//echo ("Numbers of objectives found: " . $numofObj);				//Display results

										if ($numofObj > 0){


										$sql = "SELECT gim_obj_id,gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

										$stmt = $conn->prepare($sql);
										$stmt->bindParam(':username',$username);
										$stmt->execute();
											foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $objectiveRow) {
													echo '<tr>';
													echo '<td>',$objectiveRow["gim_obj_name"],'</td>';
													echo '<td>',$objectiveRow["gim_obj_type"],'</td>';
													echo '<td>',$objectiveRow["gim_obj_date"],'</td>';
													echo'<td> 
															<div class="progress">
																<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:',$objectiveRow["gim_obj_complete"],'"></div> 
															</div>	
														</td>';
													echo '<td><a class="nav-link" href="gimUpdateObj.php?gimObj=' . $objectiveRow["gim_obj_id"] . '">Update</a></td>';
													//Deleting Objective
													echo '<td><a class="nav-link" data-toggle="modal" data-target="#deleteModal'.$objectiveRow["gim_obj_id"].'" href=""> Delete </a></td>';
													echo '<div class="modal fade" id="deleteModal'.$objectiveRow["gim_obj_id"].'">
															<div class="modal-dialog modal-dialog-centered">
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<div class="container d-flex justify-content-center">
																			<form method="post" action="">
																				<div class="form-group">
																					<h5>Delete Objective?</h5>
																					<div class="row">
																						<div class="col-6">
																							<button type="button" class="btn btn-link" value="deleteYes" > 
																								<a class="nav-link" href="gimDeleteObj.php?gimObj=' . $objectiveRow["gim_obj_id"] . '">Yes</a>
																							</button>
																						</div>
																						<div class="col-6">
																							<button type="button" class="btn btn-link" data-dismiss="modal" value="deleteNo"><a class="nav-link" href="">No</a></button>
																						</div>
																					</div>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
														</div>';

													echo ('</tr>');
												}									
										}else{
										echo('<div class="text-center" style="color: red"><h5>Currently, you have no objectives</h5></div>');
										}
									}catch(PDOException $e){
										echo "Errors " . $e->getMessage();
									}
								?>

							</tbody>
						</table>
					</div>
					<div class="container" id="mobileTable">	
						<div id="accordion">
							<?php
								$username = $_SESSION['username'];	
								try{
									$sql = "SELECT gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':username',$username);
									$stmt->execute();

									$objectiveCount = $stmt->fetchAll(PDO::FETCH_ASSOC);			//seeing if there is a matching pair within the array
									$numofObj = count($objectiveCount);								//counts how many results there are with the matching results
									//echo ("Numbers of objectives found: " . $numofObj);				//Display results

									if ($numofObj > 0){


									$sql = "SELECT gim_obj_id,gim_username,gim_obj_name,gim_obj_type,gim_obj_date,gim_obj_complete FROM gim_user_objectives WHERE gim_username=:username ";

									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':username',$username);
									$stmt->execute();
										foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $objectiveRow) {
										echo '<div class="card-header">
												<a class="card-link" data-toggle="collapse" href="#collapse',$objectiveRow['gim_obj_id'],'">
													',$objectiveRow["gim_obj_name"],'
												</a>
											</div>
											<div id="collapse',$objectiveRow["gim_obj_id"],'" class="collapse" data-parent="#accordion">
												<div class="card-body">
													<h5>Objective Type:</h5>
													<br>
													<p>',$objectiveRow["gim_obj_type"],'</p>
													<h5>Objective Date Created:</h5>
													<br>
													<p>',$objectiveRow["gim_obj_date"],'</p>
													<h5>Completion Progress:</h5>
													<br>
												<div class="progress">
													<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:',$objectiveRow["gim_obj_complete"],'"></div> 
												</div>
												<h5> Options </h5>
												<a class="nav-link" href="gimUpdateObj.php?gimObj=' . $objectiveRow["gim_obj_id"] . '">Update</a>
												<a class="nav-link" data-toggle="modal" data-target="#deleteMobileModal'.$objectiveRow["gim_obj_id"].'" href="#"> Delete </a>

													<div class="modal fade" id="deleteMobileModal'.$objectiveRow["gim_obj_id"].'" role="dialog">
														<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																<div class="modal-body">
																	<div class="container d-flex justify-content-center">
																		<form method="post" action="">
																			<div class="form-group">
																			<h5>Delete Objective?</h5>
																				<div class="row">
																					<div class="col-6">
																						<button type="button" class="btn btn-link" value="deleteYes" > 
																							<a class="nav-link" href="gimDeleteObj.php?gimObj=' . $objectiveRow["gim_obj_id"] . '">Yes</a>
																						</button>
																					</div>
																					<div class="col-6">
																						<button type="button" class="btn btn-link" data-dismiss="modal" value="deleteNo"><a class="nav-link" href="">No</a></button>
																					</div>
																				</div>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>';
										}
									}else{
									echo('<div class="text-center" style="color: red"><h5>Currently, you have no objectives</h5></div>');
									}
								}catch(PDOException $e){
									echo "Errors " . $e->getMessage();
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</body>
</html>
