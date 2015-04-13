<?php
/**
 * 上线活动：壕，投吧！你投，我就送！
 * 
 * 单笔投资金额满足如下限额要求的，可领取相应的现金奖励。
 * 2000元（含）~1万元（含）, 投资金额的5‰
 * 1万元~5万元（含）, 投资金额的6‰
 * 5万~10万元（含）, 投资金额的7‰
 * 10万元以上, 投资金额的8‰
 * 
 * 投资完后立即发放
 */
class Awards_Activity_Invest201504 extends Awards_Activity_Base {

    public function __construct(){
        $this->name      = "单笔投资奖励";
        $this->startTime = strtotime("2015-03-15 00:00:00");
        $this->endTime   = strtotime("2015-05-15 23:59:59");
        $this->desc      = '单笔投资满足限额即可领取';
    }

    public function isAchieved(Awards_Ticket $objTicket){
        return true;
    }

    /**
     * 根据单笔投资额获取奖励（单位元）
     */
    public function getValue($mixArg){
        //取整用于比较
        $intInvest  = intval($mixArg);
        if($intInvest >= 2000 && $intInvest <= 10000){
            $percent = 0.005;
        }elseif ($intInvest > 10000 && $intInvest <= 50000) {
            $percent = 0.006;
        }elseif ($intInvest > 50000 && $intInvest <=100000) {
            $percent = 0.007;
        }elseif($intInvest > 100000){
            $percent = 0.008;
        }
        return round(floatval($mixArg) * $percent, 2);
        
    }

    /**
     * 为参与活动的用户发放奖券
     */
    public function giveAward($userid, $arrParam = array()){
        if(!isset($arrParam['invest_amount']) || !isset($arrParam['investid'])){
             Base_Log::error(array(
                'msg'    => 'params not ok', 
                'userid' => $userid,
                'params' => $arrParam,
            ));
        }
        $investAmt = floatval($arrParam['invest_amount']);
        $investid  = intval($arrParam['investid']);
        $value     = $this->getValue($investAmt);
        if($this->isActive() && Base_Util_Number::floatIsGtr($value, 0.00)){
            $ticket             = new Awards_Ticket();
            $ticket->userid     = $userid;
            $ticket->ticketType = Awards_Type_TicketType::CASH;
            $ticket->awardType  = Awards_Type_AwardType::INVEST;
            $ticket->activity   = get_class($this);
            $ticket->value      = $value;
            $ticket->validTime  = strtotime(date("Y-m-d 23:59:59", strtotime("+6 month")));
            $ticket->extraid    = $investid;
            $ticket->status     = Awards_Type_TicketStatus::NOT_USED;
            $ret                = $ticket->save();
            if(!$ret){
                Base_Log::error(array(
                    'msg'      => 'Fail create invest award',
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