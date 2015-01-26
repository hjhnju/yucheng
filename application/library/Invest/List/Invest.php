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
    protected $fields = array('id', 'loan_id', 'user_id', 'name', 'duration', 'interest', 'amount', 'capital_refund', 'capital_rest', 'income', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'loan_id'         => 1,
        'user_id'         => 1,
        'duration'        => 1,
        'status'          => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

}