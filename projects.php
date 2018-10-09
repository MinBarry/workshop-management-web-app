<?php
//connect to server
	      $mysqli = new mysqli("mysql.cs.orst.edu", "cs340_barrymin", "2524", "cs340_barrymin");
	      if ($mysqli->connect_errno) {
          echo "<h1>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error."</h1>";
	      }
//select all in progress
if(isset($_GET["completed-projects"])){
     if (!($stmt = $mysqli->prepare("
        SELECT c.make, c.model, c.year, o.email, o.name, p.name, p.type, p.budget, p.description
        FROM Cars c, Owners o, Projects p, CarPartOfProject cpp
        WHERE c.owner = o.email AND p.projNo = cpp.project AND c.licensePlate = cpp.car AND cpp.progress = 'Done'"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $make=null; 
    $model=null; 
    $year=null; 
    $email=null; 
    $name=null; 
    $pname=null;
    $type=null; 
    $budget=null; 
    $description=null;
    if (!$stmt->bind_result($make,$model,$year,$email,$name,$pname,$type,$budget,$description)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    while($stmt->fetch()) {
        echo 
        "<div class='panel panel-default'>
            <div class='panel-body'>
                <table class='table table-striped'>
                <tr>
                <td>$make $model $year
                <td owner='$email'onclick='showOwner(this)'><a>Owner: $name</a>
                <tr>
                <td>$pname<td>Type: $type<td>$$budget<td>$description
                </table>
            </div>
         </div>";
    }
}
//select all completed projets
if(isset($_GET["current-projects"])){
     if (!($stmt = $mysqli->prepare("
        SELECT c.licensePlate, p.projNo, c.make, c.model, c.year, o.email, o.name, p.name, p.type, p.budget, p.description
        FROM Cars c, Owners o, Projects p, CarPartOfProject cpp
        WHERE c.owner = o.email AND p.projNo = cpp.project AND c.licensePlate = cpp.car AND cpp.progress = 'In Progress'"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $license=null;
    $projNo=null;
    $make=null; 
    $model=null; 
    $year=null; 
    $email=null; 
    $name=null; 
    $pname=null;
    $type=null; 
    $budget=null; 
    $description=null;
    if (!$stmt->bind_result($license,$projNo,$make,$model,$year,$email,$name,$pname,$type,$budget,$description)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    while($stmt->fetch()) {
        echo 
        "<div class='panel panel-default'>
            <div class='panel-body'>
                <table class='table table-striped'>
                <tr>
                <td>$make $model $year
                <td owner='$email'onclick='showOwner(this)'><a>Owner: $name</a>
                <tr>
                <td>$pname<td>Type: $type<td>$$budget<td>$description
                <td>
                <input type='button' license='$license' projNo='$projNo'class='btn btn-default' value='Complete' onclick='completeProject(this)'></input>
                </table>
            </div>
         </div>";
    }
}

//get user info
if(isset($_POST["owner-email"])){
    if (!($stmt = $mysqli->prepare("
        SELECT o.email, o.phoneNo, o.street, o.city, o.state, o.zipCode
        FROM Owners o
        WHERE o.email = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("s", $_POST["owner-email"])) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $email=null;
    $phone=null;
    $street=null;
    $city=null;
    $state=null;
    $zip=null;
    if (!$stmt->bind_result($email,$phone,$street,$city,$state,$zip)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    while($stmt->fetch()) {
        $exist= true;
        echo "<div class='holder'><div >$email $phone</div><div>$street</div><div>$city, $state, $zip</div></div>";
    }

}
//complete a project
if(isset($_POST["complete-proj"]) && isset($_POST["complete-license"])){
	if (!($stmt = $mysqli->prepare("UPDATE CarPartOfProject SET progress = 'Done' WHERE project = ? AND car = ?"))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->bind_param("is", $_POST["complete-proj"],$_POST["complete-license"])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
}
//select cars that are available for a project
if(isset($_GET["car-projno"])){
	echo $_GET['car-projno']."  ";
	if (!($stmt = $mysqli->prepare("
		SELECT licensePlate, year, model
		FROM Cars c 
		WHERE licensePlate NOT IN 
		(SELECT licensePlate 
		FROM Cars c, CarPartOfProject p 
		WHERE c.licensePlate = p.car AND p.project = ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
	if (!$stmt->bind_param("i", $_GET["car-projno"])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
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
}

//select projects that are available for a part
if(isset($_GET["part-projno"])){
	if (!($stmt = $mysqli->prepare("
		SELECT p.projNo, p.name
		FROM Projects p
		WHERE projNo NOT IN 
		(SELECT p.projNo 
		FROM Projects p, ProjectAndParts pr 
		WHERE p.projNo = pr.project AND pr.part = ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
	if (!$stmt->bind_param("s", $_GET["part-projno"])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$pno=null;
	$name=NULL;
	if (!$stmt->bind_result($pno,$name)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
	while($stmt->fetch()) {
		  echo "<option value='$pno'>$name</option>";
	}
	$stmt->close();
}
?>