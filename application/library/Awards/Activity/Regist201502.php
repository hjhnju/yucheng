<?php
class Awards_Activity_Regist201502 extends Awards_Activity_Base {

    protected $investAmtLimit;

    public function __construct(){
        $this->name      = "新手注册奖励";
        $this->startTime = strtotime("2015-02-01 00:00:00");
        $this->endTime   = strtotime("2015-05-15 23:59:59");
        //累计投资达成底限
        $this->investAmtLimit = 1000;
    }

    /**
     * 新手注册在活动时间内投资满1000元才能领取奖励
     */
    public function isAchieved(Awards_Ticket $objTicket){
        //该用户在活动期内投资总额
        $userid = intval($objTicket->userid);
        $amount = Invest_Api::getUserAmount($userid, $this->startTime, $this->endTime);
        if(Base_Util_Number::floatIsGtre($amount, $this->investAmtLimit)){
            return true;
        }
        return false;
    }

    public static function getValue($mixArg = null){
        return 30;
    }
}