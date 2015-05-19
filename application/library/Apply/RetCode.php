<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class Apply_RetCode extends Base_RetCode{

    //定义错误码：
    const EMAIL_EXIST            = 1025; //邮箱已存在
  
    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::EMAIL_EXIST           => '邮箱已存在',     
    );

}
