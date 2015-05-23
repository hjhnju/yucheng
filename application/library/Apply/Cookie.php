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
					$cookie->write($field, $value, $expire, '/apply', '', '', '');
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
		if($type == 'stock') {
			$cookie = new Base_Cookie();
			$data = $cookie->read('stock');
			$data = $data? json_decode($data) : '';
			if(is_array($data)) {
				foreach($data as $item){
					$cookies[] = (array)$item;
				}	
			}
		} else {
			$properties = $obj->properties;
			$cookies = self::read($properties);
		}
		
		return $cookies;   
	}

	/**
	 * 将所有的cookie删掉
	 * @return null
	 */
	public static function erasureCookie(){
		$expire = time() - 3600;
		$cookie = new Base_Cookie();

		$school = new Apply_Object_School();
		$personal = new Apply_Object_Personal();	
		$stock = new Apply_Object_Stock();
		$apply = new Apply_Object_Apply();
		$fields = array(
			'apply' 	=> $apply->properties,
			'personal' 	=> $personal->properties,
			'school' 	=> $school->properties,
		);
		$cookie->write('stock', $cookie->read('stock'), $expire, '/apply', '', '', '');
		foreach($fields as $item) {
			foreach($item as $field=>$value) {
				$cookie->write($field, $cookie->read($field), $expire, '/apply', '', '', '');
			}
		}
	}

	public static function showCookieValue(){
		$yesno 							= Apply_Type_YesNo::$names;
		$yesno[''] 						= '否';
		//读出所有的cookie，用来显示
        $apply 							= Apply_Cookie::parseCookie('apply');

        $school 						= Apply_Cookie::parseCookie('school');
        $school['branch_school']		= $school['branch_school']? $school['branch_school'] : 0;
        $type_list 						= Apply_Type_SchoolType::$names;
        $school['type'] 				= $type_list[$school['type']];
        $nature_list 					= Apply_Type_Nature::$names;
        $school['nature'] 				= $nature_list[$school['nature']];
        $source_list 					= Apply_Type_Source::$names;
        $school['school_source'] 		= $source_list[$school['school_source']];
        $school['is_annual_income']		= $yesno[$school['is_annual_income']];
        $school['is_profit'] 			= $yesno[$school['is_profit']];
        $school['is_other_business'] 	= $yesno[$school['is_other_business']];
        $purpose_list 					= Apply_Type_Purpose::$names;
        $school['purpose'] 				= $purpose_list[$school['purpose']];
        $province_list 					= Apply_Type_Province::$names;
        $school['province'] 			= $province_list[$school['province']];
        $city_list						= Apply_Type_Province::getCity($school['province']);
        foreach($city_list as $key=>$item) {
        	if($item['id'] = $school['city']){
        		$school['city'] = $item['name'];
        		break;
        	}
        }

        $personal 						= Apply_Cookie::parseCookie('personal');
        $house_type_list 				= Apply_Type_HouseType::$names;
        $personal['house_type']			= $house_type_list[$personal['house_type']];
        $cash_list 						= Apply_Type_Cash::$names;
        $personal['scope_cash']			= $cash_list[$personal['scope_cash']];
        $scope_stock_list 				= Apply_Type_Stock::$names;
        $personal['scope_stock']		= $scope_stock_list[$personal['scope_stock']];
        $personal['is_criminal']		= $yesno[$personal['is_criminal']];
        $personal['is_lawsuit']			= $yesno[$personal['is_lawsuit']];

        $stock 							= Apply_Cookie::parseCookie('stock');

        $data = array(
        	'apply'     => $apply,
            'school'    => $school,
            'personal'  => $personal,
            'stock'     => $stock,
        );

        return $data;
	}	
}