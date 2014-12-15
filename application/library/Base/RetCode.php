<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class Base_RetCode {

    //定义错误码：
    const SUCCESS           = 0;//成功

     //默认错误
    const UNKNOWN_ERROR     = 99; //未知错误

    //验证会话
    const SESSION_INVALID   = 410; //会话无效
    const SESSION_DENY      = 411; //非法请求
    const SESSION_NOT_LOGIN = 412; //未登录

    //其他错误
    const DB_ERROR          = 501; //数据库操作错误
    const PARAM_ERROR       = 502; //接口参数错误
    const NOT_FINISHED      = 503; //功能未实现
    const DATA_NULL         = 504; //数据为空
    const SERVICE_DEGRADED  = 505; //服务降级
    const CONFIG_FAIL       = 506; //配置错误

    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::SUCCESS           => '成功',
        self::UNKNOWN_ERROR     => '未知错误',

        self::SESSION_INVALID   => '会话无效',
        self::SESSION_DENY      => '非法请求',
        self::SESSION_NOT_LOGIN => '未登录',

        self::DB_ERROR          => '数据库操作错误',
        self::PARAM_ERROR       => '接口参数错误',
        self::NOT_FINISHED      => '功能未实现',
        self::DATA_NULL         => '数据为空',
        self::SERVICE_DEGRADED  => '服务降级',
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
