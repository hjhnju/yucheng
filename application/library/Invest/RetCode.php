<?php
/**
 * 投资返回的错误码
 * @author jiangsongfang
 *
 */
class Invest_RetCode extends Base_RetCode {
    /**
     * @var 不允许投标
     */
    const NOT_ALLOWED = 1025;
    /**
     * @var 投标金额不正确
     */
    const AMOUNT_ERROR = 1026;
    /**
     * @var 用户余额不足
     */
    const AMOUNT_NOTENOUGH = 1027;
    
    /**
     * 消息描述
     * @var array
     */
    protected static $_arrErrMap = array(
        self::NOT_ALLOWED => '抱歉，您无法投此标',
        self::AMOUNT_ERROR => '投标金额不正确',
        self::AMOUNT_NOTENOUGH => '用户余额不足',
    );
}