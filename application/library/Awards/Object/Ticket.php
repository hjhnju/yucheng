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
    protected $fields = array('id', 'type', 'value', 'valid_time', 'activity', 'userid', 'pay_time', 'create_time', 'update_time', 'memo');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'type'        => 'type',
        'value'       => 'value',
        'valid_time'  => 'validTime',
        'activity'    => 'activity',
        'userid'      => 'userid',
        'pay_time'    => 'payTime',
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
        'type'        => 1,
        'valid_time'  => 1,
        'userid'      => 1,
        'pay_time'    => 1,
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
     * 1:现金券，2:利息券，3:代金券
     * @var integer
     */
    public $type;

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
     * 活动类名
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
