<?php
require(dirname(__DIR__) . '/env.inc.php');
$startTime = strtotime("2015-04-15 00:00:00");
$endTime   = strtotime("2015-05-15 23:59:59");
$sql = "SELECT userid, count(invitee) as cnt FROM `xjd`.`user_invite` where create_time>=".$startTime." and create_time<=".$endTime." group by userid order by cnt desc limit 0,200";
$arrRow = Base_Db::getInstance('xjd')->fetchAll($sql);
$arrResult = array();
$fp = fopen("fakeresult.txt","w");
$fpr = fopen("realresult.txt","w");
$fp1 = fopen("fakepaiming.txt","w");
$fp2 = fopen("realpaiming.txt","w");
foreach ($arrRow as $row) {
	/*if($row['cnt'] < 6){
		continue;
	}*/
	$userid = $row['userid'];
	$sql = "SELECT invitee, create_time FROM `xjd`.`user_invite` where userid=".$userid." and create_time<=".$endTime." order by create_time asc";

	$arrRow2 = Base_Db::getInstance('xjd')->fetchAll($sql);
	$cnt = 0;
	$arrIp = array();
	$arrHufu = array();
	$arrInvite = array();
	foreach ($arrRow2 as $row2) {
		$objUser = User_Api::getUserObject($row2['invitee']);
		$info = User_Api::getUserObject($userid);
		
		$huifu = $objUser->huifuid;
		if(!empty($huifu)){
		    $arrIp[] = $objUser->lastip;
		    $arrHufu[] = $objUser->huifuid;
		}
		
		$arrInvite[] = $row2['invitee'];
		$name = $info->name;
		if(empty($name)){
		    $info->name = $info->phone;
		    $infostarname = Base_Util_String::starPhone($info->phone);
		}else{
		    $info->name = $name;
		    $infostarname = Base_Util_String::starUsername($name);
		}

		$name = $objUser->name;
		if(empty($name)){
		    $objUser->name = $objUser->phone;
		    $objUserstarname = Base_Util_String::starPhone($objUser->phone);
		}else{
		    $objUser->name = $name;
		    $objUserstarname = Base_Util_String::starUsername($name);
		}
		$arrOut = array($userid, $info->name,$infostarname, $row2['invitee'],$objUser->name, $objUserstarname, strftime('%Y-%m-%d %H:%M:%S', $row2['create_time']), 
			$objUser->lastip,$objUser->huifuid);
		fwrite($fp,implode(',', $arrOut)."\n");
	}
	
	
	$arrTemp['id'] = $userid; 
	$arrTemp['name'] = $info->name;
	if(empty($arrTemp['name'])){
	    $arrTemp['name'] = $info->phone;
	    $arrTemp['starname'] = Base_Util_String::starPhone($info->phone);
	}else{
	    $arrTemp['starname'] = Base_Util_String::starUsername($info->name);
	}
	$arrTemp['fake'] = count($arrInvite);
	$arrTemp['invite'] = $arrInvite;
	
	$arrIp = array_unique($arrIp);
	$arrHufu = array_unique($arrHufu);
	$arrTemp['huifu'] = count($arrHufu);
	fwrite($fp1,$userid.",".$arrTemp['name'].",".$arrTemp['starname'].",".count($arrInvite).",".count($arrIp).",".count($arrHufu)."\n");

	
	$arrTemp['real'] = count($arrIp);
	$arrResult[] = $arrTemp;
}

$counts = array();
foreach ($arrResult as $user) {
    $counts[] = $user['real'];
}
array_multisort($counts, SORT_DESC , $arrResult);
foreach ($arrResult as $val){
    $arrIpTemp = array();
    foreach ($val['invite'] as $data){
        $objUser = User_Api::getUserObject($data);
        $invite = new User_Object_Invite();
        $invite->fetch(array('userid'=>$val['id'],'invitee'=>$data));
        if(!in_array($objUser->lastip,$arrIpTemp)){
           $arrIpTemp[] = $objUser->lastip;
           $name = $objUser->name;
           if(empty($name)){
               $objUser->name = $objUser->phone;
               $starname = Base_Util_String::starPhone($objUser->phone);
           }else{
               $objUser->name = $name;
               $starname = Base_Util_String::starUsername($name);
           }
           fwrite($fpr, $val['id'].",".$val['name'].",".$val['starname'].",".$data.",".$objUser->name.",".$starname.",".strftime('%Y-%m-%d %H:%M:%S',$invite->createTime).",".$objUser->lastip.",".$objUser->huifuid."\n");
        }
    }
    fwrite($fp2,$val['id'].",".$val['name'].",".$val['starname'].",".$val['fake'].",".$val['real'].",".$val['huifu']."\n");
}
fclose($fp);
fclose($fpr);
fclose($fp1);
fclose($fp2);