<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
$arr=array();
if (empty($_POST['user'])){
	$arr['status'] = 'false';
	$arr['errMsg'] = 'user name is empty';
	echo json_encode($arr);
	return;
}
$user = $_POST['user'];
$datas = $xgDB->query("SELECT * FROM QUESTION WHERE NOT (owner = '$user' OR (participants IS NOT NULL AND participants LIKE '%$user%'))");
$arr['status'] = 'true';
$arr['errMsg'] = '';
$array = array();
foreach($datas as $key => $value) {
   array_push($array, $value);
}
if (empty($array)){
	$arr['empty'] = 'true';
	echo json_encode($arr);
	return;
}
else $arr['empty'] = 'false';
$index = rand()%sizeof($array);
echo json_encode($arr+$array[$index]);