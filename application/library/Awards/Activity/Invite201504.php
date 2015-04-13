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
        $this->startTime = strtotime("2015-03-15 00:00:00");
        $this->endTime   = strtotime("2015-05-15 23:59:59");
        $this->desc      = '好友投资得0.1%现金奖励';
    }

    public function isAchieved(Awards_Ticket $objTicket){
        //领奖时无特殊达成条件
        return true;
    }

    /**
     * 根据好友投资额获取奖励，单位元
     */
    public function getValue($mixArg){
        //根据单笔投资额判断奖励额度
        $invest  = floatval($mixArg);
        return round($invest * self::PERCENT, 2);
    }

    /**
     * 为参与活动的用户发放奖券
     */
    public function giveAward($inviterid, $arrParam = array()){
        if(!isset($arrParam['invitee_invest_amount']) || !isset($arrParam['inviteeid'])){
            Base_Log::error(array(
                'msg'       =>'params not ok', 
                'inviterid' =>$inviterid,
                'params'    =>$arrParam
            ));
            return false;
        }
        $inviteeid = intval($arrParam['inviteeid']);
        $investAmt = floatval($arrParam['invitee_invest_amount']);
        $value     = $this->getValue($investAmt);
        if($this->isActive() && Base_Util_Number::floatIsGtr($value, 0.00)){
            $ticket             = new Awards_Ticket();
            $ticket->userid     = $inviterid;
            $ticket->ticketType = Awards_Type_TicketType::CASH;
            $ticket->awardType  = Awards_Type_AwardType::INVITE;
            $ticket->activity   = get_class($this);
            $ticket->value      = $value;
            $ticket->validTime  = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));
            $ticket->extraid    = $inviteeid; //保存被邀请用户ID
            $ticket->status     = Awards_Type_TicketStatus::NOT_USED;
            $ret                = $ticket->save();
            if(!$ret){
                Base_Log::error(array(
                    'msg'       => 'Fail create inviter award for Awards_Activity_Invite201504',
                    'inviterid' => $inviterid,
                    'inviteeid' => $inviteeid,
                    'investAmt' => $investAmt,
                ));
                return false;
            }
            Msg_Api::sendmsg($inviterid, Msg_Type::AWARDS, array('data'=> $ticket->value));
            return true;
        }
        return false;
    }
}