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
     * 消息描述
     * @var array
     */
    protected static $_arrErrMap = array(
        self::NOT_ALLOWED => '抱歉，您无法投此标。',
    );
}