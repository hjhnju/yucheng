<?php 
/**
 * 财务类订单类型/状态
 */
class Finance_Order_Type  extends Base_Type{

    //订单类型
    CONST ALL              = 1;  //所有类型
    CONST NETSAVE          = 2;  //充值
    CONST CASH             = 3;  //提现
    CONST TENDERFREEZE     = 4;  //投标资金冻结
    CONST TENDERCANCEL     = 5;  //投标撤销
    CONST LOANS            = 6;  //满标打款
    CONST REPAYMENT        = 7;  //还款付息
    CONST TRANSFER         = 8;  //商户用自动扣款转账    
    CONST RECE_AWD         = 9;  //领取奖励
    CONST MONEY_BACK       = 10; //退款
    CONST MERCASH          = 11; //商户代取现
    CONST USRUNFREEZE      = 12; //投标资金解冻
    CONST UNKNOWN_TYPE     = 13; //未知类型
    CONST REFUNDED         = 14; //回款本息 vs 还款付息
    CONST LOANPAYED        = 15; //满标入款 vs 满标打款

    public static $names = array(
        self::ALL              => '所有类型',
        self::NETSAVE          => '充值',
        self::CASH             => '提现',
        self::TENDERFREEZE     => '投标资金冻结',
        self::TENDERCANCEL     => '投标撤销',
        self::LOANS            => '满标打款',
        self::REPAYMENT        => '还款付息', 
        self::TRANSFER         => '商户用自动扣款转账',
        self::MERCASH          => '商户代取现',
        self::RECE_AWD         => '领取奖励',
        self::MONEY_BACK       => '退款',
        self::UNKNOWN_TYPE     => '未知类型', 
        self::USRUNFREEZE      => '投标资金解冻',  
        self::REFUNDED         => '回款本息', 
        self::LOANPAYED        => '满标入款', 
    );

    protected static $charMap = array(
        self::ALL              => '',
        self::NETSAVE          => '+',
        self::CASH             => '-',
        self::TENDERFREEZE     => '-',
        self::TENDERCANCEL     => '+',
        self::LOANS            => '-',
        self::REPAYMENT        => '-', 
        self::TRANSFER         => '',
        self::MERCASH          => '',
        self::RECE_AWD         => '+',
        self::MONEY_BACK       => '+',
        self::UNKNOWN_TYPE     => '', 
        self::USRUNFREEZE      => '+',  
        self::REFUNDED         => '+',  
        self::LOANPAYED        => '+',  
    );

    public static function getPlusMinusChar($type){
       return isset(self::$charMap[$type]) ? self::$charMap[$type] : '';       
    }
}
