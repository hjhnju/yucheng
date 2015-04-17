<?php
require(dirname(__FILE__) . '/env.inc.php');
//读20150415_award.txt表,按手机号获取用户
class Save_Awards extends Awards_Activity_Invite20150415{
    
}
$activity = new Save_Awards();
$arrData = file('20150415_award.txt');
$arrParam = array();
foreach ($arrData as $val){
    $arrTemp = explode("\t",$val);
    $user = new User_Object_Login();
    $user->fetch(array('phone'=>$arrTemp[3]));
    if(!empty($user->userid)){
        $arrParam = array(
        	'id'      => $arrTemp[0],
            'name'    => $arrTemp[7],
            'address' => $arrTemp[6],
        );
        $bol = $activity->giveAward($user->userid, $arrParam);
        if(!$bol){
            Base_Log::notice('Store award failed,userid is:'.$arrTemp[3]);
        }
    }
}


