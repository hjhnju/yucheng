<?php
/**
 * 支付订单表
 * @author lilu
 */
class Fiance_Object_Order extends Base_Object {	
	/**
	 * 数据表名
	 * @var string
	 */
	protected $table = 'pay_order';
	
	/**
	 * 主键
	 * @var string
	 */
	protected $priKey = 'order_id';
	
	/**
	 * 类名
	 * @var string
	 */
	const CLASSNAME = 'Fiance_Object_Order';
	
	/**
	 * 对象包含的所有字段
	 * @var array
	 */
	protected $fields = array('order_id', 'user_id', 'type', 'amount', 'status', 'create_time', 'update_time', 'comment');
	
	/**
	 * 字段与属性隐射关系
	 * @var array
	 */
	public $properties = array(
        'order_id'    => 'orderId',
	    'user_id'     => 'userId',
	    'type'	      => 'type',
		'amount'      => 'amount', 
		'status'      => 'status',
		'create_time' =>'createTime',	
		'update_time' =>'updateTime',
		'comment'     =>'comment',						
	);
	
	/**
	 * 整数类型的字段
	 * @var array
	 */
	protected $intProps = array(
	    'order_id'    =>1,
	    'user_id'     =>1,
		'type'        =>1,
		'status'      =>1,		
	);
	
	/**
	 * @param array $data
	 * @return Loan_Object_Refund
	 */
	public static function init($data) {
		return parent::initObject(self::CLASSNAME, $data);
	}
	
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
	 * 支付类型
	 * @var integer
	 */
	public $type;
	
	/**
	 * 交易金额
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
	 * @var String
	 */
	public $createTime;
	
	/**
	 * 更新时间
	 * @var String
	 */
	public $updateTime;
	
	/**
	 * 备注
	 * @var varChar
	 */
	public $comment;
	
	
	
}