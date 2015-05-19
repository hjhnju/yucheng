<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Personal extends Apply_Logic_Base {
	/**
	 * @param  
	 * @return 
	 */
	
	public function savePerson() {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('personal');
		// $cookies = array(
		// 	'realname'        => 'guojinli',
	 //        'certificate'     => '130825198801193425',
	 //        'house_type'      => '1',
	 //        'detail_address'  => 'bushuo',
	 //        'cellphone'       => '15810234069',
	 //        'telephone'       => '010-7342732',
	 //        'scope_cash'      => '1',
	 //        'scope_stock'     => '2',
	 //        'is_criminal'     => '1',
	 //        'is_lawsuit'      => '0',
	 //        'apply_id'        => '11',
		// );
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		$apply = Apply_Object_Personal::init($cookies);

		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            $objRet->format();
        } else {
            $objRet = new Base_Result(Apply_RetCode::PARAM_ERROR, Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            $objRet->format();
	    }
	}

	/**
	 * @param 需要保存到cookie中的数据
	 * @return null
	 */
	public function saveCookie($param) {
		//如果没有通过验证
		if(!$this->checkParams($param)) {
			false;
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
		$object = new Apply_Object_Personal();
		$fields = $object->properties;
		
		return $fields;
	}
}