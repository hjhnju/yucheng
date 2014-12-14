<?php
/**
 * 借款信息表 列表类
 * @author jiangsongfang
 */
class Loan_List_Loan extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'user_id', 'title', 'pic', 'content', 'type_id', 'cat_id', 'duration', 'level', 'amount', 'interest', 'guarantee_type', 'audit_info', 'deadline', 'status', 'create_time', 'update_time', 'create_uid', 'full_time', 'pay_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'user_id'         => 1,
        'type_id'         => 1,
        'cat_id'          => 1,
        'duration'        => 1,
        'level'           => 1,
        'guarantee_type'  => 1,
        'status'          => 1,
        'create_uid'      => 1,
    );

}