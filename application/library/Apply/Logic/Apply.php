<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Apply extends Apply_Logic_Base {
	/**
	 * 可以为空的字段
	 */
	protected $_except = array('id', 'create_time', 'update_time', 'start_time', 'end_time', 'status');
	/**
	 * @param  null
	 * @return 
	 */
	public function saveApply() {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('apply');
		$objUser = User_Api::checkLogin();
		if($objUser) {
			$cookies['userid'] = $objUser->userid;
		}
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		//开始存储信息，此处要过滤cookie中的值是否是合法的
		$apply = Apply_Object_Apply::init($cookies);
		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, array('id'=>$apply->id), Apply_RetCode::getMsg(Apply_RetCode::SUCCESS));
            return $objRet->format();
        }else {
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
		$object = new Apply_Object_Apply();
		$fields = $object->properties;

		return $fields;
	}
}