<?php
#expected params: $sessid, user story $id, $asserts, $errors (array)
#[{"id":"errid","error":"errormessage"},{"id":"errid","error":"terribaderror"}]
import_request_variables('gp');


if(!isset($sessid)){
	echo "please call with a sessid";
	exit;
}

include('db.php');
$db = new mysqli("localhost",$user,$password,$database);


if (isset($finished)){
	/*$query = "DELETE FROM test_session WHERE id=?";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('i',$sessid);
	$s->execute();
	$s->close();*/
	exit;
}


if(!isset($id)){
	echo "please call with an id";
	exit;
}

$current = 0;
$query = "SELECT current FROM test_session WHERE id=?";
$s = $db->stmt_init();
$s->prepare($query);
$s->bind_param('i',$sessid);
$s->execute();
$s->bind_result($current);
if (!$s->fetch()){
	$s->close();
	$db->close();
	echo "invalid sessid";
	exit;
}
$s->close();
$current++;
echo $current;
$query = "UPDATE test_session SET current=? WHERE id=?";
$s = $db->stmt_init();
$s->prepare($query);
$s->bind_param('ii',$current,$sessid);
$s->execute();
$s->close();

if(isset($asserts)){
	$query = "UPDATE user_story SET asserts=? WHERE id=?";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('ii',$asserts,$id);
	$s->execute();
	$s->close();
}

if(isset($errors)){
	$s = $db->stmt_init();
	$s->prepare("DELETE FROM error WHERE user_story_id=?");
	$s->bind_param('i',$id);
	$s->execute();
	$s->close();

	//echo $errors;
	if ($errors=="[]"){
		return;
	}
	$errors = json_decode($errors);
	if ($errors == null){
		die ("Couldn't parse JSON");
	}

	foreach ($errors as $error){
		$query = "INSERT INTO error VALUES (?,?,?)";
		$s = $db->stmt_init();
		$s->prepare($query);
		$s->bind_param('sis',$error->id,$id,$error->error);
		$s->execute();
		$s->close();
	}
}

$db->close();
?>