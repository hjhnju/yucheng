<?php
/**
 * @file 融资申请企业信息表 
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
     * 字段列表
     * @var array
     */
    protected $fields = array('id', 'name', 'type', 'nature', 'province', 'city', 'school_source', 'year', 'income_year', 'profit', 'other_business', 'address', 'total_student', 'staff', 'purpose', 'guarantee_count', 'branch_school', 'apply_id', 'create_time', 'update_time');
    
    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'             => 'id',
        'name'           => 'name',
        'type'           => 'type',
        'nature'         => 'nature',
        'province'       => 'province',
        'city'           => 'city',
        'school_source'  => 'schoolSource',
        'year'           => 'year',
        'income_year'    => 'incomeYear',
        'profit'         => 'profit',
        'other_business' => 'otherBusiness',
        'address'        => 'address',
        'total_student'  => 'totalStudent',
        'staff'          => 'staff',
        'purpose'        => 'purpose',
        'guarantee_count'=> 'guaranteeCount',
        'branch_school'  => 'branchSchool',
        'apply_id'       => 'applyId',
        'create_time'    => 'createTime',
        'update_time'    => 'updateTime',
    );
     
    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'type'            => 1,
        'school_source'   => 1,
        'year'            => 1,
        'income_year'     => 1,
        'profit'          => 1,
        'other_business'  => 1,
        'total_student'   => 1,
        'staff'           => 1,
        'purpose'         => 1,
        'guarantee_count' => 1,
        'branch_school'   => 1,
        'apply_id'        => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_School
     */
    public static function init($data) {
        return parent::initObject(self::getClassName(), $data);
    }

    /**
     * @param  null
     * @return 返回当前类的名字
     */
    public static function getClassName(){
        return __CLASS__;
    }

    /**
     * 自增长的id
     * @var [int]
     */
    public $id;

    /**
     * 学校的名字
     * @var [string]
     */
    public $name;

    /**
     * 学校的类型
     * @var [int]
     */
    public $type;

    /**
     * 学校的主体性质
     * @var [int]
     */
    public $nature;

    /**
     * 学校所在的省份
     * @var [string]
     */
    public $province;

    /**
     * 学校所在的城市
     * @var [string]
     */
    public $city;

    /**
     * 学校从哪里知道我们
     * @var [int]
     */
    public $schoolSource;

    /**
     * 学校创建的年份
     * @var [int]
     */
    public $year;

    /**
     * 学校的年收入
     * @var [int]
     */
    public $incomeYear;

    /**
     * 最近一年是否盈利
     * @var [int]
     */
    public $profit;

    /**
     * 学校是否还有其他业务
     * @var [int]
     */
    public $otherBusiness;

    /**
     * 学校的详细地址
     * @var [string]
     */
    public $address;

    /**
     * 学校有多少学生
     * @var [int]
     */
    public $totalStudent;

    /**
     * 学校的职工数量
     * @var [int]
     */
    public $staff;

    /**
     * 贷款的目的
     * @var [int]
     */
    public $purpose;

    /**
     * 担保人数量
     * @var [int]
     */
    public $guaranteeCount;

    /**
     * 学校有几所分校
     * @var [int]
     */
    public $branchSchool;

    /**
     * 申请的id
     * @var [int]
     */
    public $applyId;

    /**
     * 创建时间
     * @var [int]
     */
    public $createTime;

    /**
     * 更新时间
     * @var [int]
     */
    public $updateTime;
}