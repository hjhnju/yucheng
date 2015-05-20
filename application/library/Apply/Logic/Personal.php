<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Personal extends Apply_Logic_Base {
	/**
	 * 可以为空的字段
	 */
	protected $_except = array('id', 'create_time', 'update_time', 'is_criminal', 'is_lawsuit');
	/**
	 * @param  
	 * @return 
	 */
	
	public function savePerson($apply_id) {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('personal');
		$cookies['apply_id'] = $apply_id;
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		$apply = Apply_Object_Personal::init($cookies);

		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, array('id'=>$apply->id), Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            $objRet->format();
        } else {
            return $this->errorFormat();
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