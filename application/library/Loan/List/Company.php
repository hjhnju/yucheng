<?php
/**
 * 借款企业信息 列表类
 * @author jiangsongfang
 */
class Loan_List_Company extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_company';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'school', 'area', 'assets', 'employers', 'years', 'funds', 'students', 'status', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'loan_id'     => 1,
        'user_id'     => 1,
        'employers'   => 1,
        'years'       => 1,
        'students'    => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Loan_Object_Company[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Loan_Object_Company');
    }

}