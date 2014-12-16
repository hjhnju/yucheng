<?php
/**
 * 资金记录列表类
 * @author lilu
 */
class Finance_List_Record {
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
	 * 对象包含的所有字段
	 * @var array
	 */
	protected $fields = array('id', 'order_id', 'user_id', 'type', 'amount', 'balance', 'total', 'comment', 'create_time', 'update_time','ip');
		
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
	
}