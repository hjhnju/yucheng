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
    protected $fields = array('id', 'user_id', 'order_id', 'title', 'pic', 'area', 'content', 'type_id', 'cat_id', 'fresh', 'duration', 'level', 'amount', 'interest', 'invest_cnt', 'invest_amount', 'safe_id', 'refund_type', 'audit_info', 'start_time', 'deadline', 'risk_rate', 'serv_rate', 'mang_fee', 'status', 'create_time', 'update_time', 'create_uid', 'full_time', 'pay_time', 'fail_info');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'user_id'         => 1,
        'order_id'        => 1,
        'area'            => 1,
        'type_id'         => 1,
        'cat_id'          => 1,
        'fresh'           => 1,
        'duration'        => 1,
        'level'           => 1,
        'invest_cnt'      => 1,
        'refund_type'     => 1,
        'start_time'      => 1,
        'deadline'        => 1,
        'status'          => 1,
        'create_time'     => 1,
        'update_time'     => 1,
        'create_uid'      => 1,
        'full_time'       => 1,
        'pay_time'        => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Loan_Object_Loan[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Loan_Object_Loan');
    }

}