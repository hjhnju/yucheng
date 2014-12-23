<?php
/**
 * 借款的还款计划 列表类
 * @author jiangsongfang
 */
class Loan_List_Refund extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_refund';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'period', 'capital', 'interest', 'amount', 'late_charge', 'promise_time', 'refund_time', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'user_id'     => 1,
        'period'      => 1,
        'promise_time'=> 1,
        'refund_time' => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}