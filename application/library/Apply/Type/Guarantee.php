<?php
/**
 * 担保人
 * @author guojinli
 *
 */
class Apply_Type_Guarantee extends Base_Type {  
	/**
	 * 默认key名
	 * @var string
	 */
	const DEFAULT_KEYNAME = 'guarantee';

	/**
	 * 默认类型属性名
	 * @var string
	 */
	const DEFAULT_FIELD = 'guarantee_name';

	/**
	 * @param  null
	 * @return 返回列表
	 */
	public static function getGuarantes() {
		$guarantee = array();
		for($i=0; $i<=5; $i++){
			$guarantee[$i] = $i;
		}

		return $guarantee;
	}
}