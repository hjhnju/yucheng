<?php
/**
 * 
 * @author hejunhua
 */
class User_Object_Login extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_login';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'uid';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Login';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('uid', 'status', 'name', 'passwd', 'phone', 'email', 'ip', 'login_time', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'uid'         => 'uid',
        'status'      => 'status',
        'name'        => 'name',
        'passwd'      => 'passwd',
        'phone'       => 'phone',
        'email'       => 'email',
        'ip'          => 'ip',
        'login_time'  => 'loginTime',
        'create_time' => 'createTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

    /**
     * @param array $data
     * @return User_Object_Login
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 用户id
     * @var 
     */
    public $uid;

    /**
     * 是否允许登录
     * @var 
     */
    public $status;

    /**
     * 用户名
     * @var string
     */
    public $name;

    /**
     * 用户密码md5值
     * @var string
     */
    public $passwd;

    /**
     * 用户手机号
     * @var string
     */
    public $phone;

    /**
     * 用户邮箱
     * @var string
     */
    public $email;

    /**
     * IP
     * @var string
     */
    public $ip;

    /**
     * 最近一次登录时间
     * @var string
     */
    public $loginTime;

    /**
     * 注册时间
     * @var string
     */
    public $createTime;

}
