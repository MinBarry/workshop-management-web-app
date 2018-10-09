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
        <li class="active"><a href="addtools.php">Add Tools</a></li>
		<li><a href="edittools.php">Edit Tools</a></li>
      </ul>
      
	  <form role="form" method="post">
		  <div class="form-group">
		  <label>Name: </label>
		  <input class="form-control" type="text" name="tool-name" required="required">
		  </div>
		  <div class="form-group">
		  <label>Function: </label>
		  <input class="form-control" type="text" name="tool-function">
		  </div>
		  <div class="form-group">
		  <label>Type: </label>
		  <input class="form-control" type="text" name="tool-type">
		  </div>
          <button type="Submit" class="btn btn-default">Add</button>
      </form>
	  <?php
		//connect to server
		$mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		if(isset($_POST['tool-name']) && $_POST['tool-name'] != " "){
			$success = 1;
		   if (!($stmt = $mysqli->prepare("INSERT INTO Tools(name, function, type) VALUES (?,?,?)"))) {
			  echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			  $success = 0;
		   }
		   if (!$stmt->bind_param("sss", $_POST['tool-name'],$_POST['tool-function'],$_POST['tool-type'])) {
			 echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			 $success = 0;
		   }

		   if (!$stmt->execute()) {
			 echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			 $success = 0;
		   }
		   if($success){
		   echo "<h4>Added ".$_POST['tool-name']."</h4>";
		   }
		   $stmt->close();
		}
		?>
	
    </div>
	
  </div>
</div>

</body>
</html>