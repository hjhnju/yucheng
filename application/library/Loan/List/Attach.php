<?php
/**
 * 借款附件 列表类
 * @author jiangsongfang
 */
class Loan_List_Attach extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_attach';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'type', 'title', 'url', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'user_id'     => 1,
        'type'        => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}