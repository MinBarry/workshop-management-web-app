<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
<script>
function toogleOwnerForm(){
	if($("#selectOwner").val() != "new"){
		$("#newOwnerContainer").hide();
	}else{
		$("#newOwnerContainer").show();
	}
}
</script>
<div class="container">
  <div class="page-header">
    <h1>Workshop</h1>
	<p></p>
  </div>

  
  <div class="row">
	<div class="col-md-2">
      <div class="list-group">
        <a href="index.php" class="list-group-item">Projects</a>
        <a href="tools.php" class="list-group-item">Tools</a>
		<a href="cars.php" class="list-group-item active">Cars</a>
		 
		<a href="Parts.php" class="list-group-item">Parts</a>
        <a href="money.php" class="list-group-item">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li><a href="cars.php">Browse Cars</a></li>
        <li class="active"><a href="addCars.php">Add Cars</a></li>
      </ul>
       <form role="form" method="post">
<?php
//connect to server
$mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
//add new car and owner
if(isset($_POST['car-license'])){
	$addedOwner=0;
	$owneremail=null;
	//case where user selects previous owner
	if($_POST["prevowner-email"]!= "new"){
		$owneremail = $_POST["prevowner-email"];
		$addedOwner=1;
	}else{
		//case for new owner
		if(strlen($_POST["owner-name"])!=0 && strlen($_POST["owner-email"])!=0 && strlen($_POST["owner-street"])!=0
			&& strlen($_POST["owner-city"])!=0 && strlen($_POST["owner-state"])!=0 && strlen($_POST["owner-zip"])!=0){
			if(is_numeric($_POST["owner-phone"])){
				$addedOwner=1;
				if (!($stmt = $mysqli->prepare("INSERT INTO Owners(email ,name, phoneNo, street, city, state, zipCode) VALUES (?,?,?,?,?,?,?)"))) {
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					$addedOwner = 0;
				}
				if (!$stmt->bind_param("ssissss", $_POST["owner-email"], $_POST["owner-name"],$_POST["owner-phone"],$_POST['owner-street'],$_POST['owner-city'],$_POST['owner-state'],$_POST['owner-zip'])) {
					echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
					$addedOwner = 0;
				}
				if (!$stmt->execute()) {
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					$addedOwner = 0;
				}
				$owneremail= $_POST["owner-email"];
			}else{
				echo "*Enter a valid phone number.";
				$addedOwner=0;
			}
		} else {
			$addedOwner = 0;
		}
	}
	if($addedOwner == 1 && strlen($_POST["car-license"])!= 0 && strlen($_POST["car-make"])!= 0 && strlen($_POST["car-model"])!= 0){
		if(is_numeric($_POST["car-year"]) && (intval($_POST["car-year"]) >= 1900) && (intval($_POST["car-year"]) <= 2090 )){
			$success = 1;
		   if (!($stmt = $mysqli->prepare("INSERT INTO Cars(licensePlate, make, model, year, owner) VALUES (?,?,?,?,?)"))) {
			  echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			  $success = 0;
		   }
		   if (!$stmt->bind_param("sssis", $_POST["car-license"], $_POST["car-make"],$_POST["car-model"],$_POST['car-year'],$owneremail)) {
			 echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			 $success = 0;
		   }

		   if (!$stmt->execute()) {
			 echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			 $success = 0;
		   }
		   if($success){
		   echo "<h4> Successfully added owner and car</h4>";
		   }
		   $stmt->close();
		} else{
			echo "*Enter a valid year.";
		}
	}else{
		echo "*You must fill all fields.";
	}
}
?>
		<h3>Car Information</h3>
		<div class="form-group">
		  <label>License Plate: </label>
		  <input class="form-control" type="text" name="car-license" required="required">
		</div>
		<div class="form-group">
		  <label>make: </label>
		  <input class="form-control" type="text" name="car-make">
		</div>
		<div class="form-group">
		  <label>model: </label>
		  <input class="form-control" type="text" name="car-model">
		</div>
		<div class="form-group">
		  <label>year: </label>
		  <input class="form-control" type="text" name="car-year">
		</div>
		<h3>Owner Information</h3>
		<div class="form-group">
		<select class="form-control" onchange="toogleOwnerForm()" id="selectOwner" name="prevowner-email">
		<option value="new">New Owner</option>
	  <?php

		if (!($stmt = $mysqli->prepare("SELECT email, name FROM Owners"))) {
           echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
		if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$email=NULL;
		$name=null;
		if (!$stmt->bind_result($email, $name)) {
			echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		while($stmt->fetch()) {
		  echo "<option value='$email'>$email ($name)</option>";
		}
		$stmt->close();
	  ?>
		</select>
		</div>
		<div id="newOwnerContainer">
			<div class="form-group">
			  <label>name: </label>
			  <input class="form-control" type="text" name="owner-name">
			</div>
			<div class="form-group">
			  <label>email: </label>
			  <input class="form-control" type="text" name="owner-email">
			</div>
			<div class="form-group">
			  <label>Phone: </label>
			  <input class="form-control" type="text" name="owner-phone">
			</div>
			<div class="form-group">
			  <label>street: </label>
			  <input class="form-control" type="text" name="owner-street">
			</div>
			<div class="form-group">
			  <label>city: </label>
			  <input class="form-control" type="text" name="owner-city">
			</div>
			<div class="form-group">
			  <label>state: </label>
			  <input class="form-control" type="text" name="owner-state">
			</div>
			<div class="form-group">
			  <label>zip code: </label>
			  <input class="form-control" type="text" name="owner-zip">
			</div>
		</div>
        <button type="Submit" class="btn btn-default">Add</button>
      </form>

	  <div class="panel-footer"></div>
    </div>
	
  </div>
</div>

</body>
</html>