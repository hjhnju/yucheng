<?php
/**
 * 申请信息学校表
 * @author guojinli
 */
class Apply_Object_School extends Base_Object {
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
     * 类名
     * @var string
     */
    const CLASSNAME = 'Apply_Object_School';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'name', 'type', 'nature', 'province', 'city', 'school_source', 'year', 'is_annual_income', 'is_profit', 'is_other_business', 'address', 'total_student', 'staff', 'purpose', 'guarantee_count', 'branch_school', 'apply_id', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'                  => 'id',
        'name'                => 'name',
        'type'                => 'type',
        'nature'              => 'nature',
        'province'            => 'province',
        'city'                => 'city',
        'school_source'       => 'schoolSource',
        'year'                => 'year',
        'is_annual_income'    => 'isAnnualIncome',
        'is_profit'           => 'isProfit',
        'is_other_business'   => 'isOtherBusiness',
        'address'             => 'address',
        'total_student'       => 'totalStudent',
        'staff'               => 'staff',
        'purpose'             => 'purpose',
        'guarantee_count'     => 'guaranteeCount',
        'branch_school'       => 'branchSchool',
        'apply_id'            => 'applyId',
        'create_time'         => 'createTime',
        'update_time'         => 'updateTime',
    );

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
     * @param array $data
     * @return Apply_Object_School
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
     * 学校的名字
     * @var string
     */
    public $name;

    /**
     * 学校的类型
     * @var integer
     */
    public $type;

    /**
     * 学校主体性质
     * @var integer
     */
    public $nature;

    /**
     * 学校所在的省份
     * @var integer
     */
    public $province;

    /**
     * 学校所在城市
     * @var integer
     */
    public $city;

    /**
     * 该学校是从哪里了解到我们的，也就是学校对我们来将来源是哪
     * @var integer
     */
    public $schoolSource;

    /**
     * 学校创建的时间，具体到年份
     * @var integer
     */
    public $year;

    /**
     * 年收入
     * @var integer
     */
    public $isAnnualIncome;

    /**
     * 最近一年是否盈利
     * @var integer
     */
    public $isProfit;

    /**
     * 该学校是否还有其他的业务
     * @var integer
     */
    public $isOtherBusiness;

    /**
     * 学校的具体地址
     * @var string
     */
    public $address;

    /**
     * 学校的学生总数
     * @var integer
     */
    public $totalStudent;

    /**
     * 学校的教职工数量
     * @var integer
     */
    public $staff;

    /**
     * 贷款用途
     * @var integer
     */
    public $purpose;

    /**
     * 担保人数量
     * @var integer
     */
    public $guaranteeCount;

    /**
     * 分校的数量
     * @var integer
     */
    public $branchSchool;

    /**
     * 与apply表中对应的申请id
     * @var integer
     */
    public $applyId;

    /**
     * 
     * @var integer
     */
    public $createTime;

    /**
     * 
     * @var integer
     */
    public $updateTime;

}
