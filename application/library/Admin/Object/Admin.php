<?php
/**
 * 管理员信息表
 * @author jiangsongfang
 */
class Admin_Object_Admin extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'admin';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'uid';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Admin_Object_Admin';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('uid', 'name', 'role', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'uid'         => 'uid',
        'name'        => 'name',
        'role'        => 'role',
        'status'      => 'status',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'uid'         => 1,
        'role'        => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Admin_Object_Admin
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 用户ID
     * @var integer
     */
    public $uid;

    /**
     * 姓名
     * @var string
     */
    public $name;

    /**
     * 角色
     * @var integer
     */
    public $role;

    /**
     * 状态
     * @var integer
     */
    public $status;

    /**
     * 创建时间
     * @var integer
     */
    public $createTime;

    /**
     * 更新时间
     * @var integer
     */
    public $updateTime;

}
