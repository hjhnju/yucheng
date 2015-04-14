<?php
/**
 * 邀请奖励表
 * @author hejunhua
 */
class Awards_Object_Invite extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_invite';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Awards_Object_Invite';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'inviterid', 'status', 'amount', 'memo', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'inviterid'   => 'inviterid',
        'status'      => 'status',
        'amount'      => 'amount',
        'memo'        => 'memo',
        'create_time' => 'createTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'inviterid'   => 1,
        'status'      => 1,
    );

    /**
     * @param array $data
     * @return Awards_Object_Invite
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
     * 邀请人id
     * @var integer
     */
    public $inviterid;

    /**
     * 领取状态:1-未达到, 2-已达到未领取，3-已领取
     * @var integer
     */
    public $status;

    /**
     * 奖励金额
     * @var number
     */
    public $amount;

    /**
     * 备注
     * @var string
     */
    public $memo;

    /**
     * 创建时间
     * @var 
     */
    public $createTime;

}
