<?php
/**
 * 用户第三方登陆表
 * @author hejunhua
 */
class User_Object_Third extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_third';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Third';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'authtype', 'nickname', 'openid', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'authtype'    => 'authtype',
        'nickname'    => 'nickname',
        'openid'      => 'openid',
        'create_time' => 'createTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'authtype'    => 1,
        'create_time' => 1,
    );

    /**
     * @param array $data
     * @return User_Object_Third
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 自增id
     * @var integer
     */
    public $id;

    /**
     * 用户id
     * @var integer
     */
    public $userid;

    /**
     * 第三方登录类型 1:qq, 2:weibo, 3:weixin
     * @var integer
     */
    public $authtype;

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
     * 绑定时间
     * @var integer
     */
    public $createTime;

}
