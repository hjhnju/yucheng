<?php
class Apply_Cookie {
	/**
	 * @param $param 需要保存到cookie中的数据
	 * @param $fields 查看保存的cookie键是否在当前的字段列表里
	 * @return null
	 */
	public static function save($param, $fields) {
		$expire = time() + 3600 * 24;
		$cookie = new Base_Cookie();
		if(!empty($param)) {
			foreach($param as $field=>$value) {
				if(array_key_exists($field, $fields)) {
					$cookie->write($field, $value, $expire, '', '', '', '');
				}
			}
		}	
	}

	/**
	 * @param  指定字段
	 * @return 返回指定字段的cookie值
	 */
	public static function read($fields) {
		$cookie = new Base_Cookie();
		$values = array();
		if(!empty($fields)) {
			foreach($fields as $field=>$item) {
				$value = $cookie->read($field);
				$values[$field] = $value;
			}
		}

		return $values;
	}
	/**
	 * @param $type apply|school|personal|stock，根据不同名字获得不同的类
	 * @return 解析所有的cookie
	 */
	public static function parseCookie($type){
		switch($type){
		    case 'school':
		    	$obj = new Apply_Object_School();
		        break;
		    case 'personal':
		    	$obj = new Apply_Object_Personal();	
		        break;
		    case 'stock':
		    	$obj = new Apply_Object_Stock();
		        break;
		    default:
		    	$obj = new Apply_Object_Apply();
		}
		$properties = $obj->properties;
		$cookies = self::read($properties);

		return $cookies;   
	}
}