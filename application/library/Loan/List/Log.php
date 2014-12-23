<?php
/**
 * 借款操作记录 列表类
 * @author jiangsongfang
 */
class Loan_List_Log extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_log';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'content', 'create_time', 'ip');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'user_id'     => 1,
        'create_time' => 1,
    );

}