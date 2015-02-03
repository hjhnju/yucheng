<?php
/**
 * 异常码与描述定义类
 * 各模块可以在library中自定义错误码，如User/RetCode.php
 */

class User_RetCode extends Base_RetCode{

    //定义错误码：
    const USERNAME_EXIST            = 1025; //用户名已存在
    const USERNAME_SYNTEX_ERROR     = 1026; //用户名语法错误
    
    const USERPHONE_EXIST           = 1027; //手机号已存在
    const PHONE_FORMAT_ERROR        = 1028; //手机号格式错误
    
    const GETVERICODE_FAIL          = 1029; //获取短信验证码失败
    const VERICODE_WRONG            = 1030; //短信验证码不正确
    
    const INVITER_NOT_EXIST         = 1031; //推荐人不存在
    const INVITER_SNYTEX_ERROR      = 1032; //推荐人拼写语法错误
    
    const REGIST_FAIL               = 1033; //注册失败
    
    const USER_NOT_EXSIT            = 1034; //无效的用户
    
    const USER_NAME_NOTEXIT         = 1035; //用户名错误
    
    const USER_NAME_OR_PASSWD_ERROR = 1036; //用户名或密码错误
    
    const USER_PASSWD_ERROR         = 1037; //用户密码格式错误
    
    const IMAGE_CODE_WRONG          = 1040; //图片验证码错误
    
    const GET_AUTHCODE_FAIL         = 1041; //第三方登录授权出错
    
    const UNBOUND                   = 1042; //用户未绑定第三方账号
    
    const ORIGIN_PASSWD_WRONG       = 1043; //原密码错误
    const MODIFY_PWD_FAIL           = 1044; //保存密码错误

    const BINDING_FAIL              = 1045; //绑定三方账号出错

    const GET_OPENID_FAIL           = 1055; //获取openid失败

    const SIGN_OUT_FIAL             = 1056; //登出失败
    
    const USER_NAME_OR_PHONE_ERROR  = 1057; //用户名或手机号错误
    
    const SAVE_PASSWD_WRONG         = 1058; //保存用户密码错误
    
    const USER_PHONE_NOTEXIT        = 1059; //手机号不存在
    
    const PHONE_OR_PASSWD_ERROR     = 1060; //手机号或密码错误
    
    const USER_EMAIL_NOTEXIT        = 1061; //邮箱不存在
    
    const USER_EMAIL_FORMAT_ERROR   = 1062; //邮箱格式错误
    
    const EMAIL_OR_PASSWD_ERROR     = 1063; //邮箱或密码错误
    
    
    
    /* 消息函数
     * @var array
     */
    protected static $_arrErrMap = array(
        self::USERNAME_EXIST           => '用户名已存在',
        self::USERNAME_SYNTEX_ERROR    => '用户名格式错误',
        self::USERPHONE_EXIST          => '手机号已存在',
        self::PHONE_FORMAT_ERROR       => '手机号格式有误',
        self::GETVERICODE_FAIL         => '获取短信验证码失败',
        self::VERICODE_WRONG           => '短信验证码不正确',
        self::INVITER_NOT_EXIST        => '推荐人不存在',
        self::INVITER_SNYTEX_ERROR     => '推荐人拼写语法错误',
        self::REGIST_FAIL              => '注册失败',
        self::INVITER_NOT_EXIST        => '邀请人不存在',
        self::USER_PASSWD_ERROR        => '密码只能为6-32位数字，字母及常用符号组成。',
        self::USER_NAME_NOTEXIT        => '用户名不存在',
        self::USER_NAME_OR_PASSWD_ERROR=> '用户名或密码错误',
        
        self::IMAGE_CODE_WRONG         => '图形验证码错误',
        self::GET_AUTHCODE_FAIL        => '第三方登录授权出错',
        self::UNBOUND                  => '用户未绑定第三方账号',
        self::BINDING_FAIL             => '绑定第三方账号出错',
        self::GET_OPENID_FAIL          => '获取openid失败',
        self::SIGN_OUT_FIAL            => '登出失败',
        self::USER_NAME_OR_PHONE_ERROR => '用户名或手机号错误',
        self::SAVE_PASSWD_WRONG        => '保存用户密码错误',
        self::ORIGIN_PASSWD_WRONG      => '原密码错误',
        self::USER_PHONE_NOTEXIT       => '手机号不存在',
        self::USER_EMAIL_NOTEXIT       => '邮箱不存在',
        self::PHONE_OR_PASSWD_ERROR    => '手机号或密码错误',
        self::EMAIL_OR_PASSWD_ERROR    => '邮箱或密码错误',
     
    );

}
