<?php
/**
 * @file 融资申请主表 
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
     * 字段列表
     * @var array
     */
    protected $fields = array('id', 'amount', 'duration', 'duration_month', 'userid', 'service_charge', 'rate', 'create_time', 'update_time');
	
    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'             => 'id',
        'amount'         => 'amount',
        'duration'       => 'duration',
        'duration_month' => 'durationMonth',
        'userid'         => 'userid',
        'service_charge' => 'serviceCharge',
        'rate'           => 'rate',
        'create_time'    => 'createTime',
        'update_time'    => 'updateTime',
    );
     
    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'userid'      => 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Apply_Object_Apply
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
     * 贷款总额
     * @var [decimal]
     */
    public $amount;

    /**
     * 贷款的期限，天为单位
     * @var [int]
     */
    public $duration;

    /**
     * 贷款的期限，月为单位
     * @var [int]
     */
    public $durationMonth;

    /**
     * 服务费
     * @var [decimal]
     */
    public $serviceCharge;

    /**
     * 贷款的利率
     * @var [decimal]
     */
    public $rate;

    /**
     * 贷款人
     * @var [int]
     */
    public $userid;
}