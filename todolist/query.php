<?php
$user = "todolist";
$pass = "todolist1234pass";

$con = new mysqli("localhost",$user,$pass,"todolist");
if($con->connect_error){
	die('Could not connect: '.$con->connect_error());
}

if(isset($_GET['load'])){
	$query = "SELECT id,task,tag,person,priority,depth,rank,creation FROM tasks WHERE status = 1 ORDER BY rank;";
	$result = $con->query($query);
	$lastdepth = 0;
	if ($result->num_rows > 0){
	   while($row = $result->fetch_assoc()){
	   	      echo "<div class=\"row panel-body\">\n";
		      if($row["depth"] == 0){
		      	echo "<div class=\"col-xs-6 col-md-6\">".htmlspecialchars($row["task"])."</div>\n";
		      }else{
		        $width = 6-(int)$row["depth"];
		        echo "<div class=\"col-xs-".$row["depth"]." col-md-".$row["depth"]." childtask\"><div class=\"hanger\"></div></div>\n";
			echo "<div class=\"col-xs-".(string)$width." col-md-".(string)$width."\">".htmlspecialchars($row["task"])."</div>\n";
		      }
		      echo "<div class=\"col-xs-3 col-md-3\">".htmlspecialchars($row["tag"])."</div>\n";
		      echo "<div class=\"col-xs-2 col-md-2\">".htmlspecialchars($row["person"])."</div>\n";
		      echo "<div class=\"col-xs-1 col-md-1\"><button type=\"button\" class=\"btn btn-xs btn-danger close-button\" id=\"close".$row["id"]."\">x</div>\n";
		      echo "</div>\n";
	   }
	}
}

if(isset($_GET["closed"])){
	$query = "SELECT id,task,tag,person,priority,depth,rank,creation FROM tasks WHERE status = 0 ORDER BY closing DESC LIMIT 20;";
	$result = $con->query($query);
	$lastdepth = 0;
	if ($result->num_rows > 0){
	   while($row = $result->fetch_assoc()){
	   	      echo "<div class=\"row panel-body\">\n";
		      echo "<div class=\"col-xs-6 col-md-6 closed strikethrough\">".htmlspecialchars($row["task"])."</div>\n";
		      echo "<div class=\"col-xs-3 col-md-3 closed strikethrough\">".htmlspecialchars($row["tag"])."</div>\n";
		      echo "<div class=\"col-xs-2 col-md-2 closed strikethrough\">".htmlspecialchars($row["person"])."</div>\n";
		      echo "<div class=\"col-xs-1 col-md-1\"></div>\n";
		      echo "</div>\n";
	   }
	}

}

if(isset($_POST["increaseRank"])){
	$query = "UPDATE tasks SET rank = rank + 1 WHERE status = 1;";
	$result = $con->query($query);
}
if(isset($_POST["task"])){
	$task = mysqli_real_escape_string($con,$_POST["task"]);
	$tag = mysqli_real_escape_string($con,$_POST["tag"]);
	$person = mysqli_real_escape_string($con,$_POST["person"]);
	$depth = 0;
	if(isset($_POST["depth"])){
	  $depth = (int)$_POST["depth"];
	}
	$top = $_POST["top"];
	$query = "INSERT INTO tasks (task,tag,person,priority,depth,status,rank) VALUES ('$task','$tag','$person',1,$depth,1,";
	if($top == "true"){
		$query .= "1";
	}else{
		$maxquery = "SELECT MAX(rank) as maxrank from tasks WHERE status = 1;";
		$result = $con->query($maxquery);
		$maxrank = 1;
		if ($result->num_rows){
		   while($row = $result->fetch_assoc()){
		   	      if((int)$row["maxrank"] > $maxrank){
			        $maxrank = (int)$row["maxrank"]+1;
			      }
		   }
		}
		$query .= (string)$maxrank;
	}
	$query .= ");";
	$con->query($query);
	$idquery = "SELECT MAX(id) as maxid from tasks;";
	$result = $con->query($idquery);
	$id = 10;
	if($result->num_rows){
	  while($row = $result->fetch_assoc()){
	    $id = (int)$row["maxid"];
	  }
	}
	echo "<div class=\"row panel-body\">\n";
	if($depth == 0){
	  echo "<div class=\"col-xs-6 col-md-6\">".htmlspecialchars($task)."</div>\n";
	}else{
	  $width = 6-$depth;
	  echo "<div class=\"col-xs-".(string)$depth." col-md-".(string)$depth." childtask\"><div class=\"hanger\"></div></div>\n";
	  echo "<div class=\"col-xs-".(string)$width." col-md-".(string)$width."\">".htmlspecialchars($task)."</div>\n";
	}
	echo "<div class=\"col-xs-3 col-md-3\">".htmlspecialchars($tag)."</div>\n";
	echo "<div class=\"col-xs-2 col-md-2\">".htmlspecialchars($person)."</div>\n";
	echo "<div class=\"col-xs-1 col-md-1\"><button type=\"button\" class=\"btn btn-xs btn-danger close-button\" id=\"close".(string)$id."\">x</div>\n";
	echo "</div>\n";
}

if(isset($_POST["close"])){
	$closeid = (int)$_POST["close"];
	$query = "UPDATE tasks set status = 0, closing=CURRENT_TIMESTAMP WHERE id = $closeid;";
	$con->query($query);
}

$con->close();

?>
