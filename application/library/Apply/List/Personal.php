<?php
/**
 *  列表类
 * @author hejunhua
 */
class Apply_List_Personal extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply_personal';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'realname', 'certificate', 'house_type', 'detail_address', 'cellphone', 'telephone', 'scope_cash', 'scope_stock', 'is_criminal', 'is_lawsuit', 'apply_id', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'house_type'      => 1,
        'cellphone'       => 1,
        'scope_cash'      => 1,
        'scope_stock'     => 1,
        'is_criminal'     => 1,
        'is_lawsuit'      => 1,
        'apply_id'        => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Apply_Object_Personal[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Apply_Object_Personal');
    }

}