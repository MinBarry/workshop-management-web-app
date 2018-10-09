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
    <h1>Workshop</h1>
	<p></p>
  </div>

  
  <div class="row">
	<div class="col-md-2">
      <div class="list-group">
        <a href="index.php" class="list-group-item">Projects</a>
        <a href="tools.php" class="list-group-item">Tools</a>
		<a href="cars.php" class="list-group-item">Cars</a>
		 
		<a href="Parts.php" class="list-group-item">Parts</a>
        <a href="money.php" class="list-group-item  active">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li class="active"><a href="money.php">main</a></li>
      </ul>
	  
	  <div class="col-md-3">
		  <h3>Costs:</h3>
		  <div id="costs">
<?php
//connect to server
$mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
if ($mysqli->connect_errno) {
	echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
}

if (!($stmt = $mysqli->prepare("
	SELECT sum(pr.price) FROM Parts pr, ProjectAndParts pp, CarPartOfProject cp, Cars c WHERE pr.name = pp.part AND pp.project = cp.project AND cp.car = c.licensePlate AND c.owner = 'omarAzi@gmail.com' "))) {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$partsCost = null;
$stmt->bind_result($partsCost);
$stmt->fetch();
$stmt->close();
echo "<table class='table table-striped'><tbody>";
echo "<tr> <td>Parts costs:<td>$$partsCost </tr>";
echo "</tbody></table>";
$stmt->close();
?>
		  </div>
	  </div>
	  
      <div class="col-md-3">
		  <h3>Revenue:</h3>
		  <div id="revenue">
<?php
if (!($stmt = $mysqli->prepare("SELECT sum(p.budget) FROM Projects p, CarPartOfProject cp, Cars c WHERE p.projNo = cp.project AND cp.car = c.licensePlate AND c.owner <> 'omarAzi@gmail.com' AND cp.progress='Done'"))) {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$projectsRevenue = null;
$stmt->bind_result($projectsRevenue);
$stmt->fetch();
echo "<table class='table table-striped'>";
echo "<thead> <tr> <td>Projects:<td>$$projectsRevenue </tr></thead>";
echo "</table>";
$stmt->close();
?>
		  </div>
	  </div>
      
	
    </div>
	
  </div>
</div>

</body>
</html>