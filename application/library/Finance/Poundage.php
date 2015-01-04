<?php 
/**
 * 平台收费
 * 包含所有可能用到的手续费
 * @author lilu
 */

class Finance_Poundage {
		
	/**
	 * 风险评估等级
	 */
	private static $riskLevel = array(
        0 => 'C',
		1 => 'B',
		2 => 'A',
		3 => 'AA',
		4 => 'AAA',	    
	);
		
	/**
	 * 融资服务费根据风险评估等级mapping
	 */
	private static $finance_service_fee = array (
		self::$riskLevel[0] => 0.0030,
		self::$riskLevel[1] => 0.0030,
		self::$riskLevel[2] => 0.0030,
		self::$riskLevel[3] => 0.0030,
		self::$riskLevel[4] => 0.0030,
    );
	
	/**
	 * 风险准备金
	 * 
	 */
	protected static $risk_reserve = array (
		self::$riskLevel[0] => 0.0250,
		self::$riskLevel[1] => 0.0200,
		self::$riskLevel[2] => 0.0150,
		self::$riskLevel[3] => 0.0100,
		self::$riskLevel[4] => 0.0080,
	);
	
	/**
	 * 0.35%	0.30%	0.25%	0.20%	0.15%
	 * 账户管理费
	 * Account management fees
	 */
	protected static $acc_manage_fees = array (
		self::$riskLevel[0] => 0.0035,
		self::$riskLevel[1] => 0.0030,
		self::$riskLevel[2] => 0.0025,
		self::$riskLevel[3] => 0.0020,
		self::$riskLevel[4] => 0.0015,
	);
	
	
	
}