<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class Base_RetCode {

    //定义错误码：
    const SUCCESS           = 0;//成功

    const NEED_PICTURE      = 101; //需要图片验证码
    const CSRFTOKEN_INVALID = 102; //CSRF验证不通过

    //前端跳转
    const NEED_REDIRECT     = 302;

     //默认错误
    const UNKNOWN_ERROR     = 999; //未知错误

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
    const LOCK_ERROR        = 507; //并发加锁失败

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
        self::NEED_PICTURE      => '需要图片验证码',
        self::CSRFTOKEN_INVALID => '会话token实效，请重新刷新页面',

        self::NEED_REDIRECT     => '前端跳转',
        self::LOCK_ERROR        => '加锁失败',
    );

    /**
     * 获取信息描述
     * @param  int    $exceptionCode 错误码
     * @return string            描述
     */
    public static function getMsg($exceptionCode) {

        if (isset(self::$_arrErrMap[$exceptionCode])) {
            return self::$_arrErrMap[$exceptionCode];
        } elseif(isset(static::$_arrErrMap[$exceptionCode])) {
            return static::$_arrErrMap[$exceptionCode];
        } else {
            return self::$_arrErrMap[self::UNKNOWN_ERROR];
        }
    }
}
