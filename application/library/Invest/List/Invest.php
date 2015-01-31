<?php
/**
 * 借款的投标记录 列表类
 * @author jiangsongfang
 */
class Invest_List_Invest extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'order_id', 'name', 'duration', 'interest', 'amount', 'capital_refund', 'capital_rest', 'amount_refund', 'amount_rest', 'income', 'fail_info', 'start_time', 'finish_time', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'loan_id'         => 1,
        'user_id'         => 1,
        'order_id'        => 1,
        'duration'        => 1,
        'start_time'      => 1,
        'finish_time'     => 1,
        'status'          => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Invest_Object_Invest[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Invest_Object_Invest');
    }

}