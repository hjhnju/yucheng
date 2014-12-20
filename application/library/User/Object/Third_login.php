<?php
/**
 * 
 * @author hejunhua
 */
class User_Object_Third_login extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_third_login';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'status';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Third_login';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('uid', 'type', 'nickname', 'openid', 'status', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'uid'         => 'uid',
        'type'        => 'type',
        'nickname'    => 'nickname',
        'openid'      => 'openid',
        'status'      => 'status',
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
     * @return User_Object_Third_login
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
     * 第三方登录类型
     * @var 
     */
    public $type;

    /**
     * 用户第三方站点用户名
     * @var string
     */
    public $nickname;

    /**
     * 第三方认证ID
     * @var string
     */
    public $openid;

    /**
     * 自增id
     * @var 
     */
    public $status;

    /**
     * 绑定时间
     * @var string
     */
    public $createTime;

}
