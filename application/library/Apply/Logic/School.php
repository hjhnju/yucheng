<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_School extends Apply_Logic_Base{
	/**
	 * @param  
	 * @return 
	 */
	
	public function saveSchool($apply_id) {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('school');
        $stock = Apply_Cookie::read(array('stock'=>'stock'));
        $stock = json_decode($cookies['stock']['stock']);
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		//开始存储信息
		$apply = Apply_Object_School::init($cookies);

		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, array('stock'=>$stock), Apply_RetCode::getMsg(Apply_RetCode::PARAM_ERROR));
            return $objRet->format();
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
			return false;
		}
		$fields = $this->getProperties();
		$fields['stock'] = '';
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
	/**
     * 检验字符串是否合法
     * @param  [type] $param [需要检验的字段数组]
     * @param  [type] $data  [检验字段值的数组]
     * @return [type]        [如果成功返回true,否则返回相应的header包含code和文本信息]
     */
    protected function checkParam($param, $data) {
        foreach ($param as $key => $msg) {
            if (empty($data[$key])) {
                $this->ajaxError(Apply_RetCode::PARAM_ERROR, $msg);
                return false;
            }
        }
        return true;
    }
}