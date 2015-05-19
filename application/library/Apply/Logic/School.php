<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_School extends Apply_Logic_Base{
	/**
	 * @param  
	 * @return 
	 */
	
	public function saveSchool() {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('school');

		// $cookies = array(
		// 		'name'                => 'yale univeristy',
		//        'type'                => '2',
		//        'nature'              => '1',
		//        'province'            => '1',
	 	//        'city'                => '1',
	 	//        'school_source'       => '2',
	 	//        'year'                => '2014',
	 	//        'is_annual_income'    => '1',
	 	//        'is_profit'           => '1',
	 	//        'is_other_business'   => '0',
	 	//        'address'             => 'usa',
	 	//        'total_student'       => '3450',
	 	//        'staff'               => '450',
	 	//        'purpose'             => '1',
	 	//        'guarantee_count'     => '2',
	 	//        'branch_school'       => '2',
	 	//        'apply_id'            => '1',
		// );
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		//开始存储信息
		$apply = Apply_Object_School::init($cookies);

		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            return $objRet->format();
        } else {
            $objRet = new Base_Result(Apply_RetCode::PARAM_ERROR, Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            return $objRet->format();
	    }
	}

	/**
	 * @param 需要保存到cookie中的数据
	 * @return null
	 */
	public function saveCookie($param) {
		//如果没有通过验证
		if(!$this->checkParams($param)) {
			return false;
		}
		$fields = $this->getProperties();
		Apply_Cookie::save($param, $fields);

		return true;	
	}

	/**
	 * @param  null
	 * @return 返回apply表中的字段
	 */
	public function getProperties() {
		$object = new Apply_Object_School();
		$fields = $object->properties;
		
		return $fields;
	}
}