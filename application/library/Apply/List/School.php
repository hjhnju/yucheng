<?php
/**
 *  列表类
 * @author hejunhua
 */
class Apply_List_School extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply_school';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'name', 'type', 'nature', 'province', 'city', 'school_source', 'year', 'is_annual_income', 'is_profit', 'is_other_business', 'address', 'total_student', 'staff', 'purpose', 'guarantee_count', 'branch_school', 'apply_id', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'                  => 1,
        'type'                => 1,
        'nature'              => 1,
        'province'            => 1,
        'city'                => 1,
        'school_source'       => 1,
        'year'                => 1,
        'is_annual_income'    => 1,
        'is_profit'           => 1,
        'is_other_business'   => 1,
        'total_student'       => 1,
        'staff'               => 1,
        'purpose'             => 1,
        'guarantee_count'     => 1,
        'branch_school'       => 1,
        'apply_id'            => 1,
        'create_time'         => 1,
        'update_time'         => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Apply_Object_School[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Apply_Object_School');
    }

}