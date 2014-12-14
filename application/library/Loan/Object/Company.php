<?php
/**
 * 借款企业信息
 * @author jiangsongfang
 */
class Loan_Object_Company extends Base_Object {
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
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Company';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'school', 'area', 'assets', 'employers', 'years', 'funds', 'students', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'loan_id'     => 'loanId',
        'user_id'     => 'userId',
        'school'      => 'school',
        'area'        => 'area',
        'assets'      => 'assets',
        'employers'   => 'employers',
        'years'       => 'years',
        'funds'       => 'funds',
        'students'    => 'students',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

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
    );

    /**
     * @param array $data
     * @return Loan_Object_Company
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 
     * @var integer
     */
    public $id;

    /**
     * 借款ID
     * @var integer
     */
    public $loanId;

    /**
     * 创建人
     * @var integer
     */
    public $userId;

    /**
     * 学校
     * @var string
     */
    public $school;

    /**
     * 区域
     * @var string
     */
    public $area;

    /**
     * 总资产
     * @var string
     */
    public $assets;

    /**
     * 员工数
     * @var integer
     */
    public $employers;

    /**
     * 注册年限
     * @var integer
     */
    public $years;

    /**
     * 注册资金
     * @var string
     */
    public $funds;

    /**
     * 学生数
     * @var integer
     */
    public $students;

    /**
     * 创建时间
     * @var string
     */
    public $createTime;

    /**
     * 更新时间
     * @var string
     */
    public $updateTime;

}
