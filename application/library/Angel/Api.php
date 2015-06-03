<?php
/**
 * 对外的API接口
 */
class Angel_Api {
    /**
     * 获取标的天使信息
     * @param integer $userid
     * @param integer $investId
     * @return array
     */
    public static function getAngel($userid,$investId){
        $objShare = new Invest_Object_Share();
        $objShare->fetch(array('from_userid'=>$userid,'invest_id'=>$investId));
        if(!empty($objShare->id)){
            $objInvest = new Invest_Object_Invest();
            $objInvest->fetch(array('id'=>$investId,'user_id'=>$userid));
            $selfmoney = self::getIncome($objInvest->loanId, $objInvest->amount, $objInvest->interest-$objShare->rate,$objInvest->createTime);
            $objUser  = User_Api::getUserObject($objShare->toUserid);
            return array(
                'angelrate'  => $objShare->rate,
                'angelmoney' => $objShare->income,
                'selfrate'   => $objInvest->interest-$objShare->rate,
                'selfmoney'  => $selfmoney,
                'headurl'    => $objUser->headurl,
            );
        }
        return array();
    }
    
    /**
     * 获取收益
     * @param integer $proId
     * @param integer $transAmt
     * @param float $rate
     * @param integer $time
     * @return float
     */
    public static function getIncome($proId,$transAmt,$rate,$time){
        $loan = Loan_Api::getLoanInfo($proId);
        $type = $loan['refund_type'];
        $start          = $time - 1;
        $capital_refund = 0;
        if($type === Loan_Type_RefundType::MONTH_INTEREST || $loan['duration']<30){
            $date = new DateTime();
            $date->setTimestamp($time);
            if($loan['duration']>= 30){
                $periods = ceil($loan['duration'] / 30);
                $date->modify('+'.$periods.' month');
            }else{
                $date->modify('+'.$loan['duration'].' day');
            }
            $promise  = $date->getTimestamp() - 1;
            $days     = ($promise - $start) / 3600 / 24;
            $income = Invest_Api::getInterestByDay($transAmt, $rate, $days);
        }elseif($type === Loan_Type_RefundType::AVERAGE){
            $date = new DateTime();
            $date->setTimestamp($time);
            $periods = ceil($loan['duration'] / 30);
            $start = $date->getTimestamp() - 1;
            $b = $rate/100/12;
            $a = $transAmt;
            $date->modify('+1month');
            $promise = $date->getTimestamp() - 1;
            $days    = ($promise - $start) / 3600 / 24;
            $income  = $a * $b * (pow(1 + $b, $periods)) / (pow(1 + $b, $periods) - 1)-$a/$periods;
        }
        $income = number_format($income, 2, '.', '');
        return $income;
    }
}
