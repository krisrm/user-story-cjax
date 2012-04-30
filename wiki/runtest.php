<?php
include 'head.php';

function do_post_request($url, $data, $optional_headers = null)
{
	$params = array('http' => array(
              'method' => 'POST',
              'content' => $data
	));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}

class Session {
	public $current;
	public $total;
	public $progress;
	public $error;

}

function getSession($sessid,$db){
	$query = "SELECT current,total,progress,error FROM test_session WHERE id=?";
	$s = $db->stmt_init();
	$s->prepare($query);
	$s->bind_param('i',$sessid);
	$s->execute();
	$sess = new Session();
	$s->bind_result($sess->current,$sess->total,$sess->progress,$sess->error);
	$s->fetch();
	$s->close();
	return $sess;
}

if (isset($status)){
	echo json_encode(getSession($status,$db));
	$db->close();
	exit;
}
if (!isset($total)){
	echo "No total provided";
	$db->close();
	exit;
}

//new session
$query = "INSERT INTO test_session VALUES (NULL,0,?,'','',NULL)";
$s = $db->stmt_init();
$s->prepare($query);
$s->bind_param('i',$total);
$s->execute();
$test_sess_id = $db->insert_id;
$s->close();

//delete old sessions (older than an hour, they're likely not running anymore)
$db->real_query("DELETE FROM test_session WHERE ADDTIME(created, '1:0:0') < NOW()");
//echo json_encode(getSession($test_sess_id, $db));

$doc = new DOMDocument('1.0','iso-8859-1');

$root = $doc->createElement('test-suite');
$root = $doc->appendChild($root);

$conf = $doc->createElement('config');
$conf = $root->appendChild($conf);

$server = $doc->createElement('server');
$server = $conf->appendChild($server);
$server->appendChild($doc->createTextNode($config['server']));

$app = $doc->createElement('app');
$app = $conf->appendChild($app);
$app->appendChild($doc->createTextNode($config['app']));

$script = $doc->createElement('script');
$script = $conf->appendChild($script);
$script->appendChild($doc->createTextNode($config['script']));

$callback = $doc->createElement('callback');
$callback = $conf->appendChild($callback);
$callback->appendChild($doc->createTextNode("http://".$_SERVER['HTTP_HOST'].'/wiki/testdone.php'));//TODO fix this hack

$sess = $doc->createElement('session');
$sess= $conf->appendChild($sess);
$sess->appendChild($doc->createTextNode($test_sess_id));

foreach ($stories as $st){
	$case = $doc->createElement('case');
	$case = $root->appendChild($case);

	$idAttr = $doc->createAttribute('id');
	$idAttr->value = $st->id;
	$case->appendChild($idAttr);

	$script = $doc->createElement('script');
	$script = $case->appendChild($script);
	$script->appendChild($doc->createTextNode($st->script));

}
try {
	do_post_request("http://localhost:8080/CrawlJaxServer/TestEngine", $doc->saveXML(),"content-type:text/xml");
} catch(Exception $e){
	$db->real_query("DELETE FROM test_session WHERE id="+$test_sess_id);
	echo "-1";
	exit;
}
echo $test_sess_id;
$db->close();
?>