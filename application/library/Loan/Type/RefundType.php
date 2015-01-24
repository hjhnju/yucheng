<?php
/**
 * 还款方式
 * @author jiangsongfang
 *
 */
class Loan_Type_RefundType extends Base_Type {
    /**
     * 等额本息
     * @var integer
     */
    const AVERAGE = 1;
    /**
     * 等额本金
     * @var integer
     */
    const CAPITAL = 2;
    /**
     * 按月付息，到期还本
     * @var integer
     */
    const MONTH_INTEREST = 3;
    
    /**
     * 默认key名
     * @var string
     */
    const DEFAULT_KEYNAME = 'refund_type';
    
    /**
     * 默认类型属性名
     * @var string
     */
    const DEFAULT_FIELD = 'refund_typename';
    
    /**
     * 状态名
     * @var array
     */
    public static $names = array(
        self::AVERAGE => '等额本息',
        self::CAPITAL => '等额本金',
        self::MONTH_INTEREST => '按月付息，到期还本',
    );
    
    /**
     * 获取一段时间内的还款利息
     * @param integer $type 还款方式
     * @param number $amount 本金
     * @param number $rate 利率
     * @param number $duration 时间长度，如果是是等额本息按月计算，到期还本按天计算
     * @return number
     */
    public static function getInterest($type, $amount, $rate, $duration) {
        $rate /= 100;
        if ($type == self::MONTH_INTEREST) {
            $interest = $amount * $rate * $duration / 365;
        } elseif ($type == self::AVERAGE) {
            $b = $rate / 12;
            $a = $amount;

            /*  收益计算公式
             P = A * b% * (1 + b%)^n / ((1 + b%)^n - 1)
             P: 每月还款额
             A: 借款本金
             b: 月利率
             n: 还款总期数
            
             每月月供额=〔贷款本金×月利率×(1＋月利率)＾还款月数〕÷〔(1＋月利率)＾还款月数-1〕
             每月应还利息=贷款本金×月利率×〔(1+月利率)^还款月数-(1+月利率)^(还款月序号-1)〕÷〔(1+月利率)^还款月数-1〕
             每月应还本金=贷款本金×月利率×(1+月利率)^(还款月序号-1)÷〔(1+月利率)^还款月数-1〕
             总利息=还款月数×每月月供额-贷款本金
             */
            $month = ($a * $b * pow(1 + $b, $duration)) / (pow(1 + $b, $duration) - 1);
            $interest = $month * $duration - $amount;
        }
        return round($interest, 2);
    }
}