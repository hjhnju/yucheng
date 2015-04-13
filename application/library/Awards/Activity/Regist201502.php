<?php
class Awards_Activity_Regist201502 extends Awards_Activity_Base {

    protected $investAmtLimit;

    public function __construct(){
        $this->name      = "新手注册奖励";
        $this->startTime = strtotime("2015-02-01 00:00:00");
        $this->endTime   = strtotime("2015-05-15 23:59:59");
        //累计投资达成底限
        $this->investAmtLimit = 1000;
        $this->desc      = '活动期累计投资1000元可领取';
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

    public function getValue($mixArg = null){
        return 30;
    }

    /**
     * 为参与活动的用户发放奖券
     */
    public function giveAward($userid, $arrParam = array()){
        $value = $this->getValue();
        if($this->isActive() && Base_Util_Number::floatIsGtr($value, 0.00)){
            $ticket             = new Awards_Ticket();
            $ticket->userid     = $userid;
            $ticket->ticketType = Awards_Type_TicketType::CASH;
            $ticket->awardType  = Awards_Type_AwardType::REGIST;
            $ticket->activity   = get_class($this);
            $ticket->value      = $value;
            $ticket->validTime  = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));//奖券：6个月有效期
            $ticket->extraid    = null;
            $ticket->status     = Awards_Type_TicketStatus::NOT_FINISH;
            $ret                = $ticket->save();
            if(!$ret){
                Base_Log::error(array(
                    'msg'      => 'Fail create reg award',
                    'userid'   => $userid,
                ));
                return false;
            }
            Msg_Api::sendmsg($userid, Msg_Type::AWARDS, array('data'=> $ticket->value));
            return true;
        }
        return false;
    }
}