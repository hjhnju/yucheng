<?php
require(dirname(__DIR__) . '/env.inc.php');

$sql = "SELECT userid, invitee, create_time 
	FROM `xjd`.`user_invite` where create_time>=1429027200 order by create_time asc";

$arrRow    = Base_Db::getInstance('xjd')->fetchAll($sql);
$arrCnter  = array();
$arrLastTs = array();
foreach ($arrRow as $row) {
	$userid  = intval($row['userid']);
	$invitee = intval($row['invitee']);
	/*$infos = User_Api::getInfos(array($userid,$invitee));
	if(!isset($infos[$userid]['huifuid']) || !isset($infos[$invitee]['huifuid'])){
			continue;
	}*/
	$ts = intval($row['create_time']);
	if(!isset($arrCnter[$userid])){
		$arrCnter[$userid] = 0;
		$arrLastTs[$userid] = strtotime("2020-10-10 10:10:10");
	}
	$arrCnter[$userid] += 1;
	if($arrCnter[$userid] === 6){
		if($ts < $arrLastTs[$userid]){
			$arrLastTs[$userid] = $ts;
		}
		continue;
	}
}
$arrLastTs[10229] = 1429083902;

asort($arrLastTs);
//var_dump($arrLastTs);die;

$cnt = 0;
foreach ($arrLastTs as $userid => $inviteTs) {
	$inviteTime = strftime('%Y-%m-%d %H:%M:%S', $inviteTs);
	//$info = User_Api::getInfos(array($userid));
	//$phone = $info[$userid]['phone'];
	//$username = isset($info[$userid]['name']) ? $info[$userid]['name'] : $phone;
	$objUser = User_Api::getUserObject($userid);
	$phone = $objUser->phone;
	$username = $objUser->name ? $objUser->name : $objUser->phone;
        $realname = $objUser->realname;
	$lastip = $objUser->lastip;
	$total = $arrCnter[$userid];
	if(!in_array($phone,array(13980458921,18073099877,18507308997,13507373063,13869678108,18364690301,15339930407,18764631341,18655164083,13113967363,15613242292,18970042092,18979902512,15627367171,15680160486,15112165605,15923831433,15587744707,15175290212,13485358901,18843227666,15067381359,18502918038,13177240341,13583679151,15624106522,15621615961,15662534309,18941141628,13873513255,13581159267,15261806264))){
		$cnt += 1;
		echo  $cnt.",".$username .",".$realname . "," . $phone . "," . $inviteTime.",".$total .",".$lastip."\r\n";
		//echo $username.",".$phone.",".$inviteTs. ",".$inviteTime."\n";
		/*
		echo "<tr>\n";
		echo "<td>";
		echo $cnt;
         	echo "</td>";
		echo "<td>";
		echo Base_Util_String::starUsername($username);
         	echo "</td>";
		echo "<td>";
		echo Base_Util_String::starPhone($phone);
         	echo "</td>";
		echo "<td>";
		echo $inviteTime;
         	echo "</td></tr>\n";
		*/
	}

	if($cnt === 500){
		break;
	}
}

