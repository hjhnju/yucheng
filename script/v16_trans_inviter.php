<?php
require(dirname(__FILE__) . '/env.inc.php');

$sql = "SELECT userid,inviterid,create_time 
	FROM `xjd`.`awards_invite` ORDER BY id asc";

$db = Base_Db::getInstance('xjd');
$arrRow = $db->fetchAll($sql);
$arrBinds = array();
foreach ($arrRow as $key => $row) {
	$intTs = strtotime($row['create_time']);
	if($intTs <=0){
		$intTs = 0;
	}
	$newRow = array(
		'userid'=>$row['inviterid'], 
		'invitee'=>$row['userid'],
		'create_time' => $intTs,
	);
	$arrBinds[] = $newRow;
}

$rs = $db->insertBatch('user_invite', $arrBinds);
var_dump($rs);