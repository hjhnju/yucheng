<?php
/**
 * 资金记录
 * @author lilu
 */
class Finance_Object_Record extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'finance_record';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Finance_Object_Record';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'orderId', 'orderDate', 'userId', 'type', 'amount', 'balance', 'total', 'comment', 'create_time', 'update_time', 'ip');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'orderId'     => 'orderId',
        'orderDate'   => 'orderDate',
        'userId'      => 'userId',
        'type'        => 'type',
        'amount'      => 'amount',
        'balance'     => 'balance',
        'total'       => 'total',
        'comment'     => 'comment',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
        'ip'          => 'ip',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'orderId'     => 1,
        'orderDate'   => 1,
        'userId'      => 1,
        'type'        => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Finance_Object_Record
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 
     * @var integer
     */
    public $id;

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
     * 类型
     * @var integer
     */
    public $type;

    /**
     * 金额
     * @var number
     */
    public $amount;

    /**
     * 余额
     * @var number
     */
    public $balance;

    /**
     * 系统总额
     * @var number
     */
    public $total;

    /**
     * 备注
     * @var string
     */
    public $comment;

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
     * 
     * @var string
     */
    public $ip;

}
