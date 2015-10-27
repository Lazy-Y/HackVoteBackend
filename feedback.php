<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
$arr=array();
if (empty($_POST['id'])){
	$arr['status'] = 'false';
	$arr['errMsg'] = 'user name is empty';
	echo json_encode($arr);
	return;
}
$id = $_POST['id'];
$datas = $xgDB->query("SELECT * FROM QUESTION WHERE id = '$id'");
$arr['status'] = 'true';
$arr['errMsg'] = '';
$array = array();
foreach($datas as $key => $value) {
   array_push($array, $value);
   $select = explode("§", $value['selected']);
   $opt = explode("§", $value['options']);
   $votes = array();
   for($i = 0; $i < sizeof($opt); $i++){
   		array_push($votes, '0');
   }
   foreach ($select as $key1 => $value1) {
   		if (empty($value1)){
   			continue;
   		}
   		$votes[$value1-1]+=1;
   }
   foreach ($opt as $key1 => $value1) {
   		$opt[$key1] = $value1. ", " . $votes[$key1]." votes";
   }
   $value['options'] = implode('§', $opt);
   $arr += $value;
}
if (empty($array)){
	$arr['empty'] = 'true';
	echo json_encode($arr);
	return;
}
else $arr['empty'] = 'false';
echo json_encode($arr);