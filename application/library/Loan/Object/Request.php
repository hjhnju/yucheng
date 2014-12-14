<?php
/**
 * 借款申请表
 * @author jiangsongfang
 */
class Loan_Object_Request extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_request';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Request';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'title', 'school_type', 'use_type', 'amount', 'interest', 'period', 'name', 'prov_id', 'city_id', 'contact', 'pay_type', 'content', 'create_time', 'update_time', 'status', 'update_uid');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'title'       => 'title',
        'school_type' => 'schoolType',
        'use_type'    => 'useType',
        'amount'      => 'amount',
        'interest'    => 'interest',
        'period'      => 'period',
        'name'        => 'name',
        'prov_id'     => 'provId',
        'city_id'     => 'cityId',
        'contact'     => 'contact',
        'pay_type'    => 'payType',
        'content'     => 'content',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
        'status'      => 'status',
        'update_uid'  => 'updateUid',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'school_type' => 1,
        'use_type'    => 1,
        'period'      => 1,
        'prov_id'     => 1,
        'city_id'     => 1,
        'pay_type'    => 1,
        'status'      => 1,
        'update_uid'  => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Request
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
     * 借款标题
     * @var string
     */
    public $title;

    /**
     * 学校类型
     * @var integer
     */
    public $schoolType;

    /**
     * 借款用途
     * @var integer
     */
    public $useType;

    /**
     * 借款金额
     * @var number
     */
    public $amount;

    /**
     * 利率
     * @var number
     */
    public $interest;

    /**
     * 借款期限
     * @var integer
     */
    public $period;

    /**
     * 借款人
     * @var string
     */
    public $name;

    /**
     * 所在省
     * @var integer
     */
    public $provId;

    /**
     * 所在地区
     * @var integer
     */
    public $cityId;

    /**
     * 联系方式
     * @var string
     */
    public $contact;

    /**
     * 还款方式
     * @var integer
     */
    public $payType;

    /**
     * 借款详情
     * @var string
     */
    public $content;

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

    /**
     * 状态
     * @var integer
     */
    public $status;

    /**
     * 跟进人
     * @var integer
     */
    public $updateUid;

}
