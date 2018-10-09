<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="projects.js"></script>
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
        <a href="index.php" class="list-group-item active">Projects</a>
        <a href="tools.php" class="list-group-item">Tools</a>
		<a href="cars.php" class="list-group-item">Cars</a>
		 
		<a href="Parts.php" class="list-group-item">Parts</a>
        <a href="money.php" class="list-group-item">Money Flow</a>
      </div>
	</div>
	
    <div class="col-md-9">
      <ul class="nav nav-tabs">
        <li class="active"><a href="index.php">Browse Projects</a></li>
        <li><a href="createProjects.php">Create Projects</a></li>
      </ul>
      <h3>Current Projects:</h3>
      <div id="currentProjects">
        
	  </div>
      <h3>Completed Projects:</h3>
      <div id="completedProjects">
        
      </div>
	  <div class="panel-footer"></div>
    </div>
	
  </div>
</div>

</body>
</html>