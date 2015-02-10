<?php
/**
 * 个人借款用户信息
 * @author jiangsongfang
 */
class Loan_Object_Private extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_private';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Private';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'showname', 'account', 'age', 'marriage', 'company_type', 'job_title', 'income', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'loan_id'     => 'loanId',
        'user_id'     => 'userId',
        'showname'    => 'showname',
        'account'     => 'account',
        'age'         => 'age',
        'marriage'    => 'marriage',
        'company_type'=> 'companyType',
        'job_title'   => 'jobTitle',
        'income'      => 'income',
        'status'      => 'status',
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
        'age'         => 1,
        'marriage'    => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Private
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
     * 创建人ID
     * @var integer
     */
    public $userId;

    /**
     * 显示用户名
     * @var string
     */
    public $showname;

    /**
     * 户口
     * @var string
     */
    public $account;

    /**
     * 年龄
     * @var integer
     */
    public $age;

    /**
     * 婚姻
     * @var integer
     */
    public $marriage;

    /**
     * 企业类型
     * @var string
     */
    public $companyType;

    /**
     * 职务
     * @var string
     */
    public $jobTitle;

    /**
     * 收入
     * @var string
     */
    public $income;

    /**
     * 状态
     * @var integer
     */
    public $status;

    /**
     * 创建时间
     * @var integer
     */
    public $createTime;

    /**
     * 更新时间
     * @var integer
     */
    public $updateTime;

}
