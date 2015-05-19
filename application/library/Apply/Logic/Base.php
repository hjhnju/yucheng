<?php
class Apply_Logic_Base{
	/**
	 * @param  $params 需要检查的字段和值
	 * @return true | false
	 */
	public function checkParams($params = array()) {
		$result = false;
		if(!empty($params)) {
			foreach($params as $field=>$value) {
				if($value == '') {
					return $result;
				}
			}
			$result = true;
		}
		return $result;
	}

	/**
	 * @param  
	 * @return 如果错误统一返回格式
	 */
	public function errorFormat(){
		$objRet = new Base_Result(Apply_RetCode::PARAM_ERROR, Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            return $objRet->format();
	}
}