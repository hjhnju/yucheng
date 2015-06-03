<?php
/**
 * @file 该类将写入过滤所有的apply表中的信息
 */
class Apply_Logic_School extends Apply_Logic_Base{
	/**
	 * 可以为空的字段
	 */
	protected $_except = array('id', 'create_time', 'update_time', 'is_annual_income', 'is_profit', 'is_other_business', 'branch_school', 'guarantee_count');
	/**
	 * @param  
	 * @return 
	 */
	
	public function saveSchool($apply_id) {
		//得到所有的cookie
		$cookies = Apply_Cookie::parseCookie('school');
		$cookies['apply_id'] = $apply_id;
		//如果没有通过验证
		if(!$this->checkParams($cookies)) {
			return $this->errorFormat();
		}
		//开始存储信息
		$apply = Apply_Object_School::init($cookies);
		if ($apply->save()) {
			$objRet = new Base_Result(Apply_RetCode::SUCCESS, array(), Apply_RetCode::getMsg(Apply_RetCode::SUCCESS));
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

    /**
     * 格式化信息
     * @param  [type] $data [数据]
     * @return [type] array [数据]
     */
    public function formatApply($data) {
    	$data['name'] 	 = $data['name'];

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
		$item['branch_school']		= $item['branch_school']? $item['branch_school'] : 0;
        $type_list 					= Apply_Type_SchoolType::$names;
        $item['type'] 				= $type_list[$item['type']];
        $nature_list 				= Apply_Type_Nature::$names;
        $item['nature'] 			= $nature_list[$item['nature']];
        $source_list 				= Apply_Type_Source::$names;
        $item['school_source'] 		= $source_list[$item['school_source']];
        $item['is_annual_income']	= $yesno[$item['is_annual_income']];
        $item['is_profit'] 			= $yesno[$item['is_profit']];
        $item['is_other_business'] 	= $yesno[$item['is_other_business']];
        $purpose_list 				= Apply_Type_Purpose::$names;
        $item['purpose'] 			= $purpose_list[$item['purpose']];
        $province_list 				= Apply_Type_Province::$names;
        $item['province'] 			= $province_list[$item['province']];
        $city_list					= Apply_Type_Province::getCity($item['province']);
        foreach($city_list as $key=>$value) {
        	if($value['id'] = $item['city']){
        		$item['city'] = $value['name'];
        		break;
        	}
        }

		return $item;
	}
}