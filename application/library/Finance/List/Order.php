<?php
/**
 * 支付订单 列表类
 * @author 
 */
class Pay_List_Order extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'pay_order';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'orderId';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('orderId', 'userId', 'freezeOrdId', 'freezeTrxId', 'type', 'amount', 'status', 'create_time', 'update_time', 'comment');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'orderId'     => 1,
        'userId'      => 1,
        'freezeOrdId' => 1,
        'freezeTrxId' => 1,
        'type'        => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}