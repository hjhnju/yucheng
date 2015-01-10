<?php 
/**
 * 手续费类
 * @author lilu
 */
class Finance_Fee {
		
	/**
	 * finance_service_fee
	 * 融资服务费(每笔)
	 */
	public static $finance_service_fee = array(
		'C'   => '0.0030',
		'B'   => '0.0030',
		'A'   => '0.0030',
		'AA'  => '0.0030',
		'AAA' => '0.0030',
	); 
	
	/**
	 * risk_reserve
	 * 风险准备金(年化)
	 */
	public static $risk_reserve = array(
		'C'   => '0.0250',
		'B'   => '0.0200',
		'A'   => '0.0150',
		'AA'  => '0.0100',
		'AAA' => '0.0080',
	);
	
	/**
	 *  0.35%	0.30%	0.25%	0.20%	0.15%
	 * acc_manage_fee
	 * 账户管理费(每月)
	 */
	public static $acc_manage_fee = array(
	    'C'   => '0.0035',
		'B'   => '0.0030',
		'A'   => '0.0025',
		'AA'  => '0.0020',
		'AAA' => '0.0015',
	);
	
	/**
	 * 6.700%	5.600%	4.500%	3.400%	2.600%
	 * total
	 * 总计
	 */
	 public static $total_fee = array(
	     'C'   => '0.0670',
	 	 'B'   => '0.0560',
	 	 'A'   => '0.0450',
	 	 'AA'  => '0.0340',
	 	 'AAA' => '0.0260',
	 );
	 
	 
	
}