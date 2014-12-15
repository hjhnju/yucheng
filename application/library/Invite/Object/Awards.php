<?php
/**
 * 奖励表
 * @author hejunhua
 */
class Invite_Object_Awards extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invite_awards';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Invite_Object_Awards';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'type', 'status', 'amount', 'create_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'userid'      => 'userid',
        'type'        => 'type',
        'status'      => 'status',
        'amount'      => 'amount',
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
     * @return Invite_Object_Awards
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
     * 奖励类型: 1-注册奖励, 2-邀请奖励
     * @var 
     */
    public $type;

    /**
     * 领取状态:1-未达到, 2-已达到未领取，3-已领取
     * @var 
     */
    public $status;

    /**
     * 奖励金额
     * @var 
     */
    public $amount;

    /**
     * 创建时间
     * @var 
     */
    public $createTime;

}
