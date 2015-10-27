<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
#upload: owner, problem, detail, type, options
$array = array();
if (empty($_POST['owner'])||empty($_POST['problem'])||
	empty($_POST['detail'])||empty($_POST['type'])||
	empty($_POST['options'])){
	$array['status'] = 'false';
	$array['errMsg'] = 'Please provide owner, problem, detail, type and options';
	echo json_encode($array);
	return;
}
$owner = $_POST['owner'];
$problem = $_POST['problem'];
$detail = $_POST['detail'];
$type = $_POST['type'];
$options = $_POST['options'];

$arr=$xgDB->query("INSERT INTO QUESTION (owner, problem, detail, type, options) 
	values('$owner', '$problem', '$detail', '$type', '$options')");
$xgDB->commit();

$array['status'] = "true";
echo json_encode($array);
$datas = $xgDB->query("SELECT * FROM QUESTION");