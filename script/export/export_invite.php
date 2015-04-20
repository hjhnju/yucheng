<?php
require(dirname(__FILE__) . '/env.inc.php');
$id = 100;

$sql = "SELECT userid, count(invitee) as cnt 
	FROM `xjd`.`user_invite` where create_time>=1429027200 group by userid order by cnt desc limit 0,600";

$arrRow = Base_Db::getInstance('xjd')->fetchAll($sql);
foreach ($arrRow as $row) {
	if($row['cnt'] < 6){
		continue;
	}
	$userid = $row['userid'];
	$sql = "SELECT invitee, create_time FROM `xjd`.`user_invite` where userid=".$userid." order by create_time asc";

	$arrRow2 = Base_Db::getInstance('xjd')->fetchAll($sql);
	$cnt = 0;
	foreach ($arrRow2 as $row2) {
		$cnt = $cnt + 1;
		if($cnt === 6){
			$lastTime = $row2['create_time'];
		}
		$arrOut = array($userid, $row2['invitee'], strftime('%Y-%m-%d %H:%M:%S', $row2['create_time']), 
			strftime('%Y-%m-%d %H:%M:%S', $lastTime));

		$objUser = User_Api::getUserObject($userid);
		echo implode(',', $arrOut);
	}
}


	