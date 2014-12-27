<?php
/**
 * 异常码与描述定义类
* 各模块可以在library中自定义错误码，如Msg/RetCode.php
*/

class Msg_RetCode extends Base_RetCode{

    //定义错误码：
    const USERNAME_EXIST             = 1025; //用户 名已存在
    


    const MSG_READ        = 1;  //已读消息状态
    const MSG_UNREAD      = 2;  //未读消息状态
    const MSG_REMOVE      = -1; //消息删除
    const MSG_ALL         = 0; //所有消息


    /* 消息函数
     * @var array
    */
    protected static $_arrErrMap = array(
        self::MSG_READ           => '用户名已存在',

    );

    /**
     * 获取信息描述
     * @param  int    $exceptionCode 错误码
     * @return string            描述
    */
    public static function getMsg($exceptionCode) {

        if (isset(self::$_arrErrMap[$exceptionCode])) {
            return self::$_arrErrMap[$exceptionCode];
        } else {
            return self::$_arrErrMap[self::UNKNOWN_ERROR];
        }
    }
}
