<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class Angel_RetCode extends Base_RetCode{

    //定义错误码：
    const ANGEL_CODE_WRONG      = 1025;//天使码错误
    
    const ANGEL_EXSIT           = 1026;//天使已经存在
    
    
    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::ANGEL_CODE_WRONG           => '天使码错误',  
        self::ANGEL_EXSIT           => '天使已经存在',
    );

}
