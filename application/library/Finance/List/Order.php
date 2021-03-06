<?php
/**
 * 支付订单 列表类
 * @author lilu
 */
class Finance_List_Order extends Base_List {
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
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('orderId', 'orderDate', 'userId', 'type', 'amount', 'avlBal', 'status', 'failCode', 'failDesc', 'create_time', 'update_time', 'comment', 'freezeTrxId');

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
     * 获取数据的对象数组
     * @return array|Finance_Object_Order[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Finance_Object_Order');
    }

}