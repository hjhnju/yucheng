<?php
/**
 * 异常码与描述定义类
 * 通用状态码由Base_RetCode中定义，范围[0~1024], 各模块禁止使用1024内错误码
 * 各模块业务状态码自定义，范围(1024, 65535], 如：library/User/RetCode.php, 继承library/Base/RetCode.php
 */

class Base_RetCode {

    //定义错误码：
    const SUCCESS           = 0;//成功

     //默认错误
    const UNKNOWN_ERROR     = 99; //未知错误

    //需要图片验证码验证
    const VARIFY_IMGCODE    = 101; //图片验证码

    //重定向
    const REDIRECT          = 302; //前端重定向

    //验证会话
    const SESSION_INVALID   = 601; //会话无效
    const SESSION_DENY      = 602; //非法请求
    const SESSION_NOT_LOGIN = 603; //未登录

    //其他错误
    const DB_ERROR          = 701; //数据库操作错误
    const PARAM_ERROR       = 702; //接口参数错误
    const NOT_FINISHED      = 703; //功能未实现
    const DATA_NULL         = 704; //数据为空
    const SERVICE_DEGRADED  = 705; //服务降级
    const CONFIG_FAIL       = 706; //配置错误

    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::SUCCESS           => '成功',
        self::UNKNOWN_ERROR     => '未知错误',
        self::REDIRECT          => '前端重定向',

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
