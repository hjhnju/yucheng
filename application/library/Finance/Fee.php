<?php 
/**
 * 手续费类
 * AAA=10 AA=20 A=30 B=40 C=50
 * @author lilu
 */
class Finance_Fee {
	
	/**
	 * finance_service_fee
	 * 融资服务费(每笔)
	 */
	public static $finance_service_fee = array(
		50   => 0.0030,
		40   => 0.0030,
		30   => 0.0030,
		20   => 0.0030,
		10   => 0.0030,
	); 
	
	/**
	 * risk_reserve
	 * 风险准备金(年化)
	 */
	public static $risk_reserve = array(
		50  => 0.0250,
		40  => 0.0200,
		30  => 0.0150,
		20  => 0.0100,
		10  => 0.0080,
	);
	
	/**
	 * acc_manage_fee
	 * 账户管理费(每月)
	 */
	public static $acc_manage_fee = array(
	    50   => 0.0035,
		40   => 0.0030,
		30   => 0.0025,
		20   => 0.0020,
		10   => 0.0015,
	);
	
	/**
	 * total
	 * 总计
	 */
	 public static $total_fee = array(
	     50   => 0.0670,
	 	 40   => 0.0560,
	 	 30   => 0.0450,
	 	 20   => 0.0340,
	 	 10   => 0.0260,
	 );
	 
	 
	
}