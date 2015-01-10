<?php
class Admin_RetCode extends Base_RetCode {
    /**
     * 用户名不能为空
     * @var integer
     */
    const USERNAME_EMPTY = 1025;
    /**
     * 密码不能为空
     * @var integer
     */
    const PASSWORD_EMPTY = 1026;
    /**
     * 用户不存在
     * @var integer
     */
    const USER_NOTEXISTS = 1027;
    /**
     * 密码错误
     * @var integer
     */
    const PASSWORD_WRONG = 1028;
    /**
     * 验证码错误
     * @var integer
     */
    const CAPTCHA_WRONG = 1029;
    /**
     * 不是管理员
     * @var integer
     */
    const NOT_ADMIN = 1030;
    
    protected static $_arrErrMap = array(
        self::USERNAME_EMPTY => '用户名不能为空',
        self::PASSWORD_EMPTY => '密码不能为空',
        self::USER_NOTEXISTS => '用户不存在',
        self::PASSWORD_WRONG => '密码错误',
        self::CAPTCHA_WRONG => '验证码错误',
        self::NOT_ADMIN => '不是管理员',
    );
}