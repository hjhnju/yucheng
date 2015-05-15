<?php
/**
 * 建校的时间
 * @author guojinli
 *
 */
class Apply_Type_Year extends Base_Type {  
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
	 * @param  null
	 * @return 返回年份列表
	 */
	public static function getYears() {
		$years = array();
		$next_year = date("Y", time()) + 1;
		for($i=$next_year; $i>=2010; $i--){
			$years[$i] = $i;
		}

		return $years;
	}
}