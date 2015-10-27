<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
$arr=array();
if (empty($_POST['id'])||empty($_POST['selected'])){
	$arr['status'] = 'false';
	$arr['errMsg'] = 'question id, selected is empty';
	echo json_encode($arr);
	return;
}

$id = $_POST['id'];
$selected = $_POST['selected'];

$msg = $xgDB->query("UPDATE QUESTION SET feedback = '$selected' WHERE id = '$id'");

$arr['status'] = 'true';
$arr['errMsg'] = '';
echo json_encode($arr);


$QID = $id;
$feedback = $selected;
$data = $xgDB->query("SELECT * FROM QUESTION WHERE id = '$QID'");
$users = array();
$choices = array();
$opt = array();
$type = "";
$total = array();

foreach ($data as $key => $value) {
	$users = explode('§', $value['participants']);
	$choices = explode('§', $value['selected']);
	$opt = explode('§', $value['options']);
	$type = $value['type'];
}
$count = 0;
for ($i = 1; $i <= sizeof($opt); $i++){
	$temp = array();
	for($j = 0; $j < sizeof($users); $j++){
		if ($choices[$j] == $i){
			array_push($temp, $users[$j]);
		}
	}
	if (!empty($temp)) $count++;
	$total[$i] = $temp;
}

if ($count < 2) return;
$totalTransaction = 0;
for ($i = 1; $i <= sizeof($total); $i++){
	if ($i == $feedback) {
		continue;
	}
	for ($j = 0; $j < sizeof($total[$i]); $j++){
		$loserName = $total[$i][$j];
		$data1 = $xgDB->query("SELECT * FROM USER WHERE name = '$loserName'");
		$losePoint = 0;
		foreach ($data1 as $key => $value) {
			$losePoint = round($value[$type]/100,2);
			$point = $value[$type] - $losePoint;
			$xgDB->query("UPDATE USER SET '$type' = '$point' WHERE name = '$loserName'");
		}
		$totalTransaction += $losePoint;
	}
}
$gainPoint = $totalTransaction/sizeof($total[$feedback]);
for ($i=0; $i < sizeof($total[$feedback]) ; $i++) {
	$winnerName = $total[$feedback][$i];
	$data1 = $xgDB->query("SELECT * FROM USER WHERE name = '$winnerName'");
	foreach ($data1 as $key => $value) {
		$point = $value[$type] + $gainPoint;
		$xgDB->query("UPDATE USER SET '$type' = '$point' WHERE name = '$winnerName'");
	}
}
$xgDB->commit();