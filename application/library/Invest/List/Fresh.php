<?php
/**
 * 新手标投资记录 列表类
 * @author jiangsongfang
 */
class Invest_List_Fresh extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest_fresh';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'user_id', 'loan_id', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'user_id'     => 1,
        'loan_id'     => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

}