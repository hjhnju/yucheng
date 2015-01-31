<?php 
/**
 * 手续费类
 * AAA=1 AA=2 A=3 B=4 C=5 D=6 E=7 HR=8
 * @author lilu
 * 
 * TODO://手续费根据不同客户可能不同，将费率放到loan信息中更合适
 * @author hejunhua
 */
class Finance_Fee {

    //必须两位小数点
    const MaxTenderRate   = '0.20';
    
    const MaxBorrowerRate = '1.00';
    
    /**
     * finance_service_fee
     * 融资服务费(每笔)
     */
    private static $finance_service_fee = array(
        8 => 0.0030, 
        7 => 0.0030, 
        6 => 0.0030, 
        5 => 0.0030, 
        4 => 0.0030,
        3 => 0.0030,
        2 => 0.0030,
        1 => 0.0030,
    ); 
    
    /**
     * risk_reserve_fee
     * 风险准备金(年化)
     */
    private static $risk_reserve_fee = array(
        8 => 0.0250,
        7 => 0.0250,
        6 => 0.0250,
        5 => 0.0250,
        4 => 0.0200,
        3 => 0.0150,
        2 => 0.0100,
        1 => 0.0080,
    );
    
    /**
     * acc_manage_fee
     * 账户管理费(每月)
     */
    private static $acc_manage_fee = array(
        8 => 0.0035,
        7 => 0.0035,
        6 => 0.0035,
        5 => 0.0035,
        4 => 0.0030,
        3 => 0.0025, 
        2 => 0.0020, 
        1 => 0.0015,
    );
    
    /**
     * total
     * 总计
     */
    private static $total_fee = array(
        8 => 0.0670,
        7 => 0.0670,
        5 => 0.0670,
        4 => 0.0560,
        3 => 0.0450,
        2 => 0.0340,
        1 => 0.0260,
    );

    /**
     * 每笔放款需要的费用
     * @param $loanId 借款id
     * @param $transAmt 每笔放款金额
     * @return  array $arrFeeInfo
     */
    public static function totalFeeInfo($loanId, $transAmt){

        $loanInfo  = Loan_Api::getLoanInfo($loanId);
        //TODO:借款实际时间（天）
        $days      = $loanInfo['duration'];
        $riskLevel = $loanInfo['level'];
        $riskLevel = intval($riskLevel);
        $transAmt  = floatval($transAmt);
        $days      = intval($days);

        $serviceFee = self::serviceFee($riskLevel, $transAmt);
        $riskFee    = self::riskFee($riskLevel, $transAmt, $days);
        $manageFee  = self::manageFee($riskLevel, $transAmt, $days);

        $totalFee = $serviceFee + $riskFee + $manageFee;
        $arrFeeInfo  = array(
            'serviceFee' => sprintf('%.2f', $serviceFee),
            'riskFee'    => sprintf('%.2f', $riskFee),
            'manageFee'  => sprintf('%.2f', $manageFee),
            'totalFee'   => sprintf('%.2f', $totalFee),
        );
        Base_Log::notice(array_merge(array('msg'=>'手续费计算结果'), $arrFeeInfo));
        return $arrFeeInfo;
    }
     
    /**
    * 计算融资服务费
    * @param int riskLevel AAA=10 AA=20 A=30 B=40 C=50
    * @param float transAmt
    * 
    */
    private static function serviceFee($riskLevel,$transAmt) {
        $riskLevel  = intval($riskLevel);
        $transAmt   = floatval($transAmt);    
        $serviceFee = $transAmt * self::$finance_service_fee[$riskLevel];
        return $serviceFee;
    }
     
    /**
     * 计算风险准备金
     * @param int riskLevel 风险等级
     * @param float transAmt 交易金额
     * @param int days 借款天数
     * @param float
     */
    private static function riskFee($riskLevel,$transAmt,$days) {
        $riskLevel = intval($riskLevel);
        $transAmt  = floatval($transAmt);
        $days      = intval($days);
        $dailyRate = floatval(self::$risk_reserve_fee[$riskLevel] / 365);
        $riskFee   = $transAmt * $dailyRate * $days;
        return $riskFee;
    }

    /**
     * 计算账户管理费
     * @param int riskLevel 风险等级
     * @param float transAmt 交易金额
     * @param int days 天数
     * @return float
     */
    private static function manageFee($riskLevel,$transAmt,$days) {
        $riskLevel = intval($riskLevel);
        $transAmt  = floatval($transAmt);
        $days      = intval($days);  
        $dailyRate = floatval(self::$acc_manage_fee[$riskLevel] / 365);
        $manageFee = $transAmt * $dailyRate * $days;
        return $manageFee;
    }
}