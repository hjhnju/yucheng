<?php
/**
 * 用户登录历史纪录表
 * @author jiangsongfang
 */
class User_Object_Record extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_record';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'User_Object_Record';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'status', 'ip', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'status'      => 'status',
        'ip'          => 'ip',
        'create_time' => 'createTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'status'      => 1,
        'create_time' => 1,
    );

    /**
     * @param array $data
     * @return User_Object_Record
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
     * 登录状态
     * @var integer
     */
    public $status;

    /**
     * IP
     * @var string
     */
    public $ip;

    /**
     * 登录时间
     * @var integer
     */
    public $createTime;

}
