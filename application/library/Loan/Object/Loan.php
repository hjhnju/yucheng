<?php
/**
 * 借款信息表
 * @author jiangsongfang
 */
class Loan_Object_Loan extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Loan';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'user_id', 'title', 'pic', 'content', 'type_id', 'cat_id', 'duration', 'level', 'amount', 'interest', 'guarantee_type', 'audit_info', 'deadline', 'status', 'create_time', 'update_time', 'create_uid', 'full_time', 'pay_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'              => 'id',
        'user_id'         => 'userId',
        'title'           => 'title',
        'pic'             => 'pic',
        'content'         => 'content',
        'type_id'         => 'typeId',
        'cat_id'          => 'catId',
        'duration'        => 'duration',
        'level'           => 'level',
        'amount'          => 'amount',
        'interest'        => 'interest',
        'guarantee_type'  => 'guaranteeType',
        'audit_info'      => 'auditInfo',
        'deadline'        => 'deadline',
        'status'          => 'status',
        'create_time'     => 'createTime',
        'update_time'     => 'updateTime',
        'create_uid'      => 'createUid',
        'full_time'       => 'fullTime',
        'pay_time'        => 'payTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'user_id'         => 1,
        'type_id'         => 1,
        'cat_id'          => 1,
        'duration'        => 1,
        'level'           => 1,
        'guarantee_type'  => 1,
        'status'          => 1,
        'create_uid'      => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Loan
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
     * 用户ID
     * @var integer
     */
    public $userId;

    /**
     * 标题
     * @var string
     */
    public $title;

    /**
     * 图片
     * @var string
     */
    public $pic;

    /**
     * 借款详情
     * @var string
     */
    public $content;

    /**
     * 标的类型
     * @var integer
     */
    public $typeId;

    /**
     * 借款类型
     * @var integer
     */
    public $catId;

    /**
     * 借款期限
     * @var integer
     */
    public $duration;

    /**
     * 信用级别
     * @var integer
     */
    public $level;

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
     * 担保类型
     * @var integer
     */
    public $guaranteeType;

    /**
     * 审核信息
     * @var string
     */
    public $auditInfo;

    /**
     * 截止时间
     * @var string
     */
    public $deadline;

    /**
     * 状态
     * @var integer
     */
    public $status;

    /**
     * 创建时间
     * @var string
     */
    public $createTime;

    /**
     * 修改时间
     * @var string
     */
    public $updateTime;

    /**
     * 创建人
     * @var integer
     */
    public $createUid;

    /**
     * 满标时间
     * @var string
     */
    public $fullTime;

    /**
     * 放款时间
     * @var string
     */
    public $payTime;

}
