<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class Apply_RetCode extends Base_RetCode{

    //邮箱已存在
    const EMAIL_EXIST  = 1000; 

    //邮箱格式错误
    const EMAIL_FORMAT = 1001;

    //邮箱为空
    const EMAIL_EMPTY  = 1002;
    
    //学校保存数据格式错误
    const SCHOOL_PARAM_ERROR  = 1003;

    //股权结构保存数据格式错误
    const STOCK_PARAM_ERROR   = 1004;

    //个人数据格式错误
    const PERSONAL_PARAM_ERROR= 1005;

    //身份证号错误
    const ID_CARD_WRONG = 1006;

    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::EMAIL_EXIST	=> '邮箱已存在!',     
        self::EMAIL_FORMAT	=> '邮箱格式错误!',
        self::EMAIL_EMPTY	=> '邮箱不能为空!',
        self::SCHOOL_PARAM_ERROR    => '邮箱已存在!',     
        self::STOCK_PARAM_ERROR     => '邮箱格式错误!',
        self::PERSONAL_PARAM_ERROR  => '邮箱不能为空!',
        self::ID_CARD_WRONG         => '身份证号格式错误!',
    );
}
