<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Stock extends Apply_Logic_Base{
	/**
	 * @param  
	 * @return 
	 */
	
	public function saveStock() {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('stock');
		// $cookies = array(
		// 	'name'        => 'another people',
	 //        'weight'      => '0.59',
	 //        'apply_id'    => '11',
		// );
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		$apply = Apply_Object_Stock::init($cookies);

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
		if(!$this->checkParams($cookies)) {
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
		$object = new Apply_Object_Stock();
		$fields = $object->properties;
		
		return $fields;
	}
}