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
        $loanId   = intval($loanId);
        $transAmt = floatval($transAmt);
        $loanInfo = Loan_Api::getLoanInfo($loanId);

        //借款实际时间（天）, pay_time实际是当前打款时间。这里用full_time代替
        $startTime = intval($loanInfo['full_time']);
        $days      = Loan_Type_Duration::getDays(intval($loanInfo['duration']), $startTime);

        $servFeeRate = $loanInfo['serv_rate'];
        $riskFeeRate = $loanInfo['risk_rate'];
        $mangFeeRate = $loanInfo['mang_rate'];

        $servFee  = self::servFee($servFeeRate, $transAmt);
        $riskFee  = self::riskFee($riskFeeRate, $transAmt, $days);
        $mangFee  = self::mangFee($mangFeeRate, $transAmt, $days);
        $totalFee = $servFee + $riskFee + $mangFee;
        $arrFeeInfo  = array(
            'msg'       => '手续费计算结果',
            'serv_fee'  => $servFee + $mangFee,
            'risk_fee'  => $riskFee,
            'total_fee' => $totalFee,
        );
        Base_Log::notice($arrFeeInfo);
        return $arrFeeInfo;
    }
     
    /**
    * 计算融资服务费
    * @param int riskLevel AAA=10 AA=20 A=30 B=40 C=50
    * @param float transAmt
    * 
    */
    private static function servFee($rate, $transAmt) {
        $rate     = floatval($rate);
        $transAmt = floatval($transAmt);    
        $servFee  = round($transAmt * $rate, 2);
        return $servFee;
    }
     
    /**
     * 计算风险准备金
     * @param float
     */
    private static function riskFee($rate, $transAmt, $days) {
        $rate     = floatval($rate);
        $transAmt = floatval($transAmt);
        $days     = intval($days);
        $riskFee  = round($transAmt * $rate * $days / 365, 2);
        return $riskFee;
    }

    /**
     * 计算账户管理费
     * @return float
     */
    private static function mangFee($rate, $transAmt, $days) {
        $rate      = floatval($rate);
        $transAmt  = floatval($transAmt);
        $days      = intval($days);  
        $mangFee   = round($transAmt * $rate * $days / 365, 2);
        return $mangFee;
    }
}