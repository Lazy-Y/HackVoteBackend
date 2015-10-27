<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
$arr=array();
if (empty($_POST['owner'])){
	$arr['status'] = 'false';
	$arr['errMsg'] = 'owner name is empty';
	echo json_encode($arr);
	return;
}
$owner = $_POST['owner'];
$datas = $xgDB->getlist("SELECT * FROM QUESTION WHERE owner = '$owner' AND NOT (feedback IS NULL OR feedback = '')");
$data = array();
foreach($datas as $key => $value) {
	$temp = array();
	array_push($temp, $value['problem']);
	$opt = explode('§', $value['participants']);
	if (empty($value['participants'])) array_push($temp, 0);
	else array_push($temp, count($opt));
	array_push($temp, $value['id']);
	array_push($data, $temp);
}
$arr['status'] = 'true';
$arr['errMsg'] = '';
$arr['data'] = $data;
echo json_encode($arr);