<?php
/**
 * 邀请表
 * @author hejunhua
 */
class Invite_Object_Invite extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invite';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Invite_Object_Invite';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'inviterid', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'inviterid'   => 'inviterid',
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
     * @return Invite_Object_Invite
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 自增id
     * @var 
     */
    public $id;

    /**
     * 用户id
     * @var 
     */
    public $userid;

    /**
     * 邀请人id
     * @var 
     */
    public $inviterid;

    /**
     * 创建时间
     * @var 
     */
    public $createTime;

}
