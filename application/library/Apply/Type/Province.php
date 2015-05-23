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
	const DEFAULT_KEYNAME = 'province';

	/**
	 * 默认类型属性名
	 * @var string
	 */
	const DEFAULT_FIELD = 'province_name';
    
    /**
     * 状态名
     * @var array
     */
	public static $names = array(
		self::BEIJING             => '北京',
		self::GUANGXIQU           => '广西区',
	);
	/**
	 * @param 省份的id
	 * @return 返回该省份的城市集合
	 */
	public static function getCity($pid) {
		$list = new Area_List_Area();
		$list->setFilter(array('pid'=>$pid));
	    $list->setFields(array('id', 'name'));
	    $list->setPagesize(PHP_INT_MAX);
	    $list->setOrder('id');

	    return $list->getData();
	}

	/**
	 * @param null
	 * @return 返回所有城市列表
	 */
	public static function getAllCity() {
		$city = array();
		$list = new Area_List_Area();
		foreach(self::$names as $key=>$value) {
			$list->setFilter(array('pid'=>$key));
		    $list->setFields(array('id', 'name'));
		    $list->setPagesize(PHP_INT_MAX);
		    $list->setOrder('id');
		    $city[$key] = $list->getData();
		}

		return $city;
	}
}