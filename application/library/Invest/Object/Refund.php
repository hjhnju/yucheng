<?php
/**
 * 借款的还款计划
 * @author jiangsongfang
 */
class Invest_Object_Refund extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest_refund';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Invest_Object_Refund';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'invest_id', 'loan_id', 'user_id', 'period', 'capital', 'capital_refund', 'capital_reset', 'interest', 'amount', 'late_charge', 'promise_time', 'refund_time', 'transfer', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'              => 'id',
        'invest_id'       => 'investId',
        'loan_id'         => 'loanId',
        'user_id'         => 'userId',
        'period'          => 'period',
        'capital'         => 'capital',
        'capital_refund'  => 'capitalRefund',
        'capital_reset'   => 'capitalReset',
        'interest'        => 'interest',
        'amount'          => 'amount',
        'late_charge'     => 'lateCharge',
        'promise_time'    => 'promiseTime',
        'refund_time'     => 'refundTime',
        'transfer'        => 'transfer',
        'status'          => 'status',
        'create_time'     => 'createTime',
        'update_time'     => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'invest_id'       => 1,
        'loan_id'         => 1,
        'user_id'         => 1,
        'period'          => 1,
        'promise_time'    => 1,
        'refund_time'     => 1,
        'transfer'        => 1,
        'status'          => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * @param array $data
     * @return Invest_Object_Refund
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 计划ID
     * @var integer
     */
    public $id;

    /**
     * 投标ID
     * @var integer
     */
    public $investId;

    /**
     * 借款ID
     * @var integer
     */
    public $loanId;

    /**
     * 投资人ID
     * @var integer
     */
    public $userId;

    /**
     * 期数
     * @var integer
     */
    public $period;

    /**
     * 本金
     * @var number
     */
    public $capital;

    /**
     * 已回收本金
     * @var number
     */
    public $capitalRefund;

    /**
     * 剩余本金
     * @var number
     */
    public $capitalReset;

    /**
     * 利息
     * @var number
     */
    public $interest;

    /**
     * 应还本息
     * @var number
     */
    public $amount;

    /**
     * 逾期罚息
     * @var number
     */
    public $lateCharge;

    /**
     * 应还日期
     * @var integer
     */
    public $promiseTime;

    /**
     * 回款时间
     * @var integer
     */
    public $refundTime;

    /**
     * 是否打款 0未打款 1已打款
     * @var integer
     */
    public $transfer;

    /**
     * 状态 1正常 2已还 3逾期
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
