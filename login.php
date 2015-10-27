<?php
require_once('cls_sqlite.php');
$fileName = "hackVote.sqlite";

$xgDB = new SQLite($fileName);

$xgDB->beginTransaction();
$fail = array(
	"name" => "fail",
);
if (empty($_POST['pass'])&&empty($_POST['name'])){
	echo json_encode($fail);
	return;
}
$name = $_POST['name'];
$datas = $xgDB->getlist("select * from USER where name = '$name'");
if (sizeof($datas) != 1){
    echo json_encode($fail);
    return;
}
foreach($datas as $key => $value) {
	if ($value["pass"]==$_POST["pass"]) {
		echo  json_encode($value);
		return;
	}
}
echo json_encode($fail);