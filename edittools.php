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

<div class="container">
  <div class="page-header">
    <h1>Omar's Workshop</h1>
	<p></p>
  </div>
  
  <div class="row">
	<div class="col-md-2">
      <div class="list-group">
        <a href="index.php" class="list-group-item">Projects</a>
        <a href="tools.php" class="list-group-item active">Tools</a>
		<a href="cars.php" class="list-group-item">Cars</a>
		 
		<a href="Parts.php" class="list-group-item">Parts</a>
        <a href="money.php" class="list-group-item">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li><a href="tools.php">Tools Search</a></li>
        <li><a href="addtools.php">Add Tools</a></li>
		<li class="active"><a href="edittools.php">Edit Tools</a></li>
      </ul>
 <?php
 //connect to server
	      $mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
	      if ($mysqli->connect_errno) {
          echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
	      }
//delete a tool
if(isset($_GET["delete"]) && $_GET["delete"] == "1" ){

    if (!($stmt = $mysqli->prepare("DELETE FROM Tools WHERE name = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("s", $_GET["tool-names"])) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
		
}
//edit a tool
if(isset($_GET["edit"]) && $_GET["edit"] == "1" ){
	//update name
	if(isset($_GET["tool-name"]) && $_GET["tool-name"]!= " " && $_GET["tool-name"]!= ""){
		if (!($stmt = $mysqli->prepare("UPDATE Tools SET name = ? WHERE name = ?"))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->bind_param("ss", $_GET["tool-name"],$_GET["tool-names"])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
	}
	//update function
	if(isset($_GET["tool-function"]) && $_GET["tool-function"]!= " " && $_GET["tool-function"]!= ""){
		if (!($stmt = $mysqli->prepare("UPDATE Tools SET function = ? WHERE name = ?"))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->bind_param("ss", $_GET["tool-function"],$_GET["tool-names"])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
	}
	//update type
	if(isset($_GET["tool-type"]) && $_GET["tool-type"]!= " " && $_GET["tool-type"]!= ""){
		if (!($stmt = $mysqli->prepare("UPDATE Tools SET type = ? WHERE name = ?"))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->bind_param("ss", $_GET["tool-type"],$_GET["tool-names"])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
	}
}
?>    
	  <h3>Delete A Tool</h3>

	  <form role="form" method="get">
		  <div class="form-group">
		    <label>Select Tool:</label>
		    <select class="form-control" name="tool-names">
	  <?php
		
		  if (!($stmt = $mysqli->prepare("SELECT name FROM Tools"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
		  if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		  }
		  $t=NULL;
		  if (!$stmt->bind_result($t)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
		  while($stmt->fetch()) {
		  echo "<option value='$t'>$t</option>";
		  }
		  $stmt->close();
		  ?>
		    </select>
		  </div>
		  <button type="Submit" class="btn btn-default" name='delete' value="1">Delete</button>
      </form>
	  
	  <h3>Edit A Tool</h3>

	  <form role="form" method="get">
		  <div class="form-group">
		    <label>Select Tool:</label>
		    <select class="form-control" name="tool-names">
	  <?php
		//connect to server
	      $mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
	      if ($mysqli->connect_errno) {
          echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
	      }
		  if (!($stmt = $mysqli->prepare("SELECT name FROM Tools"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
		  if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		  }
		  $t=NULL;
		  if (!$stmt->bind_result($t)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
		  while($stmt->fetch()) {
		  echo "<option value='$t'>$t</option>";
		  }
		  $stmt->close();
		  ?>
		    </select>
		  </div>
		  <div class="form-group">
		  <label>Name: </label>
		  <input class="form-control" type="text" name="tool-name">
		  </div>
		  <div class="form-group">
		  <label>Function: </label>
		  <input class="form-control" type="text" name="tool-function">
		  </div>
		  <div class="form-group">
		  <label>Type: </label>
		  <input class="form-control" type="text" name="tool-type">
		  </div>
		  <button type="Submit" class="btn btn-default" name='edit' value="1">Edit</button>
      </form>
		
	
    </div>
	
  </div>
</div>

</body>
</html>