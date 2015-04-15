<?php
require(dirname(__FILE__) . '/env.inc.php');
$id = 100;

//转换注册奖励
$sql = "SELECT userid, status, amount, create_time 
	FROM `xjd`.`awards_regist` ORDER BY userid asc";

$arrRow = Base_Db::getInstance('xjd')->fetchAll($sql);
$arrBinds = array();
$activity = new Awards_Activity_Regist201502();
foreach ($arrRow as $key => $row) {
	$intTs = strtotime($row['create_time']);
	if($intTs <=0){
		$intTs = strtotime("2015-04-01 00:00:00");
	}
	//1-未达到, 2-已达到未领取，3-已领取'
	$payTime = 0;
	$status  = Awards_Type_TicketStatus::NOT_FINISH;
	$intSt = intval($row['status']);
	if($intSt === 1){
		$status  = Awards_Type_TicketStatus::NOT_FINISH;
	}elseif (2 === $intSt) {
		$status  = Awards_Type_TicketStatus::NOT_USED;
	}elseif(3 === $intSt){
		$status  = Awards_Type_TicketStatus::EXCHANGED;
		$payTime = $row['create_time'];
	}

	$newRow = array(
		'id' => $id,
		'ticket_type' => Awards_Type_TicketType::CASH, 
		'award_type'=>Awards_Type_AwardType::REGIST,
		'value' => $row['amount'],
		'valid_time' => strtotime(date("Y-m-d 23:59:59", strtotime("+6 month"))),
		'activity' => 'Awards_Activity_Regist201502',
		'userid' => $row['userid'],
		'pay_time' => $payTime,
		'status' => $status,
		'create_time' => $intTs,
		'update_time' => time(),
	);
	$arrBinds[] = $newRow;
	$id = $id + 1;
}

$rs = Base_Db::getInstance('xjd')->insertBatch('awards_ticket', $arrBinds);
echo "插入awards_ticket:" . $rs . PHP_EOL;


//转换邀请奖励
$sql = "SELECT userid, inviterid, status, amount, create_time 
 	FROM `xjd`.`awards_invite` ORDER BY id asc";

$arrRow = Base_Db::getInstance('xjd')->fetchAll($sql);
$arrBinds = array();
$activity = new Awards_Activity_Invite201502();
foreach ($arrRow as $key => $row) {
	$intTs = strtotime($row['create_time']);
	if($intTs <=0){
		$intTs = strtotime("2015-04-01 00:00:00");
	}
	//1-未达到, 2-已达到未领取，3-已领取'
	$payTime = 0;
	$status  = Awards_Type_TicketStatus::NOT_FINISH;
	$intSt = intval($row['status']);
	if($intSt === 1){
		$status  = Awards_Type_TicketStatus::NOT_FINISH;
	}elseif (2 === $intSt) {
		$status  = Awards_Type_TicketStatus::NOT_USED;
	}elseif(3 === $intSt){
		$status  = Awards_Type_TicketStatus::EXCHANGED;
		$payTime = $row['create_time'];
	}

	$newRow = array(
		'id' => $id,
		'ticket_type' => Awards_Type_TicketType::CASH,
		'award_type'=>Awards_Type_AwardType::INVITE,
		'value' => $row['amount'],
		'valid_time' => strtotime(date("Y-m-d 23:59:59", strtotime("+6 month"))),
		'activity' => 'Awards_Activity_Invite201502',
		'userid' => $row['inviterid'],
		'pay_time' => $payTime,
		'extraid' => $row['userid'],//被邀请人
		'status' => $status,
		'create_time' => $intTs,
		'update_time' => time(),
	);
	$arrBinds[] = $newRow;
	$id = $id + 1;
}
$rs = Base_Db::getInstance('xjd')->insertBatch('awards_ticket', $arrBinds);
echo "插入awards_ticket:" . $rs . PHP_EOL;
