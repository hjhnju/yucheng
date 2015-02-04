<?php 
/**
 * 财务类订单状态
 */
class Finance_Order_Status extends Base_Type {

    //订单状态
    CONST INITIALIZE = 0;  //订单初始化
    CONST PROCESSING = 1;  //订单处理中
    CONST FAILED     = 2;  //处理结束，失败
    CONST SUCCESS    = 3;  //处理结束。成功
    
    public static $names = array(
        self::INITIALIZE => '订单初始化',
        self::PROCESSING => '订单处理中',
        self::FAILED     => '处理失败',
        self::SUCCESS    => '处理成功',
    );  

}