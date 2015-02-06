<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如Account/RetCode.php
 * 各模块业务状态码自定义，范围(1024, 65535]
 */
class Finance_RetCode extends Base_RetCode {

    
    //定义错误码:
    CONST REQUEST_API_ERROR     = 1101;//请求汇付API出错
    CONST NOTUSERCARD           = 1102;//该用户无此卡
    CONST NOTBINDANYCARD        = 1103;//用户没用绑定任何一张卡
    
    CONST CANNOT_DEL_DEFALTCARD = 1104;//默认取现卡不可以删除
    
    CONST HUIFU_RETURN_ERROR    = 1105;//汇付返回参数错误
    CONST RECEIVE_AWARDS_FAIL   = 1106;//领取奖励失败
    
    CONST ADD_BIDINFO_FAIL      = 1107;//录入投标信息失败
    
    CONST CAN_NOT_REC_AWARD     = 1108;//没有领奖资格

    /* 消息函数
     * @var array
    */
    protected static $_arrErrMap = array(
        self::REQUEST_API_ERROR     => '请求API出错',
        self::NOTUSERCARD           => '该用户无此卡',
        self::NOTBINDANYCARD        => '用户没用绑定任何一张卡',
        self::CANNOT_DEL_DEFALTCARD => '默认取现卡不可以删除',
        self::HUIFU_RETURN_ERROR    => '汇付返回参数错误',
        self::RECEIVE_AWARDS_FAIL   => '领取奖励失败',
        self::ADD_BIDINFO_FAIL      => '录入投标信息失败',
    	self::CAN_NOT_REC_AWARD     => '该用户不能领奖',
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