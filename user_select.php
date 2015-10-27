<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
$arr=array();
if (empty($_POST['name'])||empty($_POST['id'])||empty($_POST['selected'])){
	$arr['status'] = 'false';
	$arr['errMsg'] = 'user name is empty';
	echo json_encode($arr);
	return;
}

$name = $_POST['name'];
$id = $_POST['id'];
$selected = $_POST['selected'];

$datas = $xgDB->query("SELECT * FROM QUESTION WHERE id = '$id'");

foreach($datas as $key => $value) {
	if (!empty($value['participants'])){
		$name = $value['participants']."§".$name;
		$selected = $value['selected']."§".$selected;
	}
	break;
}

$msg = $xgDB->query("UPDATE QUESTION SET participants = '$name', selected = '$selected' WHERE id = '$id'");
$xgDB->commit();
$arr['status'] = 'true';
$arr['errMsg'] = '';
echo json_encode($arr);