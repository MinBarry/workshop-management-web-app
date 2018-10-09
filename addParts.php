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
	function  requestProjects(element){
		console.log("in requestProjects value="+element.value);
		$.get("projects.php", {"part-projno": element.value}, 
		function(data,status){
			//alert("Data: " + data + "\nStatus: " + status);
			$("#projectSelect").html(data);
		});
	}
	function toogleSupplierForm(){
	if($("#selectSupplier").val() != "new"){
		$("#newSupplierContainer").hide();
	}else{
		$("#newSupplierContainer").show();
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
		<a href="cars.php" class="list-group-item">Cars</a>
		 
		<a href="Parts.php" class="list-group-item  active">Parts</a>
        <a href="money.php" class="list-group-item">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li><a href="Parts.php">Browse Parts</a></li>
        <li class="active"><a href="addParts.php">Add Parts</a></li>
      </ul>
	
<?php
//connect to server
$mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
if ($mysqli->connect_errno) {
    echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
}
//add new part to previous  project
if(isset($_POST['part-name']) && isset($_POST['part-project'])){
	if($_POST['part-name']!== 0 && $_POST['part-project']!== 0) {
		$success=true;
	    if (!($stmt = $mysqli->prepare("INSERT INTO ProjectAndParts(part,project) VALUES (?,?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$success=false;
        }
		if (!$stmt->bind_param("si",$_POST['part-name'],$_POST['part-project'])) {
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
		echo $_POST['part-name']."  ".$_POST['part-project']."You must Select a part and a project";
	}
}
?>
 <form role="form" method="post">
		<h3>Use a previous part for a new project:</h3>
		<div class="form-group">
          <label>Part Name: </label>
			<select onchange="requestProjects(this)" class="form-control" name="part-name">
				<option value='0'>select part</option>
	  <?php
		
		  if (!($stmt = $mysqli->prepare("SELECT name FROM Parts"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
		  if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		  }
		  $pname=null;
		  if (!$stmt->bind_result($pname)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
		  while($stmt->fetch()) {
		  echo "<option value='$pname'>$pname</option>";
		  }
		  $stmt->close();
		  ?>
		    </select>
		</div>
		<div class="form-group">
			<label>Project: </label>
			<select id="projectSelect" class="form-control" name="part-project">
				<option value="0">Select a part first</option>
		    </select>
		</div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>

	  <form role="form" method="post">
		<h3>Add A New Car Part</h3>
<?php
//create new part
if(isset($_POST["prevsupplier-name"]) || isset($_POST["supplier-name"])){
	$addedSupplier=0;
	$suppliername=null;
	//case where user selects previous owner
	if($_POST["prevsupplier-name"]!= "new"){
		$suppliername = $_POST["prevsupplier-name"];
		$addedSupplier=1;
	}else{
		//case for new owner
		if(isset($_POST["supplier-name"]) && isset($_POST["supplier-website"]) && strlen($_POST["supplier-name"])!=0 && strlen($_POST["supplier-website"])!=0){
			
				$addedSupplier=1;
				if (!($stmt = $mysqli->prepare("INSERT INTO Suppliers(name, website) VALUES (?,?)"))) {
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					$addedSupplier = 0;
				}
				if (!$stmt->bind_param("ss", $_POST["supplier-name"],$_POST["supplier-website"])) {
					echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
					$addedSupplier = 0;
				}
				if (!$stmt->execute()) {
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					$addedSupplier = 0;
				}else{
				$suppliername= $_POST["supplier-name"];
				}
		} else {
			$addedSupplier = 0;
		}
	}
}
if(isset($_POST['newPart-project']) && isset($_POST['newPart-name'])){
	if($addedSupplier!=0 && strlen($_POST['newPart-name'])!= 0 && strlen($_POST['newPart-function'])!= 0 && strlen($_POST['newPart-brand'])!= 0) {
		if(is_numeric($_POST["newPart-price"])){
			$success=true;
			//create part in parts table
			if (!($stmt = $mysqli->prepare("
					INSERT INTO Parts(name, function, price, brand, supplier) 
					VALUES (?,?,?,?,?)"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				$success=false;
			}
			if (!$stmt->bind_param("ssiss",$_POST["newPart-name"],$_POST["newPart-function"], $_POST["newPart-price"], $_POST["newPart-brand"],$suppliername)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				$success=false;
			}
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				$success=false;
			}
			$stmt->close();
			
			if($success){
				echo "<p>successfully created part!</p>";
				if (!($stmt = $mysqli->prepare("INSERT INTO ProjectAndParts(project, part) VALUES (?,?)"))) {
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					$success=false;
				}
				if (!$stmt->bind_param("is", $_POST['newPart-project'], $_POST["newPart-name"])) {
					echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
					$success=false;
				}
				if (!$stmt->execute()) {
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					$success=false;
				}
				$stmt->close();
				if($success){
					echo "<p>Added part to the selected project!</p>";
				} else{
					"<p>But couldn't add the part to the selected project!</p>";
				}
			} else{
				echo "<p>Couldn't create part</p>";
			}
		} else {
			echo "<p>Price must be a number</p>";
		}
	} else {
		echo "<p>*You must fill all fields</p>";
	}
}
?>
		<div class="form-group">
			<label>Project: </label>
			<select class="form-control" name="newPart-project">
	  <?php
		if (!($stmt = $mysqli->prepare("SELECT projNo, name FROM Projects"))) {
           echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
		if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$pno=NULL;
		$name=null;
		if (!$stmt->bind_result($pno,$name)) {
			echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		while($stmt->fetch()) {
		  echo "<option value='$pno'>$name</option>";
		}
		$stmt->close();
	  ?>
		    </select>
			<a href="createProjects.php">Click here to create a new project</a>
		</div>
		<div class="form-group">
          <label>Name: </label>
		  <input class="form-control" type="text" name="newPart-name">
		</div>
		<div class="form-group">
			<label>Function: </label>
			<input class="form-control" type="text" name="newPart-function">
		</div>
		<div class="form-group">
			<label>Price: </label>
			<input class="form-control" type="number" name="newPart-price">
		</div>
		<div class="form-group">
			<label>Brand: </label>
			<input class="form-control" type="textarea" name="newPart-brand">
		</div>
		<div class="form-group">
		<label>Supplier: </label>
		<select class="form-control" onchange="toogleSupplierForm()" id="selectSupplier" name="prevsupplier-name">
		<option value="new">New Supplier</option>
	  <?php

		if (!($stmt = $mysqli->prepare("SELECT name FROM Suppliers"))) {
           echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
		if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$name=null;
		if (!$stmt->bind_result($name)) {
			echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		while($stmt->fetch()) {
		  echo "<option value='$name'>$name</option>";
		}
		$stmt->close();
	  ?>
		</select>
		</div>
		<div id="newSupplierContainer">
			<div class="form-group">
			  <label>name: </label>
			  <input class="form-control" type="text" name="supplier-name">
			</div>
			<div class="form-group">
			  <label>website: </label>
			  <input class="form-control" type="text" name="supplier-website">
			</div>
		</div>
        <button type="submit" class="btn btn-default">Submit</button>
		
      </form>
	  <div class="panel-footer"></div>
    </div>
	
  </div>
</div>

</body>
</html>