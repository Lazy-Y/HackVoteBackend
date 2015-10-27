<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";

$xgDB = new SQLite($fileName);

$xgDB->beginTransaction();
$array = array();
if (empty($_POST['name'])){
	$array['status'] = 'false';
	$array['errMsg'] = 'User name is empty';
	echo json_encode($array);
	return;
}
$name = $_POST['name'];
$datas = $xgDB->getlist("select * from USER where name = '$name'");
if (sizeof($datas) != 1){
	$array['status'] = 'false';
	$array['errMsg'] = 'Cannot find user';
    return;
}
foreach($datas as $key => $value) {
	$array['status'] = 'true';
	$array['errMsg'] = '';
	$array['data'] = $value;
	echo json_encode($array);
}