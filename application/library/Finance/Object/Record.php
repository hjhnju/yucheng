<?php
/**
 * 资金记录表
 * @author lilu
 */
class Finance_Object_Record extends Base_Object {
	
	/**
	 * 数据表名
	 * @var string
	 */
	protected $table = 'pay_record';
	
	/**
	 * 主键
	 * @var string
	 */
	protected $priKey = 'id';
	
	/**
	 * 类名
	 * @var string
	 */
	const CLASSNAME = 'Finance_Logic_Record';
	
	/**
	 * 对象包含的所有字段
	 * @var array
	 */
	protected $fields = array('id', 'order_id', 'user_id', 'type', 'amount', 'balance', 'total', 'comment', 'create_time', 'update_time','ip');
	
	/**
	 * 字段与属性隐射关系
	 * @var array
	 */
	public $properties = array(
		'id'          =>'id',
	    'order_id'    =>'orderId',
		'user_id'     =>'userId',
		'type'        =>'type',
		'amount'      =>'amount',
		'balance'     =>'balance',
		'total'       =>'total',
		'comment'     =>'comment',
		'create_time' =>'createTime',
		'update_time' =>'updateTime',
		'ip'          =>'ip',			
	);
	
	/**
	 * 整数类型的字段
	 * @var array
	 */
	protected $intProps = array(
		'id'        =>1,
		'order_id'  =>1,
		'user_id'   =>1,
		'type'      =>1,			
	);

	/**
	 * @param array $data
	 * @return Loan_Object_Refund
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
	 * 订单ID
	 * @var integer
	 */
	public $orderId;
	
	/**
	 * 用户ID
	 * @var integer
	 */
	public $userId;
	
	/**
	 * 订单类型
	 * @var integer
	 */
	public $type;
	
	/**
	 * 交易金额
	 * $var number
	 */
	public $amount;
	
	/**
	 * 余额
	 * @var number
	 */
	public $balance;
	
	/**
	 * 系统总额
	 * @var number
	 */
	public $total;
	
	/**
	 * 备注
	 * @var varchar
	 */
	public $comment;
	
	/**
	 * 创建时间
	 * @var String
	 */
	public $createTime;
	
	/**
	 * 更新时间
	 * @var String
	 */
	public $updateTime;
	
	/**
	 * ip
	 * @var varChar
	 */
	public $ip;
}