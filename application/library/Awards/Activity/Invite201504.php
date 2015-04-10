<?php
/**
 * 活动期间有在平台投资的，推荐人可获得所有被推荐用户投资金额总额的0.1%的现金奖励
 * 
 * 奖励在被推荐用户投资后发放给推荐人
 */ 
class Awards_Activity_Invite201504 extends Awards_Activity_Base {

    CONST PERCENT = 0.001;

    public function __construct(){
        $this->name      = "邀请好友单笔投资奖励";
        $this->startTime = strtotime("2015-04-15 00:00:00");
        $this->endTime   = strtotime("2015-05-15 23:59:59");
    }

    public function isAchieved(Awards_Ticket $objTicket){
        //领奖时无特殊达成条件
        return true
    }

    /**
     * 根据好友投资额获取奖励，单位元
     */
    public static function getValue($mixArg){
        //根据单笔投资额判断奖励额度
        $invest  = floatval($mixArg);
        return round($invest * self::PERCENT, 2);
    }
}