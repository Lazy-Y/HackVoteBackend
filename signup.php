<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";
$xgDB = new SQLite($fileName);
$xgDB->beginTransaction();
if (empty($_POST['pass'])&&empty($_POST['name'])){
	echo json_encode(array("name" => "fail"));
	return;
}
$name = $_POST['name'];
$pass = $_POST['pass'];
$datas = $xgDB->query("SELECT * FROM USER WHERE name = '$name'");
$count = 0;
foreach($datas as $key => $value){
    $count+=1;
}
if ($count > 0) {
	echo json_encode(array("name" => "exist"));
    return;
}
$arr=$xgDB->query("INSERT INTO USER (name,pass) values('$name', '$pass')");
$datas = $xgDB->query("select * from USER where name = '$name'");
$xgDB->commit();
foreach($datas as $key => $value){
    echo json_encode($value);
}