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
class Awards_Activity_Invest201502 extends Awards_Activity_Base {

    protected $investAmtLimit;

    public function __construct(){
        $this->name      = "邀请好友投资奖励";
        $this->startTime = strtotime("2015-02-01 00:00:00");
        $this->endTime   = strtotime("2015-04-15 23:59:59");
        //累计投资达成底限
        $this->investAmtLimit = 1000;
    }

    public function isAchieved(Awards_Ticket $objTicket){
        return true;
    }

    /**
     * 根据单笔投资额获取奖励（单位元）
     */
    public static function getValue($mixArg){
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
}