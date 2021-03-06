<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_Stock extends Apply_Logic_Base{
	/**
	 * @param  
	 * @return 
	 */
	
	public function saveStock($apply_id) {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('stock');
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}

		foreach($cookies as $key=>$item) {
			$item = (array)$item;
			$item['apply_id'] = $apply_id;
			$apply = Apply_Object_Stock::init($item);
			if(!$apply->save()) {
				return $this->errorFormat();
	        }
		}
		$objRet = new Base_Result(Apply_RetCode::SUCCESS, array('id'=>$apply->id), Apply_RetCode::getMsg(Apply_RetCode::SUCCESS));
		return $objRet->format();
	}

	/**
	 * @param 需要保存到cookie中的数据
	 * @return null
	 */
	public function saveCookie($param) {
		if(!$this->checkParams($param)) {
			return false;
		}
		$fields = $this->getProperties();
		Apply_Cookie::save($param, array('stock'=>'stock'));

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

    /**
	 * 只会返回第一个数组元素，目的是省的到处写 $data[0]
	 * @param  [type] $data [需要解析的数组]
	 * @return [type]       [解析数组后的数组元素]
	 */
	public function getDataItem($data) {
		$items = array();
		foreach($data as $key=>$value){
			$items[$key] = array(
				'name' 		=> $value['name'],
				'weight'	=> floatval($value['weight']) . '%',
			);
		}

		return $items;
	}
}