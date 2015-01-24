<?php
/**
 * 支付订单
 * @author lilu
 */
class Finance_Object_Order extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'finance_order';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'orderId';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Finance_Object_Order';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('orderId', 'orderDate', 'userId', 'type', 'amount', 'status', 'create_time', 'update_time', 'comment');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'orderId'     => 'orderId',
        'orderDate'   => 'orderDate',
        'userId'      => 'userId',
        'type'        => 'type',
        'amount'      => 'amount',
        'status'      => 'status',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
        'comment'     => 'comment',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'orderId'     => 1,
        'orderDate'   => 1,
        'userId'      => 1,
        'type'        => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Finance_Object_Order
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 
     * @var integer
     */
    public $orderId;

    /**
     * 订单日期
     * @var integer
     */
    public $orderDate;

    /**
     * 
     * @var integer
     */
    public $userId;

    /**
     * 支付类型
     * @var integer
     */
    public $type;

    /**
     * 金额
     * @var number
     */
    public $amount;

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

    /**
     * 备注
     * @var string
     */
    public $comment;

}
