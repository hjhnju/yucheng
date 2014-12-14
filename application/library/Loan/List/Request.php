<?php
/**
 * 借款申请表 列表类
 * @author jiangsongfang
 */
class Loan_List_Request extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_request';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'title', 'school_type', 'use_type', 'amount', 'interest', 'period', 'name', 'prov_id', 'city_id', 'contact', 'pay_type', 'content', 'create_time', 'update_time', 'status', 'update_uid');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'school_type' => 1,
        'use_type'    => 1,
        'period'      => 1,
        'prov_id'     => 1,
        'city_id'     => 1,
        'pay_type'    => 1,
        'status'      => 1,
        'update_uid'  => 1,
    );

}