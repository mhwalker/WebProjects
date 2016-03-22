<?php

// A list of permitted file extensions
$allowed = array('pdf');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	$newname = hash("md5",$_FILES['upl']['name'].strval(rand()));
	$control = hash("md5",$_FILES['upl']['name'].strval(rand()));
	$follow = hash("md5",$_FILES['upl']['name'].strval(rand()));

	$con = mysql_connect("localhost","glunamiView","GLview31415");
	if(!$con){
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db("glunamiView",$con);

	$insertquery = 'INSERT INTO files (id,control,follow) VALUES ("'.$newname.'","'.$control.'","'.$follow.'");';
	mysql_query($insertquery,$con);

	if(move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/'.$newname.'.pdf')){
		echo '{"status":"success","newname":"'.$newname.'","control":"'.$control.'","follow":"'.$follow.'"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;

?>
