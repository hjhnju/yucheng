<?php
require(dirname(__FILE__) . '/../env.inc.php');

//读20150415_award.txt表,按手机号获取用户
$activity = new Awards_Activity_Invite20150415();
$bol = $activity->giveAward($userid);

