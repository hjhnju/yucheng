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
        $this->desc      = '好友累计投资满1000元可领取';
    }

    public function isAchieved(Awards_Ticket $objTicket){
        //判断受邀人是否已投资满1000
        $invitee = intval($objTicket->extraid);
        $amount = Invest_Api::getUserAmount($invitee, $this->startTime, $this->endTime); 
        if(Base_Util_Number::floatIsGtre($amount, $this->investAmtLimit)){
            return true;
        }
        return false;
    }

    /**
     * 根据好友投资额获取奖励
     */
    public function getValue($mixArg = null){
        return 20;
    }

    /**
     * 为参与活动的用户发放奖券
     */
    public function giveAward($inviterid, $arrParam = array()){
        if(!isset($arrParam['inviteeid'])){
            Base_Log::error(array('msg'=>'no inviteeid', 'inviterid'=>$inviterid));
            return false;
        }
        $inviteeid = intval($arrParam['inviteeid']);
        $value     = $this->getValue();
        if($this->isActive() && Base_Util_Number::floatIsGtr($value, 0.00)){
            $ticket             = new Awards_Ticket();
            $ticket->userid     = $inviterid;
            $ticket->ticketType = Awards_Type_TicketType::CASH;
            $ticket->awardType  = Awards_Type_AwardType::INVITE;
            $ticket->activity   = get_class($this);
            $ticket->value      = $value;
            $ticket->validTime  = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));
            $ticket->extraid    = $inviteeid; //保存被邀请用户ID
            $ticket->status     = Awards_Type_TicketStatus::NOT_FINISH;
            $ret                = $ticket->save();
            if(!$ret){
                Base_Log::error(array(
                    'msg'       => 'Fail create inviter award for Awards_Activity_Invite201502',
                    'inviterid' => $inviterid,
                    'inviteeid' => $inviteeid,
                ));
                return false;
            }
            Msg_Api::sendmsg($inviterid, Msg_Type::AWARDS, array('data'=> $ticket->value));
            return true;
        }
        return false;
    }
}