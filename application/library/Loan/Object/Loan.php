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
    protected $fields = array('id', 'user_id', 'order_id', 'title', 'pic', 'area', 'content', 'type_id', 'cat_id', 'fresh', 'duration', 'level', 'amount', 'interest', 'invest_cnt', 'invest_amount', 'safe_id', 'refund_type', 'audit_info', 'start_time', 'deadline', 'risk_rate', 'serv_rate', 'mang_rate', 'status', 'create_time', 'update_time', 'create_uid', 'full_time', 'pay_time', 'fail_info');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'              => 'id',
        'user_id'         => 'userId',
        'order_id'        => 'orderId',
        'title'           => 'title',
        'pic'             => 'pic',
        'area'            => 'area',
        'content'         => 'content',
        'type_id'         => 'typeId',
        'cat_id'          => 'catId',
        'fresh'           => 'fresh',
        'duration'        => 'duration',
        'level'           => 'level',
        'amount'          => 'amount',
        'interest'        => 'interest',
        'invest_cnt'      => 'investCnt',
        'invest_amount'   => 'investAmount',
        'safe_id'         => 'safeId',
        'refund_type'     => 'refundType',
        'audit_info'      => 'auditInfo',
        'start_time'      => 'startTime',
        'deadline'        => 'deadline',
        'risk_rate'       => 'riskRate',
        'serv_rate'       => 'servRate',
        'mang_rate'       => 'mangRate',
        'status'          => 'status',
        'create_time'     => 'createTime',
        'update_time'     => 'updateTime',
        'create_uid'      => 'createUid',
        'full_time'       => 'fullTime',
        'pay_time'        => 'payTime',
        'fail_info'       => 'failInfo',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'user_id'         => 1,
        'order_id'        => 1,
        'area'            => 1,
        'type_id'         => 1,
        'cat_id'          => 1,
        'fresh'           => 1,
        'duration'        => 1,
        'level'           => 1,
        'invest_cnt'      => 1,
        'refund_type'     => 1,
        'start_time'      => 1,
        'deadline'        => 1,
        'status'          => 1,
        'create_time'     => 1,
        'update_time'     => 1,
        'create_uid'      => 1,
        'full_time'       => 1,
        'pay_time'        => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Loan
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 借款ID
     * @var integer
     */
    public $id;

    /**
     * 用户ID
     * @var integer
     */
    public $userId;

    /**
     * 订单号
     * @var integer
     */
    public $orderId;

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
     * 所在地
     * @var integer
     */
    public $area;

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
     * 是否新手标
     * @var integer
     */
    public $fresh;

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
     * 投标总数
     * @var integer
     */
    public $investCnt;

    /**
     * 已投资金额
     * @var number
     */
    public $investAmount;

    /**
     * 保障方式 逗号分隔
     * @var string
     */
    public $safeId;

    /**
     * 还款方式
     * @var integer
     */
    public $refundType;

    /**
     * 审核信息
     * @var string
     */
    public $auditInfo;

    /**
     * 开始时间
     * @var integer
     */
    public $startTime;

    /**
     * 截止时间
     * @var integer
     */
    public $deadline;

    /**
     * 风险准备金
     * @var number
     */
    public $riskRate;

    /**
     * 融资服务费
     * @var number
     */
    public $servRate;

    /**
     * 账户管理费
     * @var number
     */
    public $mangRate;

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
     * 修改时间
     * @var integer
     */
    public $updateTime;

    /**
     * 创建人
     * @var integer
     */
    public $createUid;

    /**
     * 满标时间
     * @var integer
     */
    public $fullTime;

    /**
     * 放款时间
     * @var integer
     */
    public $payTime;

    /**
     * 失败原因
     * @var string
     */
    public $failInfo;

}
