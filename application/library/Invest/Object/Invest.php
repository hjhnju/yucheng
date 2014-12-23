<?php
/**
 * 借款的投标记录
 * @author jiangsongfang
 */
class Invest_Object_Invest extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invest';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Invest_Object_Invest';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'loan_id', 'user_id', 'name', 'amount', 'status', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'loan_id'     => 'loanId',
        'user_id'     => 'userId',
        'name'        => 'name',
        'amount'      => 'amount',
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
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Invest_Object_Invest
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 投标ID
     * @var integer
     */
    public $id;

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
     * 姓名
     * @var string
     */
    public $name;

    /**
     * 金额
     * @var number
     */
    public $amount;

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
