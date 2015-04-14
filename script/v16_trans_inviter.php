<?php
require(dirname(__FILE__) . '/env.inc.php');

$sql = "SELECT userid,inviterid,create_time 
	FROM `xjd`.`awards_invite` ORDER BY id asc";
$id = 100;

$db = Base_Db::getInstance('xjd');
$arrRow = $db->fetchAll($sql);
$arrBinds = array();
foreach ($arrRow as $key => $row) {
	$intTs = strtotime($row['create_time']);
	if($intTs <=0){
		$intTs = strtotime("2015-04-01 00:00:00");
	}
	$newRow = array(
		'id' => $id,
		'userid'=>$row['inviterid'], 
		'invitee'=>$row['userid'],
		'create_time' => $intTs,
		'update_time' => time(),
	);
	$arrBinds[] = $newRow;
	$id = $id + 1;
}

$rs = $db->insertBatch('user_invite', $arrBinds);
var_dump($rs);
