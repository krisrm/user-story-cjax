<?php
class UserStory{
	public $id;
	public $title ='Untitled';
	public $actor ='';
	public $goal ='';
	public $benefit ='';
	public $comment ='';
	public $script ='';
	public $test_total=0;
	public $errors=array();
	public $error_total=0;

	public function __construct($id, $title='Untitled', $actor='', $goal='',$benefit='',$comment='',$script='',$test_total=0){
		$this->id = $id;
		$this->title = $title == '' ? "Untitled" : $title;
		$this->actor = $actor;
		$this->goal = $goal;
		$this->benefit = $benefit;
		$this->comment = $comment;
		$this->script = $script;
		$this->test_total= $test_total;

	}
	public function populate($db){
		$query = "SELECT * FROM user_story WHERE id=?";
		$s = $db->stmt_init();
		$s->prepare($query);
		$s->bind_param('i',$this->id);
		$s->execute();
		$s->bind_result($this->id,$this->title, $this->actor,$this->goal,$this->benefit,$this->comment,$this->script,$this->test_total);
		$s->fetch();
		$s->close();
		$this->title = $this->title == '' ? "Untitled" : $this->title;

		$query = "SELECT id, error FROM error WHERE user_story_id=?";
		$s = $db->stmt_init();
		$s->prepare($query);
		$s->bind_param('i',$this->id);
		$s->bind_result($e_id,$error);
		$s->execute();
		while($s->fetch()){
			array_push($this->errors, array($e_id,$error));
		}
		$s->close();
	}
}

function create_user_story($db){
	$query = "INSERT INTO user_story VALUES (NULL, '', '', '', '', '', '',0);";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->execute();
	$s->close();

	return $db->insert_id;
}
function delete_user_story($db,$id){
	$query = "DELETE FROM user_story WHERE id=?;";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('i',$id);
	$s->execute();
	$s->close();

	$query = "SELECT MAX(id) FROM user_story WHERE id < ?";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('i',$id);
	$s->execute();
	$s->bind_result($id);
	$s->fetch();
	$s->close();
	return $id;
}

function update_user_story($db,$st){
	echo var_dump($st);
	$query = "UPDATE user_story SET title=?,actor=?,goal=?,benefit=?,comment=?,script=? WHERE id=?;";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('ssssssi',$st->title,$st->actor,$st->goal,$st->benefit,$st->comment,$st->script,$st->id);
	$s->execute();
	$s->close();
}

function get_all_stories($db){
	$r = array();
	$query = "SELECT id,title,asserts,script FROM user_story;";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->execute();
	$s->bind_result($id,$title,$asserts,$script);
	while ($s->fetch()){
		$st= new UserStory($id,$title);
		$st->test_total = $asserts;
		$st->script = $script;
		array_push($r, $st);
	}
	$s->close();
	foreach ($r as $story){
		$query = "SELECT COUNT(*) FROM error WHERE user_story_id=?;";
		$s = $db->stmt_init();
		$s->prepare($query);
		$s->bind_param('i',$story->id);
		$s->execute();
		$s->bind_result($story->error_total);
		$s->fetch();
		$s->close();
	}
	return $r;
}
function get_configuration($db){
	$query = "SELECT * FROM configuration";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->execute();
	$s->bind_result($server,$app, $script);
	$s->fetch();
	$s->close();
	return array("server"=>$server,'app'=>$app,'script'=>$script);
}


import_request_variables("gp");

include('db.php');
$db = new mysqli("localhost",$user,$password,$database);
if($db->connect_error){ 
    die('Connection error: ('.$mysqli->connect_errno.'): '.$mysqli->connect_error); } 

if (isset($create)){
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?uid=".create_user_story($db));
	exit;
}
if (isset($update)){
	update_user_story($db,new UserStory($uid,$title,$actor,$goal,$benefit,$comment,$script));
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?uid=".$uid);
	exit;
}
if (isset($delete)){
	$new = delete_user_story($db,$uid);
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?uid=".$new);
	exit;
}
if (isset($configure)){
	$db->real_query("TRUNCATE TABLE configuration");
	$query = "INSERT INTO configuration VALUES (?,?,?)";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('sss',$server,$app,$script);
	$s->execute();
	$s->close();
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
	exit;
}

$stories = get_all_stories($db);
$story = null;
if (isset($uid)){
	$story = new UserStory($uid);
	$story->populate($db);
}

$config = get_configuration($db);

$s = $db->stmt_init();
$s->prepare("SELECT count(*) FROM error");
$s->bind_result($total_error);
$s->execute();
$s->fetch();
$s->close();
$s = $db->stmt_init();
$s->prepare("SELECT sum(asserts) FROM user_story");
$s->bind_result($total_test);
$s->execute();
$s->fetch();
$s->close();
$test_pct = ($total_test == 0) ? 0 : ($total_test-$total_error)/$total_test;


?>
