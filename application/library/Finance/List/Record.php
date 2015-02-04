<?php
/**
 * 资金记录 列表类
 * @author lilu
 */
class Finance_List_Record extends Base_List {
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
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'orderId', 'orderDate', 'userId', 'type', 'amount', 'balance', 'total', 'comment', 'create_time', 'update_time', 'ip');

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
     * 获取数据的对象数组
     * @return array|Finance_Object_Record[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Finance_Object_Record');
    }

}