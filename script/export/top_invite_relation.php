<?php
require(dirname(__FILE__) . '/env.inc.php');

$sql = "SELECT userid, invitee, create_time 
        FROM `xjd`.`user_invite` where create_time>=1429027200 order by create_time asc";

$arrRow    = Base_Db::getInstance('xjd')->fetchAll($sql);
$arrCnter  = array();
$arrLastTs = array();
$arrInvs   = array();
foreach ($arrRow as $row) {
        $userid  = intval($row['userid']);
        $invitee = intval($row['invitee']);
        $infos = User_Api::getInfos(array($userid,$invitee));
        if(!isset($infos[$userid]['huifuid']) || !isset($infos[$invitee]['huifuid'])){
                continue;
        }
        $ts = intval($row['create_time']);
        if(!isset($arrCnter[$userid])){
                $arrCnter[$userid] = 0;
                $arrLastTs[$userid] = strtotime("2020-10-10 10:10:10");
                $arrInvs[$userid] = array();
        }
        $arrCnter[$userid] += 1;
        $arrInvs[$userid][] = $invitee;
        if($arrCnter[$userid] === 6){
                if($ts < $arrLastTs[$userid]){
                        $arrLastTs[$userid] = $ts;
                }
                continue;
        }
}

asort($arrLastTs);
//var_dump($arrLastTs);die;

$cnt = 0;
foreach ($arrLastTs as $userid => $inviteTs) {
        $cnt += 1;
        $arrUid = $arrInvs[$userid];
        $arrUid[] = $userid; 
        $sql = 'SELECT t1.`userid`, t1.`name`, t1.`phone`, t1.`huifuid`, t1.`lastip`, t1.`create_time`,
                t2.`realname` FROM user_login t1 left join user_info t2 on t1.userid=t2.userid 
                where t1.userid IN ('. implode(',', $arrUid) . ')';

        $arrRow = Base_Db::getInstance('xjd')->fetchAll($sql);
        foreach ($arrRow as $row) {
                $row['name'] = isset($row['name']) ? $row['name'] : $row['phone'];
                $arrUinfo[$row['userid']] = $row;
        }
	//var_dump($arrUinfo);die;

        foreach ($arrInvs[$userid] as $invitee) {
                $invinfo = $arrUinfo[$invitee];
                $uinfo = $arrUinfo[$userid];
                echo $cnt .",". $uinfo['name'] .",". $uinfo['realname'] .",". $uinfo['phone'].",".$uinfo['lastip'].",". strftime('%Y-%m-%d %H:%M:%S',$uinfo['create_time']). ",". 
                       $invinfo['name'] .",". $invinfo['realname'] .",". $invinfo['phone'] .",".$invinfo['lastip'].",". strftime('%Y-%m-%d %H:%M:%S',$invinfo['create_time'])."\n";
        }
	if($cnt === 250){
		break;
	}
}


