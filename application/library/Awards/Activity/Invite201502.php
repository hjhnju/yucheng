<?php
/**
 * 邀请一位好友注册成功，好友累计投资满1000元，邀请人即可获得20元现金奖励。
 * 该奖券在邀请后立即发放，投资满额才能兑换
 */
class Awards_Activity_Invite201502 extends Awards_Activity_Base {

    protected $investAmtLimit;

    public function __construct(){
        $this->name      = "邀请好友投资奖励";
        $this->startTime = strtotime("2015-02-01 00:00:00");
        $this->endTime   = strtotime("2015-04-15 23:59:59");
        //累计投资达成底限
        $this->investAmtLimit = 1000;
    }

    public function isAchieved(Awards_Ticket $objTicket){
        //判断受邀人是否已投资满1000
        $invitee = intval($objTicket->extra);
        $amount = Invest_Api::getUserAmount($invitee, $this->startTime, $this->endTime); 
        if(Base_Util_Number::floatIsGtre($amount, $this->investAmtLimit)){
            return true;
        }
        return false;
    }

    /**
     * 根据好友投资额获取奖励
     */
    public static function getValue($mixArg = null){
        return 20;
    }
}