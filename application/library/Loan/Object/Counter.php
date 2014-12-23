<?php
/**
 * 借款统计数据
 * @author jiangsongfang
 */
class Loan_Object_Counter extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'loan_counter';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'user_id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Loan_Object_Counter';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('user_id', 'success', 'limit', 'total', 'finished', 'refund', 'rest', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'user_id'     => 'userId',
        'success'     => 'success',
        'limit'       => 'limit',
        'total'       => 'total',
        'finished'    => 'finished',
        'refund'      => 'refund',
        'rest'        => 'rest',
        'status'      => 'status',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'user_id'     => 1,
        'success'     => 1,
        'finished'    => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Loan_Object_Counter
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 用户ID
     * @var integer
     */
    public $userId;

    /**
     * 成功借款次数
     * @var integer
     */
    public $success;

    /**
     * 授信额度
     * @var number
     */
    public $limit;

    /**
     * 累计借款
     * @var number
     */
    public $total;

    /**
     * 还清笔数
     * @var integer
     */
    public $finished;

    /**
     * 已还金额
     * @var number
     */
    public $refund;

    /**
     * 待还本息
     * @var number
     */
    public $rest;

    /**
     * 状态 0正常
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
