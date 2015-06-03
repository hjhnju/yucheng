<?php
require(dirname(__DIR__) . '/env.inc.php');

$startTs = strtotime("2015-04-01 00:00:00");
$endTs = strtotime("2015-04-17 14:00:00");
$sql = "SELECT t1.userid, t2.realname, t2.certificate_content, t1.name, t1.phone, t1.create_time, t1.huifuid, t1.lastip
FROM `xjd`.`user_login` t1 
left join user_info t2 ON t1.userid=t2.userid 
where t1.userid>=10000 and t1.create_time>=$startTs and t1.create_time<$endTs
order by t1.userid asc
";
//真实姓名，身份证，用户名，手机，注册时间，是否开通汇付，最近ip，邀请人数，邀请人姓名，邀请人手机，投资金额

$arrRow    = Base_Db::getInstance('xjd')->fetchAll($sql);
foreach ($arrRow as $row) {
	$userid  = intval($row['userid']);
	$ts = intval($row['create_time']);
	$list = new User_List_Invite();
	$list->setFilter(array('userid'=>$userid));
	$row['inviters'] = $list->countAll();
	$invite = new User_Object_Invite(array('invitee'=>$userid));
	$inviterid = isset($invite->userid)? $invite->userid : 0;
	if($inviterid>0){
		$user = User_Api::getUserObject($inviterid);
		$row['inviter_realname'] = $user->realname;
		$row['inviter_phone'] = $user->phone;
	}else{
		$row['inviter_realname'] = '';
		$row['inviter_phone'] = ''; 
	}
	$row['amount'] = (float)Invest_Api::getUserAmount($userid);
	$row['huifuid'] = isset($row['huifuid'])?'yes':'no';
	$row['create_time'] = strftime("%Y-%m-%d %H:%M:%S", $row['create_time']);
	echo implode(',', $row) . "\n";
}

