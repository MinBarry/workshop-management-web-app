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
		 
		<a href="Parts.php" class="list-group-item  active">Parts</a>
        <a href="money.php" class="list-group-item">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li class="active"><a href="Parts.php">Browse Parts</a></li>
        <li><a href="addParts.php">Add Parts</a></li>
      </ul>
<?php
 //connect to server
	      $mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
	      if ($mysqli->connect_errno) {
          echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
	      }
if (!($stmt = $mysqli->prepare("
    SELECT p.name,p.function,p.price,p.brand,s.name,s.website, pr.name
	FROM Parts p, Suppliers s, ProjectAndParts pp, Projects pr
	WHERE p.supplier = s.name AND pp.part = p.name AND pp.project = pr.projNo"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$partname=null;
$partfunction=null; 
$price=null; 
$brand=null; 
$supliername=null; 
$website=null; 
$projectname=null;
if (!$stmt->bind_result($partname,$partfunction,$price,$brand,$supliername,$website,$projectname)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
while($stmt->fetch()) {
    echo 
    "<div class='panel panel-default'>
       <div class='panel-body'>
           <table class='table table-striped'>
           <tr>
            <td>$partname <td>$partfunction <td>$$price <td>$brand
			<tr>
			<td>$supliername <td><a href='$website'>$website</a>
            <td>Project: $projectname
            <td>
            </table>
          </div>
     </div>";
}
?>
	  <div class="panel-footer"></div>
    </div>
	
  </div>
</div>

</body>
</html>