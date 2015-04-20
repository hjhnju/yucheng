<?php
/**
 * 实物奖励表
 * @author hejunhua
 */
class Awards_Object_Entity extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_entity';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Awards_Object_Entity';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'name', 'type', 'value', 'activity', 'pay_time', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'name'        => 'name',
        'type'        => 'type',
        'value'       => 'value',
        'activity'    => 'activity',
        'pay_time'    => 'payTime',
        'status'      => 'status',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'type'        => 1,
        'pay_time'    => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Awards_Object_Entity
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 奖励id
     * @var integer
     */
    public $id;

    /**
     * 拥有用户
     * @var integer
     */
    public $userid;

    /**
     * 说明
     * @var string
     */
    public $name;

    /**
     * 奖励类型 1:注册奖励 2:邀请奖励 3:投资奖励
     * @var integer
     */
    public $type;

    /**
     * 价值
     * @var number
     */
    public $value;

    /**
     * 奖励活动的类名,含兑换条件
     * @var string
     */
    public $activity;

    /**
     * 发放时间
     * @var integer
     */
    public $payTime;

    /**
     * 发放状态 1:未发放，2:已发放，3:已收货
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
