<?php
/**
 * 借款担保信息 列表类
 * @author jiangsongfang
 */
class Loan_List_Guarantee extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_guarantee';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'name', 'account', 'age', 'marriage', 'company_type', 'job_title', 'income', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'user_id'     => 1,
        'age'         => 1,
        'marriage'    => 1,
        'status'      => 1,
    );

}