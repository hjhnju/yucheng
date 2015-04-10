<?php
class Awards_Logic_Award {

    public function __construct(){
    }

    /**
     * 用户注册时发放奖励
     * @param int $userid
     */
    public function giveRegistAward($userid){
        $userid    = intval($userid);
        if($userid <= 0){
            return false;
        }

        $ticket            = new Awards_Ticket();
        $ticket->userid    = $userid;
        $ticket->type      = Awards_Type_TicketType::CASH;
        $ticket->activity  = 'Awards_Activity_Regist201502';
        $ticket->value     = Awards_Activity_Regist201502::getValue();
        $ticket->validTime = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));//奖券：6个月有效期
        $ticket->extra     = null;
        $ret               = $ticket->save();
        if(!$ret){
            Base_Log::error(array(
                'msg'      => 'Fail create reg award',
                'userid'   => $userid,
            ));
            return false;
        }

        Msg_Api::sendmsg($userid, Msg_Type::AWARDS, array('data'=>30));
        return true;
    }

    /**
     * 当前为给投资者的邀请人发放奖励
     * @param int $userid 投资人
     */
    public function giveInviterAward($userid, $amount){
        $inviterid = User_Api::getInviteridByUserid($userid);

        $ticket            = new Awards_Ticket();
        $ticket->userid    = $inviterid;
        $ticket->type      = Awards_Type_TicketType::CASH;
        $ticket->activity  = 'Awards_Activity_Invite201504';
        $ticket->value     = Awards_Activity_Invite201504::getValue($amount);
        $ticket->validTime = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));
        $ticket->extra     = $userid; //保存被邀请用户ID
        $ret               = $ticket->save();
        if(!$ret){
            Base_Log::error(array(
                'msg'      => 'Fail create inviter award',
                'inviterid'=> $inviterid,
                'userid'   => $userid,
            ));
            return false;
        }
        return true;
    }


    /**
     * 当前为给投资者的邀请人发放奖励
     * @param int $userid 投资人
     */
    public function giveInvestAward($userid, $amount){

        $ticket            = new Awards_Ticket();
        $ticket->userid    = $inviterid;
        $ticket->type      = Awards_Type_TicketType::CASH;
        $ticket->activity  = 'Awards_Activity_Invest201504';
        $ticket->value     = Awards_Activity_Invite201504::getValue($amount);
        $ticket->validTime = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));
        $ticket->extra     = $userid; //保存被邀请用户ID
        $ret               = $ticket->save();
        if(!$ret){
            Base_Log::error(array(
                'msg'      => 'Fail create inviter award',
                'inviterid'=> $inviterid,
                'userid'   => $userid,
            ));
            return false;
        }
        return true;
    }
    
}
