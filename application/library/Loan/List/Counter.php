<?php
/**
 * 借款统计数据 列表类
 * @author jiangsongfang
 */
class Loan_List_Counter extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_counter';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'success', 'limit', 'total', 'finished', 'refund', 'rest', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'userid'     => 1,
        'success'     => 1,
        'finished'    => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}