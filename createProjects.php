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
	function  requestCars(element){
		console.log("in requestCars value="+element.value);
		$.get("projects.php", {"car-projno": element.value}, 
		function(data,status){
			//alert("Data: " + data + "\nStatus: " + status);
			$("#carSelect").html(data);
		});
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
        <a href="index.php" class="list-group-item active">Projects</a>
        <a href="tools.php" class="list-group-item">Tools</a>
		<a href="cars.php" class="list-group-item">Cars</a>
		 
		<a href="Parts.php" class="list-group-item">Parts</a>
        <a href="money.php" class="list-group-item">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li><a href="index.php">Browse Projects</a></li>
        <li class="active"><a href="createProjects.php">Create Projects</a></li>
      </ul>
      
	
<?php
//connect to server
$mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
if ($mysqli->connect_errno) {
    echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
}
//create new project from previous  project
if(isset($_POST['proj-no']) && isset($_POST['proj-car'])){
	if($_POST['proj-no']!= 0 && $_POST['proj-car']!= 0) {
		$success=true;
	    if (!($stmt = $mysqli->prepare("INSERT INTO CarPartOfProject(project, car, progress) VALUES (?,?,?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$success=false;
        }
		$progress="In progress";
	    if (!$stmt->bind_param("iss", $_POST["proj-no"],$_POST["proj-car"], $progress)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			$success=false;
		}
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			$success=false;
        }
        $stmt->close();
		if($success){
			echo "<p>successfully created project!</p>";
		}
	} else {
		echo "You must Select a project and car";
	}
}
?>
 <form role="form" method="post">
		<h3>Start a previous project for a new Car:</h3>
		<div class="form-group">
          <label>Project Name: </label>
			<select onchange="requestCars(this)" class="form-control" name="proj-no">
				<option value='0'>select project</option>
	  <?php
		
		  if (!($stmt = $mysqli->prepare("SELECT projNo, name FROM Projects"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
		  if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		  }
		  $pno=NULL;
		  $pname=null;
		  if (!$stmt->bind_result($pno, $pname)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
		  while($stmt->fetch()) {
		  echo "<option value='$pno'>$pname</option>";
		  }
		  $stmt->close();
		  ?>
		    </select>
		</div>
		<div class="form-group">
			<label>Car: </label>
			<select id="carSelect" class="form-control" name="proj-car">
				<option value="0">Select a project first</option>
		    </select>
		</div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>

	  <form role="form" method="post">
		<h3>Start a new Project</h3>
<?php
//create new project
if(isset($_POST['newProj-car']) && isset($_POST['newProj-name'])){
	if(strlen($_POST['newProj-name'])!= 0 && strlen($_POST['newProj-type'])!= 0 && strlen($_POST['newProj-desc'])!= 0) {
		if(is_numeric($_POST["newProj-budget"]) && is_numeric($_POST["newProj-time"])){
			$success=true;
			//create project in projects table
			
			$stmt = $mysqli->prepare("SELECT max(projno) FROM Projects");
			$stmt->execute();
			$projno=NULL;
			$stmt->bind_result($projno);
			$stmt->fetch();
			$stmt->close();
			$projno++;
			if (!($stmt = $mysqli->prepare("
					INSERT INTO Projects(projno, name, timeframe, type, budget, description) 
					VALUES (?,?,?,?,?,?)"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				$success=false;
			}
			if (!$stmt->bind_param("isisis",$projno, $_POST["newProj-name"],$_POST["newProj-time"], $_POST["newProj-type"], $_POST["newProj-budget"], $_POST["newProj-desc"])) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				$success=false;
			}
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				$success=false;
			}
			$stmt->close();
			
			if($success){
				echo "<p>successfully created project!</p>";
				if (!($stmt = $mysqli->prepare("INSERT INTO CarPartOfProject(project, car, progress) VALUES (?,?,?)"))) {
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					$success=false;
				}
				$progress="In progress";
				if (!$stmt->bind_param("iss", $projno,$_POST["newProj-car"], $progress)) {
					echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
					$success=false;
				}
				if (!$stmt->execute()) {
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					$success=false;
				}
				$stmt->close();
				if($success){
					echo "<p>and Started a project with the selected car!</p>";
				} else{
					"<p>But couldn't start a project with the selected car!</p>";
				}
			} else{
				echo "<p>Couldn't create project</p>";
			}
		} else {
			echo "<p>Budget and time-frame must  be numbers</p>";
		}
	} else {
		echo "<p>*You must fill all fields</p>";
	}
}
?>
		<div class="form-group">
			<label>Car: </label>
			<select class="form-control" name="newProj-car">
	  <?php
		if (!($stmt = $mysqli->prepare("SELECT licensePlate, year, model FROM Cars"))) {
           echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
		if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$car=NULL;
		$year=null;
		$model=null;
		if (!$stmt->bind_result($car,$year,$model)) {
			echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		while($stmt->fetch()) {
		  echo "<option value='$car'>$car ($year $model)</option>";
		}
		$stmt->close();
	  ?>
		    </select>
			<a href="addCars.php">Click here to add a new car</a>
		</div>
		<div class="form-group">
          <label>Name: </label>
		  <input class="form-control" type="text" name="newProj-name">
		</div>
		<div class="form-group">
			<label>Time Frame: </label>
			<input class="form-control" type="number" name="newProj-time">
		</div>
		<div class="form-group">
			<label>Type: </label>
			<input class="form-control" type="text" name="newProj-type">
		</div>
		<div class="form-group">
			<label>Budget: </label>
			<input class="form-control" type="number" name="newProj-budget">
		</div>
		<div class="form-group">
			<label>Description: </label>
			<input class="form-control" type="textarea" name="newProj-desc">
		</div>
        <button type="submit" class="btn btn-default">Submit</button>
		
      </form>
	  
	  <div class="panel-footer"></div>
    </div>
	
  </div>
</div>

</body>
</html>