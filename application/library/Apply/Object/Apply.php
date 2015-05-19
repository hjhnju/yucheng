<?php
/**
 * 申请信息主表
 * @author guojinli
 */
class Apply_Object_Apply extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'apply';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Apply_Object_Apply';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'amount', 'duration', 'duration_type', 'userid', 'service_charge', 'rate', 'status', 'start_time', 'end_time', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'              => 'id',
        'amount'          => 'amount',
        'duration'        => 'duration',
        'duration_type'   => 'durationType',
        'userid'          => 'userid',
        'service_charge'  => 'serviceCharge',
        'rate'            => 'rate',
        'status'          => 'status',
        'start_time'      => 'startTime',
        'end_time'        => 'endTime',
        'create_time'     => 'createTime',
        'update_time'     => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'              => 1,
        'duration'        => 1,
        'duration_type'   => 1,
        'userid'          => 1,
        'status'          => 1,
        'start_time'      => 1,
        'end_time'        => 1,
        'create_time'     => 1,
        'update_time'     => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Apply
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
     * 贷款总额
     * @var number
     */
    public $amount;

    /**
     * 贷款期限，如果duration_type为1则以天为单位，如果duration_type为2则以月为单位
     * @var integer
     */
    public $duration;

    /**
     * 申请带块期限类型，1为天，2为月
     * @var integer
     */
    public $durationType;

    /**
     * 申请贷款人的id
     * @var integer
     */
    public $userid;

    /**
     * 服务费
     * @var number
     */
    public $serviceCharge;

    /**
     * 贷款的利率
     * @var number
     */
    public $rate;

    /**
     * 融资申请状态
     * @var integer
     */
    public $status;

    /**
     * 开始时间
     * @var integer
     */
    public $startTime;

    /**
     * 结束时间
     * @var integer
     */
    public $endTime;

    /**
     * 创建申请贷款的日期
     * @var integer
     */
    public $createTime;

    /**
     * 更新贷款的日期
     * @var integer
     */
    public $updateTime;

}
