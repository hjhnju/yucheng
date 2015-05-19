<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class Apply_RetCode extends Base_RetCode{

    //邮箱已存在
    const EMAIL_EXIST  = 1000; 

    //邮箱格式错误
    const EMAIL_FORMAT = 10001;

    //邮箱为空
    const EMAIL_EMPTY  = 10002;
  
    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::EMAIL_EXIST	=> '邮箱已存在!',     
        self::EMAIL_FORMAT	=> '邮箱格式错误!',
        self::EMAIL_EMPTY	=> '邮箱不能为空!',
    );
}
