<?php
/**
 * 奖券表 列表类
 * @author hejunhua
 */
class Awards_List_Entity extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_entity';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'type', 'value', 'valid_time', 'claim', 'userid', 'pay_time', 'create_time', 'update_time', 'memo');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'type'        => 1,
        'valid_time'  => 1,
        'userid'      => 1,
        'pay_time'    => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Awards_Object_Entity[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Awards_Object_Entity');
    }

}