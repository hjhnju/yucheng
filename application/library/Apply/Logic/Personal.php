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

	/**
     * 格式化信息
     * @param  [type] $data [数据]
     * @return [type] array [数据]
     */
    public function formatApply($data) {
    	$data['realname'] 	 = $data['realname'];

        return $data;
    }

    /**
	 * 只会返回第一个数组元素，目的是省的到处写 $data[0]
	 * @param  [type] $data [需要解析的数组]
	 * @return [type]       [解析数组后的数组元素]
	 */
	public function getDataItem($data) {
		$item = reset($data);
		$yesno 						= Apply_Type_YesNo::$names;
        $house_type_list 			= Apply_Type_HouseType::$names;
        $item['house_type']			= $house_type_list[$item['house_type']];
        $cash_list 					= Apply_Type_Cash::$names;
        $item['scope_cash']			= $cash_list[$item['scope_cash']];
        $scope_stock_list 			= Apply_Type_Stock::$names;
        $item['scope_stock']		= $scope_stock_list[$item['scope_stock']];
        $item['is_criminal']		= $yesno[$item['is_criminal']];
        $item['is_lawsuit']			= $yesno[$item['is_lawsuit']];
        
		return $item;
	}
}