<?php
/**
 * 奖券表
 * @author hejunhua
 */
class Awards_Object_Ticket extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_ticket';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Awards_Object_Ticket';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'ticket_type', 'award_type', 'value', 'valid_time', 'activity', 'userid', 'pay_time', 'extraid', 'status', 'create_time', 'update_time', 'memo');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'ticket_type' => 'ticketType',
        'award_type'  => 'awardType',
        'value'       => 'value',
        'valid_time'  => 'validTime',
        'activity'    => 'activity',
        'userid'      => 'userid',
        'pay_time'    => 'payTime',
        'extraid'     => 'extraid',
        'status'      => 'status',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
        'memo'        => 'memo',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'ticket_type' => 1,
        'award_type'  => 1,
        'valid_time'  => 1,
        'userid'      => 1,
        'pay_time'    => 1,
        'extraid'     => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Awards_Object_Ticket
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 奖券id
     * @var integer
     */
    public $id;

    /**
     * 奖券类型 1:现金券，2:利息券，3:代金券
     * @var integer
     */
    public $ticketType;

    /**
     * 奖励类型:1-注册奖励 2-邀请奖励 3-投资奖励
     * @var integer
     */
    public $awardType;

    /**
     * 价值
     * @var number
     */
    public $value;

    /**
     * 有效截止时间
     * @var integer
     */
    public $validTime;

    /**
     * 奖励活动的类名,含兑换条件
     * @var string
     */
    public $activity;

    /**
     * 拥有用户
     * @var integer
     */
    public $userid;

    /**
     * 兑换时间
     * @var integer
     */
    public $payTime;

    /**
     * 额外字段:邀请奖励－被邀请人id, 投资奖励－投资id
     * @var integer
     */
    public $extraid;

    /**
     * 奖券状态: 1-未达成，2-未使用，3-已使用，4-已过期
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

    /**
     * 说明
     * @var string
     */
    public $memo;

}
