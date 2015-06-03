<?php
/**
 * 收益分享表 列表类
 * @author huwei
 */
class Invest_List_Share extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest_share';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'invest_id', 'from_userid', 'to_userid', 'rate', 'income', 'create_time', 'update_time', 'memo');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'invest_id'   => 1,
        'from_userid' => 1,
        'to_userid'   => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Invest_Object_Share[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Invest_Object_Share');
    }

}