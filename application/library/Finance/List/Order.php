<?php
/**
 * 支付订单列表类
 * @author lilu
 */
class Finance_List_Order extends Base_List {
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
	 * 对象包含的所有字段
	 * @var array
	 */
	protected $fields = array('order_id', 'user_id', 'type', 'amount', 'status', 'create_time', 'update_time', 'comment');
	
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
	
	
}