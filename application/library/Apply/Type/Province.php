<?php
/**
 * 开放的省份
 * @author guojinli
 *
 */
class Apply_Type_Province extends Base_Type {
	
	/**
	 * 北京
	 * 
	 * @var integer
	 */
	const BEIJING = 1;
	
	/**
	 * 广西区
	 * 
	 * @var integer
	 */
	const GUANGXIQU = 2403;
	  
	/**
	 * 默认key名
	 * @var string
	 */
	const DEFAULT_KEYNAME = 'level';

	/**
	 * 默认类型属性名
	 * @var string
	 */
	const DEFAULT_FIELD = 'level_name';
    
    /**
     * 状态名
     * @var array
     */
	public static $names = array(
		self::BEIJING             => '北京',
		self::GUANGXIQU           => '广西区',
	);
}